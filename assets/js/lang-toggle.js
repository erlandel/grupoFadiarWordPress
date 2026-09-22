document.addEventListener('DOMContentLoaded', function () {
  const toggles = document.querySelectorAll('.lang-toggle');
  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      const flags = this.querySelectorAll('.lang-flag');
      flags.forEach(function (flag) {
        flag.classList.toggle('hidden');
      });
    });
  });
});
