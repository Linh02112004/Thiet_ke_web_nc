document.addEventListener("DOMContentLoaded", function () {
    // Mở modal theo ID
    window.openModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "block";
    };

    // Đóng modal theo ID
    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "none";
    };

    // Đóng modal khi click ra ngoài
    window.addEventListener("click", function (event) {
        const compareModal = document.getElementById("compareModal");
        if (event.target === compareModal) {
            compareModal.style.display = "none";
        }
    });

    // Hiện ô nhập lý do từ chối
    const rejectButton = document.querySelector("button[onclick*='reject-reason']");
    const rejectReasonBox = document.getElementById("reject-reason");

    if (rejectButton && rejectReasonBox) {
        rejectButton.addEventListener("click", function () {
            rejectReasonBox.style.display = "block";
        });
    }
});

console.log("Admin JS loaded");
