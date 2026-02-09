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

// page fade-in after load
window.addEventListener('load', () => {
    document.documentElement.classList.add('page-loaded');
});

// Kunden-Card hover spotlight (GSAP + CSS vars)
document.addEventListener("DOMContentLoaded", () => {
    const gsapInstance = window.gsap || null;
    if (!gsapInstance) return;

    const cards = document.querySelectorAll("a.kunden-card");
    if (!cards.length) return;

    cards.forEach((card) => {
        const setX = gsapInstance.quickSetter(card, "--mx", "px");
        const setY = gsapInstance.quickSetter(card, "--my", "px");

        const updatePointer = (event) => {
            const rect = card.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            setX(x);
            setY(y);
        };

        card.addEventListener("mouseenter", (event) => {
            updatePointer(event);
            gsapInstance.to(card, {
                "--spot-opacity": 1,
                "--spot-scale": 1,
                duration: 0.35,
                ease: "power2.out",
                overwrite: true,
            });
        });

        card.addEventListener("mousemove", updatePointer);

        card.addEventListener("mouseleave", () => {
            gsapInstance.killTweensOf(card);
            gsapInstance.to(card, {
                "--spot-opacity": 0,
                "--spot-scale": 0.9,
                duration: 0.35,
                ease: "power2.out",
                overwrite: true,
                onComplete: () => {
                    gsapInstance.set(card, {
                        "--mx": "50%",
                        "--my": "50%",
                    });
                },
            });
        });
    });
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


//slider

document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.slider');
    const slides = Array.from(document.querySelectorAll('.slide'));

    const indicators = Array.from(document.querySelectorAll('.slider-bar .indikator'));
    const btnNext = document.querySelector('.slider-btn.next');
    const btnPrev = document.querySelector('.slider-btn.prev');

    if (!slider || slides.length === 0) return;

    let currentIndex = 0;

    const isMobile = () => window.innerWidth < 751;

    function setMarginLeftByIndex(index) {
        if (index === 0) slider.style.marginLeft = '0';
        else if (index === 1) slider.style.marginLeft = '-100%';
        else if (index === 2) slider.style.marginLeft = '-200%';
    }

    function setActive(index) {
        // Slide active
        slides.forEach(s => s.classList.remove('active'));
        slides[index].classList.add('active');

        // Indicator active (falls vorhanden)
        if (indicators.length) {
            indicators.forEach(i => i.classList.remove('active'));
            if (indicators[index]) indicators[index].classList.add('active');
        }

        // Slider Position
        setMarginLeftByIndex(index);
    }

    function goTo(index) {
        currentIndex = (index + slides.length) % slides.length;
        setActive(currentIndex);
    }

    // Initial state
    setActive(currentIndex);

    // Desktop: click indicators
    indicators.forEach((indikator, idx) => {
        indikator.addEventListener('click', () => {
            if (isMobile()) return; // auf Mobile hast du die Bar eh ausgeblendet
            goTo(idx);
        });
    });

    // Mobile: buttons
    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (!isMobile()) return;
            goTo(currentIndex + 1);
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (!isMobile()) return;
            goTo(currentIndex - 1);
        });
    }

    // Optional: bei Resize Position/Active konsistent halten
    window.addEventListener('resize', () => {
        setActive(currentIndex);
    });
});