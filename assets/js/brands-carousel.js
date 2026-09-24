document.addEventListener('DOMContentLoaded', function () {
  const breakpoint = window.matchMedia('(max-width: 1279px)');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  document.querySelectorAll('.brands-carousel').forEach(function (carousel) {
    const track = carousel.querySelector('.brands-carousel-track');
    const dots = carousel.parentElement.querySelector('.brands-carousel-dots');
    if (!track) return;

    let frame = null;
    let lastTime = null;
    let pauseTimer = null;
    let isPaused = false;
    let activePointerId = null;
    let pointerStartX = 0;
    let suppressNextClick = false;
    let activeDotIndex = -1;
    let direction = 1;
    const speed = 24;

    function maxDistance() {
      return Math.max(0, carousel.scrollWidth - carousel.clientWidth);
    }

    function updateDots() {
      if (!dots || !breakpoint.matches) return;

      const cards = Array.from(track.querySelectorAll('.brands-card'));
      if (!cards.length) return;

      const viewportCenter = carousel.scrollLeft + carousel.clientWidth / 2;
      let closestIndex = 0;
      let closestDistance = Infinity;

      cards.forEach(function (card, index) {
        const cardCenter = card.offsetLeft + card.offsetWidth / 2;
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
         dot.setAttribute('aria-current', index === activeDotIndex ? 'true' : 'false');
       });
     }

     function goToCard(index) {
       const cards = Array.from(track.querySelectorAll('.brands-card'));
       const card = cards[index];
       if (!card) return;

       pauseForInteraction();
       const target = card.offsetLeft - (carousel.clientWidth - card.offsetWidth) / 2;
       carousel.scrollTo({
         left: Math.max(0, Math.min(target, maxDistance())),
         behavior: reducedMotion.matches ? 'auto' : 'smooth',
       });
     }

    function stop() {
      if (frame) window.cancelAnimationFrame(frame);
      frame = null;
      lastTime = null;
    }

    function canAnimate() {
      return breakpoint.matches && !reducedMotion.matches && maxDistance() > 0;
    }

    function animate(timestamp) {
      if (!canAnimate() || isPaused) {
        stop();
        return;
      }

      if (lastTime !== null) {
        const distance = maxDistance();
        const delta = speed * (timestamp - lastTime) / 1000;
        carousel.scrollLeft += delta * direction;

        if (carousel.scrollLeft >= distance) {
          carousel.scrollLeft = distance;
          direction = -1;
        } else if (carousel.scrollLeft <= 0) {
          carousel.scrollLeft = 0;
          direction = 1;
        }
      }

      lastTime = timestamp;
      frame = window.requestAnimationFrame(animate);
    }

    function start() {
      stop();
      if (canAnimate() && !isPaused) frame = window.requestAnimationFrame(animate);
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

    carousel.addEventListener('scroll', updateDots, { passive: true });
    carousel.addEventListener('pointerdown', function (event) {
      if (!breakpoint.matches || !event.isPrimary) return;
      activePointerId = event.pointerId;
      pointerStartX = event.clientX;
      suppressNextClick = false;
      pauseForInteraction();
    }, { passive: true });
    carousel.addEventListener('pointermove', function (event) {
      if (event.pointerId === activePointerId && Math.abs(event.clientX - pointerStartX) > 10) {
        suppressNextClick = true;
      }
    }, { passive: true });
    carousel.addEventListener('pointerup', function (event) {
      if (event.pointerId === activePointerId) activePointerId = null;
      pauseForInteraction();
    }, { passive: true });
    carousel.addEventListener('pointercancel', function () {
      activePointerId = null;
      pauseForInteraction();
    }, { passive: true });
    carousel.addEventListener('wheel', pauseForInteraction, { passive: true });
     carousel.addEventListener('focusin', pauseForInteraction);

     if (dots) {
       dots.querySelectorAll('.brands-carousel-dot').forEach(function (dot, index) {
         dot.addEventListener('click', function () {
           goToCard(index);
         });
       });
     }

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
      carousel.scrollLeft = Math.min(carousel.scrollLeft, maxDistance());
      updateDots();
      start();
    }

    breakpoint.addEventListener('change', resize);
    reducedMotion.addEventListener('change', resize);
    window.addEventListener('resize', resize);
    window.addEventListener('load', resize);
    resize();
  });
});
