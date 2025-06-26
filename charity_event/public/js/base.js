document.addEventListener("DOMContentLoaded", () => {
    // Tìm kiếm sự kiện
    const searchInput = document.getElementById("searchBox");
    const eventCards = document.querySelectorAll(".event-card");

    if (searchInput) {
        searchInput.addEventListener("keyup", () => {
            const searchValue = searchInput.value.toLowerCase();
            eventCards.forEach(card => {
                const titleEl = card.querySelector(".event-title");
                if (!titleEl) return;
                const title = titleEl.textContent.toLowerCase();
                card.style.display = title.includes(searchValue) ? "block" : "none";
            });
        });
    }

    // Gửi bình luận
    const commentButton = document.querySelector(".comment-box button");
    if (commentButton) {
        commentButton.addEventListener("click", () => {
            const commentInput = document.getElementById("commentText");
            const commentSection = document.getElementById("commentSection");
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!commentInput || !commentSection || !csrfToken) {
                alert("Không thể gửi bình luận. Thiếu phần tử cần thiết.");
                return;
            }

            const commentText = commentInput.value.trim();
            if (!commentText) {
                alert("Vui lòng nhập bình luận.");
                return;
            }

            const eventId = commentSection.dataset.eventId;

            fetch(COMMENT_URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken.getAttribute("content")
                },
                body: JSON.stringify({
                    event_id: eventId,
                    comment: commentText
                })
            })
            .then(res => {
                if (!res.ok) throw new Error("Gửi bình luận thất bại");
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    alert("Bình luận đã được gửi thành công!");
                    location.reload();
                } else {
                    alert("Lỗi: " + data.message);
                }
            })
            .catch(err => {
                console.error("Gửi bình luận lỗi:", err);
                err.response && err.response.text().then(console.log);
                alert("Đã xảy ra lỗi khi gửi bình luận.");
            });
        });
    }
});
