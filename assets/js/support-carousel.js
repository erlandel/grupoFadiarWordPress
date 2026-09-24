document.addEventListener('DOMContentLoaded', function () {
  const SPEED = 48;
  const RESUME_DELAY = 15000;
  const mobileTablet = window.matchMedia('(max-width: 1279px)');
  const hoverCapable = window.matchMedia('(hover: hover) and (pointer: fine)');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  document.querySelectorAll('.support-carousel-section').forEach(function (section) {
    const viewport = section.querySelector('.support-carousel-viewport');
    const track = section.querySelector('.support-carousel-track');
    const mainSet = track ? track.querySelector('[data-support-carousel-main]') : null;
    const previousButton = section.querySelector('.support-carousel-prev');
    const nextButton = section.querySelector('.support-carousel-next');
    if (!viewport || !track || !mainSet) return;

    let frame = null;
    let lastTime = null;
    let resumeTimer = null;
    let resizeTimer = null;
    let cycle = 0;
    let position = 0;
    let mode = 'idle';
    let isHovered = false;
    let isKeyboardFocused = false;
    let activePointerId = null;
    let dragStartX = 0;
    let dragStartY = 0;
    let dragStartPosition = 0;
    let lastPointerX = 0;
    let lastPointerTime = 0;
    let velocity = 0;
    let dragAxis = null;

    function modulo(value, divisor) {
      return ((value % divisor) + divisor) % divisor;
    }

    function createClone() {
      const clone = mainSet.cloneNode(true);
      clone.removeAttribute('data-support-carousel-main');
      clone.setAttribute('aria-hidden', 'true');
      clone.querySelectorAll('img').forEach(function (image) {
        image.setAttribute('alt', '');
      });
      return clone;
    }

    // Genera solo las copias necesarias para cubrir el viewport y un ciclo adicional.
    function buildCopies() {
      const previousCycle = cycle;
      const previousProgress = previousCycle ? modulo(position, previousCycle) / previousCycle : 0;
      const gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;

      track.replaceChildren(mainSet);
      cycle = mainSet.getBoundingClientRect().width + gap;
      if (!cycle) return;

      const copyCount = Math.max(2, Math.ceil(viewport.clientWidth / cycle) + 1);
      const fragment = document.createDocumentFragment();
      for (let index = 0; index < copyCount; index++) {
        fragment.appendChild(createClone());
      }
      track.appendChild(fragment);

      position = previousProgress * cycle;
      render();
    }

    function render() {
      if (!cycle) return;
      position = modulo(position, cycle);
      track.style.transform = 'translate3d(' + (-position) + 'px, 0, 0)';
    }

    function stop() {
      if (frame) window.cancelAnimationFrame(frame);
      frame = null;
      lastTime = null;
      if (mode === 'autoplay' || mode === 'momentum' || mode === 'arrow') mode = 'idle';
    }

    function canAutoplay() {
      return !reducedMotion.matches && !isHovered && !isKeyboardFocused && mode === 'idle';
    }

    function autoplay(timestamp) {
      if (mode !== 'autoplay' || isHovered || isKeyboardFocused || reducedMotion.matches) {
        stop();
        return;
      }

      if (lastTime !== null) {
        position += SPEED * (timestamp - lastTime) / 1000;
        render();
      }

      lastTime = timestamp;
      frame = window.requestAnimationFrame(autoplay);
    }

    function startAutoplay() {
      stop();
      if (!canAutoplay()) return;
      mode = 'autoplay';
      frame = window.requestAnimationFrame(autoplay);
    }

    function scheduleAutoplay() {
      window.clearTimeout(resumeTimer);
      resumeTimer = window.setTimeout(function () {
        if (mode === 'idle') startAutoplay();
      }, RESUME_DELAY);
    }

    function stepWidth() {
      const card = mainSet.querySelector('.carousel-card');
      if (!card) return 0;
      const gap = parseFloat(window.getComputedStyle(mainSet).columnGap) || 0;
      return card.getBoundingClientRect().width + gap;
    }

    function animateTo(target) {
      stop();
      const start = position;
      const distance = target - start;
      const duration = reducedMotion.matches ? 0 : 350;
      const startedAt = performance.now();
      mode = 'arrow';

      function tick(timestamp) {
        const progress = duration ? Math.min(1, (timestamp - startedAt) / duration) : 1;
        const eased = 1 - Math.pow(1 - progress, 3);
        position = start + distance * eased;
        render();

        if (progress < 1) {
          frame = window.requestAnimationFrame(tick);
          return;
        }

        frame = null;
        mode = 'idle';
        if (!isHovered && !isKeyboardFocused) startAutoplay();
      }

      frame = window.requestAnimationFrame(tick);
    }

    function moveBy(direction) {
      const step = stepWidth();
      if (!step) return;
      window.clearTimeout(resumeTimer);
      animateTo(position + direction * step);
    }

    function startMomentum() {
      if (Math.abs(velocity) < 0.05) {
        mode = 'idle';
        scheduleAutoplay();
        return;
      }

      stop();
      mode = 'momentum';
      lastTime = null;

      function momentum(timestamp) {
        if (mode !== 'momentum') return;
        if (lastTime !== null) {
          const elapsed = Math.min(32, timestamp - lastTime);
          position -= velocity * elapsed;
          velocity *= Math.pow(0.94, elapsed / 16);
          render();
        }
        lastTime = timestamp;

        if (Math.abs(velocity) < 0.02) {
          frame = null;
          lastTime = null;
          mode = 'idle';
          scheduleAutoplay();
          return;
        }
        frame = window.requestAnimationFrame(momentum);
      }

      frame = window.requestAnimationFrame(momentum);
    }

    viewport.addEventListener('pointerdown', function (event) {
      if (!mobileTablet.matches || !event.isPrimary) return;
      stop();
      window.clearTimeout(resumeTimer);
      mode = 'drag';
      activePointerId = event.pointerId;
      dragStartX = event.clientX;
      dragStartY = event.clientY;
      dragStartPosition = position;
      lastPointerX = event.clientX;
      lastPointerTime = event.timeStamp;
      velocity = 0;
      dragAxis = null;
    }, { passive: true });

    viewport.addEventListener('pointermove', function (event) {
      if (event.pointerId !== activePointerId || mode !== 'drag') return;
      const deltaX = event.clientX - dragStartX;
      const deltaY = event.clientY - dragStartY;

      if (!dragAxis) {
        if (Math.abs(deltaX) < 6 && Math.abs(deltaY) < 6) return;
        dragAxis = Math.abs(deltaX) > Math.abs(deltaY) ? 'horizontal' : 'vertical';
        if (dragAxis === 'vertical') return;
        viewport.setPointerCapture(event.pointerId);
      }
      if (dragAxis !== 'horizontal') return;

      event.preventDefault();
      const elapsed = Math.max(1, event.timeStamp - lastPointerTime);
      velocity = (event.clientX - lastPointerX) / elapsed;
      position = dragStartPosition - deltaX;
      render();
      lastPointerX = event.clientX;
      lastPointerTime = event.timeStamp;
    });

    function finishPointer(event) {
      if (event.pointerId !== activePointerId) return;
      if (viewport.hasPointerCapture(event.pointerId)) viewport.releasePointerCapture(event.pointerId);
      activePointerId = null;

      if (dragAxis === 'horizontal') {
        startMomentum();
      } else {
        mode = 'idle';
        scheduleAutoplay();
      }
      dragAxis = null;
    }

    viewport.addEventListener('pointerup', finishPointer, { passive: true });
    viewport.addEventListener('pointercancel', finishPointer, { passive: true });

    section.addEventListener('mouseenter', function () {
      if (!hoverCapable.matches) return;
      isHovered = true;
      stop();
    });
    section.addEventListener('mouseleave', function () {
      if (!hoverCapable.matches) return;
      isHovered = false;
      if (!isKeyboardFocused && mode === 'idle') startAutoplay();
    });

    section.addEventListener('focusin', function (event) {
      if (!event.target.matches(':focus-visible')) return;
      isKeyboardFocused = true;
      stop();
    });
    section.addEventListener('focusout', function () {
      window.setTimeout(function () {
        isKeyboardFocused = section.matches(':focus-within');
        if (!isKeyboardFocused && !isHovered && mode === 'idle') startAutoplay();
      }, 0);
    });

    if (previousButton) previousButton.addEventListener('click', function () {
      moveBy(-1);
    });
    if (nextButton) nextButton.addEventListener('click', function () {
      moveBy(1);
    });

    function initialize() {
      const wasAutoplay = mode === 'autoplay';
      stop();
      buildCopies();
      if (wasAutoplay || mode === 'idle') startAutoplay();
    }

    window.requestAnimationFrame(initialize);
    window.addEventListener('load', initialize, { once: true });
    window.addEventListener('resize', function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(initialize, 150);
    });
    reducedMotion.addEventListener('change', function () {
      if (reducedMotion.matches) stop();
      else if (mode === 'idle') startAutoplay();
    });
  });
});
