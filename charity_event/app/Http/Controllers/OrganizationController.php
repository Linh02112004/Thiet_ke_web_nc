<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index()
    {
        $orgId = Auth::id();
        // Lấy các event của tổ chức đang đăng nhập, phân loại theo trạng thái
        $eventsOngoing = Event::where('organization_id', $orgId)->where('status', 'ongoing')->get();
        $eventsCompleted = Event::where('organization_id', $orgId)->where('status', 'completed')->get();

        return view('organization.index', compact('eventsOngoing', 'eventsCompleted'));
    }

    public function showEvent($id)
    {
        $event = Event::findOrFail($id);
        // Kiểm tra quyền
        $this->authorize('view', $event);

        return view('organization.event-details', compact('event'));
    }

    public function requestEdit($id)
    {
        $event = Event::findOrFail($id);
        $this->authorize('update', $event);

        return view('organization.request-edit', compact('event'));
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('organization.dashboard')->with('success', 'Xóa sự kiện thành công');
    }
}
