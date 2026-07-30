document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.querySelector('.hero-carousel');
  if (!carousel) return;

  const slides = carousel.querySelectorAll('.carousel-slide');
  const contents = carousel.querySelectorAll('.carousel-content');
  const prevBtn = carousel.querySelector('.carousel-prev');
  const nextBtn = carousel.querySelector('.carousel-next');
  let currentIndex = 0;
  let timer = null;
  let onAnimEnd = null;
  let kenBurnsPlayed = false;

  function clearTimer() {
    if (timer) { clearInterval(timer); timer = null; }
    if (onAnimEnd) {
      const el = document.querySelector('.first-slide-bg');
      if (el) el.removeEventListener('animationend', onAnimEnd);
      onAnimEnd = null;
    }
  }

  function scheduleAdvance() {
    clearTimer();

    const active = slides[currentIndex];
    const burnEl = active && active.querySelector('.first-slide-bg');

    if (currentIndex === 0 && !kenBurnsPlayed && burnEl) {
      burnEl.classList.add('ken-burns-image');
      kenBurnsPlayed = true;
      onAnimEnd = function () { onAnimEnd = null; nextSlide(); };
      burnEl.addEventListener('animationend', onAnimEnd, { once: true });
    } else {
      timer = setInterval(nextSlide, 5000);
    }
  }

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('opacity-100', i === index);
      slide.classList.toggle('scale-100', i === index);
      slide.classList.toggle('opacity-0', i !== index);
      slide.classList.toggle('scale-105', i !== index);
    });
    contents.forEach((content, i) => {
      content.classList.toggle('hidden', i !== index);
    });
    currentIndex = index;
    scheduleAdvance();
  }

  function prevSlide() {
    showSlide(currentIndex === 0 ? slides.length - 1 : currentIndex - 1);
  }

  function nextSlide() {
    showSlide(currentIndex === slides.length - 1 ? 0 : currentIndex + 1);
  }

  if (prevBtn) prevBtn.addEventListener('click', prevSlide);
  if (nextBtn) nextBtn.addEventListener('click', nextSlide);

  showSlide(0);
});