document.addEventListener("DOMContentLoaded", function () {
    const filters = {
        name: document.getElementById("filter-name"),
        startDate: document.getElementById("filter-start-date"),
        endDate: document.getElementById("filter-end-date"),
        site: document.getElementById("filter-site"),
        participation: document.getElementById("filter-participation"),
    };

    const resetButton = document.getElementById("filter-reset");
    const activityCards = document.querySelectorAll(".activity-card");

    function applyFilters() {
        const name = filters.name.value.toLowerCase();
        const startDate = filters.startDate.value;
        const endDate = filters.endDate.value;
        const site = filters.site.value;
        const participation = filters.participation.value;

        activityCards.forEach((card) => {
            const cardName = card.dataset.name;
            const cardDate = card.dataset.date;
            const cardSite = card.dataset.site;
            const cardManager = card.dataset.manager;
            const cardParticipation = card.dataset.participation;


            let isVisible = true;

            if (name && !cardName.includes(name)) isVisible = false;

            if (startDate && cardDate < startDate) isVisible = false;

            if (endDate && cardDate > endDate) isVisible = false;

            if (site && cardSite !== site) isVisible = false;

            if (participation === "created" && cardManager !== "created") isVisible = false;

            if (participation && cardParticipation !== participation) isVisible = false;

            card.style.display = isVisible ? "block" : "none";
        });
    }

    Object.values(filters).forEach((filter) => {
        filter.addEventListener("input", applyFilters);
    });

    resetButton.addEventListener("click", () => {
        Object.values(filters).forEach((filter) => (filter.value = ""));
        applyFilters();
    });
});
