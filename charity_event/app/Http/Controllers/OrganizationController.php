<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    public function index()
    {
        $orgId = Auth::id();
        $user = Auth::user();

        // Cập nhật trạng thái sự kiện nếu đã đạt đủ goal (chỉ của tổ chức hiện tại)
        DB::transaction(function () use ($orgId) {
            $eventsToComplete = DB::table('events as e')
                ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
                ->where('e.user_id', $orgId)
                ->select('e.id', DB::raw('COALESCE(SUM(d.amount), 0) as total_donations'), 'e.goal')
                ->groupBy('e.id', 'e.goal')
                ->havingRaw('COALESCE(SUM(d.amount), 0) >= e.goal')
                ->pluck('e.id');

            if ($eventsToComplete->isNotEmpty()) {
                DB::table('events')
                    ->whereIn('id', $eventsToComplete)
                    ->update(['status' => 'completed']);
            }
        });

        // Lấy danh sách sự kiện của tổ chức hiện tại kèm thông tin tổng tiền và pending edit
        $events = DB::table('events as e')
            ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->where('e.user_id', $orgId)
            ->select(
                'e.id as event_id',
                'e.event_name as name',
                'e.description',
                'e.status',
                'e.organizer_name',
                'e.location',
                'e.goal',
                DB::raw('COALESCE(SUM(d.amount), 0) as amount_raised'),
                DB::raw('(
                    SELECT COUNT(*) 
                    FROM event_edits ed 
                    WHERE ed.event_id = e.id AND ed.status = "pending"
                ) as pending'),
                'u.organization_name as organization'
            )
            ->groupBy(
                'e.id',
                'e.event_name',
                'e.description',
                'e.status',
                'e.organizer_name',
                'e.location',
                'e.goal',
                'u.organization_name'
            )
            ->get();

        // Tách ongoing và completed để dễ hiển thị
        $eventsOngoing = $events->where('status', 'ongoing');
        $eventsCompleted = $events->where('status', 'completed');

        return view('org_index', [
            'ongoingEvents' => $eventsOngoing,
            'completedEvents' => $eventsCompleted,
            'user' => $user
        ]);
    }

    // Trang chi tiết sự kiện (hiện thông tin + quyên góp + bình luận)
    public function showEventDetails($id)
    {
        $event = DB::table('events as e')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->where('e.id', $id)
            ->select(
                'e.*',
                'u.organization_name as organizer',
                DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM donations WHERE event_id = e.id) as total_donated'),
                DB::raw('(SELECT COUNT(*) FROM donations WHERE event_id = e.id) as donation_count')
            )
            ->first();

        if (!$event) {
            abort(404, 'Sự kiện không tồn tại');
        }

        $donations = DB::table('donations as d')
            ->join('users as u', 'd.donor_id', '=', 'u.id')
            ->where('d.event_id', $id)
            ->orderByDesc('d.donated_at')
            ->select('u.full_name as donor_name', 'd.amount', 'd.donated_at')
            ->get();

        $comments = DB::table('comments as c')
            ->join('users as u', 'c.user_id', '=', 'u.id')
            ->where('c.event_id', $id)
            ->orderByDesc('c.created_at')
            ->select(
                'c.comment', 'c.created_at',
                DB::raw("CASE WHEN u.role = 'organization' THEN u.organization_name ELSE u.full_name END as commenter_name")
            )
            ->get();

        return view('org_event-details', compact('event', 'donations', 'comments'));
    }

    // Hiển thị form yêu cầu chỉnh sửa sự kiện
    public function requestEditForm($id)
    {
        $event = Event::findOrFail($id);

        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        return view('organization.request-edit', compact('event'));
    }

    // Gửi yêu cầu chỉnh sửa
    public function submitEditRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer|exists:events,id',
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'goal' => 'required|numeric|min:0',
            'organizer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::id();

        $event = Event::where('id', $request->event_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$event) {
            return back()->with('error', 'Bạn không có quyền chỉnh sửa sự kiện này.');
        }

        DB::table('event_edits')->insert([
            'event_id' => $request->event_id,
            'user_id' => $user_id,
            'event_name' => $request->event_name,
            'description' => $request->description,
            'location' => $request->location,
            'goal' => $request->goal,
            'organizer_name' => $request->organizer_name,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('organization.event_details', ['id' => $request->event_id])
            ->with('success', 'Yêu cầu chỉnh sửa đã được gửi! Chờ quản trị viên duyệt.');
    }

    // Tạo sự kiện mới
    public function createEvent(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'organization') {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'goal' => 'required|numeric|min:1',
            'description' => 'required|string',
            'organizer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'bank_account' => 'required|string|max:50',
            'bank_name' => 'required|string|max:100',
        ]);

        Event::create([
            'user_id' => $user->id,
            'event_name' => $validated['event_name'],
            'location' => $validated['location'],
            'goal' => $validated['goal'],
            'description' => $validated['description'],
            'organizer_name' => $validated['organizer_name'],
            'phone' => $validated['phone'],
            'bank_account' => $validated['bank_account'],
            'bank_name' => $validated['bank_name'],
            'status' => 'ongoing',
        ]);

        return redirect()->route('org_index')->with('success', 'Sự kiện đã được tạo thành công.');
    }

    // Cập nhật thông tin tổ chức
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'social_media' => 'nullable|url|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('org_index')->with('success', 'Thông tin tổ chức đã được cập nhật.');
    }

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mật khẩu đã được thay đổi.');
    }

    // Xóa sự kiện
    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);

        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $donationCount = DB::table('donations')
            ->where('event_id', $id)
            ->count();

        if ($donationCount > 0) {
            return redirect()->route('org_index')->with('error', 'Không thể xóa sự kiện vì đã có quyên góp.');
        }

        DB::transaction(function () use ($id, $event) {
            DB::table('comments')->where('event_id', $id)->delete();
            DB::table('event_edits')->where('event_id', $id)->delete();

            DB::table('notifications')
                ->whereIn('user_id', function ($query) use ($id) {
                    $query->select('user_id')
                        ->from('events')
                        ->where('id', $id);
                })
                ->delete();

            $event->delete();
        });

        return redirect()->route('org_index')->with('success', 'Sự kiện đã được xóa thành công.');
    }
}
