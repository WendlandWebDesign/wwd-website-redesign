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
    const perpendicular = (v) => ({ x: -v.y, y: v.x });

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

    const buildJunctionPortTransition = (
        anchor,
        prevFar,
        nextFar,
        width,
        height,
        padding,
        dropScale = 1,
        sideScale = 1
    ) => {
        const anchorClamped = clampPoint(anchor, width, height, padding);
        const prevClamped = clampPoint(prevFar, width, height, padding);
        const nextClamped = clampPoint(nextFar, width, height, padding);

        const base = Math.max(10, height * 0.025);
        const maxDropToBottom = Math.max(0, (height - padding) - anchorClamped.y);
        const preferredDrop = clampNumber(base * 0.9 * dropScale, 12, 48);
        const exitDrop = clampNumber(preferredDrop, 8, Math.max(8, maxDropToBottom));
        const exitLead = base * 0.8;
        const sideShift = base * 0.25 * sideScale;

        const vin = normalizeVector(subVectors(anchorClamped, prevClamped));
        const vout = normalizeVector(subVectors(nextClamped, anchorClamped));
        const fallbackOut = Math.hypot(vout.x, vout.y) < 1e-6 ? { x: 0, y: 1 } : vout;

        const blend = normalizeVector(addVectors(vin, fallbackOut));
        const normalBase = Math.hypot(blend.x, blend.y) < 1e-6 ? fallbackOut : blend;
        const n = normalizeVector(perpendicular(normalBase));
        const pOut = clampPoint(
            { x: anchorClamped.x, y: anchorClamped.y + exitDrop },
            width,
            height,
            padding
        );

        const drop1 = clampPoint(
            {
                x: anchorClamped.x + fallbackOut.x * (exitLead * 0.25) + n.x * sideShift,
                y: anchorClamped.y + exitDrop * 0.55 + fallbackOut.y * (exitLead * 0.15),
            },
            width,
            height,
            padding
        );
        const drop2 = clampPoint(
            {
                x: anchorClamped.x + fallbackOut.x * (exitLead * 0.45) + n.x * (sideShift * 0.6),
                y: anchorClamped.y + exitDrop + fallbackOut.y * (exitLead * 0.2),
            },
            width,
            height,
            padding
        );
        const transitionPoints = [anchorClamped, drop1, drop2, pOut];
        return {
            transitionPoints,
            startOut: pOut,
            base,
            exitDrop,
        };
    };

    const deterministicOffset = (t, seed, amplitude, k1, k2) => {
        const phase = Math.PI * 2 * t;
        return (
            Math.sin(phase * k1 + seed * 0.013) * amplitude +
            Math.sin(phase * k2 + seed * 0.021) * amplitude * 0.5
        );
    };

    const buildMacroWaypoints = (from, to, segmentIndex, anchorCount, ampScale, width, height, padding) => {
        const dx = to.x - from.x;
        const dy = to.y - from.y;
        const span = Math.max(distance(from, to), 1);
        const seed = (segmentIndex + 1) * 10007 + anchorCount * 97 + Math.round(from.x + from.y);
        const dirX = dx >= 0 ? 1 : -1;
        const ampBase = clampNumber(span * 0.2 * ampScale, 22, Math.min(width, height) * 0.24);
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
        }).filter(Boolean);

        const getAnchorSafe = (index, fallback) => {
            if (index < 0 || index >= allAnchors.length) return fallback;
            return allAnchors[index];
        };

        pairs.forEach(({ currentHolder, nextHolder, fromAnchor, toAnchor }, segmentIndex) => {
            const fromAnchorPoint = getAnchorPoint(fromAnchor, svgRect);
            const toPoint = getAnchorPoint(toAnchor, svgRect);
            const padding = 10;
            const minLenBase = clampNumber(Math.min(svgRect.width, svgRect.height) * 0.025, 8, 26);
            let amplitudeScale = 1;
            let finalPoints = [fromAnchorPoint, toPoint];
            const isPortJunctionStart = segmentIndex === 1 || segmentIndex === 2;
            let dropScale = 1;
            let sideScale = 1;

            for (let iteration = 0; iteration < 4; iteration += 1) {
                let fromPoint = fromAnchorPoint;
                let transitionMeta = null;
                let transitionPoints = null;

                if (isPortJunctionStart) {
                    const prevFar = getAnchorSafe(segmentIndex - 1, fromAnchorPoint);
                    const nextFar = getAnchorSafe(segmentIndex + 1, toPoint);
                    transitionMeta = buildJunctionPortTransition(
                        fromAnchorPoint,
                        prevFar,
                        nextFar,
                        svgRect.width,
                        svgRect.height,
                        padding,
                        dropScale,
                        sideScale
                    );
                    fromPoint = transitionMeta.startOut;
                    transitionPoints = transitionMeta.transitionPoints;
                }

                let rawMacro = buildMacroWaypoints(
                    fromPoint,
                    toPoint,
                    segmentIndex,
                    holders.length,
                    amplitudeScale,
                    svgRect.width,
                    svgRect.height,
                    padding
                );

                if (transitionPoints && transitionPoints.length) {
                    rawMacro = transitionPoints.concat(rawMacro.slice(1));
                }

                let candidate = clampPoints(rawMacro, svgRect.width, svgRect.height, padding);
                candidate = chaikinSmooth(candidate);
                candidate = chaikinSmooth(candidate);
                candidate = dedupeConsecutive(candidate, 0.8);
                candidate = enforceMinSegmentLength(candidate, minLenBase + iteration * 1.5);
                candidate = removeNearCollinear(candidate, 8, minLenBase * 0.55);
                candidate = clampPoints(candidate, svgRect.width, svgRect.height, padding);
                candidate = dedupeConsecutive(candidate, 0.8);
                candidate = enforceMinSegmentLength(candidate, minLenBase + iteration * 1.5);

                if (candidate.length < 2) {
                    candidate = [fromPoint, toPoint];
                }

                const samples = sampleSplinePoints(candidate, 80, 0.5);
                const localAnchorInvalid =
                    Boolean(transitionMeta) &&
                    hasLocalSelfIntersection(candidate, transitionMeta.transitionPoints[0], transitionMeta.base * 3);
                const invalid =
                    detectSelfIntersections(samples) ||
                    hasLocalReversal(samples, fromPoint, toPoint, segmentIndex) ||
                    hasSharpTurn(samples, 140) ||
                    localAnchorInvalid;

                finalPoints = candidate;
                if (!invalid) break;
                if (localAnchorInvalid) {
                    sideScale *= 0.5;
                    dropScale *= 0.85;
                }
                amplitudeScale *= 0.75;
            }

            const smoothD = pathFromPoints(finalPoints, 0.5, 0.5) || `M ${fromAnchorPoint.x} ${fromAnchorPoint.y} L ${toPoint.x} ${toPoint.y}`;
            const path = document.createElementNS(svgNs, "path");
            path.setAttribute("d", smoothD);
            path.setAttribute("fill", "none");
            path.setAttribute("stroke", "var(--acc-clr)");
            path.setAttribute("stroke-width", "2");
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








