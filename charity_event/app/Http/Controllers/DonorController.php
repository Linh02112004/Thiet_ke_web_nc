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

        return view('donor.dn_index', compact('user', 'events'));
    }

    // public function updateInfo(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'full_name' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:20',
    //     ]);

    //     $user->update($request->only('full_name', 'phone'));

    //     return redirect()->back()->with('success', 'Cập nhật thông tin thành công.');
    // }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password_hash)) {
            return back()->withErrors(['old_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        $user->update(['password_hash' => Hash::make($request->new_password)]);

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }
}
