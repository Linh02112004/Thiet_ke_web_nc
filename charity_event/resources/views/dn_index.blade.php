<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 HY VỌNG - Người quyên góp</title>
    <link rel="stylesheet" href="{{ asset('css/donor.css') }}">
</head>
<body>
    @include('components.header')

    <main>
        @include('components.search-box')

        <h2>Sự kiện đang diễn ra</h2>
            <div id="ongoing-events" class="events-list">
            @forelse ($ongoingEvents as $event)
                @include('components.event-card', ['event' => $event])
            @empty
                <p>Không có sự kiện nào.</p>
            @endforelse
        </div>

        <h2>Sự kiện đã hoàn thành</h2>
            <div id="completed-events" class="events-list">
            @forelse ($completedEvents as $event)
                @include('components.event-card', ['event' => $event])
            @empty
                <p>Không có sự kiện nào.</p>
            @endforelse
        </div>

        @include('modals.update-info')
        @include('modals.change-password')
    </main>  

    @include( 'components.footer')

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
