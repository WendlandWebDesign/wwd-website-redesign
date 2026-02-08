// Button border animation via SVG path + GSAP
document.addEventListener("DOMContentLoaded", () => {
    const gsapInstance = window.gsap || null;
    if (!gsapInstance) return;

    const buttons = document.querySelectorAll(".btn");
    if (!buttons.length) return;

    const timelines = new WeakMap();
    const resizeTimers = new WeakMap();
    const BORDER_DUR = 0.8;

    const buildTimeline = (button) => {
        const svg = button.querySelector(".btn__svg");
        const segments = button.querySelectorAll(".btn__seg");
        const track = button.querySelector(".btn__path");
        if (!svg || !track || segments.length !== 4) return null;

        const w = Math.max(1, Math.round(button.clientWidth));
        const h = Math.max(1, Math.round(button.clientHeight));
        const strokeWidth = 4;
        const inset = strokeWidth / 2;

        svg.setAttribute("width", `${w}`);
        svg.setAttribute("height", `${h}`);
        svg.setAttribute("viewBox", `0 0 ${w} ${h}`);
        svg.setAttribute("preserveAspectRatio", "none");

        const d = `M${inset},${inset} H${w - inset} V${h - inset} H${inset} Z`;
        track.setAttribute("d", d);
        segments.forEach((seg) => {
            seg.setAttribute("d", d);
            seg.setAttribute("vector-effect", "non-scaling-stroke");
        });

        const length = track.getTotalLength();
        if (!length || !Number.isFinite(length)) return null;

        const innerW = Math.max(1, w - strokeWidth);
        const innerH = Math.max(1, h - strokeWidth);
        const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
        const lenLong = clamp(innerW * 0.60, innerW * 0.40, innerW * 0.75);
        const lenShort = clamp(innerH * 0.50, innerH * 0.35, innerH * 0.70);

        const starts = [
            { len: lenShort, offset: 0.00 * length },
            { len: lenLong, offset: 0.05 * length },
            { len: lenShort, offset: 0.50 * length },
            { len: lenLong, offset: 0.55 * length },
        ];

        segments.forEach((seg, idx) => {
            const segLen = starts[idx].len;
            const dashArray = `${segLen} ${length - segLen}`;
            gsapInstance.set(seg, {
                strokeDasharray: dashArray,
                strokeDashoffset: starts[idx].offset,
            });
        });

        const tl = gsapInstance.timeline({ paused: true, defaults: { ease: "power2.inOut" } });
        segments.forEach((seg, idx) => {
            const target = starts[idx].offset + (length * -0.75);
            tl.to(seg, { strokeDashoffset: target, duration: BORDER_DUR }, 0);
        });
        tl.to(segments, { stroke: "var(--hover-clr)", duration: BORDER_DUR, ease: "none" }, 0);

        button.dataset.btnBorderDur = String(BORDER_DUR);

        return tl;
    };

    const reset = (button) => {
        const segments = button.querySelectorAll(".btn__seg");
        if (!segments.length) return;
        gsapInstance.killTweensOf(segments);
        segments.forEach((seg) => {
            gsapInstance.set(seg, { clearProps: "stroke-dasharray,stroke-dashoffset" });
        });
    };

    const ensureTimeline = (button) => {
        let tl = timelines.get(button);
        if (tl) return tl;
        reset(button);
        tl = buildTimeline(button);
        if (tl) timelines.set(button, tl);
        return tl;
    };

    buttons.forEach((button) => {
        if (!button.querySelector(".btn__svg")) return;

        const rebuild = () => {
            const existing = timelines.get(button);
            if (existing) {
                existing.kill();
                timelines.delete(button);
            }
            reset(button);
            ensureTimeline(button);
        };

        if (typeof ResizeObserver === "function") {
            const ro = new ResizeObserver(() => {
                if (resizeTimers.has(button)) {
                    cancelAnimationFrame(resizeTimers.get(button));
                }
                resizeTimers.set(button, requestAnimationFrame(rebuild));
            });
            ro.observe(button);
        }

        button.addEventListener("mouseenter", () => {
            const tl = ensureTimeline(button);
            if (!tl) return;
            tl.play(0);
        });

        button.addEventListener("mouseleave", () => {
            const tl = timelines.get(button);
            if (tl) {
                tl.reverse();
            } else {
                reset(button);
            }
        });

        rebuild();
    });
});
