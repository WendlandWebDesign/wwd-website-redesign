//burger Menu

const menuBtn = document.querySelector(".menu");
const closeBtn = document.querySelector(".close-btn-wrapper");
const navList = document.querySelector(".nav-list-wrapper");

menuBtn.addEventListener("click", (e) => {
    navList.classList.add('active');
})
closeBtn.addEventListener("click", (e) => {
    navList.classList.remove('active');
})



// menu expands
document.addEventListener("DOMContentLoaded", () => {
    const expandItems = document.querySelectorAll(".expand-right");
    if (!expandItems.length) return;

    const mqDesktop = window.matchMedia("(min-width: 641px)");
    const mqMobile = window.matchMedia("(max-width: 640px)");

    // --- Initial: erstes Element nur ab 641px aktiv setzen
    if (mqDesktop.matches) {
        const firstItem = expandItems[0];
        const firstContent = firstItem.querySelector(".list-right-content");
        firstItem.classList.add("active");
        if (firstContent) firstContent.classList.add("active");
    }

    // --- Klick auf expand-right: zugehörigen Content aktivieren
    expandItems.forEach(item => {
        item.addEventListener("click", (e) => {
            e.stopPropagation();

            const content = item.querySelector(".list-right-content");
            if (!content) return;

            // alle anderen deaktivieren
            expandItems.forEach(other => {
                if (other !== item) {
                    other.classList.remove("active");
                    const otherContent = other.querySelector(".list-right-content");
                    if (otherContent) otherContent.classList.remove("active");
                }
            });

            // aktuelles aktivieren
            item.classList.add("active");
            content.classList.add("active");
        });
    });

    // --- Back-Buttons: nur auf Mobile (<641px) aktiviertes Submenü schließen
    document.querySelectorAll(".back-btn-wrapper").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();

            if (!mqMobile.matches) return; // nur Handy-Ansicht

            const content = btn.closest(".list-right-content");
            if (!content) return;

            const parentExpand = content.closest(".expand-right");

            content.classList.remove("active");
            if (parentExpand) parentExpand.classList.remove("active");
        });
    });
});
