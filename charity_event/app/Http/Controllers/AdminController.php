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
                'c.comment', 'c.created_at',
                DB::raw("CASE WHEN u.role = 'organization' THEN u.organization_name ELSE u.full_name END as commenter_name")
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

    
}
