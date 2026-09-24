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
  let pauseTimer = null;
  let isPaused = false;
  let activePointerId = null;
  let pointerStartX = 0;
  let suppressNextClick = false;
  let direction = 1;
  const speed = 24;

  const maxDistance = () => Math.max(0, productsCarousel.scrollWidth - productsCarousel.clientWidth);
  const stop = () => {
    if (frame) window.cancelAnimationFrame(frame);
    frame = null;
    lastTime = null;
  };
  const canAnimate = () => mobileQuery.matches && !reducedMotion.matches && maxDistance() > 0;

  const animate = (timestamp) => {
    if (!canAnimate() || isPaused) {
      stop();
      return;
    }
    if (lastTime !== null) {
      const distance = maxDistance();
      productsCarousel.scrollLeft += speed * (timestamp - lastTime) / 1000 * direction;
      if (productsCarousel.scrollLeft >= distance) {
        productsCarousel.scrollLeft = distance;
        direction = -1;
      } else if (productsCarousel.scrollLeft <= 0) {
        productsCarousel.scrollLeft = 0;
        direction = 1;
      }
    }
    lastTime = timestamp;
    frame = window.requestAnimationFrame(animate);
  };

  const start = () => {
    stop();
    if (canAnimate() && !isPaused) frame = window.requestAnimationFrame(animate);
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
    pointerStartX = event.clientX;
    suppressNextClick = false;
    pauseForInteraction();
  }, { passive: true });
  productsCarousel.addEventListener('pointermove', (event) => {
    if (event.pointerId === activePointerId && Math.abs(event.clientX - pointerStartX) > 10) {
      suppressNextClick = true;
    }
  }, { passive: true });
  productsCarousel.addEventListener('pointerup', (event) => {
    if (event.pointerId === activePointerId) activePointerId = null;
    pauseForInteraction();
  }, { passive: true });
  productsCarousel.addEventListener('pointercancel', () => {
    activePointerId = null;
    pauseForInteraction();
  }, { passive: true });
  productsCarousel.addEventListener('wheel', pauseForInteraction, { passive: true });
  productsCarousel.addEventListener('focusin', pauseForInteraction);
  productsCarousel.addEventListener('click', (event) => {
    if (suppressNextClick) {
      suppressNextClick = false;
      event.preventDefault();
      return;
    }
    if (mobileQuery.matches && event.target.closest('.promo-btn')) pauseForInteraction();
  });

  const resize = () => {
    productsCarousel.scrollLeft = Math.min(productsCarousel.scrollLeft, maxDistance());
    start();
  };

  mobileQuery.addEventListener('change', resize);
  reducedMotion.addEventListener('change', resize);
  window.addEventListener('resize', resize);
  window.addEventListener('load', resize);
  resize();
});
