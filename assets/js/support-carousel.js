document.addEventListener('DOMContentLoaded', function () {
  var SPEED = 40;
  var sections = document.querySelectorAll('.support-carousel-section');
  if (!sections.length) return;

  sections.forEach(function (section) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var track = section.querySelector('.support-carousel-track');
    if (!track) return;

    function updateDuration() {
      var set = track.querySelector('.carousel-set');
      if (!set) return;
      var tw = track.scrollWidth;
      var sw = set.scrollWidth;
      var d = tw - sw;
      track.style.setProperty('--support-marquee-duration', (d / SPEED) + 's');
      track.style.setProperty('--support-marquee-to', -(d / tw) * 100 + '%');
    }

    updateDuration();

    var resizeTimer = null;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(updateDuration, 150);
    });
  });
});
