document.addEventListener('DOMContentLoaded', function () {
  const breakpoint = window.matchMedia('(max-width: 1279px)');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const carousels = document.querySelectorAll('.brands-carousel');

  carousels.forEach(function (carousel) {
    const track = carousel.querySelector('.brands-carousel-track');
    const dots = carousel.querySelector('.brands-carousel-dots');
    if (!track) return;

    let frame = null;
    let lastTime = null;
    let position = 0;
    let phase = 0;
    let pauseTimer = null;
    let isPaused = false;
    let activePointerId = null;
    let dragStartX = 0;
    let dragStartY = 0;
    let dragStartPosition = 0;
    let dragDelta = 0;
    let isDragging = false;
    let suppressNextClick = false;
    let activeDotIndex = -1;
    const speed = 24;

    // Marca el punto correspondiente a la tarjeta más cercana al centro visible.
    function updateDots() {
      if (!dots || !breakpoint.matches) return;

      const cards = Array.from(track.querySelectorAll('.brands-card'));
      if (!cards.length) return;

      const viewportCenter = carousel.clientWidth / 2;
      let closestIndex = 0;
      let closestDistance = Infinity;

      cards.forEach(function (card, index) {
        const cardCenter = card.offsetLeft + card.offsetWidth / 2 - position;
        const distance = Math.abs(cardCenter - viewportCenter);
        if (distance < closestDistance) {
          closestDistance = distance;
          closestIndex = index;
        }
      });

      if (closestIndex === activeDotIndex) return;
      activeDotIndex = closestIndex;
      dots.querySelectorAll('.brands-carousel-dot').forEach(function (dot, index) {
        dot.classList.toggle('bg-secondary', index === activeDotIndex);
        dot.classList.toggle('bg-white', index !== activeDotIndex);
      });
    }

    function maxDistance() {
      const cards = track.querySelectorAll('.brands-card');
      if (!cards.length) return 0;

      const lastCard = cards[cards.length - 1];
      const trackStyles = window.getComputedStyle(track);
      const rightPadding = parseFloat(trackStyles.paddingRight) || 0;
      const lastEdge = lastCard.offsetLeft + lastCard.offsetWidth + rightPadding;
      return Math.max(0, lastEdge - carousel.clientWidth);
    }

    function canAnimate() {
      return breakpoint.matches && !reducedMotion.matches && maxDistance() > 0;
    }

    function render() {
      track.style.transform = breakpoint.matches ? 'translate3d(' + (-position) + 'px, 0, 0)' : '';
      updateDots();
    }

    function syncPhase(movingBackward) {
      const limit = maxDistance();
      if (limit === 0) {
        phase = 0;
        return;
      }

      const progress = Math.min(1, Math.max(0, position / limit));
      const angle = Math.acos(1 - 2 * progress);
      phase = movingBackward ? Math.PI * 2 - angle : angle;
    }

    function stop() {
      if (frame) {
        window.cancelAnimationFrame(frame);
        frame = null;
      }
      lastTime = null;
    }

    function animate(timestamp) {
      if (!canAnimate() || isPaused) {
        stop();
        return;
      }

      if (lastTime !== null) {
        const limit = maxDistance();
        phase += speed * Math.PI * ((timestamp - lastTime) / 1000) / limit;
        phase %= Math.PI * 2;
        position = limit * (1 - Math.cos(phase)) / 2;

        render();
      }

      lastTime = timestamp;
      frame = window.requestAnimationFrame(animate);
    }

    function start() {
      stop();
      if (!breakpoint.matches) {
        position = 0;
        render();
        return;
      }
      if (canAnimate() && !isPaused) {
        frame = window.requestAnimationFrame(animate);
      }
    }

    function pauseForInteraction() {
      isPaused = true;
      stop();
      window.clearTimeout(pauseTimer);
      pauseTimer = window.setTimeout(function () {
        isPaused = false;
        start();
      }, 15000);
    }

    carousel.addEventListener('pointerdown', function (event) {
      if (!breakpoint.matches || !event.isPrimary) return;

      activePointerId = event.pointerId;
      dragStartX = event.clientX;
      dragStartY = event.clientY;
      dragStartPosition = position;
      dragDelta = 0;
      isDragging = false;
      suppressNextClick = false;
      pauseForInteraction();
    });
    carousel.addEventListener('pointermove', function (event) {
      if (event.pointerId !== activePointerId) return;

      const deltaX = event.clientX - dragStartX;
      const deltaY = event.clientY - dragStartY;

      if (!isDragging) {
        if (Math.abs(deltaX) < 10 && Math.abs(deltaY) < 10) return;
        if (Math.abs(deltaY) > Math.abs(deltaX)) return;

        isDragging = true;
        carousel.setPointerCapture(event.pointerId);
      }

      event.preventDefault();
      dragDelta = deltaX;
      position = Math.min(maxDistance(), Math.max(0, dragStartPosition - deltaX));
      render();
      pauseForInteraction();
    });
    carousel.addEventListener('pointerup', function (event) {
      if (event.pointerId !== activePointerId) return;

      if (isDragging) {
        suppressNextClick = true;
        syncPhase(dragDelta > 0);
        window.setTimeout(function () {
          suppressNextClick = false;
        }, 0);
      }

      if (carousel.hasPointerCapture(event.pointerId)) {
        carousel.releasePointerCapture(event.pointerId);
      }

      activePointerId = null;
      isDragging = false;
      pauseForInteraction();
    });
    carousel.addEventListener('pointercancel', function (event) {
      if (event.pointerId !== activePointerId) return;
      activePointerId = null;
      isDragging = false;
      syncPhase(dragDelta > 0);
      pauseForInteraction();
    });
    carousel.addEventListener('wheel', pauseForInteraction, { passive: true });
    carousel.addEventListener('focusin', pauseForInteraction);
    track.addEventListener('click', function (event) {
      if (!breakpoint.matches) return;

      if (suppressNextClick) {
        suppressNextClick = false;
        event.preventDefault();
        return;
      }

      const card = event.target.closest('.brands-card');
      if (!card) return;

      if (event.target.closest('a') && card.classList.contains('is-expanded')) return;

      event.preventDefault();
      const willExpand = !card.classList.contains('is-expanded');
      carousel.querySelectorAll('.brands-card.is-expanded').forEach(function (openCard) {
        openCard.classList.remove('is-expanded');
      });
      card.classList.toggle('is-expanded', willExpand);
      pauseForInteraction();
    });

    function resize() {
      const limit = maxDistance();
      if (limit === 0) {
        position = 0;
        phase = 0;
      } else {
        const progress = Math.min(1, position / limit);
        const angle = Math.acos(1 - 2 * progress);
        phase = phase > Math.PI ? Math.PI * 2 - angle : angle;
        position = limit * (1 - Math.cos(phase)) / 2;
      }
      render();
      start();
    }

    breakpoint.addEventListener('change', resize);
    reducedMotion.addEventListener('change', resize);
    window.addEventListener('resize', resize);
    window.addEventListener('load', resize);
    updateDots();
    start();
  });
});
