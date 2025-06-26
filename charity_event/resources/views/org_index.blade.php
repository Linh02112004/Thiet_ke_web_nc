@extends('layouts.master')

@section('title', '🌱 HY VỌNG - Tổ chức')

@section('content')

    @include('components.search-box')

    <h2>Sự kiện đang diễn ra</h2>
    <div id="ongoing-events" class="events-list">
        @forelse ($ongoingEvents as $event)
            @include('components.event-card', ['event' => $event])
        @empty
            <p>Không có sự kiện nào.</p>
        @endforelse
    </div>

    <h2>Sự kiện đã hoàn thành</h2>
    <div id="completed-events" class="events-list">
        @forelse ($completedEvents as $event)
            @include('components.event-card', ['event' => $event])
        @empty
            <p>Không có sự kiện nào.</p>
        @endforelse
    </div>

    @include('modals.update-info')
    @include('modals.change-password')
    @include('modals.create-event')
@endsection

@push('scripts')
    <script>
        window.orgMarkReadUrl = "{{ route('organization.notifications.markRead') }}";
        console.log("notificationDropdown:", notificationDropdown);
    </script>
@endpush
