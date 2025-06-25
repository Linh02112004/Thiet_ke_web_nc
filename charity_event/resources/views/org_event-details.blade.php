@extends('layouts.master')

@section('title', '🌱 HY VỌNG - Tổ chức')

@section('content')
    <div class="container">
        <h1>{{ $event->event_name }}</h1>
        <div class="content-wrapper">
            <!-- Container Left -->
            <div class="container-left">
                <hr>
                <p><strong>Mô tả:</strong> {!! nl2br(e($event->description)) !!}</p>
                <p><strong>Tổ chức:</strong> {{ $event->organizer }}</p>
                <p><strong>Tên người phụ trách:</strong> {{ $event->organizer_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $event->phone }}</p>
                <p><strong>Địa điểm sự kiện:</strong> {{ $event->location }}</p>
                <p><strong>Mục tiêu quyên góp:</strong> {{ number_format($event->goal) }} VND</p>
                <p><strong>Số tiền đã quyên góp:</strong> {{ number_format($event->total_donated) }} VND</p>

                @if ($event->donation_count == 0)
                    <button onclick="window.location.href='{{ url('or_deleteEvents.php?id=' . $event->id) }}'">Xóa sự kiện</button>
                @endif
                <button onclick="openEditModal()">Yêu cầu Sửa Sự kiện</button>

                <hr>
                <h3>Bình luận</h3>
                <div class="comment-box">
                    <textarea id="commentText" placeholder="Nhập bình luận của bạn..." rows="2"></textarea>
                    <button onclick="submitComment()">🗨️</button>
                </div>

                <div id="commentSection">
                    @if ($comments->isNotEmpty())
                        <ul>
                            @foreach ($comments as $comment)
                                <li>
                                    <strong>{{ $comment->commenter_name }}:</strong> {{ $comment->comment }}<br>
                                    <small>{{ $comment->created_at }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Chưa có bình luận nào.</p>
                    @endif
                </div>
            </div>

            <!-- Container Right -->
            <div class="container-right">
                <hr>
                <div class="right-top">
                    <h2>{{ number_format($event->total_donated) }} VND</h2>
                    <p>trong tổng số tiền là {{ number_format($event->goal) }} VND</p>
                    <div class="progress-bar">
                        @php
                            $percent = $event->goal > 0 ? ($event->total_donated / $event->goal) * 100 : 0;
                        @endphp
                        <div class="progress" style="width: {{ $percent }}%;">
                            {{ round($percent) }}%
                        </div>
                    </div>
                    <p>{{ $donations->count() }} lượt quyên góp</p>
                </div>

                <div class="right-bottom">
                    <h3>Danh sách quyên góp</h3>
                    <table border="1">
                        <tr>
                            <th>STT</th>
                            <th>Họ và Tên</th>
                            <th>Số tiền</th>
                            <th>Thời gian</th>
                        </tr>
                        @foreach ($donations as $index => $donation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $donation->donor_name }}</td>
                                <td>{{ number_format($donation->amount) }} VND</td>
                                <td>{{ $donation->donated_at }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('modals.request-edit', ['event' => $event])
@endsection

@push('scripts')
    <script src="{{ asset('js/organization.js') }}"></script>
@endpush

<!-- JS popup & comment -->
<script>
function openEditModal() {
    document.getElementById('editEventModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    let modal = document.getElementById('editEventModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

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
</script>
</body>
</html>
