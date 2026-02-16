//burger Menu

const menuBtn = document.querySelector(".menu");
const closeBtn = document.querySelector(".close-btn-wrapper");
const navList = document.querySelector(".nav-list-wrapper");
const siteOverlay = document.querySelector(".site-overlay");


const navContactBtn = document.querySelector(".nav-contact-btn");
const navContactBtnMobile = document.querySelector(".nav-contact-btn-mobile");
const navContact = document.querySelector(".nav-contact-holder");
const navContactCloseBtn = document.querySelector(".nav-contact-close-wrapper");


menuBtn.addEventListener("click", (e) => {
    document.dispatchEvent(new CustomEvent("nav:open"));
    navList.classList.add('active');
    navContact.classList.remove('active');
    document.body.classList.add('is-nav-open');
})
closeBtn.addEventListener("click", (e) => {
    navList.classList.remove('active');
    document.body.classList.remove('is-nav-open');
})
if (siteOverlay) {
    siteOverlay.addEventListener("click", () => {
        navList.classList.remove('active');
        navContact.classList.remove('active');
        document.body.classList.remove('is-nav-open');
    });
}



//nav contact
navContactBtn.addEventListener("click", function() {
    navContact.classList.add("active");
    navList.classList.remove('active');
    document.body.classList.add('is-nav-open');
})
navContactBtnMobile.addEventListener("click", function() {
    navContact.classList.add("active");
    document.body.classList.add('is-nav-open');
})
navContactCloseBtn.addEventListener("click", function() {
    navContact.classList.remove("active");
    document.body.classList.remove('is-nav-open');
})




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
        const ease = 0.1;
        let targetX = 0;
        let targetY = 0;
        let currentX = 0;
        let currentY = 0;
        let rafId = null;

        const updatePointer = (event) => {
            const rect = card.getBoundingClientRect();
            targetX = event.clientX - rect.left;
            targetY = event.clientY - rect.top;
        };

        const tick = () => {
            currentX += (targetX - currentX) * ease;
            currentY += (targetY - currentY) * ease;
            setX(currentX);
            setY(currentY);
            rafId = requestAnimationFrame(tick);
        };

        const startRaf = () => {
            if (rafId) return;
            rafId = requestAnimationFrame(tick);
        };

        const stopRaf = () => {
            if (!rafId) return;
            cancelAnimationFrame(rafId);
            rafId = null;
        };

        card.addEventListener("mouseenter", (event) => {
            updatePointer(event);
            currentX = targetX;
            currentY = targetY;
            setX(currentX);
            setY(currentY);
            startRaf();
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
            stopRaf();
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
        if (document.body.classList.contains("is-nav-open")) {
            lastScrollY = window.scrollY;
            return;
        }

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
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const slides = Array.from(document.querySelectorAll('.slide'));

    const indicators = Array.from(document.querySelectorAll('.slider-bar .indikator'));
    const btnNext = document.querySelector('.slider-btn.next');
    const btnPrev = document.querySelector('.slider-btn.prev');

    if (!slider || !sliderWrapper || slides.length === 0) return;

    let currentIndex = 0;
    let timer = null;
    let isPaused = false;
    const autoplayDelay = 5000;
    const autoplayEnabled = true;
    const minSwipeDistance = 40;
    const maxVerticalDrift = 80;
    const horizontalDominanceFactor = 1.2;
    const interactiveSelector = 'a,button,input,textarea,select,label,[data-no-pause]';
    const isMobile = () => window.innerWidth < 801;
    const isInteractive = (el) => Boolean(el && el.closest(interactiveSelector));

    let startX = 0;
    let startY = 0;
    let isPointerTracking = false;
    let pointerId = null;
    let swipeMoved = false;

    function setMarginLeftByIndex(index) {
        if (index === 0) slider.style.marginLeft = '0';
        else if (index === 1) slider.style.marginLeft = '-100%';
        else if (index === 2) slider.style.marginLeft = '-200%';
    }

    function setActive(index) {
        slides.forEach((s) => s.classList.remove('active'));
        slides[index].classList.add('active');

        if (indicators.length) {
            indicators.forEach((i) => i.classList.remove('active'));
            if (indicators[index]) indicators[index].classList.add('active');
        }

        setMarginLeftByIndex(index);
    }

    function goTo(index) {
        currentIndex = (index + slides.length) % slides.length;
        setActive(currentIndex);
    }

    function stopAutoplay() {
        if (!timer) return;
        clearInterval(timer);
        timer = null;
    }

    function startAutoplay() {
        if (!autoplayEnabled || isPaused || timer) return;
        timer = setInterval(() => {
            goTo(currentIndex + 1);
        }, autoplayDelay);
    }

    function resetAutoplayAfterInteraction() {
        if (!autoplayEnabled || isPaused) return;
        stopAutoplay();
        startAutoplay();
    }

    function togglePause() {
        isPaused = !isPaused;
        sliderWrapper.classList.toggle('is-paused', isPaused);
        if (isPaused) {
            stopAutoplay();
            return;
        }
        startAutoplay();
    }

    function handleManualNavigation(nextIndex) {
        goTo(nextIndex);
        resetAutoplayAfterInteraction();
    }

    function processSwipe(deltaX, deltaY) {
        const absX = Math.abs(deltaX);
        const absY = Math.abs(deltaY);
        const isHorizontalSwipe =
            absX >= minSwipeDistance &&
            absY <= maxVerticalDrift &&
            absX > absY * horizontalDominanceFactor;

        if (!isHorizontalSwipe) return false;
        if (deltaX < 0) handleManualNavigation(currentIndex + 1);
        else handleManualNavigation(currentIndex - 1);
        return true;
    }

    function onPointerDown(event) {
        if (event.pointerType === 'mouse' && event.button !== 0) return;
        startX = event.clientX;
        startY = event.clientY;
        isPointerTracking = true;
        pointerId = event.pointerId;
        swipeMoved = false;
    }

    function onPointerMove(event) {
        if (!isPointerTracking || pointerId !== event.pointerId) return;
        if (Math.abs(event.clientX - startX) > 4 || Math.abs(event.clientY - startY) > 4) {
            swipeMoved = true;
        }
    }

    function endPointerTracking(event) {
        if (!isPointerTracking || pointerId !== event.pointerId) return;
        const deltaX = event.clientX - startX;
        const deltaY = event.clientY - startY;
        processSwipe(deltaX, deltaY);
        isPointerTracking = false;
        pointerId = null;
    }

    function bindSwipeHandlers() {
        if ('PointerEvent' in window) {
            sliderWrapper.addEventListener('pointerdown', onPointerDown, { passive: true });
            sliderWrapper.addEventListener('pointermove', onPointerMove, { passive: true });
            sliderWrapper.addEventListener('pointerup', endPointerTracking, { passive: true });
            sliderWrapper.addEventListener('pointercancel', endPointerTracking, { passive: true });
            return;
        }

        sliderWrapper.addEventListener('touchstart', (event) => {
            const touch = event.changedTouches[0];
            if (!touch) return;
            startX = touch.clientX;
            startY = touch.clientY;
        }, { passive: true });

        sliderWrapper.addEventListener('touchend', (event) => {
            const touch = event.changedTouches[0];
            if (!touch) return;
            const deltaX = touch.clientX - startX;
            const deltaY = touch.clientY - startY;
            processSwipe(deltaX, deltaY);
        }, { passive: true });
    }

    setActive(currentIndex);
    startAutoplay();
    bindSwipeHandlers();

    indicators.forEach((indikator, idx) => {
        indikator.addEventListener('click', () => {
            if (isMobile()) return;
            handleManualNavigation(idx);
        });
    });

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (!isMobile()) return;
            handleManualNavigation(currentIndex + 1);
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (!isMobile()) return;
            handleManualNavigation(currentIndex - 1);
        });
    }

    sliderWrapper.addEventListener('click', (event) => {
        if (swipeMoved) {
            swipeMoved = false;
            return;
        }
        if (isInteractive(event.target)) return;
        togglePause();
    });

window.addEventListener('resize', () => {
        setActive(currentIndex);
    });
});

// contact form: client-side 30s lock UX + required-field hint
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".contact-form");
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const messageEl = document.getElementById("contact-form-message");
    const lockEl = document.getElementById("mail-lock");
    if (!submitBtn || !messageEl) return;

    let timerId = null;
    let remainingSeconds = 0;

    const setMessage = (text, source) => {
        messageEl.textContent = text;
        messageEl.dataset.messageSource = source;
    };

    const clearMessageBySource = (source) => {
        if (messageEl.dataset.messageSource === source) {
            messageEl.textContent = "";
            delete messageEl.dataset.messageSource;
        }
    };

    const stopTimer = () => {
        if (timerId) {
            clearInterval(timerId);
            timerId = null;
        }
    };

    const renderLockState = () => {
        if (remainingSeconds <= 0) {
            submitBtn.disabled = false;
            clearMessageBySource("timer");
            stopTimer();
            return;
        }

        submitBtn.disabled = true;
        setMessage(`Bitte warten Sie ${remainingSeconds} Sekunden, bevor Sie erneut senden.`, "timer");

        if (!timerId) {
            timerId = setInterval(() => {
                remainingSeconds -= 1;
                renderLockState();
            }, 1000);
        }
    };

    if (lockEl && lockEl.dataset.remaining) {
        const parsedRemaining = Number(lockEl.dataset.remaining);
        if (Number.isFinite(parsedRemaining) && parsedRemaining > 0) {
            remainingSeconds = Math.ceil(parsedRemaining);
        }
    }

    const getFieldLabel = (field) => {
        if (!field || !field.id) return null;
        return form.querySelector(`label[for="${field.id}"]`);
    };

    const syncInvalidLabel = (field) => {
        if (field instanceof HTMLInputElement && field.type === "checkbox") {
            return;
        }
        const label = getFieldLabel(field);
        if (!label) return;
        if (field.checkValidity()) {
            label.classList.remove("is-invalid");
        } else {
            label.classList.add("is-invalid");
        }
    };

    const syncCheckboxInvalidState = (field) => {
        if (!(field instanceof HTMLInputElement) || field.type !== "checkbox") {
            return;
        }
        const isInvalid = field.required && !field.checked;
        field.classList.toggle("is-invalid", isInvalid);
    };

    const requiredFields = Array.from(form.querySelectorAll("input[required], textarea[required], select[required]"));
    const valueFields = Array.from(
        form.querySelectorAll('input:not([type="checkbox"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="file"]), textarea')
    );

    const syncHasValueClass = (field) => {
        if (field.value.trim() !== "") {
            field.classList.add("has-value");
        } else {
            field.classList.remove("has-value");
        }
    };

    const syncAllHasValueClasses = () => {
        valueFields.forEach((field) => {
            syncHasValueClass(field);
        });
    };

    submitBtn.addEventListener("click", () => {
        requiredFields.forEach((field) => {
            syncCheckboxInvalidState(field);
            syncInvalidLabel(field);
        });
        if (!form.checkValidity()) {
            setMessage("Bitte alle Pflichtfelder ausf\u00fcllen", "required");
        }
    });

    form.addEventListener("input", () => {
        if (messageEl.dataset.messageSource === "required" && form.checkValidity()) {
            clearMessageBySource("required");
        }
    });

    form.addEventListener("input", (event) => {
        const field = event.target;
        if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement)) {
            return;
        }
        if (!field.matches("input[required], textarea[required], select[required]")) {
            if (field.matches('input:not([type="checkbox"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="file"]), textarea')) {
                syncHasValueClass(field);
            }
            return;
        }
        syncHasValueClass(field);
        syncInvalidLabel(field);
    });

    form.addEventListener("change", (event) => {
        const field = event.target;
        if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement)) {
            return;
        }
        if (field instanceof HTMLInputElement && field.type === "checkbox") {
            if (field.checked) {
                field.classList.remove("is-invalid");
            }
            return;
        }
        if (!field.matches('input:not([type="checkbox"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="file"]), textarea')) {
            return;
        }
        syncHasValueClass(field);
    });
    syncAllHasValueClasses();
    setTimeout(syncAllHasValueClasses, 250);

    renderLockState();
});

// website-check form: server-driven lock UX + has-value + invalid states
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("website-check-form");
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const messageEl = document.getElementById("website-check-form-error");
    const lockEl = document.getElementById("website-check-mail-lock");
    if (!submitBtn || !messageEl) return;

    let timerId = null;
    let remainingSeconds = 0;

    const setMessage = (text, source) => {
        messageEl.textContent = text;
        messageEl.dataset.messageSource = source;
    };

    const clearMessageBySource = (source) => {
        if (messageEl.dataset.messageSource === source) {
            messageEl.textContent = "";
            delete messageEl.dataset.messageSource;
        }
    };

    const stopTimer = () => {
        if (timerId) {
            clearInterval(timerId);
            timerId = null;
        }
    };

    const renderLockState = () => {
        if (remainingSeconds <= 0) {
            submitBtn.disabled = false;
            clearMessageBySource("timer");
            stopTimer();
            return;
        }

        submitBtn.disabled = true;
        setMessage(`Bitte warten Sie ${remainingSeconds} Sekunden, bevor Sie erneut senden.`, "timer");

        if (!timerId) {
            timerId = setInterval(() => {
                remainingSeconds -= 1;
                renderLockState();
            }, 1000);
        }
    };

    if (lockEl && lockEl.dataset.remaining) {
        const parsedRemaining = Number(lockEl.dataset.remaining);
        if (Number.isFinite(parsedRemaining) && parsedRemaining > 0) {
            remainingSeconds = Math.ceil(parsedRemaining);
        }
    }

    const getFieldLabel = (field) => {
        if (!field || !field.id) return null;
        return form.querySelector(`label[for="${field.id}"]`);
    };

    const syncInvalidLabel = (field) => {
        if (field instanceof HTMLInputElement && field.type === "checkbox") {
            return;
        }
        const label = getFieldLabel(field);
        if (!label) return;
        if (field.checkValidity()) {
            label.classList.remove("is-invalid");
        } else {
            label.classList.add("is-invalid");
        }
    };

    const syncCheckboxInvalidState = (field) => {
        if (!(field instanceof HTMLInputElement) || field.type !== "checkbox") {
            return;
        }
        const isInvalid = field.required && !field.checked;
        field.classList.toggle("is-invalid", isInvalid);
    };

    const requiredFields = Array.from(form.querySelectorAll("input[required], textarea[required], select[required]"));
    const valueFields = Array.from(
        form.querySelectorAll('input:not([type="checkbox"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="file"]), textarea')
    );

    const syncHasValueClass = (field) => {
        if (field.value.trim() !== "") {
            field.classList.add("has-value");
        } else {
            field.classList.remove("has-value");
        }
    };

    const syncAllHasValueClasses = () => {
        valueFields.forEach((field) => {
            syncHasValueClass(field);
        });
    };

    submitBtn.addEventListener("click", () => {
        requiredFields.forEach((field) => {
            syncCheckboxInvalidState(field);
            syncInvalidLabel(field);
        });
        if (!form.checkValidity()) {
            setMessage("Bitte alle Pflichtfelder ausfüllen", "required");
        }
    });

    form.addEventListener("input", () => {
        if (messageEl.dataset.messageSource === "required" && form.checkValidity()) {
            clearMessageBySource("required");
        }
    });

    form.addEventListener("input", (event) => {
        const field = event.target;
        if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement)) {
            return;
        }
        if (!field.matches("input[required], textarea[required], select[required]")) {
            if (field.matches('input:not([type="checkbox"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="file"]), textarea')) {
                syncHasValueClass(field);
            }
            return;
        }
        syncHasValueClass(field);
        syncInvalidLabel(field);
    });

    form.addEventListener("change", (event) => {
        const field = event.target;
        if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement)) {
            return;
        }
        if (field instanceof HTMLInputElement && field.type === "checkbox") {
            if (field.checked) {
                field.classList.remove("is-invalid");
            }
            return;
        }
        if (!field.matches('input:not([type="checkbox"]):not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="file"]), textarea')) {
            return;
        }
        syncHasValueClass(field);
    });

    syncAllHasValueClasses();
    setTimeout(syncAllHasValueClasses, 250);

    renderLockState();
});
