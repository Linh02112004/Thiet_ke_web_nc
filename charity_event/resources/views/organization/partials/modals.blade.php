<!-- Modal tạo sự kiện -->
<div id="modalCreateEvent" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h3 class="text-lg font-bold mb-4">Tạo sự kiện mới</h3>
        <form action="{{ route('organization.event.create') }}" method="POST">
            @csrf
            <label class="block mb-2">Tên sự kiện</label>
            <input type="text" name="event_name" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Thời gian bắt đầu</label>
            <input type="date" name="start_date" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Thời gian kết thúc</label>
            <input type="date" name="end_date" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Địa điểm</label>
            <input type="text" name="location" class="w-full border p-2 rounded mb-3" required>

            <label class="block mb-2">Mục tiêu quyên góp (VNĐ)</label>
            <input type="number" name="amount_target" class="w-full border p-2 rounded mb-3" required>

            <div class="flex justify-end space-x-2">
                <button type="button" id="btnCancelCreate" class="px-4 py-2 bg-gray-400 rounded">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Tạo</button>
            </div>
        </form>
    </div>
</div>

<!-- Các modal cập nhật thông tin và đổi mật khẩu tương tự -->
