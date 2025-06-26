<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Cập nhật trạng thái sự kiện nếu đã đạt đủ goal
        DB::transaction(function () {
            $eventsToComplete = DB::table('events as e')
                ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
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

        // Lấy danh sách sự kiện với tổng tiền đã quyên góp
        $events = DB::table('events as e')
        ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
        ->join('users as u', 'e.user_id', '=', 'u.id') // user_id là tổ chức
        ->select(
            'e.id as event_id',
            'e.event_name as name',
            'e.description',
            'e.status',
            'u.organization_name as organization',
            'e.organizer_name',
            'e.location',
            'e.goal',
            DB::raw('COALESCE(SUM(d.amount), 0) as amount_raised'),
            DB::raw('(
                SELECT COUNT(*) 
                FROM event_edits ed 
                WHERE ed.event_id = e.id AND ed.status = "pending"
            ) as pending')
        )
        ->groupBy(
            'e.id',
            'e.event_name',
            'e.description',
            'e.status',
            'u.organization_name',
            'e.organizer_name',
            'e.location',
            'e.goal'
        )
        ->get();

        $eventsOngoing = $events->where('status', 'ongoing');
        $eventsCompleted = $events->where('status', 'completed');

        return view('ad_index', [
            'ongoingEvents' => $eventsOngoing,
            'completedEvents' => $eventsCompleted,
            'user' => $user
        ]);
    }

    public function showEventDetails($id)
    {
        $event = DB::table('events as e')
            ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->where('e.id', $id)
            ->select(
                'e.*',
                'u.organization_name as organizer',
                DB::raw('COALESCE(SUM(d.amount), 0) as amount_raised'),
                DB::raw('COUNT(d.id) as donation_count')
            )
            ->groupBy(
                'e.id',
                'e.event_name',
                'e.description',
                'e.status',
                'e.organizer_name',
                'e.location',
                'e.goal',
                'e.phone',
                'e.bank_account',
                'e.bank_name',
                'e.user_id',
                'e.created_at',
                'e.updated_at',
                'u.organization_name'
            )
            ->first();

        if (!$event) {
            abort(404, 'Sự kiện không tồn tại.');
        }

        $donations = DB::table('donations as d')
            ->join('users as u', 'd.donor_id', '=', 'u.id')
            ->where('d.event_id', $id)
            ->select('u.full_name as donor_name', 'd.amount', 'd.donated_at')
            ->orderByDesc('d.donated_at')
            ->get();

        $comments = DB::table('comments as c')
            ->join('users as u', 'c.user_id', '=', 'u.id')
            ->where('c.event_id', $id)
            ->select(
                'c.comment',
                'c.created_at',
                DB::raw("
                    CASE 
                        WHEN u.role = 'admin' THEN 'Quản trị viên'
                        WHEN u.role = 'organization' THEN CONCAT('Tổ chức ', u.organization_name)
                        ELSE u.full_name
                    END as commenter_name
                ")
            )
            ->orderByDesc('c.created_at')
            ->get();

        $original = DB::table('events')->where('id', $id)->first();

        $edit = DB::table('event_edits')
            ->where('event_id', $id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->first();

        return view('ad_event-details', compact('event', 'donations', 'comments', 'original', 'edit'));
    }

    public function approveEdit(Request $request, $id)
    {
        $eventId = (int) $id;
        $action = $request->input('action');
        $reason = trim($request->input('reason', '')); // Nếu từ chối

        // Lấy bản chỉnh sửa đang chờ duyệt
        $edit = DB::table('event_edits')
            ->where('event_id', $eventId)
            ->where('status', 'pending')
            ->first();

        if (!$edit) {
            return back()->with('error', 'Không tìm thấy bản chỉnh sửa hoặc đã được xử lý.');
        }

        $event = DB::table('events')->where('id', $eventId)->first();
        if (!$event) {
            return back()->with('error', 'Sự kiện không tồn tại.');
        }

        if ($action === 'approve') {
            // Cập nhật sự kiện gốc từ event_edits
            DB::table('events')->where('id', $eventId)->update([
                'event_name'     => $edit->event_name,
                'description'    => $edit->description,
                'location'       => $edit->location,
                'goal'           => $edit->goal,
                'organizer_name' => $edit->organizer_name,
                'phone'          => $edit->phone,
            ]);

            // Gửi thông báo cho tổ chức
            $message = "Yêu cầu chỉnh sửa sự kiện \"{$edit->event_name}\" của bạn đã được duyệt và cập nhật thành công.";
            DB::table('notifications')->insert([
                'user_id' => $event->user_id,
                'message' => $message,
                'created_at' => now(),
            ]);
        } elseif ($action === 'reject') {
            if (empty($reason)) {
                return back()->with('error', 'Lý do từ chối không được để trống.');
            }

            // Gửi thông báo từ chối
            $message = "Yêu cầu chỉnh sửa sự kiện \"{$edit->event_name}\" của bạn đã bị từ chối: $reason";
            DB::table('notifications')->insert([
                'user_id' => $event->user_id,
                'message' => $message,
                'created_at' => now(),
            ]);
        } else {
            return back()->with('error', 'Hành động không hợp lệ.');
        }

        // Xóa bản ghi chỉnh sửa sau khi xử lý
        DB::table('event_edits')->where('event_id', $eventId)->delete();

        return back()->with('success', 'Yêu cầu chỉnh sửa đã được xử lý.');
    }
}
