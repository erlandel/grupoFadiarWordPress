document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-category-modal-component]').forEach(function (root) {
    if (root.dataset.categoryModalInitialized === 'true') return;
    root.dataset.categoryModalInitialized = 'true';

    const opener = root.querySelector('[data-filter-open], [data-category-open]');
    const modal = root.querySelector('[data-filter-modal], [data-category-modal]');
    const closeButton = root.querySelector('[data-filter-close], [data-category-close]');

    if (!opener || !modal || !closeButton) return;

    const arrowIcon = opener.querySelector('svg');
    const form = root.querySelector('[data-filter-form], [data-category-form]');
    const radios = form ? form.querySelectorAll('input[type="radio"]') : [];
    let previousFocus = null;

    const open = function () {
      previousFocus = document.activeElement;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      modal.setAttribute('aria-hidden', 'false');
      opener.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
      if (arrowIcon) arrowIcon.classList.add('rotate-180');
      closeButton.focus();
    };

    const close = function () {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      modal.setAttribute('aria-hidden', 'true');
      opener.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      if (arrowIcon) arrowIcon.classList.remove('rotate-180');
      if (previousFocus && typeof previousFocus.focus === 'function') previousFocus.focus();
    };

    opener.addEventListener('click', open);
    closeButton.addEventListener('click', close);

    modal.addEventListener('click', function (event) {
      if (event.target === modal) close();
    });

    radios.forEach(function (radio) {
      radio.addEventListener('change', function () {
        const url = radio.dataset.modalUrl;
        if (url && url !== '#') window.location.href = url;
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
    });
  });
});
