document.addEventListener("DOMContentLoaded", function () {
    // Hàm thiết lập modal chung
    function setupModal(triggerId, modalId) {
        const trigger = document.getElementById(triggerId);
        const modal = document.getElementById(modalId);
        const closeBtn = modal?.querySelector(".close");

        if (trigger && modal && closeBtn) {
            trigger.addEventListener("click", function (event) {
                event.preventDefault();
                modal.style.display = "block";
            });

            closeBtn.addEventListener("click", function () {
                modal.style.display = "none";
            });

            window.addEventListener("click", function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        }
    }

    // Thiết lập các modal cụ thể
    setupModal("updateInfoLink", "updateInfoModal");
    setupModal("changePasswordLink", "changePasswordModal");
    setupModal("createEventButton", "createEventModal");

    // === MODAL CHỈ SỬ DỤNG JS THUẦN ===
    window.openEditModal = function () {
        const modal = document.getElementById("editEventModal");
        if (modal) modal.style.display = "block";
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "none";
    };

    window.addEventListener("click", function (event) {
        const modal = document.getElementById("editEventModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    const notificationsBtn = document.getElementById("notifications");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notifBadge = document.getElementById("notif-badge");

    notificationsBtn?.addEventListener("click", function (event) {
        event.preventDefault();
        const isVisible = notificationDropdown.style.display === "block";
        notificationDropdown.style.display = isVisible ? "none" : "block";

        if (notifBadge && notifBadge.style.display !== "none") {
            fetch("organization.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "mark_seen=1"
            }).then(res => res.text()).then(responseText => {
                if (responseText === 'success') {
                    notifBadge.style.display = "none";
                    document.querySelectorAll(".notification-dropdown ul li.unread")
                        .forEach(li => li.classList.remove("unread"));
                }
            });
        }
    });

    document.addEventListener("click", function (event) {
        if (!notificationsBtn.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.style.display = "none";
        }
    });
});

console.log("JS loaded");
