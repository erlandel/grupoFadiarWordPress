document.addEventListener('DOMContentLoaded', function () {
  var breakpoint = window.matchMedia('(max-width: 1279px)');
  var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var carousels = document.querySelectorAll('.brands-carousel');

  carousels.forEach(function (carousel) {
    var track = carousel.querySelector('.brands-carousel-track');
    var dots = carousel.querySelector('.brands-carousel-dots');
    if (!track) return;

    var frame = null;
    var lastTime = null;
    var position = 0;
    var phase = 0;
    var pauseTimer = null;
    var isPaused = false;
    var activePointerId = null;
    var dragStartX = 0;
    var dragStartY = 0;
    var dragStartPosition = 0;
    var dragDelta = 0;
    var isDragging = false;
    var suppressNextClick = false;
    var activeDotIndex = -1;
    var speed = 24;

    // Marca el punto correspondiente a la tarjeta más cercana al centro visible.
    function updateDots() {
      if (!dots || !breakpoint.matches) return;

      var cards = Array.from(track.querySelectorAll('.brands-card'));
      if (!cards.length) return;

      var viewportCenter = carousel.clientWidth / 2;
      var closestIndex = 0;
      var closestDistance = Infinity;

      cards.forEach(function (card, index) {
        var cardCenter = card.offsetLeft + card.offsetWidth / 2 - position;
        var distance = Math.abs(cardCenter - viewportCenter);
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
      var cards = track.querySelectorAll('.brands-card');
      if (!cards.length) return 0;

      var lastCard = cards[cards.length - 1];
      var trackStyles = window.getComputedStyle(track);
      var rightPadding = parseFloat(trackStyles.paddingRight) || 0;
      var lastEdge = lastCard.offsetLeft + lastCard.offsetWidth + rightPadding;
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
      var limit = maxDistance();
      if (limit === 0) {
        phase = 0;
        return;
      }

      var progress = Math.min(1, Math.max(0, position / limit));
      var angle = Math.acos(1 - 2 * progress);
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
        var limit = maxDistance();
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

      var deltaX = event.clientX - dragStartX;
      var deltaY = event.clientY - dragStartY;

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

      var card = event.target.closest('.brands-card');
      if (!card) return;

      if (event.target.closest('a') && card.classList.contains('is-expanded')) return;

      event.preventDefault();
      var willExpand = !card.classList.contains('is-expanded');
      carousel.querySelectorAll('.brands-card.is-expanded').forEach(function (openCard) {
        openCard.classList.remove('is-expanded');
      });
      card.classList.toggle('is-expanded', willExpand);
      pauseForInteraction();
    });

    function resize() {
      var limit = maxDistance();
      if (limit === 0) {
        position = 0;
        phase = 0;
      } else {
        var progress = Math.min(1, position / limit);
        var angle = Math.acos(1 - 2 * progress);
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
