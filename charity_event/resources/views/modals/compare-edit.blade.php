<div id="compareModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('compareModal')">&times;</span>
        <h2>So sánh thay đổi</h2>
        <table border="1">
            <tr><th>Trường</th><th>Dữ liệu cũ</th><th>Dữ liệu mới</th></tr>
            <tr><td>Tên sự kiện</td><td>{{ $original->event_name }}</td><td>{{ $edit->event_name }}</td></tr>
            <tr><td>Mô tả</td><td>{!! nl2br(e($original->description)) !!}</td><td>{!! nl2br(e($edit->description)) !!}</td></tr>
            <tr><td>Địa điểm</td><td>{{ $original->location }}</td><td>{{ $edit->location }}</td></tr>
            <tr><td>Mục tiêu</td><td>{{ number_format($original->goal) }} VND</td><td>{{ number_format($edit->goal) }} VND</td></tr>
            <tr><td>Người phụ trách</td><td>{{ $original->organizer_name }}</td><td>{{ $edit->organizer_name }}</td></tr>
            <tr><td>Số điện thoại</td><td>{{ $original->phone }}</td><td>{{ $edit->phone }}</td></tr>
        </table>

        <form id="approveForm" method="POST" action="{{ route('admin.event.approveEdit', ['id' => $event->id]) }}">
            @csrf
            <input type="hidden" name="edit_id" value="{{ $edit->id }}">
            <input type="hidden" name="action" id="formAction" value="">

            <div class="button-group">
                <button type="submit" onclick="submitApprove('approve')">Chấp nhận</button>
                <button type="button" id="showRejectReason">Từ chối</button>
            </div>

            <div class="reject-reason" id="reject-reason" style="display:none; margin-top: 10px;">
                <textarea name="reason" id="reasonTextarea" placeholder="Nhập lý do từ chối..." rows="2"></textarea>
                <button type="submit" onclick="submitApprove('reject')">📩 Gửi</button>
            </div>
        </form>
    </div>
</div>

<script>
function submitApprove(action) {
    event.preventDefault();

    const form = document.getElementById('approveForm');
    const formActionInput = document.getElementById('formAction');
    const textarea = document.getElementById('reasonTextarea');

    formActionInput.value = action;

    if (action === 'approve') {
        textarea.removeAttribute('required');
        form.submit();
    } else if (action === 'reject') {
        if (textarea.value.trim() === '') {
            alert('Vui lòng nhập lý do từ chối.');
            textarea.focus();
            return;
        }
        textarea.setAttribute('required', 'required');
        form.submit();
    }
}

document.getElementById('showRejectReason').addEventListener('click', function () {
    const rejectDiv = document.getElementById('reject-reason');
    rejectDiv.style.display = 'block';
    document.getElementById('reasonTextarea').focus();
});
</script>
