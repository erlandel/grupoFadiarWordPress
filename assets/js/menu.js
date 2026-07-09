document.addEventListener('DOMContentLoaded', function () {
  const menuLinks = document.querySelectorAll('.font-bold.text-lg .flex li a');
  const anchorSections = ['ourBrands', 'products'];
  const homeUrl = window.location.origin + '/';
  
  if (window.location.pathname !== '/' && window.location.pathname !== '/index.php') return;

  let activeSectionId = null;
  const intersectingSections = new Set();

  function setActiveLink(id) {
    menuLinks.forEach((link) => {
      const href = link.getAttribute('href');
      let isActive = false;

      if (id === 'home') {
        isActive = (href === homeUrl || href === homeUrl + '/');
      } else if (id) {
        isActive = href.endsWith('#' + id);
      }

      link.classList.toggle('bg-dark', isActive);
      link.classList.toggle('text-secondary', isActive);
      link.classList.toggle('text-slate-800', !isActive);
    });
    activeSectionId = id;
  }

  function updateActiveLink() {
    if (window.scrollY < 100) {
      setActiveLink('home');
      return;
    }

    if (intersectingSections.size > 0) {
      const activeId = [...anchorSections].reverse().find(id => intersectingSections.has(id));
      setActiveLink(activeId);
    } else {
      setActiveLink('home');
    }
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

  updateActiveLink();
});
