document.addEventListener('DOMContentLoaded', function () {
  const sections = document.querySelectorAll('.reveal-section');
  if (!sections.length) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (reduced || !('IntersectionObserver' in window)) {
    for (let s = 0; s < sections.length; s++) {
      const allItems = sections[s].querySelectorAll('.reveal-item, .reveal-item--zoom');
      for (let i = 0; i < allItems.length; i++) {
        allItems[i].classList.add('is-visible-item');
      }
    }
    return;
  }

  const STAGGER = 120;
  const MAX_ITEMS = 10;

  for (let s = 0; s < sections.length; s++) {
    const section = sections[s];
    const items = section.querySelectorAll('.reveal-item');
    for (let i = 0; i < items.length; i++) {
      const idx = i < MAX_ITEMS ? i : MAX_ITEMS - 1;
      items[i].style.setProperty('--reveal-delay', idx * STAGGER + 'ms');
    }

    const productGrids = section.querySelectorAll('.grid');
    for (let pg = 0; pg < productGrids.length; pg++) {
      const grid = productGrids[pg];
      const products = grid.querySelectorAll('.reveal-item--zoom');
      if (products.length < 2) continue;

      const ordered = [];
      for (let pi = 0; pi < products.length; pi++) {
        ordered.push(products[pi]);
      }
      ordered.sort(function (a, b) {
        const oa = parseInt(a.getAttribute('data-reveal-order'), 10) || 0;
        const ob = parseInt(b.getAttribute('data-reveal-order'), 10) || 0;
        return oa - ob;
      });

      for (let k = 0; k < ordered.length; k++) {
        ordered[k].style.setProperty('--reveal-delay', k * 180 + 'ms');
      }
    }

    const cardGroups = section.querySelectorAll('.flex.flex-wrap');
    for (let g = 0; g < cardGroups.length; g++) {
      const group = cardGroups[g];
      const zoomCards = group.querySelectorAll('.reveal-item--zoom');
      if (zoomCards.length < 3) continue;

      let beforeCount = 0;
      let prev = group.previousElementSibling;
      while (prev) {
        beforeCount += prev.querySelectorAll('.reveal-item, .reveal-item--zoom').length;
        prev = prev.previousElementSibling;
      }

      const offset = beforeCount * STAGGER;
      const len = zoomCards.length;
      for (let i = 0; i < len; i++) {
        let delay;
        if (i === 0)            delay = 0;
        else if (i === len - 1) delay = STAGGER;
        else                    delay = STAGGER * 2;
        zoomCards[i].style.setProperty('--reveal-delay', (offset + delay) + 'ms');
      }
    }

    const pairContainers = section.querySelectorAll('[data-reveal-pairs]');
    for (let pc = 0; pc < pairContainers.length; pc++) {
      const container = pairContainers[pc];
      const pairSize = parseInt(container.getAttribute('data-reveal-pairs'), 10) || 2;
      const pairItems = container.querySelectorAll('.reveal-item');
      if (pairItems.length < 2) continue;

      let beforeCount = 0;
      let prev = container.previousElementSibling;
      while (prev) {
        beforeCount += prev.querySelectorAll('.reveal-item, .reveal-item--zoom').length;
        prev = prev.previousElementSibling;
      }

      const offset = beforeCount * STAGGER;
      for (let i = 0; i < pairItems.length; i++) {
        const pairIndex = Math.floor(i / pairSize);
        const delay = offset + pairIndex * STAGGER * pairSize;
        pairItems[i].style.setProperty('--reveal-delay', delay + 'ms');
      }
    }

    const chunkContainers = section.querySelectorAll('[data-reveal-chunk]');
    for (let cc = 0; cc < chunkContainers.length; cc++) {
      const chunkItems = chunkContainers[cc].querySelectorAll('.reveal-item, .reveal-item--zoom');
      for (let ci = 0; ci < chunkItems.length; ci++) {
        chunkItems[ci].style.setProperty('--reveal-delay', ci * STAGGER + 'ms');
      }
    }
  }

  const itemObserver = new IntersectionObserver(function (entries) {
    for (let e = 0; e < entries.length; e++) {
      const entry = entries[e];
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible-item');
        itemObserver.unobserve(entry.target);
      }
    }
  }, {
    threshold: 0.01,
    rootMargin: '0px 0px 0px 0px'
  });

  for (let s = 0; s < sections.length; s++) {
    const allItems = sections[s].querySelectorAll('.reveal-item, .reveal-item--zoom');
    for (let i = 0; i < allItems.length; i++) {
      itemObserver.observe(allItems[i]);
    }
  }
});
