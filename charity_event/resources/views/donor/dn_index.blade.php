<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 HY VỌNG - Người quyên góp</title>
    <link rel="stylesheet" href="{{ asset('css/donor.css') }}">
</head>
<body>
    <header>
        <h1><a href="{{ route('donor.dn_index') }}">🌱 HY VỌNG</a></h1>
        <div class="header-right">
            <div id="userMenu">
                <span id="userName">Xin chào, {{ Auth::user()->full_name }}</span>
                <span id="arrowDown" class="arrow">▼</span>
                <div id="dropdown" class="dropdown-content">
                    <a id="updateInfoLink" href="#">Cập nhật thông tin</a>
                    <a id="changePasswordLink" href="#">Thay đổi mật khẩu</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        Đăng xuất
                    </a>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div id="searchBoxContainer">
            <input type="text" id="searchBox" placeholder="Tìm kiếm sự kiện">
            <button id="searchButton">Tìm kiếm</button>
        </div>

        <h2>Sự kiện đang diễn ra</h2>
        <div id="ongoing-events" class="events-list">
            @foreach ($events as $event)
                @if ($event->status === 'ongoing' && $event->amount_raised < $event->goal)
                    <div class="event-card">
                        <h3>{{ $event->name }}</h3>
                        <div class="event-description">{!! nl2br(e($event->description)) !!}</div>
                        <p><strong>Tổ chức:</strong> {{ $event->organization }}</p>
                        <p><strong>Người phụ trách:</strong> {{ $event->organizer_name }}</p>
                        <p><strong>Địa điểm hỗ trợ:</strong> {{ $event->location }}</p>
                        @php
                            $progress = $event->goal > 0 ? min(100, ($event->amount_raised / $event->goal) * 100) : 0;
                        @endphp
                        <div class="progress-bar">
                            <div class="progress" style="width: {{ $progress }}%;">
                                {{ number_format($progress, 0) }}%
                            </div>
                        </div>
                        <button onclick="window.location.href='dn_eventDetails.php?id={{ $event->event_id }}'">Quyên góp</button>
                    </div>
                @endif
            @endforeach
        </div>

        <h2>Sự kiện đã hoàn thành</h2>
        <div id="completed-events" class="events-list">
            @foreach ($events as $event)
                @if ($event->status === 'completed' || $event->amount_raised >= $event->goal)
                    <div class="event-card">
                        <h3>{{ $event->name }}</h3>
                        <p>{!! nl2br(e($event->description)) !!}</p>
                        <p><strong>Tổ chức:</strong> {{ $event->organization }}</p>
                        <p><strong>Người phụ trách:</strong> {{ $event->organizer_name }}</p>
                        <p><strong>Địa điểm hỗ trợ:</strong> {{ $event->location }}</p>
                        @php
                            $progress = $event->goal > 0 ? min(100, ($event->amount_raised / $event->goal) * 100) : 0;
                        @endphp
                        <div class="progress-bar">
                            <div class="progress" style="width: {{ $progress }}%;">
                                {{ number_format($progress, 0) }}%
                            </div>
                        </div>
                        <button onclick="window.location.href='#?id={{ $event->event_id }}'">Quyên góp</button>
                    </div>
                @endif
            @endforeach
        </div>
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

    <!-- Pop-up Cập nhật thông tin -->
    <div id="updateInfoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('updateInfoModal')">&times;</span>
            <h1>Cập nhật thông tin</h1>
            <form action="updateInfor.php" method="POST">
                <div class="form-container">
                    <div class="form-section">
                        <h2>Thông tin cá nhân</h2>
                        <input type="hidden" name="role" value="donor">
                        <label for="fullname">Họ và Tên:</label>
                        <input type="text" id="fullname" name="fullname" value="{{ $user->full_name }}" required>

                        <label for="phone">Số điện thoại:</label>
                        <input type="tel" id="phone" name="phone" value="{{ $user->phone }}" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}" required>

                        <label for="social_media">Mạng xã hội:</label>
                        <input type="url" id="social_media" name="social_media" value="{{ $user->social_media }}">
                    </div>
                </div>
                <button type="submit">Cập nhật thông tin</button>
            </form>
        </div>
    </div>

    <!-- Pop-up Thay đổi mật khẩu -->
    <div id="changePasswordModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('changePasswordModal')">&times;</span>
            <h1>Thay đổi mật khẩu</h1>
            <form action="changePassword.php" method="POST">
                <div class="form-container">
                    <div class="form-section">
                        <label for="current_password">Mật khẩu hiện tại:</label>
                        <input type="password" id="current_password" name="current_password" required>

                        <label for="new_password">Mật khẩu mới:</label>
                        <input type="password" id="new_password" name="new_password" required>

                        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <button type="submit">Thay đổi mật khẩu</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const updateInfoModal = document.getElementById("updateInfoModal");
            const updateInfoLink = document.getElementById("updateInfoLink");
            const closeUpdateInfo = updateInfoModal.querySelector(".close");

            updateInfoLink.addEventListener("click", function (event) {
                event.preventDefault();
                updateInfoModal.style.display = "block";
            });

            closeUpdateInfo.addEventListener("click", function () {
                updateInfoModal.style.display = "none";
            });

            window.addEventListener("click", function (event) {
                if (event.target === updateInfoModal) {
                    updateInfoModal.style.display = "none";
                }
            });

            const changePasswordModal = document.getElementById("changePasswordModal");
            const changePasswordLink = document.getElementById("changePasswordLink");
            const closeChangePassword = changePasswordModal.querySelector(".close");

            changePasswordLink.addEventListener("click", function (event) {
                event.preventDefault();
                changePasswordModal.style.display = "block";
            });

            closeChangePassword.addEventListener("click", function () {
                changePasswordModal.style.display = "none";
            });

            window.addEventListener("click", function (event) {
                if (event.target === changePasswordModal) {
                    changePasswordModal.style.display = "none";
                }
            });

            const searchBox = document.getElementById("searchBox");
            const eventCards = document.querySelectorAll(".event-card");

            searchBox.addEventListener("input", function () {
                const searchText = searchBox.value.trim().toLowerCase();
                eventCards.forEach(eventCard => {
                    const eventName = eventCard.querySelector("h3").textContent.toLowerCase();
                    eventCard.style.display = eventName.includes(searchText) ? "block" : "none";
                });
            });
        });
    </script>
</body>
</html>
