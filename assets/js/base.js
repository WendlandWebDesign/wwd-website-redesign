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

// Menu hover: move burger ::before lines via CSS variable
document.querySelectorAll(".menu").forEach(menu => {
    const spans = menu.querySelectorAll(".burger .bar");
    if (!spans.length || !window.gsap) return;

    const tl = window.gsap.timeline({ paused: true });
    spans.forEach((span, index) => {
        const spanW = span.clientWidth;
        const beforeStyle = window.getComputedStyle(span, "::before");
        const beforeW = Math.round(parseFloat(beforeStyle.width)) || 0;
        const maxRight = Math.max(0, Math.round(spanW - beforeW));
        const targetX = index === 1 ? maxRight : -maxRight;
        tl.to(span, {
            "--before-x": `${targetX}px`,
            duration: 0.3,
            ease: "power2.out",
        }, 0);
    });

    menu.addEventListener("mouseenter", () => tl.play());
    menu.addEventListener("mouseleave", () => tl.reverse());
});


//scroll reveal
function revealOnScroll(selector = '.reveal') {
    const elements = document.querySelectorAll(selector);

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target); // stop observing after reveal
            }
        });
    }, {
        threshold: 0,            // Sobald ein Pixel sichtbar ist
        rootMargin: '0px 0px -50px 0px' // 50px vom unteren Viewport-Bereich abziehen
    });

    elements.forEach(el => observer.observe(el));
}

document.addEventListener('DOMContentLoaded', () => {
    revealOnScroll('.reveal');
});


//observer for nav
document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector("nav");
    const hero = document.querySelector(".observe-nav");

    if (!navbar || !hero) return;

    let lastScrollY = window.scrollY;
    let isActive = false;

    // Intersection Observer für Hero
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (!entry.isIntersecting) {
                navbar.classList.add("active");
                isActive = true;
            } else {
                navbar.classList.remove("active", "hide");
                isActive = false;
            }
        },
        {
            threshold: 0,
        }
    );

    observer.observe(hero);

    // Scroll-Logik
    window.addEventListener("scroll", () => {
        if (!isActive) return;

        const currentScrollY = window.scrollY;

        if (currentScrollY > lastScrollY && currentScrollY > 100) {
            // Scroll nach unten
            navbar.classList.add("hide");
        } else {
            // Scroll nach oben
            navbar.classList.remove("hide");
        }

        lastScrollY = currentScrollY;
    });
});
