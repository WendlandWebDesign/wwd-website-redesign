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
        const fromAnchor = currentHolder.querySelector(".mini-heading__anchor");
        const toAnchor = nextHolder.querySelector(".mini-heading__anchor");
        if (!fromAnchor || !toAnchor) continue;
        pairs.push({ currentHolder, nextHolder, fromAnchor, toAnchor });
    }

    if (!pairs.length) return;

    const clearAll = () => {
        while (svg.firstChild) {
            svg.removeChild(svg.firstChild);
        }
        while (triggers.length) {
            const st = triggers.pop();
            if (st) st.kill();
        }
    };

    const getAnchorPoint = (anchor, svgRect) => {
        const rect = anchor.getBoundingClientRect();
        return {
            x: rect.left - svgRect.left + rect.width / 2,
            y: rect.top - svgRect.top + rect.height / 2,
        };
    };

    const clampNumber = (value, min, max) => Math.min(max, Math.max(min, value));
    const distance = (a, b) => Math.hypot(b.x - a.x, b.y - a.y);
    const lerpPoint = (a, b, t) => ({
        x: a.x + (b.x - a.x) * t,
        y: a.y + (b.y - a.y) * t,
    });
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
            if (distance(out[out.length - 1], points[i]) > eps) {
                out.push(points[i]);
            }
        }
        return out;
    };

    const smoothstep = (edge0, edge1, x) => {
        const t = clampNumber((x - edge0) / (edge1 - edge0), 0, 1);
        return t * t * (3 - 2 * t);
    };

    const enforceMinSegmentLength = (points, minLen) => {
        if (points.length < 3) return points.slice();
        const out = [points[0]];
        for (let i = 1; i < points.length - 1; i += 1) {
            if (distance(out[out.length - 1], points[i]) >= minLen) {
                out.push(points[i]);
            }
        }
        out.push(points[points.length - 1]);
        if (out.length > 2 && distance(out[out.length - 2], out[out.length - 1]) < minLen) {
            out.splice(out.length - 2, 1);
        }
        return out;
    };

    const angleAt = (prev, curr, next) => {
        const v1x = prev.x - curr.x;
        const v1y = prev.y - curr.y;
        const v2x = next.x - curr.x;
        const v2y = next.y - curr.y;
        const m1 = Math.hypot(v1x, v1y);
        const m2 = Math.hypot(v2x, v2y);
        if (m1 < 1e-6 || m2 < 1e-6) return 180;
        const dot = clampNumber((v1x * v2x + v1y * v2y) / (m1 * m2), -1, 1);
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
            if ((nearStraight && Math.min(d1, d2) <= distEps * 1.6) || d1 < distEps || d2 < distEps) {
                continue;
            }
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
            out.push({
                x: 0.75 * p0.x + 0.25 * p1.x,
                y: 0.75 * p0.y + 0.25 * p1.y,
            });
            out.push({
                x: 0.25 * p0.x + 0.75 * p1.x,
                y: 0.25 * p0.y + 0.75 * p1.y,
            });
        }
        out.push(points[points.length - 1]);
        return out;
    };

    const safeDiv = (n, d) => n / (Math.abs(d) < 1e-6 ? (d < 0 ? -1e-6 : 1e-6) : d);
    const mix = (a, b, t) => ({
        x: a.x + (b.x - a.x) * t,
        y: a.y + (b.y - a.y) * t,
    });

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

    const segmentIntersection = (a, b, c, d) => {
        const orient = (p, q, r) => (q.x - p.x) * (r.y - p.y) - (q.y - p.y) * (r.x - p.x);
        const onSeg = (p, q, r) =>
            Math.min(p.x, r.x) - 1e-6 <= q.x &&
            q.x <= Math.max(p.x, r.x) + 1e-6 &&
            Math.min(p.y, r.y) - 1e-6 <= q.y &&
            q.y <= Math.max(p.y, r.y) + 1e-6;

        const o1 = orient(a, b, c);
        const o2 = orient(a, b, d);
        const o3 = orient(c, d, a);
        const o4 = orient(c, d, b);

        if ((o1 > 0 && o2 < 0 || o1 < 0 && o2 > 0) && (o3 > 0 && o4 < 0 || o3 < 0 && o4 > 0)) {
            return true;
        }
        if (Math.abs(o1) < 1e-6 && onSeg(a, c, b)) return true;
        if (Math.abs(o2) < 1e-6 && onSeg(a, d, b)) return true;
        if (Math.abs(o3) < 1e-6 && onSeg(c, a, d)) return true;
        if (Math.abs(o4) < 1e-6 && onSeg(c, b, d)) return true;
        return false;
    };

    const detectSelfIntersections = (samples) => {
        if (samples.length < 4) return false;
        for (let i = 0; i < samples.length - 1; i += 1) {
            for (let j = i + 2; j < samples.length - 1; j += 1) {
                if (j === i + 1) continue;
                if (segmentIntersection(samples[i], samples[i + 1], samples[j], samples[j + 1])) {
                    return true;
                }
            }
        }
        return false;
    };

    const hasSharpTurn = (samples, minInteriorAngleDeg = 140) => {
        if (samples.length < 3) return false;
        for (let i = 1; i < samples.length - 1; i += 1) {
            const interior = angleAt(samples[i - 1], samples[i], samples[i + 1]);
            if (interior < minInteriorAngleDeg) return true;
        }
        return false;
    };

    const hasLocalReversal = (samples, start, end, segmentIndex) => {
        if (samples.length < 4) return false;
        const base = { x: end.x - start.x, y: end.y - start.y };
        const n = samples.length - 1;
        const frontWindow = Math.max(3, Math.floor(n * 0.12));
        const backStart = Math.max(1, Math.floor(n * 0.88));

        for (let i = 1; i < frontWindow; i += 1) {
            const step = { x: samples[i].x - samples[i - 1].x, y: samples[i].y - samples[i - 1].y };
            if (step.x * base.x + step.y * base.y < 0) return true;
        }

        for (let i = backStart; i < n; i += 1) {
            const step = { x: samples[i].x - samples[i - 1].x, y: samples[i].y - samples[i - 1].y };
            if (step.x * base.x + step.y * base.y < 0) return true;
        }

        // Segment 3->4 special progress guard in final 40%.
        if (segmentIndex === 2) {
            const guardStart = Math.floor(n * 0.6);
            for (let i = guardStart; i < n; i += 1) {
                const curr = samples[i];
                const next = samples[Math.min(i + 1, n)];
                const step = { x: next.x - curr.x, y: next.y - curr.y };
                const towardEnd = { x: end.x - curr.x, y: end.y - curr.y };
                if (step.x * towardEnd.x + step.y * towardEnd.y <= 0) return true;
            }
        }
        return false;
    };

    const normalizeVector = (v) => {
        const m = Math.hypot(v.x, v.y);
        if (m < 1e-6) return { x: 0, y: 0 };
        return { x: v.x / m, y: v.y / m };
    };
    const scaleVector = (v, s) => ({ x: v.x * s, y: v.y * s });
    const addVectors = (a, b) => ({ x: a.x + b.x, y: a.y + b.y });
    const subVectors = (a, b) => ({ x: a.x - b.x, y: a.y - b.y });
    const parsePx = (value, fallback = 0) => {
        const n = parseFloat(value);
        return Number.isFinite(n) ? n : fallback;
    };

    const segmentNearCircle = (a, b, center, radius) => {
        if (distance(a, center) <= radius || distance(b, center) <= radius) return true;
        const ab = subVectors(b, a);
        const ac = subVectors(center, a);
        const abLenSq = Math.max(1e-6, ab.x * ab.x + ab.y * ab.y);
        const t = clampNumber((ac.x * ab.x + ac.y * ab.y) / abLenSq, 0, 1);
        const proj = { x: a.x + ab.x * t, y: a.y + ab.y * t };
        return distance(proj, center) <= radius;
    };

    const hasLocalSelfIntersection = (points, center, radius) => {
        if (points.length < 4) return false;
        for (let i = 0; i < points.length - 1; i += 1) {
            const a = points[i];
            const b = points[i + 1];
            if (!segmentNearCircle(a, b, center, radius)) continue;
            for (let j = i + 2; j < points.length - 1; j += 1) {
                if (j === i + 1) continue;
                const c = points[j];
                const d = points[j + 1];
                if (!segmentNearCircle(c, d, center, radius)) continue;
                if (segmentIntersection(a, b, c, d)) {
                    return true;
                }
            }
        }
        return false;
    };

    const getMiniHeadingBeforePort = (
        holder,
        svgRect,
        fallbackPoint = null
    ) => {
        if (!holder || !svgRect) return fallbackPoint;
        const heading = holder.querySelector(".mini-heading");
        if (!heading) return fallbackPoint;

        const headingRect = heading.getBoundingClientRect();
        const headingStyle = window.getComputedStyle(heading);
        const beforeStyle = window.getComputedStyle(heading, "::before");

        const beforeWidth = Math.max(
            parsePx(beforeStyle.width, 0),
            parsePx(beforeStyle.minWidth, 0),
            parsePx(beforeStyle.height, 0),
            40
        );
        const headingPadLeft = parsePx(headingStyle.paddingLeft, 0);
        const beforeMarginLeft = parsePx(beforeStyle.marginLeft, 0);
        const portX = headingRect.left + headingPadLeft + beforeMarginLeft + beforeWidth * 0.5;
        const portY = headingRect.top + headingRect.height * 0.5;

        return {
            x: portX - svgRect.left,
            y: portY - svgRect.top,
        };
    };

    const buildPoint2PortTransition = (
        port,
        prevFar,
        nextFar,
        width,
        height,
        padding,
        handleScale = 1,
        dropScale = 1
    ) => {
        const anchorClamped = clampPoint(port, width, height, padding);
        const prevClamped = clampPoint(prevFar, width, height, padding);
        const nextClamped = clampPoint(nextFar, width, height, padding);

        const base = Math.max(12, height * 0.03);
        const handleLen = Math.max(18, width * 0.04) * handleScale;
        const maxDropToBottom = Math.max(0, (height - padding) - anchorClamped.y);
        const preferredDrop = clampNumber(base * 0.9 * dropScale, 12, 50);
        const exitDrop = clampNumber(preferredDrop, 8, Math.max(8, maxDropToBottom));

        const vin = normalizeVector(subVectors(anchorClamped, prevClamped));
        const rawOut = normalizeVector(subVectors(nextClamped, anchorClamped));
        const fallbackIn = Math.hypot(vin.x, vin.y) < 1e-6 ? { x: 0, y: 1 } : vin;
        const fallbackOut = Math.hypot(rawOut.x, rawOut.y) < 1e-6 ? fallbackIn : rawOut;

        const interiorAtPort = angleAt(
            { x: anchorClamped.x - fallbackIn.x, y: anchorClamped.y - fallbackIn.y },
            anchorClamped,
            { x: anchorClamped.x + fallbackOut.x, y: anchorClamped.y + fallbackOut.y }
        );
        let vout = fallbackOut;
        if (interiorAtPort < 150) {
            const blendedOut = normalizeVector(addVectors(
                scaleVector(fallbackOut, 0.65),
                scaleVector(fallbackIn, 0.35)
            ));
            if (Math.hypot(blendedOut.x, blendedOut.y) > 1e-6) {
                vout = blendedOut;
            }
        }

        const p2Pre = clampPoint(
            {
                x: anchorClamped.x - fallbackIn.x * handleLen,
                y: anchorClamped.y - fallbackIn.y * handleLen,
            },
            width,
            height,
            padding
        );
        const p2Post = clampPoint(
            {
                x: anchorClamped.x + vout.x * handleLen,
                y: anchorClamped.y + vout.y * handleLen,
            },
            width,
            height,
            padding
        );

        const drop1 = clampPoint(
            {
                x: anchorClamped.x + vout.x * (handleLen * 0.35),
                y: anchorClamped.y + vout.y * (handleLen * 0.35) + exitDrop * 0.55,
            },
            width,
            height,
            padding
        );
        const drop2 = clampPoint(
            {
                x: anchorClamped.x + vout.x * (handleLen * 0.75),
                y: anchorClamped.y + vout.y * (handleLen * 0.75) + exitDrop,
            },
            width,
            height,
            padding
        );

        return {
            port: anchorClamped,
            p2Pre,
            p2Post,
            drop1,
            drop2,
            startOut: drop2,
            base,
            exitDrop,
            handleLen,
        };
    };

    const buildPoint2IncomingPortMatchedWaypoints = (
        fromPoint,
        port,
        width,
        height,
        padding,
        topInScale = 1,
        ampScale = 1,
        tailLenScale = 1,
        tailAmpScale = 1,
        useTail0 = true
    ) => {
        const signBased = (port.x - fromPoint.x) >= 0 ? 1 : -1;
        const A1x = clampNumber(width * 0.06, 20, 90) * ampScale;
        const A1y = clampNumber(height * 0.05, 18, 70) * ampScale;
        const A2x = A1x * 1.15;
        const A2y = A1y * 0.85;

        const leadOutBase = lerpPoint(fromPoint, port, 0.12);
        const swing1Base = lerpPoint(fromPoint, port, 0.30);
        const crossOverBase = lerpPoint(fromPoint, port, 0.48);
        const swing2Base = lerpPoint(fromPoint, port, 0.60);

        const smallX = signBased * 0.15;
        const tIn = normalizeVector({ x: smallX, y: 1 });
        let nIn = normalizeVector({ x: -tIn.y, y: tIn.x });
        if ((signBased > 0 && nIn.x < 0) || (signBased < 0 && nIn.x > 0)) {
            nIn = scaleVector(nIn, -1);
        }

        const tailLen = clampNumber(Math.max(40, width * 0.10) * tailLenScale, 40, 140);
        const tailAmp = clampNumber(Math.max(8, width * 0.012) * tailAmpScale, 8, 24);
        const tailLift = clampNumber(Math.max(16, height * 0.04) * topInScale, 16, 70);
        const tail2 = clampPoint(
            {
                x: port.x - tIn.x * (tailLen * 0.95) + nIn.x * (tailAmp * 0.9),
                y: port.y - tIn.y * (tailLen * 0.95) + nIn.y * (tailAmp * 0.9) - tailLift * 0.35,
            },
            width,
            height,
            padding
        );
        const tail1 = clampPoint(
            {
                x: port.x - tIn.x * (tailLen * 0.55) + nIn.x * (-tailAmp * 0.6),
                y: port.y - tIn.y * (tailLen * 0.55) + nIn.y * (-tailAmp * 0.6) - tailLift * 0.15,
            },
            width,
            height,
            padding
        );
        const tail0 = clampPoint(
            {
                x: port.x - tIn.x * (tailLen * 0.25) + nIn.x * (tailAmp * 0.25),
                y: port.y - tIn.y * (tailLen * 0.25) + nIn.y * (tailAmp * 0.25),
            },
            width,
            height,
            padding
        );

        const leadOut = {
            x: leadOutBase.x - A1x * 0.75 * signBased,
            y: leadOutBase.y + A1y * 0.6,
        };
        const swing1 = {
            x: swing1Base.x - A2x * signBased,
            y: swing1Base.y + A2y,
        };
        const crossOver = {
            x: crossOverBase.x + A2x * 0.55 * signBased,
            y: crossOverBase.y + A2y * 0.15,
        };
        const swing2 = {
            x: swing2Base.x + A1x * signBased,
            y: swing2Base.y - A1y * 0.25,
        };

        const points = [
            fromPoint,
            leadOut,
            swing1,
            crossOver,
            swing2,
            tail2,
            tail1,
            ...(useTail0 ? [tail0] : []),
            clampPoint(port, width, height, padding),
        ];
        return {
            points: clampPoints(points, width, height, padding),
            tIn,
        };
    };

    const hasSharpTurnNearPoint = (samples, center, radius, minInteriorAngleDeg = 150) => {
        if (samples.length < 3) return false;
        const r = Math.max(8, radius);
        for (let i = 1; i < samples.length - 1; i += 1) {
            if (distance(samples[i], center) > r) continue;
            const interior = angleAt(samples[i - 1], samples[i], samples[i + 1]);
            if (interior < minInteriorAngleDeg) return true;
        }
        return false;
    };

    const hasSharpTurnAroundNearestSample = (samples, center, window = 10, minInteriorAngleDeg = 155) => {
        if (samples.length < 3) return false;
        let nearest = 0;
        let nearestDist = Number.POSITIVE_INFINITY;
        for (let i = 0; i < samples.length; i += 1) {
            const d = distance(samples[i], center);
            if (d < nearestDist) {
                nearestDist = d;
                nearest = i;
            }
        }
        const start = Math.max(1, nearest - window);
        const end = Math.min(samples.length - 2, nearest + window);
        for (let i = start; i <= end; i += 1) {
            const interior = angleAt(samples[i - 1], samples[i], samples[i + 1]);
            if (interior < minInteriorAngleDeg) return true;
        }
        return false;
    };

    const findNearestSampleIndex = (samples, center) => {
        let nearest = 0;
        let nearestDist = Number.POSITIVE_INFINITY;
        for (let i = 0; i < samples.length; i += 1) {
            const d = distance(samples[i], center);
            if (d < nearestDist) {
                nearestDist = d;
                nearest = i;
            }
        }
        return nearest;
    };

    const getSampleTangent = (samples, index) => {
        if (samples.length < 2) return { x: 0, y: 1 };
        const i = clampNumber(index, 1, samples.length - 1);
        return normalizeVector(subVectors(samples[i], samples[i - 1]));
    };

    const hasInteriorKinkBeforeNearest = (samples, center, backWindow = 8, minInteriorAngleDeg = 160) => {
        if (samples.length < 3) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(1, nearest - backWindow);
        const end = Math.max(start, nearest);
        for (let i = start; i <= end; i += 1) {
            if (i <= 0 || i >= samples.length - 1) continue;
            const interior = angleAt(samples[i - 1], samples[i], samples[i + 1]);
            if (interior < minInteriorAngleDeg) return true;
        }
        return false;
    };

    const hasInteriorKinkAfterNearest = (samples, center, forwardWindow = 8, minInteriorAngleDeg = 160) => {
        if (samples.length < 3) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(1, nearest);
        const end = Math.min(samples.length - 2, nearest + forwardWindow);
        for (let i = start; i <= end; i += 1) {
            if (i <= 0 || i >= samples.length - 1) continue;
            const interior = angleAt(samples[i - 1], samples[i], samples[i + 1]);
            if (interior < minInteriorAngleDeg) return true;
        }
        return false;
    };

    const hasImmediateCurvatureAfterNearest = (samples, center, minTurnDeg = 2.5) => {
        if (samples.length < 5) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(1, nearest);
        const end = Math.min(samples.length - 3, nearest + 2);
        let hits = 0;
        for (let i = start; i <= end; i += 1) {
            const t0 = getSampleTangent(samples, i);
            const t1 = getSampleTangent(samples, i + 1);
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            const turnDeg = (Math.acos(dot) * 180) / Math.PI;
            if (turnDeg > minTurnDeg) hits += 1;
        }
        return hits >= 2;
    };

    const hasStraightRunAfterNearest = (samples, center, steps = 10, dotThreshold = 0.999, maxAllowed = 2) => {
        if (samples.length < 4) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(1, nearest);
        const end = Math.min(samples.length - 3, nearest + steps);
        let nearStraightHits = 0;
        for (let i = start; i <= end; i += 1) {
            const t0 = getSampleTangent(samples, i);
            const t1 = getSampleTangent(samples, i + 1);
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            if (dot > dotThreshold) nearStraightHits += 1;
        }
        return nearStraightHits > maxAllowed;
    };

    const hasCurvyApproachBeforeNearest = (samples, center, backWindow = 20, minTurnDeg = 1.8, minHits = 5) => {
        if (samples.length < 4) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(1, nearest - backWindow);
        const end = Math.max(start, nearest - 1);
        let hits = 0;
        for (let i = start; i <= end; i += 1) {
            const t0 = getSampleTangent(samples, i);
            const t1 = getSampleTangent(samples, i + 1);
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            const turnDeg = (Math.acos(dot) * 180) / Math.PI;
            if (turnDeg > minTurnDeg) hits += 1;
        }
        return hits >= minHits;
    };

    const hasStraightRunBeforeNearest = (samples, center, backWindow = 20, dotThreshold = 0.999, maxRun = 3) => {
        if (samples.length < 4) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(1, nearest - backWindow);
        const end = Math.max(start, nearest - 1);
        let run = 0;
        for (let i = start; i <= end; i += 1) {
            const t0 = getSampleTangent(samples, i);
            const t1 = getSampleTangent(samples, i + 1);
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            if (dot > dotThreshold) {
                run += 1;
                if (run > maxRun) return true;
            } else {
                run = 0;
            }
        }
        return false;
    };

    const hasPositiveDxAfterNearest = (samples, center, steps = 8, minSumDx = 8) => {
        if (samples.length < 3) return false;
        const nearest = findNearestSampleIndex(samples, center);
        const start = Math.max(0, nearest);
        const end = Math.min(samples.length - 2, nearest + steps);
        let sumDx = 0;
        for (let i = start; i <= end; i += 1) {
            sumDx += samples[i + 1].x - samples[i].x;
        }
        return sumDx > minSumDx;
    };

    const medianOf = (values) => {
        if (!values.length) return 0;
        const sorted = values.slice().sort((a, b) => a - b);
        const mid = Math.floor(sorted.length * 0.5);
        if (sorted.length % 2 === 0) return (sorted[mid - 1] + sorted[mid]) * 0.5;
        return sorted[mid];
    };

    const crossZ = (a, b) => a.x * b.y - a.y * b.x;
    const signNonZero = (n, eps = 1e-6) => (n > eps ? 1 : (n < -eps ? -1 : 0));

    const getSampleRangeForSegment = (samples, startPoint, endPoint) => {
        const rawStart = findNearestSampleIndex(samples, startPoint);
        const rawEnd = findNearestSampleIndex(samples, endPoint);
        const s0 = Math.max(0, Math.min(rawStart, rawEnd));
        const s1 = Math.min(samples.length - 1, Math.max(rawStart, rawEnd));
        return { s0, s1 };
    };

    const analyzeWaveSegmentQuality = (segPoints, startPoint, endPoint, debugLabel = "") => {
        const sampleN = 220;
        const samples = sampleSplinePoints(segPoints, sampleN, 0.5);
        if (samples.length < 12) {
            return {
                samples,
                startAngleHits: 0,
                startStraightRunInvalid: true,
                crossSignFlips: 99,
                spikeCount: 99,
                straightRunMax: 99,
                noStraightInvalid: true,
                zigZagInvalid: true,
                dentInvalid: true,
                pass: false,
            };
        }

        const { s0, s1 } = getSampleRangeForSegment(samples, startPoint, endPoint);
        const firstWindowEnd = Math.min(samples.length - 3, s0 + 12);
        let startAngleHits = 0;
        let straightRun = 0;
        let straightRunMax = 0;

        for (let i = Math.max(1, s0); i <= firstWindowEnd; i += 1) {
            const t0 = normalizeVector(subVectors(samples[i], samples[i - 1]));
            const t1 = normalizeVector(subVectors(samples[i + 1], samples[i]));
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            const turnDeg = (Math.acos(dot) * 180) / Math.PI;
            if (turnDeg > 2.0) startAngleHits += 1;
            if (dot > 0.999) {
                straightRun += 1;
                straightRunMax = Math.max(straightRunMax, straightRun);
            } else {
                straightRun = 0;
            }
        }

        let crossSignFlips = 0;
        let prevSign = 0;
        const turnDegSamples = [];
        for (let i = Math.max(1, s0 + 1); i <= Math.min(samples.length - 2, s1 - 1); i += 1) {
            const t0 = normalizeVector(subVectors(samples[i], samples[i - 1]));
            const t1 = normalizeVector(subVectors(samples[i + 1], samples[i]));
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            const turnDeg = (Math.acos(dot) * 180) / Math.PI;
            turnDegSamples.push(turnDeg);
            const sign = signNonZero(crossZ(t0, t1), 1e-5);
            if (sign !== 0) {
                if (prevSign !== 0 && sign !== prevSign) crossSignFlips += 1;
                prevSign = sign;
            }
        }
        const turnMedian = Math.max(0.001, medianOf(turnDegSamples));
        const turnSpikeThreshold = Math.max(4.0, turnMedian * 2.0);
        let spikeCount = 0;
        for (let i = 0; i < turnDegSamples.length; i += 1) {
            if (turnDegSamples[i] > turnSpikeThreshold) spikeCount += 1;
        }

        const noStraightInvalid = startAngleHits < 3 || straightRunMax > 3;
        const zigZagInvalid = crossSignFlips > 2;
        const dentInvalid = spikeCount > 0;
        const pass = !noStraightInvalid && !zigZagInvalid && !dentInvalid;

        if (HERO_SNAKE_DEBUG) {
            console.groupCollapsed(`wave-segment quality ${debugLabel}`);
            console.log("startAngleHits:", startAngleHits);
            console.log("straightRunMax:", straightRunMax);
            console.log("crossSignFlips:", crossSignFlips);
            console.log("turnMedian:", turnMedian);
            console.log("spikeCount:", spikeCount);
            console.log("pass:", pass);
            console.groupEnd();
        }

        return {
            samples,
            startAngleHits,
            startStraightRunInvalid: straightRunMax > 3,
            crossSignFlips,
            spikeCount,
            straightRunMax,
            noStraightInvalid,
            zigZagInvalid,
            dentInvalid,
            pass,
        };
    };

    const detectDentSampleIndices = (samples, turnMin, jerkThresh) => {
        if (samples.length < 8) return [];
        const turns = new Array(samples.length).fill(0);
        const dots = new Array(samples.length).fill(1);
        const signs = new Array(samples.length).fill(0);

        for (let i = 1; i < samples.length - 1; i += 1) {
            const v1 = normalizeVector(subVectors(samples[i], samples[i - 1]));
            const v2 = normalizeVector(subVectors(samples[i + 1], samples[i]));
            const dot = clampNumber(v1.x * v2.x + v1.y * v2.y, -1, 1);
            dots[i] = dot;
            turns[i] = Math.acos(dot);
            signs[i] = signNonZero(crossZ(v1, v2));
        }

        const raw = [];
        for (let i = 2; i < samples.length - 3; i += 1) {
            const winStart = Math.max(1, i - 6);
            const winEnd = Math.min(samples.length - 2, i + 6);
            const winTurns = [];
            for (let w = winStart; w <= winEnd; w += 1) winTurns.push(turns[w]);
            const med = medianOf(winTurns);

            const spike = turns[i] > Math.max(turnMin, med * 2.2);
            const flip =
                signs[i] !== 0 &&
                signs[i - 1] !== 0 &&
                signs[i + 1] !== 0 &&
                signs[i] !== signs[i - 1] &&
                signs[i] !== signs[i + 1] &&
                turns[i] > turnMin * 0.7 &&
                turns[i] < Math.PI * 0.25;
            const jerk = Math.abs(dots[i] - dots[i - 1]) > jerkThresh;

            if ((spike && flip) || (spike && jerk) || (flip && jerk)) {
                raw.push(i);
            }
        }

        if (!raw.length) return raw;
        const grouped = [raw[0]];
        for (let i = 1; i < raw.length; i += 1) {
            if (raw[i] - grouped[grouped.length - 1] <= 3) {
                const prev = grouped[grouped.length - 1];
                if (turns[raw[i]] > turns[prev]) grouped[grouped.length - 1] = raw[i];
            } else {
                grouped.push(raw[i]);
            }
        }
        return grouped;
    };

    const nearestPointIndexInChain = (points, target, used = null) => {
        let nearest = 0;
        let best = Number.POSITIVE_INFINITY;
        for (let i = 0; i < points.length; i += 1) {
            if (used && used.has(i)) continue;
            const d = distance(points[i], target);
            if (d < best) {
                best = d;
                nearest = i;
            }
        }
        return nearest;
    };

    const enforcePinnedPoints = (points, pinnedPoints) => {
        if (!points.length || !pinnedPoints.length) return points;
        const out = points.slice();
        const used = new Set();
        pinnedPoints.forEach((pin) => {
            const idx = nearestPointIndexInChain(out, pin, used);
            out[idx] = { x: pin.x, y: pin.y };
            used.add(idx);
        });
        return out;
    };

    const hasGlobalStraightRun = (samples, dotThreshold = 0.999, maxRun = 3) => {
        if (samples.length < 4) return false;
        let run = 0;
        for (let i = 1; i < samples.length - 1; i += 1) {
            const t0 = normalizeVector(subVectors(samples[i], samples[i - 1]));
            const t1 = normalizeVector(subVectors(samples[i + 1], samples[i]));
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            if (dot > dotThreshold) {
                run += 1;
                if (run > maxRun) return true;
            } else {
                run = 0;
            }
        }
        return false;
    };

    const hasGlobalHardKink = (samples, kinkDotMin = 0.92) => {
        if (samples.length < 4) return false;
        for (let i = 1; i < samples.length - 1; i += 1) {
            const t0 = normalizeVector(subVectors(samples[i], samples[i - 1]));
            const t1 = normalizeVector(subVectors(samples[i + 1], samples[i]));
            const dot = clampNumber(t0.x * t1.x + t0.y * t1.y, -1, 1);
            if (dot < kinkDotMin) return true;
        }
        return false;
    };

    const applyLocalDentFairing = (
        points,
        dentIndices,
        splineSamples,
        pinnedSet,
        lambda,
        bump,
        width,
        height,
        padding
    ) => {
        if (!dentIndices.length || points.length < 5) return points.slice();
        const out = points.slice();
        dentIndices.forEach((sampleIdx) => {
            const samplePoint = splineSamples[sampleIdx];
            const k = nearestPointIndexInChain(out, samplePoint);
            const start = Math.max(1, k - 2);
            const end = Math.min(out.length - 2, k + 2);
            for (let j = start; j <= end; j += 1) {
                if (j <= 0 || j >= out.length - 1) continue;
                if (pinnedSet.has(j)) continue;
                const prev = out[j - 1];
                const curr = out[j];
                const next = out[j + 1];
                const midpoint = {
                    x: (prev.x + next.x) * 0.5,
                    y: (prev.y + next.y) * 0.5,
                };
                let nextPoint = {
                    x: curr.x + lambda * (midpoint.x - curr.x),
                    y: curr.y + lambda * (midpoint.y - curr.y),
                };
                const interior = angleAt(prev, nextPoint, next);
                if (interior > 175) {
                    const tLocal = normalizeVector(subVectors(next, prev));
                    let nLocal = normalizeVector({ x: -tLocal.y, y: tLocal.x });
                    const c = crossZ(subVectors(curr, prev), subVectors(next, curr));
                    const s = signNonZero(c);
                    if (s < 0) nLocal = scaleVector(nLocal, -1);
                    nextPoint = {
                        x: nextPoint.x + nLocal.x * bump,
                        y: nextPoint.y + nLocal.y * bump,
                    };
                }
                out[j] = clampPoint(nextPoint, width, height, padding);
            }
        });
        return out;
    };

    const runCurvatureUniformityPass = (
        points,
        pinnedPoints,
        width,
        height,
        padding,
        minLen,
        collinearAngleEps,
        sampleN
    ) => {
        if (points.length < 5) return points.slice();
        const turnMin = (3 * Math.PI) / 180;
        const jerkThresh = 0.12;
        const bump = clampNumber(width * 0.002, 1.5, 6);
        const lambdaPasses = [0.35, 0.35, 0.22];

        let current = points.slice();
        current = enforcePinnedPoints(current, pinnedPoints);

        for (let pass = 0; pass < lambdaPasses.length; pass += 1) {
            const samples = sampleSplinePoints(current, sampleN, 0.5);
            const dents = detectDentSampleIndices(samples, turnMin, jerkThresh);
            const hasStraight = hasGlobalStraightRun(samples, 0.999, 3);
            const hasKink = hasGlobalHardKink(samples, 0.92);
            if (!dents.length && !hasStraight && !hasKink) {
                return current;
            }

            const pinnedSet = new Set();
            const pinnedAnchors = enforcePinnedPoints(current, pinnedPoints);
            pinnedPoints.forEach((pin) => {
                pinnedSet.add(nearestPointIndexInChain(pinnedAnchors, pin));
            });

            current = applyLocalDentFairing(
                current,
                dents,
                samples,
                pinnedSet,
                lambdaPasses[pass],
                bump,
                width,
                height,
                padding
            );
            current = enforcePinnedPoints(current, pinnedPoints);
            current = clampPoints(current, width, height, padding);
            current = dedupeConsecutive(current, 0.8);
            current = enforceMinSegmentLength(current, minLen);
            current = removeNearCollinear(current, collinearAngleEps, minLen * 0.7);
            current = clampPoints(current, width, height, padding);
            current = dedupeConsecutive(current, 0.8);
            current = enforceMinSegmentLength(current, minLen);
            current = enforcePinnedPoints(current, pinnedPoints);
        }
        return current;
    };

    const deterministicOffset = (t, seed, amplitude, k1, k2) => {
        const phase = Math.PI * 2 * t;
        return (
            Math.sin(phase * k1 + seed * 0.013) * amplitude +
            Math.sin(phase * k2 + seed * 0.021) * amplitude * 0.5
        );
    };

    const buildPoint2OutgoingPortBlendTransition = (
        port,
        toPoint,
        width,
        height,
        padding,
        tIn,
        rScale = 1,
        sScale = 1,
        blendFactor = 0.55,
        exit3BackScale = 1
    ) => {
        const dirToP3Raw = normalizeVector(subVectors(toPoint, port));
        const dirToP3 = Math.hypot(dirToP3Raw.x, dirToP3Raw.y) < 1e-6 ? { x: 0, y: 1 } : dirToP3Raw;
        const tOutBlend = normalizeVector(addVectors(
            scaleVector(tIn, 1 - blendFactor),
            scaleVector(dirToP3, blendFactor)
        ));
        const tBase = Math.hypot(tOutBlend.x, tOutBlend.y) < 1e-6 ? dirToP3 : tOutBlend;
        let n = normalizeVector({ x: -tBase.y, y: tBase.x });
        const wantRight = toPoint.x >= port.x;
        if ((wantRight && n.x < 0) || (!wantRight && n.x > 0)) {
            n = scaleVector(n, -1);
        }
        n = scaleVector(n, -1);

        const R = clampNumber(Math.max(14, width * 0.020) * rScale, 14, 40);
        const S = clampNumber(R * 0.70 * sScale, 10, 34);

        const exit1 = clampPoint(
            {
                x: port.x + tBase.x * (R * 0.35) + n.x * (S * 1.00),
                y: port.y + tBase.y * (R * 0.35) + n.y * (S * 1.00),
            },
            width,
            height,
            padding
        );
        const exit2 = clampPoint(
            {
                x: port.x + tBase.x * (R * 1.05) + n.x * (S * 0.55),
                y: port.y + tBase.y * (R * 1.05) + n.y * (S * 0.55),
            },
            width,
            height,
            padding
        );
        const exit3 = clampPoint(
            {
                x: port.x + tBase.x * (R * 2.00) + n.x * (-S * 0.12 * exit3BackScale),
                y: port.y + tBase.y * (R * 2.00) + n.y * (-S * 0.12 * exit3BackScale),
            },
            width,
            height,
            padding
        );
        const transitionPoints = [clampPoint(port, width, height, padding), exit1, exit2, exit3];
        return {
            transitionPoints,
            startOut: exit3,
            tOut: tBase,
            n,
            dirToP3,
        };
    };

    const makeWaveSegment = (start, end, bounds, options = {}) => {
        const width = bounds.width;
        const height = bounds.height;
        const padding = bounds.padding;
        const earlyRampEnd = Number.isFinite(options.earlyRampEnd) ? options.earlyRampEnd : 0.45;
        const ampMultiplier = Number.isFinite(options.ampMultiplier) ? options.ampMultiplier : 1;
        const includeStartMicroArc = options.includeStartMicroArc !== false;

        const p0 = clampPoint(start, width, height, padding);
        const p1 = clampPoint(end, width, height, padding);
        const v = subVectors(p1, p0);
        const len = Math.max(1e-6, Math.hypot(v.x, v.y));
        let t = { x: v.x / len, y: v.y / len };
        if (Math.hypot(t.x, t.y) < 1e-6) t = { x: 0, y: 1 };
        let n = normalizeVector({ x: -t.y, y: t.x });
        const wantRight = p1.x >= p0.x;
        if ((wantRight && n.x < 0) || (!wantRight && n.x > 0)) n = scaleVector(n, -1);

        const A = clampNumber(len * 0.16 * ampMultiplier, 45, 190);
        const Ay = clampNumber(height * 0.05, 18, 120);
        const rampAt = (u) => smoothstep(0, earlyRampEnd, clampNumber(u, 0, 1));
        const addWaveOffset = (u, nScale, yScale) => {
            const base = lerpPoint(p0, p1, u);
            const normalOffset = scaleVector(n, A * nScale * rampAt(u));
            return clampPoint(
                addVectors(
                    addVectors(base, normalOffset),
                    { x: 0, y: Ay * yScale }
                ),
                width,
                height,
                padding
            );
        };

        const points = [p0];
        if (includeStartMicroArc) {
            const startRadius = clampNumber(len * 0.085, 12, 46);
            const startSide = clampNumber(A * 0.20, 10, 34);
            const e1 = clampPoint(
                addVectors(
                    addVectors(p0, scaleVector(t, startRadius * 0.28)),
                    scaleVector(n, startSide * 0.95)
                ),
                width,
                height,
                padding
            );
            const e2 = clampPoint(
                addVectors(
                    addVectors(p0, scaleVector(t, startRadius * 0.82)),
                    scaleVector(n, startSide * 0.60)
                ),
                width,
                height,
                padding
            );
            const e3 = clampPoint(
                addVectors(
                    addVectors(p0, scaleVector(t, startRadius * 1.55)),
                    scaleVector(n, startSide * 0.22)
                ),
                width,
                height,
                padding
            );
            points.push(e1, e2, e3);
        }

        const m1 = addWaveOffset(0.30, 1.00, 0.20);
        const m2 = addWaveOffset(0.60, -0.75, 0.10);
        const m3 = addWaveOffset(0.82, 0.35, 0.04);
        points.push(m1, m2, m3, p1);
        return clampPoints(points, width, height, padding);
    };

    const buildMacroWaypoints = (
        from,
        to,
        segmentIndex,
        anchorCount,
        ampScale,
        width,
        height,
        padding,
        options = {}
    ) => {
        const earlyRampEnd = Number.isFinite(options.earlyRampEnd) ? options.earlyRampEnd : 0.4;
        const ampMultiplier = Number.isFinite(options.ampMultiplier) ? options.ampMultiplier : 1;
        const dx = to.x - from.x;
        const dy = to.y - from.y;
        const span = Math.max(distance(from, to), 1);
        const seed = (segmentIndex + 1) * 10007 + anchorCount * 97 + Math.round(from.x + from.y);
        const dirX = dx >= 0 ? 1 : -1;
        const ampBase = clampNumber(span * 0.2 * ampScale * ampMultiplier, 22, Math.min(width, height) * 0.24);
        const nudgeX = deterministicOffset(0.5, seed, ampBase * 0.12, 0.65, 1.1);
        const nudgeY = deterministicOffset(0.35, seed + 17, ampBase * 0.2, 0.75, 1.2);

        let points = [from];
        if (segmentIndex === 0) {
            const dxKick = clampNumber(Math.abs(dx) * 0.18 + ampBase * 0.16, 14, span * 0.4);
            const dyKick = clampNumber(Math.abs(dy) * 0.18 + ampBase * 0.3, 16, span * 0.45);
            const approachX = clampNumber(Math.abs(dx) * 0.12 + ampBase * 0.4, 16, span * 0.5);
            const approachY = clampNumber(Math.abs(dy) * 0.08 + ampBase * 0.25, 12, span * 0.35);
            points = points.concat([
                { x: from.x - dxKick * dirX * 0.3, y: from.y + dyKick + nudgeY * 0.3 },
                { x: lerpPoint(from, to, 0.4).x - ampBase + nudgeX, y: lerpPoint(from, to, 0.4).y + ampBase * 0.8 + nudgeY },
                { x: lerpPoint(from, to, 0.7).x + ampBase * 0.6 + nudgeX * 0.4, y: lerpPoint(from, to, 0.7).y + ampBase * 0.2 + nudgeY * 0.3 },
                { x: to.x - approachX * dirX, y: to.y + approachY },
            ]);
        } else if (segmentIndex === 1) {
            const amp = ampBase * 1.1;
            const p33 = lerpPoint(from, to, 0.33);
            const p66 = lerpPoint(from, to, 0.66);
            const approachX = clampNumber(Math.abs(dx) * 0.1 + amp * 0.28, 14, span * 0.45);
            const approachY = clampNumber(Math.abs(dy) * 0.08 + amp * 0.2, 10, span * 0.35);
            points = points.concat([
                { x: p33.x + amp * dirX + deterministicOffset(0.33, seed, amp * 0.15, 0.8, 1.15), y: p33.y - amp * 0.3 + deterministicOffset(0.33, seed + 9, amp * 0.18, 0.72, 1.05) },
                { x: p66.x - amp * dirX + deterministicOffset(0.66, seed, amp * 0.15, 0.8, 1.15), y: p66.y + amp * 0.3 + deterministicOffset(0.66, seed + 9, amp * 0.18, 0.72, 1.05) },
                { x: to.x - approachX * dirX, y: to.y + approachY },
            ]);
        } else {
            const amp = ampBase * 1.2;
            const p30 = lerpPoint(from, to, 0.3);
            const p55 = lerpPoint(from, to, 0.55);
            const p80 = lerpPoint(from, to, 0.8);
            points = points.concat([
                { x: p30.x + amp * 1.2 * dirX + deterministicOffset(0.3, seed, amp * 0.12, 0.7, 1.0), y: p30.y - amp * 0.2 + deterministicOffset(0.3, seed + 5, amp * 0.12, 0.8, 1.2) },
                { x: p55.x + amp * 1.6 * dirX + deterministicOffset(0.55, seed, amp * 0.15, 0.7, 1.1), y: p55.y + amp * 0.4 + deterministicOffset(0.55, seed + 5, amp * 0.1, 0.75, 1.05) },
                { x: p80.x + amp * 0.6 * dirX + deterministicOffset(0.8, seed, amp * 0.1, 0.7, 1.1), y: p80.y + amp * 0.2 + deterministicOffset(0.8, seed + 5, amp * 0.1, 0.75, 1.05) },
            ]);
        }
        points.push(to);

        if (segmentIndex === 0 && points.length > 3) {
            const base = subVectors(to, from);
            const baseLenSq = Math.max(1e-6, base.x * base.x + base.y * base.y);
            for (let i = 1; i < points.length - 1; i += 1) {
                const rel = subVectors(points[i], from);
                const t = clampNumber((rel.x * base.x + rel.y * base.y) / baseLenSq, 0, 1);
                if (t <= 0.6) continue;
                const linePoint = lerpPoint(from, to, t);
                const offset = subVectors(points[i], linePoint);
                const keep = 1 - smoothstep(0.6, 1, t);
                points[i] = addVectors(linePoint, scaleVector(offset, keep));
            }
        }
        if (segmentIndex === 1 && points.length > 3) {
            const base = subVectors(to, from);
            const baseLenSq = Math.max(1e-6, base.x * base.x + base.y * base.y);
            for (let i = 1; i < points.length - 1; i += 1) {
                const rel = subVectors(points[i], from);
                const t = clampNumber((rel.x * base.x + rel.y * base.y) / baseLenSq, 0, 1);
                if (t >= earlyRampEnd) continue;
                const linePoint = lerpPoint(from, to, t);
                const offset = subVectors(points[i], linePoint);
                const ramp = smoothstep(0, earlyRampEnd, t);
                points[i] = addVectors(linePoint, scaleVector(offset, ramp));
            }
        }
        if (segmentIndex === 2 && points.length > 3) {
            const base = subVectors(to, from);
            const baseLenSq = Math.max(1e-6, base.x * base.x + base.y * base.y);
            for (let i = 1; i < points.length - 1; i += 1) {
                const rel = subVectors(points[i], from);
                const t = clampNumber((rel.x * base.x + rel.y * base.y) / baseLenSq, 0, 1);
                if (t >= 0.70) continue;
                const linePoint = lerpPoint(from, to, t);
                const offset = subVectors(points[i], linePoint);
                const ramp = smoothstep(0, 0.70, t);
                points[i] = addVectors(linePoint, scaleVector(offset, ramp));
            }
        }

        return clampPoints(points, width, height, padding);
    };

    const pathFromPoints = (points, alpha = 0.5, tension = 0.5) => {
        if (points.length < 2) return "";
        const fmt = (n) => Number(n.toFixed(2));
        let d = `M ${fmt(points[0].x)} ${fmt(points[0].y)}`;
        for (let i = 0; i < points.length - 1; i += 1) {
            const p0 = points[Math.max(0, i - 1)];
            const p1 = points[i];
            const p2 = points[i + 1];
            const p3 = points[Math.min(points.length - 1, i + 2)];
            const curve = centripetalSegmentToBezier(p0, p1, p2, p3, alpha, tension);
            d += ` C ${fmt(curve.c1.x)} ${fmt(curve.c1.y)}, ${fmt(curve.c2.x)} ${fmt(curve.c2.y)}, ${fmt(curve.p2.x)} ${fmt(curve.p2.y)}`;
        }
        return d;
    };

    const buildPaths = () => {
        clearAll();
        const svgRect = svg.getBoundingClientRect();
        if (svgRect.width <= 0 || svgRect.height <= 0) return;
        svg.setAttribute("viewBox", `0 0 ${svgRect.width} ${svgRect.height}`);
        svg.setAttribute("preserveAspectRatio", "none");

        const allAnchors = holders.map((holder) => {
            const anchor = holder.querySelector(".mini-heading__anchor");
            return anchor ? getAnchorPoint(anchor, svgRect) : null;
        });

        const getAnchorSafe = (index, fallback) => {
            if (index < 0 || index >= allAnchors.length) return fallback;
            return allAnchors[index] || fallback;
        };

        const point2Holder = holders[1] || null;
        const point2Anchor = getAnchorSafe(1, null);
        const point2Port =
            getMiniHeadingBeforePort(point2Holder, svgRect, point2Anchor) || point2Anchor;
        const point1ForPort = getAnchorSafe(0, point2Port);
        const point2SignBase = (point2Port.x - point1ForPort.x) >= 0 ? 1 : -1;
        const point2IncomingTIn = normalizeVector({ x: point2SignBase * 0.15, y: 1 });

        pairs.forEach(({ currentHolder, nextHolder, fromAnchor, toAnchor }, segmentIndex) => {
            const fromAnchorPoint = getAnchorPoint(fromAnchor, svgRect);
            const baseToPoint = getAnchorPoint(toAnchor, svgRect);
            const padding = 10;
            const minLenBase = clampNumber(Math.min(svgRect.width, svgRect.height) * 0.025, 8, 26);
            let amplitudeScale = 1;
            let finalPoints = [fromAnchorPoint, baseToPoint];
            const isPoint2Incoming = segmentIndex === 0 && Boolean(point2Port);
            const isPoint2Outgoing = segmentIndex === 1 && Boolean(point2Port);
            const isPoint3Outgoing = segmentIndex === 2;
            const fallbackToPoint = isPoint2Incoming && point2Port ? point2Port : baseToPoint;
            let p2DropScale = 1;
            let p2HandleScale = 1;
            let p2TopInScale = 1;
            let p2AmpScale = 1;
            let p2TailLenScale = 1;
            let p2TailAmpScale = 1;
            let p2UseTail0 = true;
            let p2OutBlendFactor = 0.55;
            let p2OutRampEnd = 0.45;
            let p2OutAmpScale = 1;
            let p2OutRScale = 1;
            let p2OutSScale = 1;
            let p2OutExit3BackScale = 1;
            let p3WaveAmpScale = 1;
            let p3WaveExtraSmooth = 0;
            let p3WaveRetryCount = 0;
            let p2GuardAdjustments = 0;

            for (let iteration = 0; iteration < 4; iteration += 1) {
                let fromPoint = fromAnchorPoint;
                let toPoint = baseToPoint;
                let transitionMeta = null;
                let transitionPoints = null;
                let incomingPortMeta = null;
                let outgoingPortMeta = null;

                if (isPoint2Incoming || isPoint2Outgoing) {
                    const prevFar = getAnchorSafe(0, fromAnchorPoint);
                    const nextFar = getAnchorSafe(2, baseToPoint);
                    transitionMeta = buildPoint2PortTransition(
                        point2Port,
                        prevFar,
                        nextFar,
                        svgRect.width,
                        svgRect.height,
                        padding,
                        p2HandleScale,
                        p2DropScale
                    );
                    if (isPoint2Incoming) {
                        toPoint = transitionMeta.port;
                    }
                    if (isPoint2Outgoing) {
                        outgoingPortMeta = buildPoint2OutgoingPortBlendTransition(
                            transitionMeta.port,
                            toPoint,
                            svgRect.width,
                            svgRect.height,
                            padding,
                            point2IncomingTIn,
                            p2OutRScale,
                            p2OutSScale,
                            p2OutBlendFactor,
                            p2OutExit3BackScale
                        );
                        fromPoint = outgoingPortMeta.startOut;
                        transitionPoints = outgoingPortMeta.transitionPoints;
                    }
                }
                let rawMacro;
                if (isPoint2Incoming && transitionMeta) {
                    incomingPortMeta = buildPoint2IncomingPortMatchedWaypoints(
                        fromPoint,
                        transitionMeta.port,
                        svgRect.width,
                        svgRect.height,
                        padding,
                        p2TopInScale,
                        p2AmpScale,
                        p2TailLenScale,
                        p2TailAmpScale,
                        p2UseTail0
                    );
                    rawMacro = incomingPortMeta.points;
                } else {
                    const useWaveStyle = isPoint2Outgoing || isPoint3Outgoing;
                    const macroOptions = isPoint2Outgoing
                        ? { earlyRampEnd: p2OutRampEnd, ampMultiplier: p2OutAmpScale, includeStartMicroArc: false }
                        : (
                            isPoint3Outgoing
                                ? { earlyRampEnd: 0.45, ampMultiplier: p3WaveAmpScale, includeStartMicroArc: true }
                                : {}
                        );
                    if (useWaveStyle) {
                        rawMacro = makeWaveSegment(
                            fromPoint,
                            toPoint,
                            { width: svgRect.width, height: svgRect.height, padding },
                            macroOptions
                        );
                    } else {
                        rawMacro = buildMacroWaypoints(
                            fromPoint,
                            toPoint,
                            segmentIndex,
                            holders.length,
                            amplitudeScale,
                            svgRect.width,
                            svgRect.height,
                            padding,
                            macroOptions
                        );
                    }
                    if (!isPoint3Outgoing && transitionPoints && transitionPoints.length) {
                        rawMacro = transitionPoints.concat(rawMacro.slice(1));
                    }
                }

                let candidate = clampPoints(rawMacro, svgRect.width, svgRect.height, padding);
                candidate = chaikinSmooth(candidate);
                candidate = chaikinSmooth(candidate);
                if (isPoint3Outgoing && p3WaveExtraSmooth > 0) {
                    for (let s = 0; s < p3WaveExtraSmooth; s += 1) {
                        candidate = chaikinSmooth(candidate);
                    }
                }
                candidate = dedupeConsecutive(candidate, 0.8);
                const segmentMinLen = isPoint2Outgoing
                    ? clampNumber(minLenBase * 0.7 + iteration * 1.1, 6, minLenBase + 2)
                    : (
                        isPoint2Incoming
                            ? clampNumber(minLenBase * 0.72 + iteration * 1.1, 6, minLenBase + 2)
                            : (
                                isPoint3Outgoing
                                    ? clampNumber(minLenBase * 0.52 + iteration * 0.6, 4, minLenBase)
                                    : (minLenBase + iteration * 1.5)
                            )
                    );
                candidate = enforceMinSegmentLength(candidate, segmentMinLen);
                const collinearAngleEps = isPoint2Incoming ? 6 : (isPoint3Outgoing ? 3 : 8);
                candidate = removeNearCollinear(candidate, collinearAngleEps, minLenBase * 0.55);
                candidate = clampPoints(candidate, svgRect.width, svgRect.height, padding);
                candidate = dedupeConsecutive(candidate, 0.8);
                candidate = enforceMinSegmentLength(candidate, segmentMinLen);
                const pinnedPoints = [fromAnchorPoint, baseToPoint];
                if (transitionMeta && transitionMeta.port) pinnedPoints.push(transitionMeta.port);
                const uniformitySamples = holders.length <= 4 ? 300 : 240;
                candidate = runCurvatureUniformityPass(
                    candidate,
                    pinnedPoints,
                    svgRect.width,
                    svgRect.height,
                    padding,
                    segmentMinLen,
                    collinearAngleEps,
                    uniformitySamples
                );
                if (candidate.length < 2) {
                    candidate = [fromPoint, toPoint];
                }

                const sampleCount = isPoint2Incoming ? 160 : (isPoint3Outgoing ? 240 : (isPoint2Outgoing ? 200 : 80));
                const samples = sampleSplinePoints(candidate, sampleCount, 0.5);
                const localAnchorInvalid =
                    Boolean(transitionMeta && isPoint2Outgoing) &&
                    hasLocalSelfIntersection(candidate, transitionMeta.port, transitionMeta.base * 3);
                const point2SharpTurnInvalid =
                    Boolean(transitionMeta && (isPoint2Incoming || isPoint2Outgoing)) &&
                    (
                        hasSharpTurnNearPoint(samples, transitionMeta.port, transitionMeta.handleLen * 2.4, 155) ||
                        hasSharpTurnAroundNearestSample(samples, transitionMeta.port, 10, 155)
                    );
                const point2NearestIndex = transitionMeta ? findNearestSampleIndex(samples, transitionMeta.port) : 1;
                const point2Tan = getSampleTangent(samples, point2NearestIndex);
                const point2Dot = incomingPortMeta ? (point2Tan.x * incomingPortMeta.tIn.x + point2Tan.y * incomingPortMeta.tIn.y) : 1;
                const point2IncomingFromAboveInvalid =
                    Boolean(transitionMeta && isPoint2Incoming) &&
                    point2Tan.y <= 0;
                const point2TangentMismatchInvalid =
                    Boolean(transitionMeta && isPoint2Incoming) &&
                    point2Dot < 0.92;
                const point2PrePortKinkInvalid =
                    Boolean(transitionMeta && isPoint2Incoming) &&
                    hasInteriorKinkBeforeNearest(samples, transitionMeta.port, 8, 160);
                const point2ApproachNotCurvyInvalid =
                    Boolean(transitionMeta && isPoint2Incoming) &&
                    !hasCurvyApproachBeforeNearest(samples, transitionMeta.port, 20, 1.8, 5);
                const point2ApproachStraightRunInvalid =
                    Boolean(transitionMeta && isPoint2Incoming) &&
                    hasStraightRunBeforeNearest(samples, transitionMeta.port, 20, 0.999, 3);
                const point2TanIn = point2Tan;
                const point2TanOut = getSampleTangent(samples, Math.min(samples.length - 1, point2NearestIndex + 1));
                const point2InOutDot = point2TanIn.x * point2TanOut.x + point2TanIn.y * point2TanOut.y;
                const point2OutToP3Dot = outgoingPortMeta
                    ? point2TanOut.x * outgoingPortMeta.dirToP3.x + point2TanOut.y * outgoingPortMeta.dirToP3.y
                    : 1;
                const point2PostPortContinuityInvalid =
                    Boolean(transitionMeta && isPoint2Outgoing) &&
                    (point2InOutDot < 0.95 || point2OutToP3Dot < 0.75);
                const point2PostPortKinkInvalid =
                    Boolean(transitionMeta && isPoint2Outgoing) &&
                    hasInteriorKinkAfterNearest(samples, transitionMeta.port, 8, 160);
                const point2PostPortStraightInvalid =
                    Boolean(transitionMeta && isPoint2Outgoing) &&
                    !hasImmediateCurvatureAfterNearest(samples, transitionMeta.port, 2.5);
                const point2PostPortStraightRunInvalid =
                    Boolean(transitionMeta && isPoint2Outgoing) &&
                    hasStraightRunAfterNearest(samples, transitionMeta.port, 10, 0.999, 2);
                const point2OutDirSampleIndex = Math.min(samples.length - 1, point2NearestIndex + 2);
                const point2OutDirRel = subVectors(samples[point2OutDirSampleIndex], transitionMeta ? transitionMeta.port : fromPoint);
                const point2OutSideSign = outgoingPortMeta
                    ? (point2OutDirRel.x * outgoingPortMeta.n.x + point2OutDirRel.y * outgoingPortMeta.n.y)
                    : 1;
                const point2PostPortDirectionInvalid =
                    Boolean(transitionMeta && isPoint2Outgoing && outgoingPortMeta) &&
                    point2OutSideSign <= 0;
                const point3WaveQuality = isPoint3Outgoing
                    ? analyzeWaveSegmentQuality(candidate, fromPoint, toPoint, `iter-${iteration}`)
                    : {
                        noStraightInvalid: false,
                        zigZagInvalid: false,
                        dentInvalid: false,
                        pass: true,
                    };
                const invalid =
                    detectSelfIntersections(samples) ||
                    hasLocalReversal(samples, fromPoint, toPoint, segmentIndex) ||
                    hasSharpTurn(samples, 140) ||
                    point2SharpTurnInvalid ||
                    point2TangentMismatchInvalid ||
                    point2PrePortKinkInvalid ||
                    point2ApproachNotCurvyInvalid ||
                    point2ApproachStraightRunInvalid ||
                    point2PostPortContinuityInvalid ||
                    point2PostPortKinkInvalid ||
                    point2PostPortStraightInvalid ||
                    point2PostPortStraightRunInvalid ||
                    point2PostPortDirectionInvalid ||
                    point3WaveQuality.noStraightInvalid ||
                    point3WaveQuality.zigZagInvalid ||
                    point3WaveQuality.dentInvalid ||
                    point2IncomingFromAboveInvalid ||
                    localAnchorInvalid;

                finalPoints = candidate;
                if (!invalid) break;
                if (point2SharpTurnInvalid && p2GuardAdjustments < 3) {
                    if (isPoint2Incoming) {
                        p2TopInScale *= 1.1;
                        p2AmpScale *= 0.9;
                    } else {
                        p2HandleScale *= 1.15;
                        p2DropScale *= 0.85;
                    }
                    p2GuardAdjustments += 1;
                    continue;
                }
                if ((point2ApproachNotCurvyInvalid || point2ApproachStraightRunInvalid) && p2GuardAdjustments < 3) {
                    p2TailAmpScale *= 1.15;
                    p2TailLenScale *= 1.10;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (point2PostPortContinuityInvalid && p2GuardAdjustments < 3) {
                    p2OutRScale *= 1.1;
                    p2OutRampEnd = Math.min(0.6, p2OutRampEnd + 0.08);
                    p2OutAmpScale *= 0.9;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (point2PostPortKinkInvalid && p2GuardAdjustments < 3) {
                    p2OutBlendFactor = 0.45;
                    p2OutExit3BackScale = Math.min(p2OutExit3BackScale, 0.8);
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (point2PostPortStraightInvalid && p2GuardAdjustments < 3) {
                    p2OutSScale *= 1.15;
                    p2OutRScale *= 0.95;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (point2PostPortStraightRunInvalid && p2GuardAdjustments < 3) {
                    p2OutSScale *= 1.15;
                    p2OutRScale *= 0.95;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (point2PostPortDirectionInvalid && p2GuardAdjustments < 3) {
                    p2OutSScale *= 1.15;
                    p2OutRScale *= 1.1;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (isPoint3Outgoing && !point3WaveQuality.pass && p3WaveRetryCount < 3) {
                    if (point3WaveQuality.zigZagInvalid || point3WaveQuality.dentInvalid || point3WaveQuality.noStraightInvalid) {
                        p3WaveAmpScale *= 0.90;
                    }
                    if (point3WaveQuality.dentInvalid) {
                        p3WaveExtraSmooth = 1;
                    }
                    p3WaveRetryCount += 1;
                    continue;
                }
                if ((point2IncomingFromAboveInvalid || point2TangentMismatchInvalid) && p2GuardAdjustments < 3) {
                    p2TopInScale *= 1.1;
                    p2TailAmpScale = Math.max(0.9, p2TailAmpScale * 0.9);
                    p2TailLenScale *= 1.1;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (point2PrePortKinkInvalid && p2GuardAdjustments < 3) {
                    p2TailAmpScale = Math.max(0.9, p2TailAmpScale * 0.9);
                    p2TailLenScale *= 1.1;
                    p2UseTail0 = true;
                    p2GuardAdjustments += 1;
                    continue;
                }
                if (localAnchorInvalid) {
                    p2HandleScale *= 1.08;
                    p2DropScale *= 0.9;
                }
                amplitudeScale *= 0.75;
            }

            const smoothD = pathFromPoints(finalPoints, 0.5, 0.5) || `M ${fromAnchorPoint.x} ${fromAnchorPoint.y} L ${fallbackToPoint.x} ${fallbackToPoint.y}`;
            const path = document.createElementNS(svgNs, "path");
            path.setAttribute("d", smoothD);
            path.setAttribute("fill", "none");
            path.setAttribute("stroke", "var(--acc-clr)");
            path.setAttribute("stroke-width", "4");
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

            if (tween.scrollTrigger) {
                triggers.push(tween.scrollTrigger);
            }
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








