document.addEventListener("DOMContentLoaded", function () {
    // Modal Cập nhật thông tin
    const updateInfoModal = document.getElementById("updateInfoModal");
    const updateInfoLink = document.getElementById("updateInfoLink"); // Liên kết mở modal
    const closeUpdateInfo = updateInfoModal.querySelector(".close"); // Nút đóng modal

    updateInfoLink.addEventListener("click", function (event) {
        event.preventDefault();
        updateInfoModal.style.display = "block"; // Hiển thị modal
    });

    closeUpdateInfo.addEventListener("click", function () {
        updateInfoModal.style.display = "none"; // Đóng modal
    });

    window.addEventListener("click", function (event) {
        if (event.target === updateInfoModal) {
            updateInfoModal.style.display = "none"; // Đóng modal khi click ra ngoài
        }
    });

    // Modal Thay đổi mật khẩu
    const changePasswordModal = document.getElementById("changePasswordModal");
    const changePasswordLink = document.getElementById("changePasswordLink"); // Liên kết mở modal
    const closeChangePassword = changePasswordModal.querySelector(".close"); // Nút đóng modal

    changePasswordLink.addEventListener("click", function (event) {
        event.preventDefault();
        changePasswordModal.style.display = "block"; // Hiển thị modal
    });

    closeChangePassword.addEventListener("click", function () {
        changePasswordModal.style.display = "none"; // Đóng modal
    });

    window.addEventListener("click", function (event) {
        if (event.target === changePasswordModal) {
            changePasswordModal.style.display = "none"; // Đóng modal khi click ra ngoài
        }
    });

    // Xử lý modal tạo sự kiện
    const create_eventModal = document.getElementById("create_eventModal");
    const createEventButton = document.getElementById("createEventButton");
    const closeButton = create_eventModal.querySelector(".close");

    createEventButton.addEventListener("click", function (event) {
        event.preventDefault();
        create_eventModal.style.display = "block";
    });

    closeButton.addEventListener("click", function () {
        create_eventModal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === create_eventModal) {
            create_eventModal.style.display = "none";
        }
    });

    // Xử lý thông báo
    const notificationsBtn = document.getElementById("notifications");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notifBadge = document.getElementById("notif-badge");

    notificationsBtn.addEventListener("click", function (event) {
        event.preventDefault();

        // Thay đổi trạng thái của dropdown
        if (notificationDropdown.style.display === "none" || notificationDropdown.style.display === "") {
            notificationDropdown.style.display = "block"; // Hiển thị dropdown
        } else {
            notificationDropdown.style.display = "none"; // Ẩn dropdown
        }

        // Gửi yêu cầu AJAX để đánh dấu thông báo là đã đọc
        if (notifBadge && notifBadge.style.display !== "none") {
            fetch("organization.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "mark_seen=1" // Gửi yêu cầu để đánh dấu tất cả thông báo là đã đọc
            }).then(response => response.text())
                .then(responseText => {
                    if (responseText === 'success') {
                        notifBadge.style.display = "none"; // Ẩn badge khi tất cả thông báo đã được đánh dấu là đã đọc
                        document.querySelectorAll(".notification-dropdown ul li.unread").forEach(li => {
                            li.classList.remove("unread"); // Xóa class "unread" từ các thông báo
                        });
                    }
                });
        }
    });

    // Đóng dropdown khi click ra ngoài
    document.addEventListener("click", function (event) {
        if (!notificationsBtn.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.style.display = "none"; // Ẩn dropdown khi click ngoài
        }
    });

    // Tm kiếm Sự kiện theo tên
    const searchBox = document.getElementById("searchBox");
    const eventCards = document.querySelectorAll(".event-card");

    searchBox.addEventListener("input", function () {
        const searchText = searchBox.value.trim().toLowerCase();

        eventCards.forEach(eventCard => {
            const eventName = eventCard.querySelector("h3").textContent.toLowerCase();
            if (eventName.includes(searchText)) {
                eventCard.style.display = "block";
            } else {
                eventCard.style.display = "none";
            }
        });
    });
});