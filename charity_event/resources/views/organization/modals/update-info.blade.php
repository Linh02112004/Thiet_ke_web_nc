<div id="updateInfoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('updateInfoModal')">&times;</span>
        <h1>Cập nhật thông tin Tổ chức</h1>
        <form action="{{ route('organization.updateInfo') }}" method="POST">
            @csrf
            <div class="form-container">
                <div class="form-section">
                    <label for="organization_name">Tên tổ chức:</label>
                    <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name', $user->organization_name) }}" required>

                    <label for="full_name">Tên người đại diện:</label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>

                    <label for="phone">Số điện thoại liên hệ:</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>

                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">

                    <label for="website">Website:</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $user->website ?? '') }}">

                    <label for="social_media">Mạng xã hội:</label>
                    <input type="url" id="social_media" name="social_media" value="{{ old('social_media', $user->social_media ?? '') }}">
                </div>
            </div>
            <button type="submit">Cập nhật thông tin</button>
        </form>
    </div>
</div>
