document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.faq-item').forEach(function (item) {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');
    const chevron = item.querySelector('.faq-chevron');

    if (!question) return;

    question.addEventListener('click', function () {
      const isOpen = !answer.classList.contains('hidden');
      answer.classList.toggle('hidden');
      if (chevron) chevron.classList.toggle('rotate-180');
    });
  });
});
