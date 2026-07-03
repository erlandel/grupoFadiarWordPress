document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.querySelector('.hero-carousel');
  if (!carousel) return;

  const slides = carousel.querySelectorAll('.carousel-slide');
  const contents = carousel.querySelectorAll('.carousel-content');
  const prevBtn = carousel.querySelector('.carousel-prev');
  const nextBtn = carousel.querySelector('.carousel-next');
  let currentIndex = 0;
  let interval;

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
    resetInterval();
  }

  function prevSlide() {
    const newIndex = currentIndex === 0 ? slides.length - 1 : currentIndex - 1;
    showSlide(newIndex);
  }

  function nextSlide() {
    const newIndex = currentIndex === slides.length - 1 ? 0 : currentIndex + 1;
    showSlide(newIndex);
  }

  function resetInterval() {
    clearInterval(interval);
    interval = setInterval(nextSlide, 5000);
  }

  if (prevBtn) prevBtn.addEventListener('click', prevSlide);
  if (nextBtn) nextBtn.addEventListener('click', nextSlide);

  showSlide(0);
});
