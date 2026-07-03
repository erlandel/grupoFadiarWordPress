document.addEventListener('DOMContentLoaded', function () {
  const fullscreenOverlay = document.querySelector('.promo-fullscreen-overlay');
  const fullscreenClose = document.querySelector('.promo-fullscreen-close');
  const fullscreenImg = document.querySelector('.promo-fullscreen-img');

  document.querySelectorAll('.promo-download').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const src = this.getAttribute('data-src');
      if (!src) return;
      const link = document.createElement('a');
      link.href = src;
      link.download = 'promocion-' + Date.now() + '.png';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });
  });

  document.querySelectorAll('.promo-fullscreen-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const src = this.getAttribute('data-src');
      if (!src || !fullscreenOverlay || !fullscreenImg) return;
      fullscreenImg.src = src;
      fullscreenOverlay.classList.remove('hidden');
    });
  });

  if (fullscreenOverlay) {
    fullscreenOverlay.addEventListener('click', function (e) {
      if (e.target === this || e.target === fullscreenClose) {
        this.classList.add('hidden');
      }
    });
  }
});
