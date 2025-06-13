<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DANH SÁCH SỰ KIỆN - HY VỌNG</title>
    <link rel="stylesheet" href="{{ asset('css/organization.css') }}">
</head>
<body>
<header>
    <h1><a href="{{ route('organization.org_index') }}">🌱 HY VỌNG</a></h1>
    <div class="header-right">
        <div id="userMenu">
            <span>Xin chào, Tổ chức {{ Auth::user()->organization_name }}</span>
            <div class="dropdown-content">
                <a href="#" onclick="document.getElementById('updateInfoModal').style.display='block'">Cập nhật thông tin</a>
                <a href="#" onclick="document.getElementById('changePasswordModal').style.display='block'">Thay đổi mật khẩu</a>
                <a href="{{ route('logout') }}">Đăng xuất</a>
            </div>
        </div>
    </div>
</header>

<main>
    <div class="text-right mb-4">
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" onclick="document.getElementById('createEventModal').style.display='block'">Tạo sự kiện mới</button>
    </div>

    <h2 class="text-2xl font-bold text-center mb-4">DANH SÁCH SỰ KIỆN</h2>

    <h3 class="text-xl font-semibold mb-2">Sự kiện đang diễn ra</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        @forelse ($ongoingEvents as $event)
            @include('organization.partials.event-card', ['event' => $event])
        @empty
            <p>Không có sự kiện nào.</p>
        @endforelse
    </div>

    <h3 class="text-xl font-semibold mb-2">Sự kiện đã hoàn thành</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($completedEvents as $event)
            @include('organization.partials.event-card', ['event' => $event])
        @empty
            <p>Không có sự kiện nào.</p>
        @endforelse
    </div>

    @include('organization.modals.update-info')
    @include('organization.modals.change-password')
    @include('organization.modals.create-event')
</main>

<footer>
    <div class="footer-container">
        <h1>🌱 HY VỌNG</h1>
        <ul class="footer-links">
            <li><a href="#">Điều khoản & Điều kiện</a></li>
            <li><a href="#">Chính sách bảo mật</a></li>
            <li><a href="#">Chính sách Cookie</a></li>
        </ul>
        <p class="footer-copyright">Copyright © 2025 Hope.</p>
    </div>
</footer>

<script src="{{ asset('js/organization.js') }}"></script>
</body>
</html>
