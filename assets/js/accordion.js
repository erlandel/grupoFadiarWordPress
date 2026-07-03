document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.accordion-item').forEach(function (item) {
    const header = item.querySelector('.accordion-header');
    const content = item.querySelector('.accordion-content');
    const iconUp = item.querySelector('.accordion-icon-up');
    const iconDown = item.querySelector('.accordion-icon-down');

    if (!header) return;

    header.addEventListener('click', function () {
      const isOpen = !content.classList.contains('hidden');

      document.querySelectorAll('.accordion-item').forEach(function (other) {
        if (other !== item) {
          other.querySelector('.accordion-content')?.classList.add('hidden');
          other.querySelector('.accordion-icon-up')?.classList.add('hidden');
          other.querySelector('.accordion-icon-down')?.classList.remove('hidden');
          other.classList.remove('bg-dark', 'text-white');
          other.classList.add('bg-white', 'text-dark');
        }
      });

      content.classList.toggle('hidden');
      if (iconUp) iconUp.classList.toggle('hidden');
      if (iconDown) iconDown.classList.toggle('hidden');

      if (!isOpen) {
        item.classList.add('bg-dark', 'text-white');
        item.classList.remove('bg-white', 'text-dark');
      } else {
        item.classList.remove('bg-dark', 'text-white');
        item.classList.add('bg-white', 'text-dark');
      }
    });
  });
});
