const HERO_SNAKE_DEBUG = false;
if (HERO_SNAKE_DEBUG) {
    console.log("[animations.js] loaded");
}

const getGsapScrollTrigger = () => {
    const gsapInstance = window.gsap || null;
    const ScrollTrigger = window.ScrollTrigger || null;
    if (!gsapInstance || !ScrollTrigger) return null;
    if (!window.__wwdScrollTriggerRegistered) {
        gsapInstance.registerPlugin(ScrollTrigger);
        window.__wwdScrollTriggerRegistered = true;
    }
    return { gsapInstance, ScrollTrigger };
};

// menu expands
document.addEventListener("DOMContentLoaded", () => {
    const expandItems = document.querySelectorAll(".expand-right");
    if (!expandItems.length) return;
    const panels = document.querySelectorAll(".list-right-content");
    const listRightWrapper = document.querySelector(".list-right-wrapper");
    const gsapInstance = window.gsap || null;
    let activePanel = document.querySelector(".list-right-content.active");
    let currentTimeline = null;

    const mqDesktop = window.matchMedia("(min-width: 641px)");
    const mqMobile = window.matchMedia("(max-width: 640px)");
    const updatePanelState = () => {
        if (!listRightWrapper) return;
        const anyActive = document.querySelector(".list-right-content.active");
        listRightWrapper.classList.toggle("has-active-panel", Boolean(anyActive));
    };

    // --- Initial: erstes Element nur ab 641px aktiv setzen
    if (mqDesktop.matches) {
        const firstItem = expandItems[0];
        const targetKey = firstItem.getAttribute("data-nav-target");
        const firstContent = targetKey
            ? document.querySelector(`.list-right-content[data-nav-panel="${targetKey}"]`)
            : null;
        firstItem.classList.add("active");
        if (firstContent) firstContent.classList.add("active");
        activePanel = firstContent || activePanel;
        updatePanelState();
    }

    const getCards = (panel) => {
        if (!panel) return [];
        return Array.from(panel.querySelectorAll(".js-right-card"));
    };

    const getCta = (panel) => {
        if (!panel) return null;
        return panel.querySelector(".js-right-cta");
    };

    const ENTER_CARD_X = -28;
    const ENTER_CTA_X = -64;

    const setPanelInitialState = (panel) => {
        if (!gsapInstance || !panel) return;
        const cards = getCards(panel);
        const cta = getCta(panel);
        if (cta) {
            gsapInstance.set(cta, { autoAlpha: 0, x: ENTER_CTA_X });
        }
        if (cards.length) {
            gsapInstance.set(cards, { autoAlpha: 0, x: ENTER_CARD_X });
        }
    };

    const animatePanelSwitch = (fromPanel, toPanel) => {
        if (!gsapInstance) return;
        if (currentTimeline) {
            currentTimeline.kill();
            currentTimeline = null;
        }

        const fromCards = getCards(fromPanel);
        const toCards = getCards(toPanel);
        const fromCta = getCta(fromPanel);
        const toCta = getCta(toPanel);

        currentTimeline = gsapInstance.timeline({ defaults: { ease: "power2.out" } });

        if (fromCta) {
            currentTimeline.to(fromCta, {
                autoAlpha: 0,
                x: 16,
                duration: 0.18,
            });
        }

        if (fromCards.length) {
            currentTimeline.to(fromCards, {
                autoAlpha: 0,
                x: 16,
                duration: 0.22,
                stagger: 0.04,
            });
        }

        currentTimeline.add(() => {
            panels.forEach(panel => panel.classList.remove("active"));
            if (toPanel) {
                setPanelInitialState(toPanel);
                toPanel.classList.add("active");
            }
            activePanel = toPanel || null;
            updatePanelState();
        }, ">");
        currentTimeline.addLabel("enter", ">");

        if (toCta) {
            currentTimeline.to(toCta, {
                autoAlpha: 1,
                x: 0,
                duration: 0.3,
                ease: "power2.out",
            }, "enter");
        }

        if (toCards.length) {
            currentTimeline.to(toCards, {
                autoAlpha: 1,
                x: 0,
                duration: 0.25,
                stagger: 0.08,
                ease: "power2.out",
            }, toCta ? "enter+=0.06" : "enter");
        }
    };

    // --- Klick auf expand-right: zugehrigen Content aktivieren
    expandItems.forEach(item => {
        item.addEventListener("click", (e) => {
            e.stopPropagation();

            const targetKey = item.getAttribute("data-nav-target");
            const content = targetKey
                ? document.querySelector(`.list-right-content[data-nav-panel="${targetKey}"]`)
                : null;
            if (!content) return;

            const previousPanel = activePanel;
            if (previousPanel === content) return;

            // alle anderen deaktivieren
            expandItems.forEach(other => {
                if (other !== item) {
                    other.classList.remove("active");
                }
            });

            // aktuelles aktivieren
            item.classList.add("active");
            if (!gsapInstance) {
                panels.forEach(panel => panel.classList.remove("active"));
                content.classList.add("active");
                activePanel = content;
                updatePanelState();
                return;
            }

            animatePanelSwitch(previousPanel, content);
        });
    });

    // --- Back-Buttons: nur auf Mobile (<641px) aktiviertes Submenu schließen
    document.querySelectorAll(".back-btn-wrapper").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();

            if (!mqMobile.matches) return; // nur Handy-Ansicht

            const content = btn.closest(".list-right-content");
            if (!content) return;

            // reset all expand items before switching panels
            expandItems.forEach(item => item.classList.remove("active"));

            const targetKey = content.getAttribute("data-nav-panel");
            const parentExpand = targetKey
                ? document.querySelector(`.expand-right[data-nav-target="${targetKey}"]`)
                : null;

            content.classList.remove("active");
            if (parentExpand) parentExpand.classList.remove("active");
            activePanel = null;
            updatePanelState();
        });
    });

    document.addEventListener("nav:open", () => {
        if (!gsapInstance || !activePanel) return;
        if (currentTimeline) {
            currentTimeline.kill();
            currentTimeline = null;
        }
        setPanelInitialState(activePanel);
        currentTimeline = gsapInstance.timeline({ defaults: { ease: "power2.out" } });
        const cta = getCta(activePanel);
        const cards = getCards(activePanel);
        if (cta) {
            currentTimeline.to(cta, {
                autoAlpha: 1,
                x: 0,
                duration: 0.3,
                ease: "power2.out",
            }, 0);
        }
        if (cards.length) {
            currentTimeline.to(cards, {
                autoAlpha: 1,
                x: 0,
                duration: 0.25,
                stagger: 0.08,
                ease: "power2.out",
            }, cta ? 0.06 : 0);
        }
    });
});

const runBorderSnakeOnce = (btn, options = {}) => {
    if (!btn) return false;

    const duration = Number.isFinite(options.duration) ? options.duration : 800;
    const onComplete = typeof options.onComplete === "function" ? options.onComplete : null;
    const svg = btn.querySelector(".btn__svg");
    const path = btn.querySelector(".btn__path") || btn.querySelector(".btn__seg--1");
    const segs = Array.from(btn.querySelectorAll(".btn__seg"));

    if (HERO_SNAKE_DEBUG) {
        console.log("[border-snake] svg:", svg);
        console.log("[border-snake] path:", path);
        console.log("[border-snake] segments:", segs.length);
    }
    if (!svg || !path || segs.length !== 4) return false;

    const w = Math.max(1, Math.round(btn.clientWidth));
    const h = Math.max(1, Math.round(btn.clientHeight));
    const strokeWidth = 4;
    const inset = strokeWidth / 2;

    svg.setAttribute("width", `${w}`);
    svg.setAttribute("height", `${h}`);
    svg.setAttribute("viewBox", `0 0 ${w} ${h}`);
    svg.setAttribute("preserveAspectRatio", "none");

    const d = `M${inset},${inset} H${w - inset} V${h - inset} H${inset} Z`;
    path.setAttribute("d", d);
    segs.forEach((seg) => {
        seg.setAttribute("d", d);
        seg.setAttribute("vector-effect", "non-scaling-stroke");
    });

    const length = path.getTotalLength();
    if (!length || !Number.isFinite(length)) return false;

    const innerW = Math.max(1, w - strokeWidth);
    const innerH = Math.max(1, h - strokeWidth);
    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
    const lenLong = clamp(innerW * 0.60, innerW * 0.40, innerW * 0.75);
    const lenShort = clamp(innerH * 0.50, innerH * 0.35, innerH * 0.70);

    // Idle corner positions from btn-border-snake.js
    const starts = [
        { len: lenShort, offset: 0.00 * length },
        { len: lenLong, offset: 0.05 * length },
        { len: lenShort, offset: 0.50 * length },
        { len: lenLong, offset: 0.55 * length },
    ];

    const applyIdleState = () => {
        segs.forEach((seg, idx) => {
            const segLen = starts[idx].len;
            const dashArray = `${segLen} ${length - segLen}`;
            seg.style.strokeDasharray = dashArray;
            seg.style.strokeDashoffset = `${starts[idx].offset}`;
        });
    };

    const easeInOutQuad = (t) => (t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2);

    applyIdleState();
    btn.dataset.borderSnakeRunning = "1";
    if (HERO_SNAKE_DEBUG) {
        console.log("[border-snake] starting");
    }

    const startOffsets = starts.map((s) => s.offset);
    const targetShift = length * -1;
    let start = null;

    const step = (ts) => {
        if (btn.dataset.borderSnakeRunning !== "1") return;
        if (start === null) start = ts;
        const p = Math.min(1, (ts - start) / duration);
        const eased = easeInOutQuad(p);
        const delta = eased * targetShift;
        segs.forEach((seg, idx) => {
            seg.style.strokeDashoffset = `${startOffsets[idx] + delta}`;
        });
        if (p < 1) {
            requestAnimationFrame(step);
            return;
        }
        applyIdleState();
        btn.dataset.borderSnakeRunning = "0";
        if (onComplete) onComplete();
        if (HERO_SNAKE_DEBUG) {
            console.log("[border-snake] done + cleaned");
        }
    };

    requestAnimationFrame(() => requestAnimationFrame(step));
    return true;
};

// Adapted from btn-border-snake.js for hero load animation.
const runHeroLoadSnake = (btn, options = {}) => {
    if (!btn || btn.dataset.heroSnakeDone === "1" || btn.dataset.heroSnakeRunning === "1") return;
    btn.dataset.heroSnakeRunning = "1";
    if (!runBorderSnakeOnce(btn, options)) {
        btn.dataset.heroSnakeRunning = "0";
        return;
    }
    const duration = Number.isFinite(options.duration) ? options.duration : 800;
    setTimeout(() => {
        btn.dataset.heroSnakeRunning = "0";
        btn.dataset.heroSnakeDone = "1";
        if (HERO_SNAKE_DEBUG) {
            console.log("[hero-load-snake] done + cleaned");
        }
    }, duration + 30);
};

const initHeroLoadSnake = () => {
    const selector = '[data-hero-snake-load="1"]';
    let observer = null;
    let rafId = 0;
    let timeoutId = 0;

    const stopAll = () => {
        if (observer) observer.disconnect();
        if (rafId) cancelAnimationFrame(rafId);
        if (timeoutId) clearTimeout(timeoutId);
        observer = null;
        rafId = 0;
        timeoutId = 0;
    };

    const tryStart = (btn) => {
        if (!btn || btn.dataset.heroSnakeDone === "1") return;

        const startWhenRendered = () => {
            const maxWaitMs = 5000;
            const start = performance.now();

            const tick = () => {
                if (btn.dataset.heroSnakeDone === "1") {
                    stopAll();
                    return;
                }
                const rect = btn.getBoundingClientRect();
                if (rect.width > 2 && rect.height > 2) {
                    stopAll();
                    runHeroLoadSnake(btn, { duration: 800 });
                    return;
                }
                if (performance.now() - start < maxWaitMs) {
                    rafId = requestAnimationFrame(tick);
                } else {
                    stopAll();
                }
            };

            rafId = requestAnimationFrame(tick);
        };

        startWhenRendered();
    };

    const immediateBtn = document.querySelector(selector);
    if (immediateBtn) {
        tryStart(immediateBtn);
        return;
    }

    if (document.body) {
        observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                for (const node of mutation.addedNodes) {
                    if (!(node instanceof Element)) continue;
                    const match = node.matches(selector)
                        ? node
                        : node.querySelector(selector);
                    if (match) {
                        tryStart(match);
                        return;
                    }
                }
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
        timeoutId = setTimeout(() => {
            stopAll();
        }, 8000);
    }
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initHeroLoadSnake);
} else {
    initHeroLoadSnake();
}

// home hero fan image intro
document.addEventListener("DOMContentLoaded", () => {
    const gsapInstance = window.gsap || null;
    if (!gsapInstance) return;

    const heroFaecher = document.querySelector(".hero-fächer");
    if (!heroFaecher) return;

    const getStartX = () => {
        const rect = heroFaecher.getBoundingClientRect();
        return window.innerWidth + rect.width + 40;
    };

    gsapInstance.set(heroFaecher, {
        x: getStartX(),
        rotate: 6,
        transformOrigin: "center center",
    });

    gsapInstance.to(heroFaecher, {
        x: 0,
        rotate: 0,
        duration: 0.9,
        ease: "power3.out",
    });
});


// two-img layout scroll effect (img_2 moves up while section is in view)
document.addEventListener("DOMContentLoaded", () => {
    const gsapBundle = getGsapScrollTrigger();
    if (!gsapBundle) return;
    const { gsapInstance } = gsapBundle;

    const layouts = document.querySelectorAll(".two-img-layout");
    if (!layouts.length) return;

    layouts.forEach((layout) => {
        const img1 = layout.querySelector('[data-twoimg="img1"]');
        const img2 = layout.querySelector('[data-twoimg="img2"]');
        if (!img1 || !img2) return;

        const getShiftUp = () => {
            const rect1 = img1.getBoundingClientRect();
            const rect2 = img2.getBoundingClientRect();
            const top1 = rect1.top + window.scrollY;
            const top2 = rect2.top + window.scrollY;
            const targetTop2 = top1 + rect1.height * 0.2;
            return Math.max(0, top2 - targetTop2);
        };

        gsapInstance.set([img2, img1], { y: 0, clearProps: "x", force3D: false });
        gsapInstance.to([img2, img1], {
            y: (index) => (index === 0 ? -getShiftUp() : getShiftUp()),
            ease: "none",
            force3D: false,
            immediateRender: false,
            scrollTrigger: {
                trigger: layout,
                start: "top bottom",
                end: "bottom top",
                scrub: true,
                invalidateOnRefresh: true,
                onRefreshInit: () => gsapInstance.set(img2, { y: 0 }),
            },
        });
    });
});

// one-img layout bottom-holder scroll effect (moves up while section is in view)
window.addEventListener("load", () => {
    const gsapBundle = getGsapScrollTrigger();
    if (!gsapBundle) return;
    const { gsapInstance } = gsapBundle;

    const containers = document.querySelectorAll(".one-img-layout-holder");
    if (!containers.length) return;

    const remToPx = (rem) => {
        const rootSize = getComputedStyle(document.documentElement).fontSize;
        return rem * parseFloat(rootSize || "16");
    };

    containers.forEach((container) => {
        const txtHolder = container.querySelector(".txt-holder");
        const bottomHolder = container.querySelector(".bottom-holder");
        if (!txtHolder || !bottomHolder) return;

        const computeShiftUp = () => {
            // Reset to avoid measuring a transformed position
            gsapInstance.set(bottomHolder, { y: 0 });

            const gapWanted = remToPx(1.5);
            const txtRect = txtHolder.getBoundingClientRect();
            const bottomRect = bottomHolder.getBoundingClientRect();
            const currentGap = bottomRect.top - txtRect.bottom;
            return Math.max(0, currentGap - gapWanted);
        };

        gsapInstance.to(bottomHolder, {
            y: () => -computeShiftUp(),
            ease: "none",
            force3D: false,
            immediateRender: false,
            scrollTrigger: {
                trigger: container,
                start: "top bottom",
                end: "bottom top",
                scrub: true,
                invalidateOnRefresh: true,
                immediateRender: false,
                onRefreshInit: () => gsapInstance.set(bottomHolder, { y: 0 }),
            },
        });
    });
});

// one-img layout img-holder scroll effect (starts at +80px and moves to 0 while section is in view)
window.addEventListener("load", () => {
    const gsapBundle = getGsapScrollTrigger();
    if (!gsapBundle) return;
    const { gsapInstance } = gsapBundle;

    const containers = document.querySelectorAll(".one-img-layout-holder");
    if (!containers.length) return;

    containers.forEach((container) => {
        const imgHolder = container.querySelector(".img-holder");
        if (!imgHolder) return;

        gsapInstance.fromTo(
            imgHolder,
            { y: 80 },
            {
                y: 0,
                ease: "none",
                immediateRender: false,
                scrollTrigger: {
                    trigger: container,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true,
                    invalidateOnRefresh: true,
                    immediateRender: false,
                    onRefreshInit: () => gsapInstance.set(imgHolder, { y: 80 }),
                },
            }
        );
    });
});
// home hero fan image intro
document.addEventListener("DOMContentLoaded", () => {
    const gsapInstance = window.gsap || null;
    if (!gsapInstance) return;

    const heroFaecher = document.querySelector(".hero-f�cher");
    if (!heroFaecher) return;

    const getStartX = () => {
        const rect = heroFaecher.getBoundingClientRect();
        return window.innerWidth + rect.width + 40;
    };

    gsapInstance.set(heroFaecher, {
        x: getStartX(),
        rotate: 6,
        transformOrigin: "center center",
    });

    gsapInstance.to(heroFaecher, {
        x: 0,
        rotate: 0,
        duration: 0.9,
        ease: "power3.out",
    });
});

// home hero headline intro
document.addEventListener("DOMContentLoaded", () => {
    const gsapInstance = window.gsap || null;
    if (!gsapInstance) return;

    const headline = document.querySelector(".home-hero__headline");
    if (!headline) return;
    const lines = headline.querySelectorAll(".hero-line");
    if (!lines.length) return;

    gsapInstance.set(lines, { x: -40, autoAlpha: 0 });
    const tl = gsapInstance.timeline({ defaults: { ease: "power2.out", duration: 0.6 } });
    tl.to(lines[0], { x: 0, autoAlpha: 1 })
      .to(lines[1], { x: 0, autoAlpha: 1 }, "+=0.1");

    const title = document.querySelector(".home-hero__title");
    if (!title) return;
    const typed = title.querySelector(".typed");
    if (!typed) return;
    const cursor = title.querySelector(".cursor");
    const heroBtn = document.querySelector(".home-hero .btn");

    const buildTypedChars = (container) => {
        if (container.dataset.typewriterReady === "1") {
            return Array.from(container.querySelectorAll(".char, .space"));
        }
        const text = title.getAttribute("aria-label") || container.textContent || "";
        container.textContent = "";
        const chars = [];
        for (let i = 0; i < text.length; i += 1) {
            const ch = text[i];
            const span = document.createElement("span");
            if (ch === " ") {
                span.classList.add("space");
                span.textContent = "\u00A0";
            } else {
                span.classList.add("char");
                span.textContent = ch;
                if (i === 0 || text[i - 1] === " ") {
                    span.classList.add("blue");
                }
            }
            chars.push(span);
            container.appendChild(span);
        }
        container.dataset.typewriterReady = "1";
        return chars;
    };

    const charSpans = buildTypedChars(typed);
    if (!charSpans.length) return;

    gsapInstance.set(charSpans, { autoAlpha: 0, display: "none" });
    if (cursor) {
        gsapInstance.set(cursor, { autoAlpha: 0 });
    }

    tl.add(() => {
        if (cursor) gsapInstance.set(cursor, { autoAlpha: 1 });
    }, "+=0.15");

    tl.to(charSpans, {
        autoAlpha: 1,
        display: "inline-block",
        duration: 0,
        ease: "none",
        stagger: 0.055,
    }, ">");

    if (heroBtn) {
        tl.to(heroBtn, {
            autoAlpha: 1,
            duration: 0.4,
            ease: "none",
        }, "<+0.1");
    }

    const cursorBlink = cursor
        ? gsapInstance.to(cursor, {
            autoAlpha: 0,
            duration: 0.5,
            repeat: -1,
            yoyo: true,
            ease: "none",
            paused: true,
        })
        : null;

    tl.add(() => {
        if (cursorBlink) cursorBlink.play(0);
    }, ">");

    tl.add(() => {
        if (cursorBlink) cursorBlink.pause(0);
        if (cursor) gsapInstance.set(cursor, { autoAlpha: 0 });
    }, "+=1");
});

const initSliderViewportAnimations = () => {
    const slider = document.querySelector(".slider");
    if (!slider) return;
    if (slider.dataset.sliderViewportAnimInit === "1") return;
    slider.dataset.sliderViewportAnimInit = "1";

    const slides = Array.from(slider.querySelectorAll(".slide"));
    if (!slides.length) return;

    let activeSlide = null;
    let textObserver = null;
    let buttonObserver = null;

    const getActiveSlide = () => {
        return slides.find((slide) =>
            slide.classList.contains("active") || slide.classList.contains("is-active")
        );
    };

    const clearAnimated = () => {
        slides.forEach((slide) => slide.classList.remove("is-animated"));
    };

    const resetSlideSnakeFlags = (slide) => {
        if (!slide) return;
        const btn = slide.querySelector('[data-slide-snake-btn="1"]') || slide.querySelector(".right .btn");
        if (!btn) return;
        delete btn.dataset.snakeAnimating;
        delete btn.dataset.snakeDone;
    };

    const triggerRightTextInview = (slide) => {
        if (!slide) return;
        slide.classList.remove("is-animated");
        void slide.offsetHeight;
        slide.classList.add("is-animated");
    };

    const triggerSlideButtonSnakeInview = (slide) => {
        if (!slide) return;
        const btn = slide.querySelector('[data-slide-snake-btn="1"]') || slide.querySelector(".right .btn");
        if (!btn) return;
        if (btn.dataset.snakeAnimating === "1") return;
        btn.dataset.snakeAnimating = "1";
        requestAnimationFrame(() => {
            const started = runBorderSnakeOnce(btn, {
                duration: 800,
                onComplete: () => {
                    btn.dataset.snakeAnimating = "0";
                    btn.dataset.snakeDone = "1";
                },
            });
            if (!started) {
                btn.dataset.snakeAnimating = "0";
            }
        });
    };

    const setupInviewObservers = (slide) => {
        if (!slide) return;

        if (textObserver) textObserver.disconnect();
        if (buttonObserver) buttonObserver.disconnect();
        textObserver = null;
        buttonObserver = null;

        if (!("IntersectionObserver" in window)) {
            triggerRightTextInview(slide);
            triggerSlideButtonSnakeInview(slide);
            return;
        }

        const rightEl = slide.querySelector(".right") || slide.querySelector(".slider-heading");
        const btnEl = slide.querySelector('[data-slide-snake-btn="1"]') || slide.querySelector(".right .btn");
        const ioOptions = {
            threshold: 0.35,
            rootMargin: "0px 0px -10% 0px",
        };

        if (rightEl) {
            textObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    triggerRightTextInview(slide);
                    if (textObserver && rightEl) textObserver.unobserve(rightEl);
                });
            }, ioOptions);
            textObserver.observe(rightEl);
        }

        if (btnEl) {
            buttonObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    triggerSlideButtonSnakeInview(slide);
                    if (buttonObserver && btnEl) buttonObserver.unobserve(btnEl);
                });
            }, ioOptions);
            buttonObserver.observe(btnEl);
        }
    };

    const updateActive = () => {
        const next = getActiveSlide();
        if (!next || next === activeSlide) return;
        if (activeSlide) {
            activeSlide.classList.remove("is-animated");
            resetSlideSnakeFlags(activeSlide);
        }
        activeSlide = next;
        clearAnimated();
        setupInviewObservers(next);
    };

    updateActive();
    if (!activeSlide) {
        requestAnimationFrame(updateActive);
        setTimeout(updateActive, 50);
    }

    if (slider.__sliderViewportObserver) {
        slider.__sliderViewportObserver.disconnect();
    }

    const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            if (mutation.type === "attributes" && mutation.attributeName === "class") {
                updateActive();
                break;
            }
        }
    });

    slides.forEach((slide) => {
        observer.observe(slide, { attributes: true, attributeFilter: ["class"] });
    });

    slider.__sliderViewportObserver = observer;
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSliderViewportAnimations);
} else {
    initSliderViewportAnimations();
}

const initLeistungenCardsInviewAnimation = () => {
    const cards = document.querySelectorAll(".dienstleistung-card");
    if (!cards.length) return;

    if (!("IntersectionObserver" in window)) {
        cards.forEach((card) => card.classList.add("is-inview"));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add("is-inview");
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.3,
        }
    );

    cards.forEach((card) => observer.observe(card));
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initLeistungenCardsInviewAnimation);
} else {
    initLeistungenCardsInviewAnimation();
}

const initWebsiteWegMiniHeadingConnectors = () => {
    const gsapBundle = getGsapScrollTrigger();
    if (!gsapBundle) return;
    const { gsapInstance, ScrollTrigger } = gsapBundle;

    const websiteWeg = document.querySelector(".website-weg");
    if (!websiteWeg) return;

    let svg = websiteWeg.querySelector(".website-weg__connector");
    if (!svg) {
        svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.classList.add("website-weg__connector");
        svg.setAttribute("aria-hidden", "true");
        websiteWeg.prepend(svg);
    }

    const holders = Array.from(websiteWeg.querySelectorAll(".website-weg__overlay .txt-holder"));
    if (!svg || holders.length < 2) return;

    if (typeof websiteWeg.__miniHeadingConnectorCleanup === "function") {
        websiteWeg.__miniHeadingConnectorCleanup();
    }

    const svgNs = "http://www.w3.org/2000/svg";
    const pairs = [];
    const triggers = [];
    let resizeRafId = 0;
    let resizeObserver = null;

    for (let i = 0; i < holders.length - 1; i += 1) {
        const currentHolder = holders[i];
        const nextHolder = holders[i + 1];
        pairs.push({ currentHolder, nextHolder });
    }

    if (!pairs.length) return;

    const clearAll = () => {
        while (svg.firstChild) svg.removeChild(svg.firstChild);
        while (triggers.length) {
            const st = triggers.pop();
            if (st) st.kill();
        }
    };

    const clampNumber = (value, min, max) => Math.min(max, Math.max(min, value));
    const distance = (a, b) => Math.hypot(b.x - a.x, b.y - a.y);
    const subVectors = (a, b) => ({ x: a.x - b.x, y: a.y - b.y });
    const addVectors = (a, b) => ({ x: a.x + b.x, y: a.y + b.y });
    const scaleVector = (v, s) => ({ x: v.x * s, y: v.y * s });
    const lerpPoint = (a, b, t) => ({ x: a.x + (b.x - a.x) * t, y: a.y + (b.y - a.y) * t });
    const crossZ = (a, b) => a.x * b.y - a.y * b.x;

    const normalizeVector = (v) => {
        const m = Math.hypot(v.x, v.y);
        if (m < 1e-6) return { x: 0, y: 0 };
        return { x: v.x / m, y: v.y / m };
    };

    const smoothstep = (edge0, edge1, x) => {
        const t = clampNumber((x - edge0) / Math.max(1e-6, edge1 - edge0), 0, 1);
        return t * t * (3 - 2 * t);
    };

    const clampPoint = (point, width, height, padding) => ({
        x: clampNumber(point.x, padding, width - padding),
        y: clampNumber(point.y, padding, height - padding),
    });

    const clampPoints = (points, width, height, padding) =>
        points.map((point) => clampPoint(point, width, height, padding));

    const dedupeConsecutive = (points, eps) => {
        if (points.length < 2) return points.slice();
        const out = [points[0]];
        for (let i = 1; i < points.length; i += 1) {
            if (distance(out[out.length - 1], points[i]) > eps) out.push(points[i]);
        }
        return out;
    };

    const enforceMinSegmentLength = (points, minLen) => {
        if (points.length < 3) return points.slice();
        const out = [points[0]];
        for (let i = 1; i < points.length - 1; i += 1) {
            if (distance(out[out.length - 1], points[i]) >= minLen) out.push(points[i]);
        }
        out.push(points[points.length - 1]);
        if (out.length > 2 && distance(out[out.length - 2], out[out.length - 1]) < minLen) {
            out.splice(out.length - 2, 1);
        }
        return out;
    };

    const angleAt = (prev, curr, next) => {
        const v1 = subVectors(prev, curr);
        const v2 = subVectors(next, curr);
        const m1 = Math.hypot(v1.x, v1.y);
        const m2 = Math.hypot(v2.x, v2.y);
        if (m1 < 1e-6 || m2 < 1e-6) return 180;
        const dot = clampNumber((v1.x * v2.x + v1.y * v2.y) / (m1 * m2), -1, 1);
        return (Math.acos(dot) * 180) / Math.PI;
    };

    const removeNearCollinear = (points, angleEpsDeg, distEps) => {
        if (points.length < 3) return points.slice();
        const out = [points[0]];
        for (let i = 1; i < points.length - 1; i += 1) {
            const prev = out[out.length - 1];
            const curr = points[i];
            const next = points[i + 1];
            const d1 = distance(prev, curr);
            const d2 = distance(curr, next);
            const interior = angleAt(prev, curr, next);
            const nearStraight = Math.abs(180 - interior) <= angleEpsDeg;
            if ((nearStraight && Math.min(d1, d2) <= distEps * 1.6) || d1 < distEps || d2 < distEps) continue;
            out.push(curr);
        }
        out.push(points[points.length - 1]);
        return out;
    };

    const chaikinSmooth = (points) => {
        if (points.length < 2) return points.slice();
        const out = [points[0]];
        for (let i = 0; i < points.length - 1; i += 1) {
            const p0 = points[i];
            const p1 = points[i + 1];
            out.push({ x: 0.75 * p0.x + 0.25 * p1.x, y: 0.75 * p0.y + 0.25 * p1.y });
            out.push({ x: 0.25 * p0.x + 0.75 * p1.x, y: 0.25 * p0.y + 0.75 * p1.y });
        }
        out.push(points[points.length - 1]);
        return out;
    };

    const safeDiv = (n, d) => n / (Math.abs(d) < 1e-6 ? (d < 0 ? -1e-6 : 1e-6) : d);
    const mix = (a, b, t) => ({ x: a.x + (b.x - a.x) * t, y: a.y + (b.y - a.y) * t });

    const evalCentripetalCatmullSegment = (p0, p1, p2, p3, u, alpha = 0.5) => {
        const t0 = 0;
        const t1 = t0 + Math.pow(Math.max(distance(p0, p1), 1e-3), alpha);
        const t2 = t1 + Math.pow(Math.max(distance(p1, p2), 1e-3), alpha);
        const t3 = t2 + Math.pow(Math.max(distance(p2, p3), 1e-3), alpha);
        const t = t1 + (t2 - t1) * clampNumber(u, 0, 1);

        const a1 = mix(p0, p1, safeDiv(t - t0, t1 - t0));
        const a2 = mix(p1, p2, safeDiv(t - t1, t2 - t1));
        const a3 = mix(p2, p3, safeDiv(t - t2, t3 - t2));
        const b1 = mix(a1, a2, safeDiv(t - t0, t2 - t0));
        const b2 = mix(a2, a3, safeDiv(t - t1, t3 - t1));
        return mix(b1, b2, safeDiv(t - t1, t2 - t1));
    };

    const sampleSplinePoints = (points, minSamples, alpha = 0.5) => {
        if (points.length < 2) return points.slice();
        const segCount = points.length - 1;
        const totalSamples = Math.max(minSamples, segCount * 20);
        const out = [];
        for (let i = 0; i <= totalSamples; i += 1) {
            const globalT = (i / totalSamples) * segCount;
            const seg = Math.min(segCount - 1, Math.floor(globalT));
            const u = globalT - seg;
            const p0 = points[Math.max(0, seg - 1)];
            const p1 = points[seg];
            const p2 = points[seg + 1];
            const p3 = points[Math.min(points.length - 1, seg + 2)];
            out.push(evalCentripetalCatmullSegment(p0, p1, p2, p3, u, alpha));
        }
        return out;
    };

    const getSampleTangent = (samples, index) => {
        const i0 = Math.max(0, index - 1);
        const i1 = Math.min(samples.length - 1, index + 1);
        return normalizeVector(subVectors(samples[i1], samples[i0]));
    };

    const medianOf = (values) => {
        if (!values.length) return 0;
        const sorted = values.slice().sort((a, b) => a - b);
        const mid = Math.floor(sorted.length * 0.5);
        if (sorted.length % 2 === 0) return (sorted[mid - 1] + sorted[mid]) * 0.5;
        return sorted[mid];
    };

    const findNearestPointIndex = (points, target) => {
        let best = 0;
        let bestDist = Number.POSITIVE_INFINITY;
        for (let i = 0; i < points.length; i += 1) {
            const d = distance(points[i], target);
            if (d < bestDist) {
                bestDist = d;
                best = i;
            }
        }
        return best;
    };

    const findNearestSampleIndex = (samples, target) => {
        let best = 0;
        let bestDist = Number.POSITIVE_INFINITY;
        for (let i = 0; i < samples.length; i += 1) {
            const d = distance(samples[i], target);
            if (d < bestDist) {
                bestDist = d;
                best = i;
            }
        }
        return best;
    };

    const centripetalSegmentToBezier = (p0, p1, p2, p3, alpha = 0.5, tension = 0.5) => {
        const evalAt = (u) => evalCentripetalCatmullSegment(p0, p1, p2, p3, u, alpha);
        const derivativeAt = (u) => {
            const eps = 0.002;
            const u0 = clampNumber(u - eps, 0, 1);
            const u1 = clampNumber(u + eps, 0, 1);
            const a = evalAt(u0);
            const b = evalAt(u1);
            const inv = 1 / Math.max(1e-6, u1 - u0);
            return { x: (b.x - a.x) * inv, y: (b.y - a.y) * inv };
        };

        const tangentScale = 1 - tension * 0.5;
        const d1 = derivativeAt(0);
        const d2 = derivativeAt(1);
        return {
            c1: { x: p1.x + (d1.x * tangentScale) / 3, y: p1.y + (d1.y * tangentScale) / 3 },
            c2: { x: p2.x - (d2.x * tangentScale) / 3, y: p2.y - (d2.y * tangentScale) / 3 },
            p2,
        };
    };

    const pathFromPoints = (points, alpha = 0.5, tension = 0.5) => {
        if (!points.length) return "";
        if (points.length === 1) return `M ${points[0].x} ${points[0].y}`;
        if (points.length === 2) return `M ${points[0].x} ${points[0].y} L ${points[1].x} ${points[1].y}`;

        let d = `M ${points[0].x} ${points[0].y}`;
        for (let i = 0; i < points.length - 1; i += 1) {
            const p0 = points[Math.max(0, i - 1)];
            const p1 = points[i];
            const p2 = points[i + 1];
            const p3 = points[Math.min(points.length - 1, i + 2)];
            const bez = centripetalSegmentToBezier(p0, p1, p2, p3, alpha, tension);
            d += ` C ${bez.c1.x} ${bez.c1.y}, ${bez.c2.x} ${bez.c2.y}, ${bez.p2.x} ${bez.p2.y}`;
        }
        return d;
    };

    const getAnchorPoint = (holder, svgRect) => {
        const anchor = holder.querySelector(".mini-heading__anchor");
        if (!anchor) return null;
        const rect = anchor.getBoundingClientRect();
        return {
            x: rect.left - svgRect.left + rect.width * 0.5,
            y: rect.top - svgRect.top + rect.height * 0.5,
        };
    };

    const parsePx = (value) => {
        const n = parseFloat(value);
        return Number.isFinite(n) ? n : 0;
    };

    const getPseudoCenter = (holderRect, pseudoStyle, fallbackCenter) => {
        const width = Math.max(0, parsePx(pseudoStyle.width));
        const height = Math.max(0, parsePx(pseudoStyle.height));
        if (width <= 0 || height <= 0) return fallbackCenter;

        const left = pseudoStyle.left !== "auto" ? parsePx(pseudoStyle.left) : null;
        const right = pseudoStyle.right !== "auto" ? parsePx(pseudoStyle.right) : null;
        const top = pseudoStyle.top !== "auto" ? parsePx(pseudoStyle.top) : null;
        const bottom = pseudoStyle.bottom !== "auto" ? parsePx(pseudoStyle.bottom) : null;

        let x = fallbackCenter.x;
        let y = fallbackCenter.y;

        if (left !== null) x = holderRect.left + left + width * 0.5;
        else if (right !== null) x = holderRect.right - right - width * 0.5;

        if (top !== null) y = holderRect.top + top + height * 0.5;
        else if (bottom !== null) y = holderRect.bottom - bottom - height * 0.5;

        return { x, y };
    };

    const applyTransformOffset = (point, transform) => {
        if (!transform || transform === "none") return point;
        const matrix3d = transform.match(/matrix3d\(([^)]+)\)/);
        if (matrix3d) {
            const parts = matrix3d[1].split(",").map((v) => parseFloat(v.trim()));
            if (parts.length === 16) {
                return { x: point.x + (parts[12] || 0), y: point.y + (parts[13] || 0) };
            }
        }
        const matrix2d = transform.match(/matrix\(([^)]+)\)/);
        if (matrix2d) {
            const parts = matrix2d[1].split(",").map((v) => parseFloat(v.trim()));
            if (parts.length === 6) {
                return { x: point.x + (parts[4] || 0), y: point.y + (parts[5] || 0) };
            }
        }
        return point;
    };

    const getPoint2PortAnchor = (holder, svgRect) => {
        if (!holder) return null;
        const anchor = holder.querySelector(".mini-heading__anchor");
        if (!anchor) return null;
        const anchorRect = anchor.getBoundingClientRect();
        const fallbackCenter = {
            x: anchorRect.left + anchorRect.width * 0.5,
            y: anchorRect.top + anchorRect.height * 0.5,
        };

        const pseudoStyle = getComputedStyle(holder, "::before");
        if (!pseudoStyle || pseudoStyle.content === "none") return null;

        const holderRect = holder.getBoundingClientRect();
        let center = getPseudoCenter(holderRect, pseudoStyle, fallbackCenter);
        center = applyTransformOffset(center, pseudoStyle.transform);

        if (!Number.isFinite(center.x) || !Number.isFinite(center.y)) return null;
        return {
            x: center.x - svgRect.left,
            y: center.y - svgRect.top,
        };
    };

    const getDeterministicSeed = (segmentIndex, start, end) =>
        (segmentIndex + 1) * 10007 + Math.round(start.x + start.y) * 31 + Math.round(end.x + end.y) * 17;

    const waveU = [0.10, 0.20, 0.32, 0.44, 0.56, 0.68, 0.80, 0.90, 0.96];

    const buildWaveSegment = (start, end, segmentIndex, bounds, state) => {
        const p0 = clampPoint(start, bounds.width, bounds.height, bounds.padding);
        const p1 = clampPoint(end, bounds.width, bounds.height, bounds.padding);
        const axis = subVectors(p1, p0);
        const axisLen = Math.max(1e-6, Math.hypot(axis.x, axis.y));
        let t = { x: axis.x / axisLen, y: axis.y / axisLen };
        if (Math.hypot(t.x, t.y) < 1e-6) t = { x: 0, y: 1 };

        let n = normalizeVector({ x: -t.y, y: t.x });
        const seed = getDeterministicSeed(segmentIndex, p0, p1);
        const nSign = seed % 2 === 0 ? 1 : -1;
        n = scaleVector(n, nSign);
        const segmentAdjust = state.segmentAdjust[segmentIndex] || { ampMul: 1, fMode: 0 };

        const phase = (seed % 360) * (Math.PI / 180);
        const fBase = seed % 2 === 0 ? 1.25 : 1.5;
        const f = segmentAdjust.fMode === -1 ? 1.25 : (segmentAdjust.fMode === 1 ? 1.5 : fBase);
        const warp = (0.12 + (seed % 7) * 0.01) * state.warpScale;
        const ampBase = clampNumber(axisLen * 0.22, 70, 260);
        const amp = clampNumber(
            ampBase * (0.92 + (seed % 9) * 0.01) * state.ampScale * segmentAdjust.ampMul,
            0,
            300
        );
        const yBiasAmp = clampNumber(bounds.height * 0.01, 2, 10);

        const out = [p0];
        for (let i = 0; i < waveU.length; i += 1) {
            const u = waveU[i];
            const edge = smoothstep(0.0, 0.12, u) * smoothstep(1.0, 0.88, u);
            const ramp = 0.55 + 0.45 * edge;
            const waveBase = Math.sin(2 * Math.PI * f * u + phase);
            const waveHarm = 0.28 * Math.sin(2 * Math.PI * (f * 0.5) * u + phase * 0.7);
            const lowWarp = 0.85 + 0.15 * Math.sin(2 * Math.PI * u + phase * 0.33);
            const offset = (waveBase + waveHarm) * lowWarp * (1 + warp * 0.08);

            const axisPoint = lerpPoint(p0, p1, u);
            const normalDelta = scaleVector(n, amp * ramp * offset);
            const yBias = {
                x: 0,
                y: Math.sin(2 * Math.PI * u + phase) * yBiasAmp,
            };
            out.push(clampPoint(addVectors(addVectors(axisPoint, normalDelta), yBias), bounds.width, bounds.height, bounds.padding));
        }
        out.push(p1);
        return out;
    };

    const buildBaseChain = (anchors, bounds, state) => {
        const chain = [anchors[0]];
        for (let i = 0; i < anchors.length - 1; i += 1) {
            const segment = buildWaveSegment(anchors[i], anchors[i + 1], i, bounds, state);
            for (let j = 1; j < segment.length; j += 1) chain.push(segment[j]);
        }
        return chain;
    };

    const applyPointBumps = (points, bumps, bounds) => {
        if (!bumps.length || points.length < 3) return points.slice();
        const out = points.slice();
        for (let i = 0; i < bumps.length; i += 1) {
            const bump = bumps[i];
            const idx = clampNumber(bump.index, 1, out.length - 2);
            out[idx] = clampPoint({
                x: out[idx].x + bump.dx,
                y: out[idx].y + bump.dy,
            }, bounds.width, bounds.height, bounds.padding);
        }
        return out;
    };

    const runHygiene = (points, bounds, minLen, angleEpsDeg, chaikinPasses = 2) => {
        let out = clampPoints(points, bounds.width, bounds.height, bounds.padding);
        for (let i = 0; i < chaikinPasses; i += 1) out = chaikinSmooth(out);
        out = dedupeConsecutive(out, 0.8);
        out = enforceMinSegmentLength(out, minLen);
        out = removeNearCollinear(out, angleEpsDeg, minLen * 0.45);
        out = clampPoints(out, bounds.width, bounds.height, bounds.padding);
        out = dedupeConsecutive(out, 0.8);
        out = enforceMinSegmentLength(out, minLen);
        return out;
    };

    const analyzeStraightRuns = (samples, anchors) => {
        let hasInvalid = false;
        const segmentIndices = [];
        for (let s = 0; s < anchors.length - 1; s += 1) {
            const i0 = findNearestSampleIndex(samples, anchors[s]);
            const i1 = findNearestSampleIndex(samples, anchors[s + 1]);
            const from = Math.max(1, Math.min(i0, i1));
            const to = Math.min(samples.length - 3, Math.max(i0, i1));
            let run = 0;
            for (let i = from; i <= to; i += 1) {
                const t0 = getSampleTangent(samples, i);
                const t1 = getSampleTangent(samples, i + 1);
                const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
                if (dot > 0.999) {
                    run += 1;
                    if (run >= 3) {
                        hasInvalid = true;
                        segmentIndices.push(s);
                        break;
                    }
                } else {
                    run = 0;
                }
            }
        }
        return { hasInvalid, segmentIndices: Array.from(new Set(segmentIndices)) };
    };

    const analyzeDents = (samples, anchors) => {
        if (samples.length < 12) {
            return { hasInvalid: true, spikeCount: 99, flipOverflow: true };
        }

        const turns = [];
        const turnByIndex = new Array(samples.length).fill(0);
        const signs = new Array(samples.length).fill(0);

        for (let i = 1; i < samples.length - 1; i += 1) {
            const t0 = getSampleTangent(samples, i);
            const t1 = getSampleTangent(samples, i + 1);
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            const turn = (Math.acos(dot) * 180) / Math.PI;
            const sign = Math.abs(crossZ(t0, t1)) < 1e-6 ? 0 : (crossZ(t0, t1) > 0 ? 1 : -1);
            turns.push(turn);
            turnByIndex[i] = turn;
            signs[i] = sign;
        }

        const turnMedian = Math.max(0.1, medianOf(turns));
        let spikeCount = 0;
        for (let i = 2; i < samples.length - 2; i += 1) {
            const win = [];
            for (let j = Math.max(1, i - 5); j <= Math.min(samples.length - 2, i + 5); j += 1) {
                win.push(turnByIndex[j]);
            }
            const localMedian = Math.max(0.1, medianOf(win));
            if (turnByIndex[i] > localMedian * 2.0 && turnByIndex[i] > 4.0) spikeCount += 1;
        }

        let flipOverflow = false;
        const flipSegments = [];
        for (let s = 0; s < anchors.length - 1; s += 1) {
            const i0 = findNearestSampleIndex(samples, anchors[s]);
            const i1 = findNearestSampleIndex(samples, anchors[s + 1]);
            const from = Math.min(i0, i1);
            const to = Math.max(i0, i1);
            let flips = 0;
            let prevSign = 0;
            for (let i = Math.max(1, from); i <= Math.min(samples.length - 2, to); i += 1) {
                const sign = signs[i];
                if (sign === 0) continue;
                if (prevSign !== 0 && sign !== prevSign) flips += 1;
                prevSign = sign;
            }
            if (flips > 2) {
                flipOverflow = true;
                flipSegments.push(s);
            }
        }

        return {
            hasInvalid: spikeCount > 0 || flipOverflow || turnMedian < 0.2,
            spikeCount,
            flipOverflow,
            flipSegments,
        };
    };

    const buildLocalBumps = (points, samples, straightInfo, bounds, iterationSeed) => {
        if (!straightInfo.hasInvalid || !straightInfo.violating.length) return [];
        const bumpMag = clampNumber(bounds.width * 0.004, 2, 10);
        const bumps = [];
        const picks = [
            straightInfo.violating[Math.floor(straightInfo.violating.length * 0.35)] || straightInfo.violating[0],
            straightInfo.violating[Math.floor(straightInfo.violating.length * 0.7)] || straightInfo.violating[straightInfo.violating.length - 1],
        ];

        for (let i = 0; i < picks.length && bumps.length < 2; i += 1) {
            const sampleIdx = picks[i];
            const sample = samples[sampleIdx];
            if (!sample) continue;
            const pointIdx = findNearestPointIndex(points, sample);
            const tangent = getSampleTangent(samples, sampleIdx);
            const normal = normalizeVector({ x: -tangent.y, y: tangent.x });
            const sign = ((iterationSeed + i) % 2 === 0) ? 1 : -1;
            bumps.push({
                index: pointIdx,
                dx: normal.x * bumpMag * sign,
                dy: normal.y * bumpMag * sign,
            });
        }
        return bumps;
    };

    const buildAnchors = (svgRect, bounds) => {
        const anchors = [];
        for (let i = 0; i < holders.length; i += 1) {
            const anchor = getAnchorPoint(holders[i], svgRect);
            if (!anchor) return null;
            anchors.push(clampPoint(anchor, bounds.width, bounds.height, bounds.padding));
        }

        if (anchors.length > 1) {
            const port = getPoint2PortAnchor(holders[1], svgRect);
            if (port) {
                anchors[1] = clampPoint(port, bounds.width, bounds.height, bounds.padding);
            }
        }

        return anchors;
    };

    const buildFinalChain = (anchors, bounds) => {
        const minLen = clampNumber(bounds.width * 0.008, 6, 16);
        const angleEpsDeg = 3;
        const segmentCount = Math.max(1, anchors.length - 1);
        const state = {
            ampScale: 1,
            warpScale: 1,
            bumps: [],
            straightTunePasses: 0,
            segmentAdjust: Array.from({ length: segmentCount }, () => ({ ampMul: 1, fMode: 0 })),
        };

        let finalPoints = anchors.slice();
        for (let iteration = 0; iteration < 3; iteration += 1) {
            const baseChain = buildBaseChain(anchors, bounds, state);
            const bumped = applyPointBumps(baseChain, state.bumps, bounds);
            const chaikinPasses = 2;
            const candidate = runHygiene(bumped, bounds, minLen, angleEpsDeg, chaikinPasses);
            const samples = sampleSplinePoints(candidate, 380, 0.5);

            const straightInfo = analyzeStraightRuns(samples, anchors);
            const dentInfo = analyzeDents(samples, anchors);
            finalPoints = candidate;

            if (!straightInfo.hasInvalid && !dentInfo.hasInvalid) break;

            if (straightInfo.hasInvalid && state.straightTunePasses < 2) {
                for (let i = 0; i < straightInfo.segmentIndices.length; i += 1) {
                    const segIdx = straightInfo.segmentIndices[i];
                    const segAdj = state.segmentAdjust[segIdx];
                    if (!segAdj) continue;
                    segAdj.ampMul = clampNumber(segAdj.ampMul * 1.12, 1, 3);
                    if (segAdj.fMode === 0) segAdj.fMode = 1;
                }
                state.straightTunePasses += 1;
            }

            state.bumps = [];

            if (dentInfo.hasInvalid) {
                state.ampScale *= 0.92;
                state.warpScale *= 0.95;
                if (dentInfo.flipOverflow && dentInfo.flipSegments.length) {
                    for (let i = 0; i < dentInfo.flipSegments.length; i += 1) {
                        const segIdx = dentInfo.flipSegments[i];
                        const segAdj = state.segmentAdjust[segIdx];
                        if (!segAdj) continue;
                        segAdj.ampMul = clampNumber(segAdj.ampMul * 0.92, 0.7, 3);
                        segAdj.fMode = -1;
                    }
                }
            }
        }

        return finalPoints;
    };

    const buildPaths = () => {
        clearAll();

        const svgRect = websiteWeg.getBoundingClientRect();
        const padding = clampNumber(Math.min(svgRect.width, svgRect.height) * 0.03, 14, 40);
        svg.setAttribute("width", `${svgRect.width}`);
        svg.setAttribute("height", `${svgRect.height}`);
        svg.setAttribute("viewBox", `0 0 ${svgRect.width} ${svgRect.height}`);

        const bounds = {
            width: svgRect.width,
            height: svgRect.height,
            padding,
        };

        const anchors = buildAnchors(svgRect, bounds);
        if (!anchors || anchors.length < 2) return;

        const finalChain = buildFinalChain(anchors, bounds);
        const smoothD = pathFromPoints(finalChain, 0.5, 0.5);
        if (!smoothD) return;
        const anchorIndices = anchors.map((anchor) => findNearestPointIndex(finalChain, anchor));

        pairs.forEach(({ currentHolder, nextHolder }, segmentIndex) => {
            const fromAnchor = anchors[segmentIndex];
            const toAnchor = anchors[segmentIndex + 1];
            if (!fromAnchor || !toAnchor) return;
            const i0 = clampNumber(Math.min(anchorIndices[segmentIndex], anchorIndices[segmentIndex + 1]), 0, finalChain.length - 1);
            const i1 = clampNumber(Math.max(anchorIndices[segmentIndex], anchorIndices[segmentIndex + 1]), 0, finalChain.length - 1);
            const segmentPathPoints = finalChain.slice(i0, i1 + 1);
            segmentPathPoints[0] = fromAnchor;
            segmentPathPoints[segmentPathPoints.length - 1] = toAnchor;
            const segmentD = pathFromPoints(segmentPathPoints, 0.5, 0.5) || smoothD;

            const path = document.createElementNS(svgNs, "path");
            path.setAttribute("d", segmentD);
            path.setAttribute("fill", "none");
            path.setAttribute("stroke", "var(--acc-clr)");
            path.setAttribute("stroke-width", "6");
            path.setAttribute("vector-effect", "non-scaling-stroke");
            svg.appendChild(path);

            const length = path.getTotalLength();
            gsapInstance.set(path, {
                strokeDasharray: length,
                strokeDashoffset: length,
            });

            const tween = gsapInstance.to(path, {
                strokeDashoffset: 0,
                ease: "none",
                scrollTrigger: {
                    trigger: currentHolder,
                    endTrigger: nextHolder,
                    start: "top center",
                    end: "top center",
                    scrub: true,
                    invalidateOnRefresh: true,
                },
            });

            if (tween.scrollTrigger) triggers.push(tween.scrollTrigger);
        });
    };

    const onRefreshInit = () => {
        buildPaths();
    };

    buildPaths();
    ScrollTrigger.addEventListener("refreshInit", onRefreshInit);
    requestAnimationFrame(() => {
        ScrollTrigger.refresh(true);
    });

    if ("ResizeObserver" in window) {
        resizeObserver = new ResizeObserver(() => {
            if (resizeRafId) cancelAnimationFrame(resizeRafId);
            resizeRafId = requestAnimationFrame(() => {
                ScrollTrigger.refresh();
            });
        });
        resizeObserver.observe(websiteWeg);
    }

    const onResize = () => {
        if (resizeRafId) cancelAnimationFrame(resizeRafId);
        resizeRafId = requestAnimationFrame(() => {
            ScrollTrigger.refresh();
        });
    };
    window.addEventListener("resize", onResize);

    websiteWeg.__miniHeadingConnectorCleanup = () => {
        ScrollTrigger.removeEventListener("refreshInit", onRefreshInit);
        window.removeEventListener("resize", onResize);
        if (resizeRafId) cancelAnimationFrame(resizeRafId);
        if (resizeObserver) resizeObserver.disconnect();
        clearAll();
    };
};
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initWebsiteWegMiniHeadingConnectors);
} else {
    initWebsiteWegMiniHeadingConnectors();
}

const initWebsiteWegInview = () => {
    const websiteWeg = document.querySelector(".website-weg");
    const stickyMedia = document.querySelector(".website-weg__media");
    const items = Array.from(document.querySelectorAll(".website-weg .txt-holder"));
    if (!websiteWeg || !stickyMedia || !items.length) return;

    items.forEach((item) => item.classList.remove("is-inview"));
    return;

    let activated = false;
    let ticking = false;

    const enableItemObservers = () => {
        if (!("IntersectionObserver" in window)) {
            items.forEach((item) => item.classList.add("is-inview"));
            return;
        }

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add("is-inview");
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.35,
                rootMargin: "0px 0px -10% 0px",
            }
        );

        items.forEach((item) => observer.observe(item));
    };

    const checkStuck = () => {
        ticking = false;
        if (activated) return;
        const rect = stickyMedia.getBoundingClientRect();
        const offset = 0;
        const isStuck = Math.abs(rect.top - offset) <= 1;
        if (!isStuck) return;
        activated = true;
        websiteWeg.classList.add("is-inview");
        enableItemObservers();
    };

    const onScroll = () => {
        if (ticking || activated) return;
        ticking = true;
        requestAnimationFrame(checkStuck);
    };

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);
    onScroll();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initWebsiteWegInview);
} else {
    initWebsiteWegInview();
}








