document.addEventListener('DOMContentLoaded', function () {
  const openBtn = document.querySelector('.menu-mobile-open');
  const closeBtn = document.querySelector('.menu-mobile-close');
  const overlay = document.querySelector('.menu-mobile-overlay');
  const panel = document.querySelector('.menu-mobile-panel');

  if (!openBtn || !panel) return;

  function openMenu() {
    panel.classList.remove('hidden');
    if (overlay) overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    panel.classList.add('hidden');
    if (overlay) overlay.classList.add('hidden');
    document.body.style.overflow = '';
  }

  openBtn.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (overlay) overlay.addEventListener('click', closeMenu);

  panel.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });
});
