
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

            const targetKey = content.getAttribute("data-nav-panel");
            const parentExpand = targetKey
                ? document.querySelector(`.expand-right[data-nav-target="${targetKey}"]`)
                : null;

            content.classList.remove("active");
            if (parentExpand) parentExpand.classList.remove("active");
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
