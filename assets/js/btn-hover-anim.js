(() => {
  const init = () => {
    if (!window.gsap) {
      return;
    }

    const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const buttons = document.querySelectorAll('.btn.light');

    buttons.forEach((btn) => {
      const p = btn.querySelector('p');
      if (!p) {
        return;
      }

      const icon = btn.querySelector('.icon--arrow-white');

      const textNodes = Array.from(p.childNodes).filter(
        (node) => node.nodeType === Node.TEXT_NODE && node.nodeValue && node.nodeValue.trim() !== ''
      );

      let chars = [];
      textNodes.forEach((node) => {
        const text = node.nodeValue || '';
        const frag = document.createDocumentFragment();
        Array.from(text).forEach((char) => {
          const span = document.createElement('span');
          span.className = 'btn__char';
          if (char === ' ') {
            span.textContent = ' ';
            span.style.whiteSpace = 'pre';
          } else {
            span.textContent = char;
          }
          frag.appendChild(span);
          chars.push(span);
        });
        node.parentNode.replaceChild(frag, node);
      });

      const acColor = getComputedStyle(btn).getPropertyValue('--ac-clr').trim();
      const baseTextColor = getComputedStyle(p).color;
      chars.forEach((span) => {
        span.style.color = baseTextColor;
      });

      let svgEls = [];
      if (icon) {
        svgEls = Array.from(
          icon.querySelectorAll('path, circle, rect, polygon, line, polyline, ellipse')
        ).filter((el) => {
          if (typeof el.getBBox !== 'function') {
            return false;
          }
          const box = el.getBBox();
          return box && box.width > 0;
        });

        svgEls.sort((a, b) => a.getBBox().x - b.getBBox().x);

        svgEls.forEach((el) => {
          const styles = getComputedStyle(el);
          const fill = styles.fill;
          const stroke = styles.stroke;
          el.dataset.fill0 = fill;
          el.dataset.stroke0 = stroke;
        });
      }

      const applyState = (isActive) => {
        const color = isActive ? acColor : baseTextColor;
        chars.forEach((span) => {
          span.style.color = color;
        });

        svgEls.forEach((el) => {
          const fill0 = el.dataset.fill0 || '';
          const stroke0 = el.dataset.stroke0 || '';
          if (fill0 && fill0 !== 'none') {
            el.style.fill = isActive ? acColor : fill0;
          }
          if (stroke0 && stroke0 !== 'none') {
            el.style.stroke = isActive ? acColor : stroke0;
          }
        });
      };

      if (prefersReduced) {
        btn.addEventListener('mouseenter', () => applyState(true));
        btn.addEventListener('mouseleave', () => applyState(false));
        return;
      }

      const tl = window.gsap.timeline({ paused: true });
      if (chars.length) {
        tl.to(chars, {
          color: acColor,
          duration: 0.45,
          ease: 'power2.out',
          stagger: { each: 0.03, from: 0 },
        }, 0);
      }
      if (svgEls.length) {
        tl.to(svgEls, {
          fill: acColor,
          stroke: acColor,
          duration: 0.4,
          ease: 'power2.out',
          stagger: { each: 0.02, from: 0 },
        }, 0);
      }

      btn.addEventListener('mouseenter', () => {
        tl.timeScale(1).play();
      });

      btn.addEventListener('mouseleave', () => {
        tl.timeScale(0.8).reverse();
      });
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
