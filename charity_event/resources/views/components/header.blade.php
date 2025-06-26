@php
    $role = Auth::user()->role;
    $homeRoute = match ($role) {
        'admin' => route('ad_index'),
        'organization' => route('org_index'),
        'donor' => route('dn_index'),
        default => '#'
    };

    $greeting = match ($role) {
        'admin' => 'Quản Trị Viên',
        'organization' => 'Tổ chức ' . Auth::user()->organization_name,
        'donor' => Auth::user()->full_name,
        default => 'Người dùng'
    };
@endphp

<header>
    <h1><a href="{{ $homeRoute }}">🌱 HY VỌNG</a></h1>

    {{-- Giao diện người dùng --}}
    <div class="header-right">
        <div id="userMenu">
            <span id="userName">Xin chào, {{ $greeting }}</span>
            <span id="arrowDown" class="arrow">▼</span>
            <div class="dropdown-content">
                @if ($role !== 'admin')
                    <a href="#" id="updateInfoLink">Cập nhật thông tin</a>
                    <a href="#" id="changePasswordLink">Thay đổi mật khẩu</a>
                @endif
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Đăng xuất</a>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

            {{-- Chỉ hiển thị nếu role là organization --}}
    @if ($role === 'organization')
        <div id="authLinks" style="margin-left: auto;">
            <div class="auth-buttons">
                <a id="createEventButton" href="#">Tạo sự kiện</a>

                <div id="notifications-container">
                    <a id="notifications" href="#">Thông báo 
                        @php
                            $unread_count = isset($notifications) 
                                ? collect($notifications)->where('seen', 0)->count() 
                                : 0;
                        @endphp
                        <span id="notif-badge" @if ($unread_count == 0) style="display:none;" @endif>
                            {{ $unread_count }}
                        </span>
                    </a>

                    <div id="notificationDropdown" class="notification-dropdown">
                        <ul id="notificationList">
                            @if (empty($notifications))
                                <li><p>Không có thông báo nào.</p></li>
                            @else
                                @foreach ($notifications as $notif)
                                    <li class="{{ $notif->seen ? '' : 'unread' }}">
                                        <p>{{ $notif->message }}</p>
                                        <small>{{ \Carbon\Carbon::parse($notif->created_at)->format('d/m/Y H:i') }}</small>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
</header>
