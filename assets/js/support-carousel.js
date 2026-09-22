document.addEventListener('DOMContentLoaded', function () {
  const SPEED = 40;
  const sections = document.querySelectorAll('.support-carousel-section');
  if (!sections.length) return;

  sections.forEach(function (section) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const track = section.querySelector('.support-carousel-track');
    if (!track) return;

    function updateDuration() {
      const set = track.querySelector('.carousel-set');
      if (!set) return;
      const tw = track.scrollWidth;
      const sw = set.scrollWidth;
      const d = tw - sw;
      track.style.setProperty('--support-marquee-duration', (d / SPEED) + 's');
      track.style.setProperty('--support-marquee-to', -(d / tw) * 100 + '%');
    }

    updateDuration();

    let resizeTimer = null;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(updateDuration, 150);
    });
  });
});
