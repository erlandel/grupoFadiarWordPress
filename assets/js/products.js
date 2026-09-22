document.addEventListener('DOMContentLoaded', () => {
  const mobileQuery = window.matchMedia('(max-width: 767px)');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const productsCarousel = document.querySelector('.products-carousel');
  const videos = document.querySelectorAll('.product-video');

  const updateVideoButton = (video, isPlaying) => {
    const button = video.parentElement.querySelector('.product-video-toggle');
    if (!button) return;

    button.querySelector('.product-video-play').classList.toggle('hidden', isPlaying);
    button.querySelector('.product-video-pause').classList.toggle('hidden', !isPlaying);
    button.setAttribute('aria-label', isPlaying ? 'Pausar video' : 'Reproducir video');
    button.setAttribute('aria-pressed', String(isPlaying));
  };

  const syncVideoMode = () => {
    videos.forEach((video) => {
      if (mobileQuery.matches) {
        video.pause();
        updateVideoButton(video, false);
      } else {
        video.play().catch(() => {});
      }
    });
  };

  videos.forEach((video) => {
    const button = video.parentElement.querySelector('.product-video-toggle');
    if (!button) return;

    button.addEventListener('click', (event) => {
      event.stopPropagation();
      if (video.paused) {
        video.play().then(() => updateVideoButton(video, true)).catch(() => {});
      } else {
        video.pause();
        updateVideoButton(video, false);
      }
    });

    video.addEventListener('play', () => updateVideoButton(video, true));
    video.addEventListener('pause', () => updateVideoButton(video, false));
  });

  mobileQuery.addEventListener('change', syncVideoMode);
  syncVideoMode();

  if (!productsCarousel) return;

  const track = productsCarousel.querySelector('.products-carousel-track');
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
  const speed = 24;

  const cards = () => track.querySelectorAll('.reveal-item--zoom');

  const maxDistance = () => {
    const productCards = cards();
    if (!productCards.length) return 0;

    const lastCard = productCards[productCards.length - 1];
    const trackStyles = window.getComputedStyle(track);
    const carouselStyles = window.getComputedStyle(productsCarousel);
    const rightPadding = parseFloat(trackStyles.paddingRight) || 0;
    const horizontalPadding = (parseFloat(carouselStyles.paddingLeft) || 0) + (parseFloat(carouselStyles.paddingRight) || 0);
    const visibleWidth = productsCarousel.clientWidth - horizontalPadding;
    const lastEdge = lastCard.offsetLeft + lastCard.offsetWidth + rightPadding;
    return Math.max(0, lastEdge - visibleWidth);
  };

  const canAnimate = () => mobileQuery.matches && !reducedMotion.matches && maxDistance() > 0;

  const render = () => {
    track.style.transform = mobileQuery.matches ? `translate3d(${-position}px, 0, 0)` : '';
  };

  const syncPhase = (movingBackward) => {
    const limit = maxDistance();
    if (limit === 0) {
      phase = 0;
      return;
    }

    const progress = Math.min(1, Math.max(0, position / limit));
    const angle = Math.acos(1 - 2 * progress);
    phase = movingBackward ? Math.PI * 2 - angle : angle;
  };

  const stop = () => {
    if (frame) {
      window.cancelAnimationFrame(frame);
      frame = null;
    }
    lastTime = null;
  };

  const animate = (timestamp) => {
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
  };

  const start = () => {
    stop();
    if (!mobileQuery.matches) {
      position = 0;
      phase = 0;
      render();
      return;
    }

    if (canAnimate() && !isPaused) {
      frame = window.requestAnimationFrame(animate);
    }
  };

  const pauseForInteraction = () => {
    if (!mobileQuery.matches) return;

    isPaused = true;
    stop();
    window.clearTimeout(pauseTimer);
    pauseTimer = window.setTimeout(() => {
      isPaused = false;
      start();
    }, 15000);
  };

  productsCarousel.addEventListener('pointerdown', (event) => {
    if (!mobileQuery.matches || !event.isPrimary) return;

    activePointerId = event.pointerId;
    dragStartX = event.clientX;
    dragStartY = event.clientY;
    dragStartPosition = position;
    dragDelta = 0;
    isDragging = false;
    suppressNextClick = false;
    pauseForInteraction();
  });

  productsCarousel.addEventListener('pointermove', (event) => {
    if (event.pointerId !== activePointerId) return;

    const deltaX = event.clientX - dragStartX;
    const deltaY = event.clientY - dragStartY;
    if (!isDragging) {
      if (Math.abs(deltaX) < 10 && Math.abs(deltaY) < 10) return;
      if (Math.abs(deltaY) > Math.abs(deltaX)) return;
      isDragging = true;
      productsCarousel.setPointerCapture(event.pointerId);
    }

    event.preventDefault();
    dragDelta = deltaX;
    position = Math.min(maxDistance(), Math.max(0, dragStartPosition - deltaX));
    render();
    pauseForInteraction();
  });

  productsCarousel.addEventListener('pointerup', (event) => {
    if (event.pointerId !== activePointerId) return;

    if (isDragging) {
      suppressNextClick = true;
      syncPhase(dragDelta > 0);
      window.setTimeout(() => {
        suppressNextClick = false;
      }, 0);
    }

    if (productsCarousel.hasPointerCapture(event.pointerId)) {
      productsCarousel.releasePointerCapture(event.pointerId);
    }

    activePointerId = null;
    isDragging = false;
    pauseForInteraction();
  });

  productsCarousel.addEventListener('pointercancel', (event) => {
    if (event.pointerId !== activePointerId) return;
    activePointerId = null;
    isDragging = false;
    syncPhase(dragDelta > 0);
    pauseForInteraction();
  });

  productsCarousel.addEventListener('wheel', pauseForInteraction, { passive: true });
  productsCarousel.addEventListener('focusin', pauseForInteraction);
  productsCarousel.addEventListener('click', (event) => {
    if (suppressNextClick) {
      suppressNextClick = false;
      event.preventDefault();
      return;
    }

    if (mobileQuery.matches && event.target.closest('.promo-btn')) {
      pauseForInteraction();
    }
  });

  const resize = () => {
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
  };

  mobileQuery.addEventListener('change', resize);
  reducedMotion.addEventListener('change', resize);
  window.addEventListener('resize', resize);
  window.addEventListener('load', resize);
  start();
});
