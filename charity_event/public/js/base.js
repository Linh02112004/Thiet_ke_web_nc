document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchBox");
    const eventCards = document.querySelectorAll(".event-card");

    searchInput.addEventListener("keyup", () => {
        const searchValue = searchInput.value.toLowerCase();
        eventCards.forEach(card => {
            const title = card.querySelector(".event-title").textContent.toLowerCase();
            card.style.display = title.includes(searchValue) ? "block" : "none";
        });
    });
});
