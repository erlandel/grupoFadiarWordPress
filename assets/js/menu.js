document.addEventListener('DOMContentLoaded', function () {
  const menuLinks = document.querySelectorAll('.font-bold.text-lg .flex li a');
  const anchorSections = ['ourBrands', 'products'];

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
