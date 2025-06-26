@extends('layouts.master')

@section('title', '🌱 HY VỌNG - Quản trị viên')

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
@endsection


