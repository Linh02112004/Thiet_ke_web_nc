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
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <section class="slideshow-hero">
        <div class="hero-overlay"></div>
        <div class="overlay-content">
            <h1>Quản lý sự kiện từ thiện dễ dàng và hiệu quả</h1>
            <p>
                Từ việc tạo sự kiện, ghi nhận đóng góp đến tương tác với người tham gia – tất cả được thực hiện
                chỉ trong một nền tảng duy nhất. Giao diện thân thiện, dễ sử dụng và minh bạch.
            </p>
        </div>
    </section>
    <section class="how-it-works">
    <h2>Quản lý sự kiện từ thiện hoạt động như thế nào?</h2>
    <div class="steps">
        <div class="step">
            <div class="icon-circle">
                <i class="fas fa-search-dollar"></i>
            </div>
            <h3>1. Tìm chiến dịch</h3>
            <p>Tìm kiếm các chiến dịch gây quỹ mà bạn quan tâm</p>
        </div>
        <div class="step">
            <div class="icon-circle">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3>2. Ủng hộ</h3>
            <p>Chọn mức ủng hộ và thực hiện thanh toán an toàn</p>
        </div>
        <div class="step">
            <div class="icon-circle">
                <i class="fas fa-heart"></i>
            </div>
            <h3>3. Theo dõi</h3>
            <p>Nhận cập nhật về tình hình chiến dịch bạn đã ủng hộ</p>
        </div>
    </div>
</section>
   <section class="stats-section">
    <div class="stats-container">
        <!-- Bên trái: mô tả -->
        <div class="stats-text">
            <p class="stats-subtitle">TỔNG QUAN SỰ KIỆN</p>
            <h2>Quản lý sự kiện từ thiện với các chỉ số minh bạch</h2>
            <p class="stats-description">
                Theo dõi toàn bộ hoạt động của sự kiện từ thiện: tổng chiến dịch, số tiền đã quyên góp, số người tham gia ủng hộ... tất cả hiển thị rõ ràng, minh bạch trong một màn hình duy nhất.
            </p>
            <a href="#" class="stats-button">Xem chi tiết sự kiện</a>
        </div>

        <!-- Bên phải: các chỉ số -->
        <div class="stats-cards">
            <div class="stat-card yellow-card">
                <p class="card-label">CHIẾN DỊCH</p>
                <p class="card-value">12</p>
            </div>
            <div class="stat-card green-card">
                <p class="card-label">ĐÃ QUYÊN GÓP</p>
                <p class="card-value">$8,450</p>
            </div>
            <div class="stat-card blue-card">
                <p class="card-label">NGƯỜI ỦNG HỘ</p>
                <div class="circle-chart">
                    <span>93%</span>
                </div>
            </div>
        </div>
    </div>
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
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const heroSection = document.querySelector('.slideshow-hero');
        const backgroundImages = [
            "{{ asset('img/sukien.jpg') }}",
            "{{ asset('img/sukien1.jpg') }}"
        ];

        let currentIndex = 0;

        function updateBackground() {
            heroSection.style.backgroundImage = `url('${backgroundImages[currentIndex]}')`;
            currentIndex = (currentIndex + 1) % backgroundImages.length;
        }

        updateBackground();
        setInterval(updateBackground, 5000);
    });
</script>

</body>
</html>
