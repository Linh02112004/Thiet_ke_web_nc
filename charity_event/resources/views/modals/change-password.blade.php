@php
    $role = Auth::user()->role;
@endphp

<div id="changePasswordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('changePasswordModal')">&times;</span>
        <h2>Thay đổi mật khẩu</h2>

        <form action="{{ $role === 'organization' ? route('organization.changePassword') : route('donor.changePassword') }}" method="POST">
            @csrf
            <div class="form-container">
                <div class="form-section">
                    <label for="current_password">Mật khẩu hiện tại:</label>
                    <input type="password" id="current_password" name="current_password" required>

                    <label for="new_password">Mật khẩu mới:</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="new_password_confirmation">Xác nhận mật khẩu mới:</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>
            </div>
            <button type="submit">Thay đổi mật khẩu</button>
        </form>
    </div>
</div>
