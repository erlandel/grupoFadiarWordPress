document.addEventListener('DOMContentLoaded', function () {

  var scrollHash = sessionStorage.getItem('gfScrollTo');
  if (scrollHash) {
    sessionStorage.removeItem('gfScrollTo');
    var targetEl = document.querySelector(scrollHash);
    if (targetEl) {
      setTimeout(function () {
        targetEl.scrollIntoView({ block: 'start', behavior: 'smooth' });
      }, 100);
    }
  }

  var overlay = document.querySelector('.search-overlay');
  var backdrop = document.querySelector('.search-backdrop');
  var panelWrapper = document.querySelector('.search-panel-wrapper');
  if (!overlay || !backdrop || !panelWrapper) return;

  var input = overlay.querySelector('.search-input');
  var resultsList = overlay.querySelector('.search-results-list');
  var resultsContainer = overlay.querySelector('.search-results');
  var emptyState = overlay.querySelector('.search-empty');
  var noResults = overlay.querySelector('.search-no-results');
  var queryText = overlay.querySelector('.search-query-text');
  var loading = overlay.querySelector('.search-loading');
  var filters = overlay.querySelectorAll('.search-filter');
  var closeBtn = overlay.querySelector('.search-close');

  var currentFilter = 'todos';
  var debounceTimer = null;
  var restUrl = window.grupofadiarSearchData ? window.grupofadiarSearchData.restUrl : '/wp-json/grupofadiar/v1/search';

  function openSearch() {
    var header = document.querySelector('.site-header');
    var headerBottom = header ? header.getBoundingClientRect().bottom : 0;
    backdrop.style.top = headerBottom + 'px';
    panelWrapper.style.top = (headerBottom + 4) + 'px';
    overlay.classList.remove('hidden');
    setTimeout(function () {
      input.focus();
    }, 100);
    clearResults();
    showEmptyState();
  }

  function closeSearch() {
    overlay.classList.add('hidden');
    input.value = '';
    clearResults();
    showEmptyState();
  }

  function clearResults() {
    resultsList.innerHTML = '';
    hideAllStates();
  }

  function hideAllStates() {
    if (emptyState) emptyState.classList.add('hidden');
    if (noResults) noResults.classList.add('hidden');
    if (loading) loading.classList.add('hidden');
  }

  function showEmptyState() {
    hideAllStates();
    if (emptyState) emptyState.classList.remove('hidden');
  }

  function showLoading() {
    hideAllStates();
    if (loading) loading.classList.remove('hidden');
  }

  function showNoResults(query) {
    hideAllStates();
    if (noResults) {
      noResults.classList.remove('hidden');
      if (queryText) queryText.textContent = query;
    }
  }

  function setActiveFilter(el) {
    filters.forEach(function (f) {
      f.classList.remove('active', 'bg-dark', 'text-secondary');
      f.classList.add('bg-dark/10', 'text-dark');
    });
    el.classList.add('active', 'bg-dark', 'text-secondary');
    el.classList.remove('bg-dark/10', 'text-dark');
    currentFilter = el.getAttribute('data-filter');
  }

  function fetchResults() {
    var query = input.value.trim();
    if (query.length < 1 && currentFilter === 'todos') {
      clearResults();
      showEmptyState();
      return;
    }

    showLoading();

    var lang = document.documentElement.lang || '';
    if (lang) {
        lang = lang.replace('-', '_');
    }

    var url = restUrl + '?search=' + encodeURIComponent(query) + '&filter=' + encodeURIComponent(currentFilter) + '&lang=' + encodeURIComponent(lang);

    fetch(url)
      .then(function (response) { return response.json(); })
      .then(function (data) {
        renderResults(data);
      })
      .catch(function () {
        hideAllStates();
        resultsList.innerHTML = '<div class="text-center py-10 text-dark/50 text-lg"><p>Error al buscar. Intenta de nuevo.</p></div>';
      });
  }

  function hasHashInPath(urlStr) {
    try {
      var u = new URL(urlStr, window.location.origin);
      return u.hash && u.hash.length > 1;
    } catch (e) {
      return false;
    }
  }

  function isSamePage(urlStr) {
    try {
      var u = new URL(urlStr, window.location.origin);
      return u.origin === window.location.origin && u.pathname === window.location.pathname;
    } catch (e) {
      return false;
    }
  }

  function handleCardClick(e) {
    var card = e.target.closest('a');
    if (!card) return;

    var href = card.getAttribute('href');
    if (!href) return;

    if (card.getAttribute('target') === '_blank') return;

    if (!hasHashInPath(href)) return;

    e.preventDefault();

    closeSearch();

    if (isSamePage(href)) {
      var hash = href.indexOf('#') !== -1 ? href.substring(href.indexOf('#')) : '';
      if (hash && hash.length > 1) {
        var el = document.querySelector(hash);
        if (el) {
          setTimeout(function () {
            el.scrollIntoView({ block: 'start', behavior: 'smooth' });
          }, 350);
        }
      }
    } else {
      sessionStorage.setItem('gfScrollTo', href.indexOf('#') !== -1 ? href.substring(href.indexOf('#')) : '');
      window.location.href = href;
    }
  }

  function renderResults(data) {
    hideAllStates();

    if (!data || !data.results || data.results.length === 0) {
      if (input.value.trim() === '') {
        showEmptyState();
      } else {
        showNoResults(input.value.trim());
      }
      return;
    }

    resultsList.innerHTML = '';

    data.results.forEach(function (item) {
      var card = document.createElement('a');
      card.className = 'flex items-start gap-4 p-4 rounded-xl transition-colors duration-200 hover:bg-dark/5 group cursor-pointer';

      if (item.external_url) {
        card.setAttribute('target', '_blank');
        card.setAttribute('rel', 'noopener noreferrer');
        card.href = item.external_url;
      } else {
        card.href = item.permalink;
      }

      var thumbnailHtml = '';
      if (item.thumbnail) {
        thumbnailHtml = '<div class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-dark/5"><img src="' + escapeHtml(item.thumbnail) + '" alt="' + escapeHtml(item.title) + '" class="w-full h-full object-cover" /></div>';
      } else if (item.type === 'producto') {
        thumbnailHtml = '<div class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-dark/5 flex items-center justify-center text-dark/20">' + getIconPlaceholder() + '</div>';
      }

      var badgeClass = 'bg-dark/75 text-secondary';

      card.innerHTML = thumbnailHtml +
        '<div class="flex-1 min-w-0">' +
          '<div class="flex items-center gap-2 mb-1">' +
            '<span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full ' + badgeClass + '">' + escapeHtml(item.category || item.type) + '</span>' +
          '</div>' +
          '<h4 class="text-base md:text-lg font-bold text-dark transition-colors truncate">' + escapeHtml(item.title) + '</h4>' +
          (item.excerpt ? '<p class="text-sm text-dark/60 mt-1 line-clamp-2">' + escapeHtml(item.excerpt) + '</p>' : '') +
          (item.external_url ? '<span class="inline-block mt-2 text-xs font-bold text-dark">' + escapeHtml(item.external_text || 'Abrir tienda') + ' →</span>' : '') +
        '</div>';

      resultsList.appendChild(card);
    });

    resultsContainer.scrollTop = 0;
  }

  function getIconPlaceholder() {
    return '<svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
  }

  function escapeHtml(str) {
    if (!str) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

  resultsList.addEventListener('click', handleCardClick);

  document.querySelectorAll('.search-open').forEach(function (btn) {
    btn.addEventListener('click', openSearch);
  });

  if (closeBtn) closeBtn.addEventListener('click', closeSearch);
  if (backdrop) backdrop.addEventListener('click', closeSearch);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !overlay.classList.contains('hidden')) {
      closeSearch();
    }
  });

  input.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchResults, 300);
  });

  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      clearTimeout(debounceTimer);
      fetchResults();
    }
  });

  filters.forEach(function (f) {
    f.addEventListener('click', function () {
      setActiveFilter(f);
      clearTimeout(debounceTimer);
      fetchResults();
    });
  });

  showEmptyState();
});
