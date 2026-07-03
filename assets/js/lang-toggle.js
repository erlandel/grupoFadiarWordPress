document.addEventListener('DOMContentLoaded', function () {
  var toggles = document.querySelectorAll('.lang-toggle');
  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      var flags = this.querySelectorAll('.lang-flag');
      flags.forEach(function (flag) {
        flag.classList.toggle('hidden');
      });
    });
  });
});
