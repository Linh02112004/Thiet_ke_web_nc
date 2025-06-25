<!-- Modal yêu cầu chỉnh sửa sự kiện -->
<div id="editEventModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width: 600px; margin: auto; padding: 20px; background-color: white; border-radius: 10px;">
        <span onclick="closeModal('editEventModal')" class="close" style="float: right; font-size: 20px; cursor: pointer;">&times;</span>
        <h2>Yêu cầu chỉnh sửa sự kiện</h2>

        <form action="{{ route('organization.event.requestEdit') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <div class="form-group">
                <label for="event_name">Tên sự kiện</label>
                <input type="text" id="event_name" name="event_name" value="{{ old('event_name', $event->event_name) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="location">Địa điểm hỗ trợ</label>
                <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" required>
            </div>

            <div class="form-group">
                <label for="goal">Mục tiêu quyên góp (VND)</label>
                <input type="number" id="goal" name="goal" min="0" value="{{ old('goal', $event->goal) }}" required>
            </div>

            <div class="form-group">
                <label for="organizer_name">Người phụ trách</label>
                <input type="text" id="organizer_name" name="organizer_name" value="{{ old('organizer_name', $event->organizer_name) }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $event->phone) }}" required>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 5px;">Gửi yêu cầu</button>
                <button type="button" onclick="closeModal('editEventModal')" class="btn" style="margin-left: 10px;">Hủy</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modal nền */
.modal {
    position: fixed;
    z-index: 9999;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.modal-content input, .modal-content textarea {
    width: 100%;
    padding: 6px;
    margin-top: 4px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
</style>
