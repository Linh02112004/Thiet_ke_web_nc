@php
    $role = Auth::user()->role;
    $user = Auth::user();
@endphp

<div id="updateInfoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('updateInfoModal')">&times;</span>
        <h2>Cập nhật thông tin{{ $role === 'organization' ? ' Tổ chức' : '' }}</h2>

        <form action="{{ $role === 'organization' ? route('organization.updateInfo') : route('donor.updateInfo') }}" method="POST">
            @csrf
            <div class="form-container">
                <div class="form-section">

                    @if ($role === 'donor')
                        <input type="hidden" name="role" value="donor">

                        <label for="fullname">Họ và Tên:</label>
                        <input type="text" id="fullname" name="fullname" value="{{ old('fullname', $user->full_name) }}" required>

                        <label for="phone">Số điện thoại:</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>

                        <label for="social_media">Mạng xã hội:</label>
                        <input type="url" id="social_media" name="social_media" value="{{ old('social_media', $user->social_media) }}">
                    
                    @elseif ($role === 'organization')
                        <label for="organization_name">Tên tổ chức:</label>
                        <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name', $user->organization_name) }}" required>

                        <label for="description">Mô tả tổ chức:</label>
                        <textarea id="description" name="description" rows="3">{{ old('description', $user->description) }}</textarea>

                        <label for="phone">Số điện thoại liên hệ:</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>

                        <label for="address">Địa chỉ:</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">

                        <label for="website">Website:</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $user->website ?? '') }}">

                        <label for="social_media">Mạng xã hội:</label>
                        <input type="url" id="social_media" name="social_media" value="{{ old('social_media', $user->social_media ?? '') }}">
                    @endif

                </div>
            </div>
            <button type="submit">Cập nhật thông tin</button>
        </form>
    </div>
</div>
