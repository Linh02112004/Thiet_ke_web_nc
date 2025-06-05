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
        dd(Auth::check(), Auth::user());

        // Cập nhật trạng thái nếu đã đạt đủ goal
        // Lưu ý: Laravel không hỗ trợ JOIN trong UPDATE cho mọi DBMS, nên có thể cần sửa hoặc làm query thủ công
        DB::transaction(function () {
            $eventsToComplete = DB::table('events as e')
                ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
                ->select('e.id', DB::raw('COALESCE(SUM(d.amount), 0) as total_donations'), 'e.goal')
                ->groupBy('e.id', 'e.goal')
                ->havingRaw('COALESCE(SUM(d.amount), 0) >= e.goal')
                ->pluck('e.id');

            if ($eventsToComplete->isNotEmpty()) {
                DB::table('events')->whereIn('id', $eventsToComplete)->update(['status' => 'completed']);
            }
        });

        $events = DB::table('events as e')
            ->leftJoin('donations as d', 'e.id', '=', 'd.event_id')
            ->join('users as u', 'e.organization_id', '=', 'u.id') // sửa lại field đúng
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
            ->groupBy('e.id', 'u.organization_name', 'e.event_name', 'e.description', 'e.status', 'e.organizer_name', 'e.location', 'e.goal')
            ->get();

        return view('donor.dashboard', compact('user', 'events'));
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('full_name', 'phone'));

        return redirect()->back()->with('success', 'Information updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Incorrect old password']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
