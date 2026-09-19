document.addEventListener('DOMContentLoaded', function () {
  const menuLinks = document.querySelectorAll('.desktop-primary-links > li > a');
  const newsMenuTrigger = document.querySelector('[data-news-menu-trigger]');
  const newsMenuDropdown = document.querySelector('[data-news-menu-dropdown]');
  const anchorSections = ['ourBrands', 'products'];

  if (newsMenuTrigger && newsMenuDropdown) {
    let closeTimer;
    const newsMenuIcon = newsMenuTrigger.querySelector('svg');

    function positionNewsMenu() {
      const rect = newsMenuTrigger.getBoundingClientRect();
      newsMenuDropdown.style.left = rect.left + 'px';
      newsMenuDropdown.style.top = rect.bottom + 8 + 'px';
    }

    function openNewsMenu() {
      window.clearTimeout(closeTimer);
      positionNewsMenu();
      newsMenuDropdown.classList.remove('hidden');
      newsMenuTrigger.setAttribute('aria-expanded', 'true');
      if (newsMenuIcon) newsMenuIcon.classList.add('rotate-180');
    }

    function closeNewsMenu() {
      newsMenuDropdown.classList.add('hidden');
      newsMenuTrigger.setAttribute('aria-expanded', 'false');
      if (newsMenuIcon) newsMenuIcon.classList.remove('rotate-180');
    }

    function scheduleNewsMenuClose() {
      window.clearTimeout(closeTimer);
      closeTimer = window.setTimeout(closeNewsMenu, 150);
    }

    newsMenuTrigger.addEventListener('mouseenter', openNewsMenu);
    newsMenuTrigger.addEventListener('mouseleave', scheduleNewsMenuClose);
    newsMenuDropdown.addEventListener('mouseenter', function () { window.clearTimeout(closeTimer); });
    newsMenuDropdown.addEventListener('mouseleave', scheduleNewsMenuClose);
    newsMenuTrigger.addEventListener('click', function () {
      if (newsMenuDropdown.classList.contains('hidden')) openNewsMenu();
      else closeNewsMenu();
    });

    document.addEventListener('click', function (event) {
      if (!newsMenuTrigger.contains(event.target) && !newsMenuDropdown.contains(event.target)) closeNewsMenu();
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') closeNewsMenu();
    });
    window.addEventListener('resize', function () {
      if (!newsMenuDropdown.classList.contains('hidden')) positionNewsMenu();
    });
    window.addEventListener('scroll', function () {
      if (!newsMenuDropdown.classList.contains('hidden')) positionNewsMenu();
    }, { passive: true });
  }

  // TranslatePress usa /en/ (u otro slug): detectar home por body class, no solo por pathname.
  const isFrontPage =
    document.body.classList.contains('home') ||
    document.body.classList.contains('front-page');
  if (!isFrontPage) return;

  const homeLink = menuLinks[0];
  const homeHref = homeLink ? homeLink.getAttribute('href') : null;

  let activeSectionId = null;
  const intersectingSections = new Set();

  function normalizePath(pathname) {
    let path = (pathname || '/').replace(/\/$/, '') || '/';
    // Quitar prefijo de idioma tipo /en o /es
    path = path.replace(/^\/[a-z]{2}(?:-[a-z]{2})?(?=\/|$)/i, '') || '/';
    return path.replace(/\/$/, '') || '/';
  }

  function isHomeHref(href) {
    if (!href || href.includes('#')) return false;
    if (homeHref && (href === homeHref || href === homeHref.replace(/\/$/, '') || href === homeHref + '/')) {
      return true;
    }
    try {
      const path = normalizePath(new URL(href, window.location.href).pathname);
      return path === '/' || path === '/index.php';
    } catch {
      return false;
    }
  }

  function setActiveLink(id) {
    menuLinks.forEach((link) => {
      const href = link.getAttribute('href');
      let isActive = false;

      if (id === 'home') {
        isActive = isHomeHref(href);
      } else if (id) {
        isActive = href.endsWith('#' + id);
      }

      link.classList.toggle('bg-dark', isActive);
      link.classList.toggle('text-secondary', isActive);
      link.classList.toggle('text-dark', !isActive);
    });
    activeSectionId = id;
  }

  function isSectionInView(id) {
    const el = document.getElementById(id);
    if (!el) return false;
    const rect = el.getBoundingClientRect();
    return rect.top < window.innerHeight * 0.5 && rect.bottom > window.innerHeight * 0.2;
  }

  function updateActiveLink() {
    if (intersectingSections.size > 0) {
      const activeId = [...anchorSections].reverse().find(id => intersectingSections.has(id));
      if (activeId) {
        setActiveLink(activeId);
        return;
      }
    }

    const hash = window.location.hash.slice(1);
    if (anchorSections.includes(hash) && isSectionInView(hash)) {
      setActiveLink(hash);
      return;
    }

    setActiveLink('home');
  }

  const observerOptions = {
    root: null,
    rootMargin: '-20% 0px -70% 0px',
    threshold: 0
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        intersectingSections.add(entry.target.id);
      } else {
        intersectingSections.delete(entry.target.id);
      }
    });
    updateActiveLink();
  }, observerOptions);

  anchorSections.forEach((id) => {
    const el = document.getElementById(id);
    if (el) observer.observe(el);
  });

  window.addEventListener('scroll', updateActiveLink);
  window.addEventListener('hashchange', updateActiveLink);

  updateActiveLink();
});
