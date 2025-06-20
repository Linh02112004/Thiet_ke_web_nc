<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function index()
    {
        $orgId = Auth::id();
        $user = Auth::user();

        $eventsOngoing = Event::where('user_id', $orgId)->where('status', 'ongoing')->get();
        $eventsCompleted = Event::where('user_id', $orgId)->where('status', 'completed')->get();

        return view('organization.org_index', [
            'ongoingEvents' => $eventsOngoing,
            'completedEvents' => $eventsCompleted,
            'user' => $user
        ]);
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

        return redirect()->route('organization.org_index')->with('success', 'Xóa sự kiện thành công');
    }

    public function editInfo()
    {
        $user = Auth::user();
        return view('organization.modals.update-info', compact('user'));
    }

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

        return redirect()->route('organization.org_index')->with('success', 'Thông tin tổ chức đã được cập nhật.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // Laravel yêu cầu trường xác nhận phải là `new_password_confirmation`
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mật khẩu đã được thay đổi.');
    }

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

        return redirect()->route('organization.org_index')->with('success', 'Sự kiện đã được tạo thành công.');
    }
}
