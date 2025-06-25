<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DANH SÁCH SỰ KIỆN - HY VỌNG</title>
    <link rel="stylesheet" href="{{ asset('css/organization.css') }}">
</head>
<body>

@include('components.header')

<main>
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
</main>

@include( 'components.footer')

<script src="{{ asset('js/organization.js') }}"></script>
</body>
</html>
