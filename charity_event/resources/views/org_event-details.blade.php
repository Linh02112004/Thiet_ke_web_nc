@extends('layouts.master')

@section('title', '🌱 HY VỌNG - '. $event->event_name)

@section('content')
    <div class="container">
        <h1>{{ $event->event_name }}</h1>
        <div class="content-wrapper">
            <div class="container-left">
                <hr>
                @include('components.event-info')
                @if ($event->donation_count == 0)
                    <form action="{{ route('organization.event.delete', ['id' => $event->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sự kiện này?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa sự kiện</button>
                    </form>
                @endif
                <button onclick="openEditModal()">Yêu cầu Sửa Sự kiện</button>

                <hr>
                <h3>Bình luận</h3>
                @include('components.comment-box')

                @include('components.comment-section')
            </div>

            <div class="container-right">
                <hr>
                <div class="right-top">
                    @include('components.progress-bar')
                </div>

                <div class="right-bottom">
                    @include('components.donation-table')
                </div>
            </div>
        </div>
    </div>

    @include('modals.request-edit', ['event' => $event])
@endsection
<!-- <script>
function submitComment() {
    let commentText = document.getElementById("commentText").value.trim();
    if (commentText === "") {
        alert("Vui lòng nhập bình luận.");
        return;
    }

    let eventId = "{{ $event->id }}";

    fetch("{{ url('add_comment.php') }}", {
        method: "POST",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `event_id=${eventId}&comment=${encodeURIComponent(commentText)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert("Lỗi: " + data.message);
        }
    })
    .catch(error => console.error("Lỗi khi gửi bình luận:", error));
}
</script> -->
