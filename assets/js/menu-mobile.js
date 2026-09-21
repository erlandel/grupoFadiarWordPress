document.addEventListener('DOMContentLoaded', function () {
  const openBtn = document.querySelector('.menu-mobile-open');
  const closeBtn = document.querySelector('.menu-mobile-close');
  const overlay = document.querySelector('.menu-mobile-overlay');
  const panel = document.querySelector('.menu-mobile-panel');
  const newsMenu = document.querySelector('[data-mobile-news-menu]');
  const newsMenuTrigger = document.querySelector('[data-mobile-news-menu-trigger]');
  const newsMenuDropdown = document.querySelector('[data-mobile-news-menu-dropdown]');
  const mobileNavLinks = document.querySelectorAll('[data-mobile-nav-link]');

  if (!openBtn || !panel) return;

  function openMenu() {
    panel.classList.remove('hidden');
    if (overlay) overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    updateMobileNavState();
  }

  function closeMenu() {
    panel.classList.add('hidden');
    if (overlay) overlay.classList.add('hidden');
    if (newsMenu) newsMenu.open = false;
    if (newsMenuDropdown) newsMenuDropdown.classList.add('hidden');
    document.body.style.overflow = '';
  }

  function positionNewsMenu() {
    if (!newsMenuTrigger || !newsMenuDropdown) return;
    const rect = newsMenuTrigger.getBoundingClientRect();
    newsMenuDropdown.style.left = rect.left + 'px';
    newsMenuDropdown.style.top = rect.bottom + 8 + 'px';
  }

  openBtn.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (overlay) overlay.addEventListener('click', closeMenu);

  if (newsMenu && newsMenuDropdown) {
    newsMenu.addEventListener('toggle', function () {
      if (newsMenu.open) {
        positionNewsMenu();
        newsMenuDropdown.classList.remove('hidden');
      } else {
        newsMenuDropdown.classList.add('hidden');
      }
    });

    window.addEventListener('resize', function () {
      if (newsMenu.open) positionNewsMenu();
    });
  }

  function setMobileNavState(activeId) {
    mobileNavLinks.forEach(function (link) {
      const isActive = link.dataset.mobileNavLink === activeId;
      link.classList.toggle('bg-dark', isActive);
      link.classList.toggle('text-secondary', isActive);
      link.classList.toggle('text-dark', !isActive);
    });
  }

  function isSectionInView(id) {
    const section = document.getElementById(id);
    if (!section) return false;

    const rect = section.getBoundingClientRect();
    return rect.top < window.innerHeight * 0.5 && rect.bottom > window.innerHeight * 0.25;
  }

  function updateMobileNavState() {
    const isFrontPage = document.body.classList.contains('home') || document.body.classList.contains('front-page');
    if (!mobileNavLinks.length || !isFrontPage) return;

    if (isSectionInView('products')) {
      setMobileNavState('products');
    } else if (isSectionInView('ourBrands')) {
      setMobileNavState('ourBrands');
    } else {
      setMobileNavState('home');
    }
  }

  panel.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  if (newsMenuDropdown) {
    newsMenuDropdown.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', closeMenu);
    });
  }

  window.addEventListener('scroll', updateMobileNavState, { passive: true });
  window.addEventListener('hashchange', updateMobileNavState);
  updateMobileNavState();
});
