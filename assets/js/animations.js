
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

    // --- Back-Buttons: nur auf Mobile (<641px) aktiviertes Submen schlieen
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



// two-img layout scroll effect (img_2 moves up while section is in view)
document.addEventListener("DOMContentLoaded", () => {
    const gsapInstance = window.gsap || null;
    const ScrollTrigger = window.ScrollTrigger || null;
    if (!gsapInstance || !ScrollTrigger) return;

    gsapInstance.registerPlugin(ScrollTrigger);

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
