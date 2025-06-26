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
    setupModal("donateButton", "donationModal");

    // === MODAL JS THUẦN ===
    window.openEditModal = function () {
        const modal = document.getElementById("editEventModal");
        if (modal) modal.style.display = "block";
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "none";
    };

    // === XỬ LÝ QUYÊN GÓP VIETQR ===
    window.closeDonationModal = function () {
        const modal = document.getElementById("donationModal");
        const qrCode = document.getElementById("qrcode");
        const confirmBtn = document.getElementById("confirmBtn");

        if (modal) modal.style.display = "none";
        if (qrCode) qrCode.style.display = "none";
        if (confirmBtn) confirmBtn.style.display = "none";
    };

    window.generateVietQR = function () {
        const amount = document.getElementById("donationAmount").value;
        const account = document.getElementById("donationModal").dataset.account;
        const bankCode = document.getElementById("donationModal").dataset.bankCode;

        if (!amount || amount < 1000 || !bankCode) {
            alert('Ngân hàng không hỗ trợ hoặc số tiền không hợp lệ!');
            return;
        }

        const qrURL = `https://img.vietqr.io/image/${bankCode}-${account}-compact.png?amount=${amount}&addInfo=QuyenGop`;
        const qrCode = document.getElementById("qrcode");
        qrCode.src = qrURL;
        qrCode.style.display = "block";

        setTimeout(() => {
            const confirmBtn = document.getElementById("confirmBtn");
            confirmBtn.style.display = "inline-block";
        }, 5000);
    };

    window.confirmDonation = function () {
        const amount = document.getElementById("donationAmount").value;
        const confirmRoute = document.getElementById("donationModal").dataset.confirmRoute;
        const eventId = document.getElementById("donationModal").dataset.eventId;
        const csrfToken = document.getElementById("donationModal").dataset.csrf;

        fetch(confirmRoute, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ event_id: eventId, amount: amount })
        })
            .then(res => res.json())
            .then(data => {
                alert(data.success ? 'Quyên góp thành công!' : 'Thất bại: ' + data.message);
                if (data.success) location.reload();
            });
    };
});

console.log("Donor JS loaded");
