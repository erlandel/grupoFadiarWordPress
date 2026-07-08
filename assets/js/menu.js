document.addEventListener('DOMContentLoaded', function () {
  const menuLinks = document.querySelectorAll('.font-bold.text-lg .flex li a');
  const anchorSections = ['ourBrands', 'products'];
  const homeUrl = window.location.origin + '/';
  
  if (window.location.pathname !== '/' && window.location.pathname !== '/index.php') return;

  let activeSectionId = null;

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

  const observerOptions = {
    root: null,
    rootMargin: '-20% 0px -70% 0px', 
    threshold: 0
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        setActiveLink(entry.target.id);
      }
    });
  }, observerOptions);

  anchorSections.forEach((id) => {
    const el = document.getElementById(id);
    if (el) observer.observe(el);
  });

  window.addEventListener('scroll', () => {
    if (window.scrollY < 100) {
      if (activeSectionId !== 'home') {
        setActiveLink('home');
      }
    }
  });

  // Initial state
  setActiveLink('home');
});
