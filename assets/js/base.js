//burger Menu

const menuBtn = document.querySelector(".menu");
const closeBtn = document.querySelector(".close-btn-wrapper");
const navList = document.querySelector(".nav-list-wrapper");
const siteOverlay = document.querySelector(".site-overlay");


const navContactBtn = document.querySelector(".nav-contact-btn");
const navContactBtnMobile = document.querySelector(".nav-contact-btn-mobile");
const navContact = document.querySelector(".nav-contact-holder");
const navContactCloseBtn = document.querySelector(".nav-contact-close-wrapper");


if (menuBtn && navList && navContact) {
    menuBtn.addEventListener("click", () => {
        document.dispatchEvent(new CustomEvent("nav:open"));
        navList.classList.add('active');
        navContact.classList.remove('active');
        document.body.classList.add('is-nav-open');
    });
}

if (closeBtn && navList) {
    closeBtn.addEventListener("click", () => {
        navList.classList.remove('active');
        document.body.classList.remove('is-nav-open');
    });
}
if (siteOverlay) {
    siteOverlay.addEventListener("click", () => {
        if (navList) navList.classList.remove('active');
        if (navContact) navContact.classList.remove('active');
        document.body.classList.remove('is-nav-open');
    });
}



//nav contact
if (navContactBtn && navContact) {
    navContactBtn.addEventListener("click", () => {
        navContact.classList.add("active");
        if (navList) navList.classList.remove('active');
        document.body.classList.add('is-nav-open');
    });
}

if (navContactBtnMobile && navContact) {
    navContactBtnMobile.addEventListener("click", () => {
        navContact.classList.add("active");
        document.body.classList.add('is-nav-open');
    });
}

if (navContactCloseBtn && navContact) {
    navContactCloseBtn.addEventListener("click", () => {
        navContact.classList.remove("active");
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

    // Intersection Observer fÃ¼r Hero
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

// shared form UX helpers for contact + website-check
const FORM_COOLDOWN_DURATION_MS = 30 * 1000;

function getStoredTimestamp(key) {
    try {
        const value = window.localStorage.getItem(key);
        if (!value) return null;
        const parsed = Number(value);
        return Number.isFinite(parsed) ? parsed : null;
    } catch (error) {
        return null;
    }
}

function setLastSentNow(key) {
    try {
        window.localStorage.setItem(key, String(Date.now()));
    } catch (error) {
        // noop
    }
}

function clearCooldown(key) {
    try {
        window.localStorage.removeItem(key);
    } catch (error) {
        // noop
    }
}

function setStorageFlag(key, value) {
    try {
        if (value) {
            window.localStorage.setItem(key, "1");
        } else {
            window.localStorage.removeItem(key);
        }
    } catch (error) {
        // noop
    }
}

function hasStorageFlag(key) {
    try {
        return window.localStorage.getItem(key) === "1";
    } catch (error) {
        return false;
    }
}

function getCooldownRemaining(key, durationMs) {
    const lastSentAt = getStoredTimestamp(key);
    if (!lastSentAt || lastSentAt <= 0) {
        clearCooldown(key);
        return 0;
    }

    const elapsed = Date.now() - lastSentAt;
    if (!Number.isFinite(elapsed) || elapsed < 0 || elapsed >= durationMs) {
        clearCooldown(key);
        return 0;
    }

    return durationMs - elapsed;
}

function formatDurationMmSs(ms) {
    const totalSeconds = Math.max(0, Math.ceil(ms / 1000));
    const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, "0");
    const seconds = String(totalSeconds % 60).padStart(2, "0");
    return `${minutes}:${seconds}`;
}

function startCooldownTimer({ key, durationMs, onTick, onDone }) {
    let timerId = null;
    let stopped = false;

    const tick = () => {
        if (stopped) return;
        const remainingMs = getCooldownRemaining(key, durationMs);
        if (typeof onTick === "function") onTick(remainingMs);
        if (remainingMs <= 0) {
            if (timerId) clearInterval(timerId);
            timerId = null;
            if (typeof onDone === "function") onDone();
        }
    };

    tick();
    if (getCooldownRemaining(key, durationMs) > 0) {
        timerId = window.setInterval(tick, 1000);
    }

    return () => {
        stopped = true;
        if (timerId) clearInterval(timerId);
    };
}

function consumeSuccessParams(paramNames) {
    if (!Array.isArray(paramNames) || !paramNames.length) return false;
    const url = new URL(window.location.href);
    let successDetected = false;
    let urlChanged = false;

    paramNames.forEach((paramName) => {
        const value = url.searchParams.get(paramName);
        if (value === null) return;
        if (value === "1" || value === "true" || value === "ok") {
            successDetected = true;
            url.searchParams.delete(paramName);
            urlChanged = true;
        }
    });

    if (urlChanged) {
        const query = url.searchParams.toString();
        const nextUrl = `${url.pathname}${query ? `?${query}` : ""}${url.hash}`;
        window.history.replaceState({}, document.title, nextUrl);
    }

    return successDetected;
}

function findScopedElement(form, selector) {
    if (!selector) return null;
    const parentElement = form.parentElement;
    return form.querySelector(selector)
        || (parentElement ? parentElement.querySelector(selector) : null)
        || document.querySelector(selector);
}

function initManagedForm(form, options = {}) {
    if (!form) return;

    const boundFlag = options.boundFlag || "managedBound";
    if (form.dataset[boundFlag] === "1") return;
    form.dataset[boundFlag] = "1";

    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (!submitBtn) return;

    const cooldownKey = options.cooldownKey || "";
    const successStateKey = options.successStateKey
        || (cooldownKey ? `${cooldownKey}_success` : "");
    const durationMs = Number.isFinite(options.cooldownDurationMs)
        ? options.cooldownDurationMs
        : FORM_COOLDOWN_DURATION_MS;

    const feedbackSelector = options.feedbackSelector || options.messageSelector || "";
    const feedbackEl = findScopedElement(form, feedbackSelector);
    const successEl = findScopedElement(form, options.successSelector || "");
    const successText = options.successText || "Formular wurde versendet.";

    const setSubmitDisabled = (disabled) => {
        submitBtn.disabled = disabled;
        submitBtn.setAttribute("aria-disabled", disabled ? "true" : "false");
    };

    const setFeedback = (text, source) => {
        if (!feedbackEl) return;
        feedbackEl.textContent = text || "";
        if (source) {
            feedbackEl.dataset.messageSource = source;
        } else {
            delete feedbackEl.dataset.messageSource;
        }
        feedbackEl.hidden = !text;
    };

    const clearFeedbackBySource = (source) => {
        if (!feedbackEl) return;
        if (feedbackEl.dataset.messageSource === source) {
            setFeedback("", "");
        }
    };

    const setFormVisible = (visible) => {
        form.hidden = !visible;
    };

    const setSuccessVisible = (visible, text = successText) => {
        if (!successEl) return;
        successEl.hidden = !visible;
        successEl.textContent = text;
    };

    let stopCooldown = null;

    const applyCooldownUi = (remainingMs, mode) => {
        if (remainingMs > 0) {
            const formatted = formatDurationMmSs(remainingMs);
            setSubmitDisabled(true);

            if (mode === "success") {
                setFormVisible(false);
                setSuccessVisible(true, successText);
                setFeedback("", "");
            } else {
                setFormVisible(true);
                setSuccessVisible(false);
                setFeedback(`Bitte warten: ${formatted}`, "timer");
            }

            return;
        }

        setSubmitDisabled(false);
        clearFeedbackBySource("timer");
        if (mode === "success") {
            setFormVisible(true);
            setSuccessVisible(false);
        }
    };

    const startCooldown = (mode) => {
        if (!cooldownKey) return;
        if (typeof stopCooldown === "function") {
            stopCooldown();
        }
        stopCooldown = startCooldownTimer({
            key: cooldownKey,
            durationMs,
            onTick: (remainingMs) => applyCooldownUi(remainingMs, mode),
            onDone: () => {
                clearCooldown(cooldownKey);
                if (successStateKey) {
                    setStorageFlag(successStateKey, false);
                }
                applyCooldownUi(0, mode);
            },
        });
    };

    const handleSuccess = () => {
        if (!cooldownKey) return;
        setLastSentNow(cooldownKey);
        if (successStateKey) {
            setStorageFlag(successStateKey, true);
        }
        startCooldown("success");
    };

    form.addEventListener("wwd:form-success", handleSuccess);

    const successDetectedFromUrl = consumeSuccessParams(options.successParams || []);
    if (successDetectedFromUrl) {
        handleSuccess();
    } else if (cooldownKey) {
        const remaining = getCooldownRemaining(cooldownKey, durationMs);
        if (remaining > 0) {
            const successModeActive = successStateKey && hasStorageFlag(successStateKey);
            startCooldown(successModeActive ? "success" : "default");
        } else {
            clearCooldown(cooldownKey);
            if (successStateKey) {
                setStorageFlag(successStateKey, false);
            }
            applyCooldownUi(0, "default");
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
            setFeedback(options.requiredMessage || "Bitte alle Pflichtfelder ausfüllen", "required");
        }
    });

    form.addEventListener("input", () => {
        if (feedbackEl && feedbackEl.dataset.messageSource === "required" && form.checkValidity()) {
            clearFeedbackBySource("required");
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
}

function initContactForm() {
    const form = document.getElementById("contact-form")
        || Array.from(document.querySelectorAll("form.contact-form")).find((candidate) => {
        const actionInput = candidate.querySelector('input[name="action"]');
        return Boolean(actionInput && actionInput.value === "wwd_send_mail_contact");
    }) || document.querySelector('form[data-form="contact"]');

    initManagedForm(form, {
        boundFlag: "contactFormBound",
        cooldownKey: "cooldown_contact_lastSentAt",
        cooldownDurationMs: FORM_COOLDOWN_DURATION_MS,
        feedbackSelector: "#contact-form-message, [data-contact-form-message]",
        successSelector: "#contact-form-success, [data-contact-form-success]",
        successParams: ["sent"],
        requiredMessage: "Bitte alle Pflichtfelder ausfüllen",
        successText: "Formular wurde erfolgreich versendet",
    });
}

function initWebsiteCheckForm() {
    const form = document.getElementById("website-check-form")
        || document.querySelector(".wc-form form.contact-form, form[data-form='website-check']");

    initManagedForm(form, {
        boundFlag: "websiteCheckFormBound",
        cooldownKey: "cooldown_wc_lastSentAt",
        cooldownDurationMs: FORM_COOLDOWN_DURATION_MS,
        feedbackSelector: "#website-check-form-error, [data-website-check-form-error]",
        successSelector: "#website-check-form-success, [data-website-check-form-success]",
        successParams: ["sent"],
        requiredMessage: "Bitte alle Pflichtfelder ausfüllen",
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initContactForm();
    initWebsiteCheckForm();
});

