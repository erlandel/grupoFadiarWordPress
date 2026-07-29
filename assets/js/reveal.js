document.addEventListener('DOMContentLoaded', function () {
  var sections = document.querySelectorAll('.reveal-section');
  if (!sections.length) return;

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (reduced || !('IntersectionObserver' in window)) {
    for (var s = 0; s < sections.length; s++) {
      var allItems = sections[s].querySelectorAll('.reveal-item, .reveal-item--zoom');
      for (var i = 0; i < allItems.length; i++) {
        allItems[i].classList.add('is-visible-item');
      }
    }
    return;
  }

  var STAGGER = 120;
  var MAX_ITEMS = 10;

  for (var s = 0; s < sections.length; s++) {
    var section = sections[s];
    var items = section.querySelectorAll('.reveal-item');
    for (var i = 0; i < items.length; i++) {
      var idx = i < MAX_ITEMS ? i : MAX_ITEMS - 1;
      items[i].style.setProperty('--reveal-delay', idx * STAGGER + 'ms');
    }

    var productGrids = section.querySelectorAll('.grid');
    for (var pg = 0; pg < productGrids.length; pg++) {
      var grid = productGrids[pg];
      var products = grid.querySelectorAll('.reveal-item--zoom');
      if (products.length < 2) continue;

      var ordered = [];
      for (var pi = 0; pi < products.length; pi++) {
        ordered.push(products[pi]);
      }
      ordered.sort(function (a, b) {
        var oa = parseInt(a.getAttribute('data-reveal-order'), 10) || 0;
        var ob = parseInt(b.getAttribute('data-reveal-order'), 10) || 0;
        return oa - ob;
      });

      for (var k = 0; k < ordered.length; k++) {
        ordered[k].style.setProperty('--reveal-delay', k * 180 + 'ms');
      }
    }

    var cardGroups = section.querySelectorAll('.flex.flex-wrap');
    for (var g = 0; g < cardGroups.length; g++) {
      var group = cardGroups[g];
      var zoomCards = group.querySelectorAll('.reveal-item--zoom');
      if (zoomCards.length < 3) continue;

      var beforeCount = 0;
      var prev = group.previousElementSibling;
      while (prev) {
        beforeCount += prev.querySelectorAll('.reveal-item, .reveal-item--zoom').length;
        prev = prev.previousElementSibling;
      }

      var offset = beforeCount * STAGGER;
      var len = zoomCards.length;
      for (var i = 0; i < len; i++) {
        var delay;
        if (i === 0)            delay = 0;
        else if (i === len - 1) delay = STAGGER;
        else                    delay = STAGGER * 2;
        zoomCards[i].style.setProperty('--reveal-delay', (offset + delay) + 'ms');
      }
    }

    var pairContainers = section.querySelectorAll('[data-reveal-pairs]');
    for (var pc = 0; pc < pairContainers.length; pc++) {
      var container = pairContainers[pc];
      var pairSize = parseInt(container.getAttribute('data-reveal-pairs'), 10) || 2;
      var pairItems = container.querySelectorAll('.reveal-item');
      if (pairItems.length < 2) continue;

      var beforeCount = 0;
      var prev = container.previousElementSibling;
      while (prev) {
        beforeCount += prev.querySelectorAll('.reveal-item, .reveal-item--zoom').length;
        prev = prev.previousElementSibling;
      }

      var offset = beforeCount * STAGGER;
      for (var i = 0; i < pairItems.length; i++) {
        var pairIndex = Math.floor(i / pairSize);
        var delay = offset + pairIndex * STAGGER * pairSize;
        pairItems[i].style.setProperty('--reveal-delay', delay + 'ms');
      }
    }
  }

  var itemObserver = new IntersectionObserver(function (entries) {
    for (var e = 0; e < entries.length; e++) {
      var entry = entries[e];
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible-item');
        itemObserver.unobserve(entry.target);
      }
    }
  }, {
    threshold: 0.18,
    rootMargin: '0px 0px -10% 0px'
  });

  for (var s = 0; s < sections.length; s++) {
    var allItems = sections[s].querySelectorAll('.reveal-item, .reveal-item--zoom');
    for (var i = 0; i < allItems.length; i++) {
      itemObserver.observe(allItems[i]);
    }
  }
});
