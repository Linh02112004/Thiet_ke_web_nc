<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DonorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cập nhật trạng thái các sự kiện đã đạt đủ goal
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

        // Lấy danh sách sự kiện với tổng số tiền đã quyên góp
        $events = DB::table('events as e')
            ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->select(
                'e.id as event_id',
                'e.event_name as name',
                'e.description',
                'e.status',
                'u.organization_name as organization',
                'e.organizer_name',
                'e.location',
                'e.goal',
                DB::raw('COALESCE(SUM(d.amount), 0) as amount_raised')
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

        return view('dn_index', [
            'ongoingEvents' => $eventsOngoing,
            'completedEvents' => $eventsCompleted,
            'user' => $user
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'social_media' => 'nullable|url|max:255',
        ]);

        $user->full_name = $request->input('fullname');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->social_media = $request->input('social_media');
        $user->save();

        return redirect()->route('dn_index')->with('success', 'Thông tin tổ chức đã được cập nhật.');
    }


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

    public function showEventDetails($id)
    {
        $event = DB::table('events as e')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
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
            ->orderByDesc('d.donated_at')
            ->select('u.full_name as donor_name', 'd.amount', 'd.donated_at')
            ->get();

        $comments = DB::table('comments as c')
            ->join('users as u', 'c.user_id', '=', 'u.id')
            ->where('c.event_id', $id)
            ->orderByDesc('c.created_at')
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
            ->get();

        $bankCodes = [
            "BIDV" => "BIDV",
            "Vietcombank" => "VCB",
            "Techcombank" => "TCB",
            "Agribank" => "VBA",
            "ACB" => "ACB",
            "MB Bank" => "MB",
            "VPBank" => "VPB"
        ];

        $bankCode = $bankCodes[$event->bank_name] ?? null;
        $user = Auth::user();
        $fullName = $user->full_name ?? 'Người dùng';

        return view('dn_event-details', compact('event', 'donations', 'comments', 'bankCode', 'fullName'));
    }

    public function confirmDonation(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'amount' => 'required|integer|min:1000'
        ]);

        $user = Auth::user();

        DB::table('donations')->insert([
            'event_id' => $request->event_id,
            'donor_id' => $user->id,
            'amount' => $request->amount,
            'donated_at' => now()
        ]);

        $updated = DB::table('events as e')
            ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
            ->where('e.id', $request->event_id)
            ->select(
                'e.goal',
                DB::raw('COALESCE(SUM(d.amount), 0) as amount_raised')
            )
            ->groupBy('e.id', 'e.goal')
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Quyên góp thành công!',
            'data' => [
                'goal' => $updated->goal,
                'amount_raised' => $updated->amount_raised
            ]
        ]);
    }
}
