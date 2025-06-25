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
}
