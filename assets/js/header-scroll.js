document.addEventListener('DOMContentLoaded', function () {
  const header = document.querySelector('.site-header');
  if (!header) return;

  const isHomePage = document.body.classList.contains('home');

  function handleScroll() {
    if (!isHomePage) {
      header.classList.add('bg-white/30', 'backdrop-blur-md', 'shadow-lg');
      return;
    }
    if (window.scrollY > 50) {
      header.classList.add('bg-white/30', 'backdrop-blur-md', 'shadow-lg');
      header.classList.remove('bg-transparent');
    } else {
      header.classList.remove('bg-white/30', 'backdrop-blur-md', 'shadow-lg');
      header.classList.add('bg-transparent');
    }
  }

  if (!isHomePage) {
    header.classList.add('sticky', 'top-0');
  } else {
    header.classList.add('fixed');
  }

  window.addEventListener('scroll', handleScroll);
  handleScroll();
});
