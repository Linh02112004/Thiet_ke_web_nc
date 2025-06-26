<!DOCTYPE html>
@extends('layouts.master')

@section('title', '🌱 HY VỌNG - Người quyên góp')

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
@endsection

@push('scripts')
    <script src="{{ asset('js/donor.js') }}"></script>
@endpush
</body>
</html>
