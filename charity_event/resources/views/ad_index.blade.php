<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 HY VỌNG - Quản trị viên</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
    </main>

    @include( 'components.footer')
</body>
</html>
