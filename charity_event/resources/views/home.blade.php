@if (session('success'))
    <script>alert('{{ session('success') }}');</script>
@endif
@if (session('error'))
    <script>alert('{{ session('error') }}');</script>
@endif


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 HY VỌNG</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <header>
        <h1><a href="{{ route('home') }}">🌱 HY VỌNG</a></h1>
        <div class="header-right">
            <div id="authLinks">
                <div class="auth-buttons">
                    <a id="loginBtn" href="#">Đăng nhập</a> /
                    <a id="registerBtn" href="#">Đăng ký</a>
                </div>
            </div>
            <nav class="home-link-container">
                <a href="#gioithieu">Giới thiệu</a>
                <a href="#huongdan">Hướng dẫn tạo sự kiện</a>
                <a href="#chucnang">Các chức năng chính</a>
            </nav>
        </div>
    </header>

    <main>
        <section id="gioithieu">
            <h2>Giới thiệu</h2>
            <p>Nền tảng giúp tổ chức và quản lý các sự kiện từ thiện một cách hiệu quả.</p>
        </section>
        <section id="huongdan">
            <h2>Hướng dẫn tạo sự kiện</h2>
            <p>Hướng dẫn chi tiết cách tạo một sự kiện mới và quản lý các sự kiện.</p>
        </section>
        <section id="chucnang">
            <h2>Các chức năng chính</h2>
            <ul>
                <li>Quản lý sự kiện</li>
                <li>Đăng ký tham gia</li>
                <li>Hỗ trợ và đóng góp</li>
            </ul>
        </section>
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

    @include('partials.auth-modals')

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
