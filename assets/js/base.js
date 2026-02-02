//burger Menu

const menuBtn = document.querySelector(".menu");
const closeBtn = document.querySelector(".close-btn-wrapper");
const navList = document.querySelector(".nav-list-wrapper");
const siteOverlay = document.querySelector(".site-overlay");

menuBtn.addEventListener("click", (e) => {
    document.dispatchEvent(new CustomEvent("nav:open"));
    navList.classList.add('active');
    document.body.classList.add('is-nav-open');
})
closeBtn.addEventListener("click", (e) => {
    navList.classList.remove('active');
    document.body.classList.remove('is-nav-open');
})
if (siteOverlay) {
    siteOverlay.addEventListener("click", () => {
        navList.classList.remove('active');
        document.body.classList.remove('is-nav-open');
    });
}

