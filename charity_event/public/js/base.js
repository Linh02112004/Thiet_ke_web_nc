document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const eventCards = document.querySelectorAll(".event-card");

    searchInput.addEventListener("keyup", () => {
        const searchValue = searchInput.value.toLowerCase();
        eventCards.forEach(card => {
            const title = card.querySelector(".card-title").textContent.toLowerCase();
            card.style.display = title.includes(searchValue) ? "block" : "none";
        });
    });
});