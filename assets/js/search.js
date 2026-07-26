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
  var loaderMore = overlay.querySelector('.search-loader-more');
  var sentinel = overlay.querySelector('.search-sentinel');
  var filters = overlay.querySelectorAll('.search-filter');
  var closeBtn = overlay.querySelector('.search-close');

  var currentFilter = 'todos';
  var debounceTimer = null;
  var restUrl = window.grupofadiarSearchData ? window.grupofadiarSearchData.restUrl : '/wp-json/grupofadiar/v1/search';
  var strings = window.gfStrings || {};
  var currentPage = 1;
  var totalResults = 0;
  var currentQuery = '';
  var isLoadingMore = false;
  var perPage = 10;

  var suggestionHint = overlay.querySelector('.search-suggestion-hint');
  var affinityMap = {
    productos: ['producto', 'productos', 'marca', 'marcas', 'eon', 'vital', 'lammina', 'comprar', 'precio', 'oferta', 'tienda'],
    noticias: ['noticia', 'noticias', 'feria', 'evento', 'eventos', 'lanzamiento', 'lanzamientos', 'actualidad', 'novedad', 'novedades'],
    corporativa: ['historia', 'mision', 'vision', 'valores', 'pilar', 'pilares', 'liderazgo', 'equipo', 'nosotros', 'sobre nosotros', 'i+d', 'innovacion', 'compromiso', 'modelo de negocio', 'escuelas', 'proyectos', 'comunidades', 'estrategia'],
    garantias: ['garantia', 'garantias', 'warranty', 'reclamacion', 'reclamar', 'reclamo', 'servicio tecnico', 'reparacion', 'reparar', 'registrar compra', 'numero de serie', 'soporte', 'evaluacion tecnica', 'paso', 'contactar soporte', 'faq', 'preguntas frecuentes']
  };

  var scrollObserver = new IntersectionObserver(function (entries) {
    if (entries[0].isIntersecting && hasMorePages() && !isLoadingMore) {
      isLoadingMore = true;
      if (loaderMore) loaderMore.classList.remove('hidden');
      currentPage++;
      fetchResults(true);
    }
  }, { root: resultsContainer, rootMargin: '200px' });

  function hasMorePages() {
    return currentPage * perPage < totalResults;
  }

  function decodeEntities(str) {
    if (!str) return '';
    var txt = document.createElement('textarea');
    txt.innerHTML = str;
    return txt.value;
  }

  function normalizeStr(str) {
    return str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  }

  function suggestFilter(query) {
    clearSuggestion();
    if (query.length < 2) return;

    var q = normalizeStr(query);
    var bestFilter = null;
    var bestScore = 0;

    for (var key in affinityMap) {
      var score = 0;
      var words = affinityMap[key];
      for (var i = 0; i < words.length; i++) {
        if (q.indexOf(normalizeStr(words[i])) !== -1) {
          score++;
        }
      }
      if (score > bestScore) {
        bestScore = score;
        bestFilter = key;
      }
    }

    if (bestFilter && bestScore > 0) {
      filters.forEach(function (f) {
        if (f.getAttribute('data-filter') === bestFilter) {
          f.classList.add('is-suggested');
        }
      });
      if (suggestionHint) {
        var filterName = '';
        filters.forEach(function (f) {
          if (f.getAttribute('data-filter') === bestFilter) {
            filterName = f.textContent.trim();
          }
        });
        var label = strings['search.suggestion.label'] || 'Sugerencia:';
        suggestionHint.innerHTML = label + ' <strong>' + filterName + '</strong>';
        suggestionHint.classList.remove('hidden');
      }
    }
  }

  function clearSuggestion() {
    filters.forEach(function (f) { f.classList.remove('is-suggested'); });
    if (suggestionHint) suggestionHint.classList.add('hidden');
  }

  function openSearch() {
    var header = document.querySelector('.site-header');
    var headerHeight = header ? header.getBoundingClientRect().height : 80;
    var headerBottom = header ? header.getBoundingClientRect().bottom : 0;
    panelWrapper.style.setProperty('--header-h', headerHeight + 'px');
    backdrop.style.top = headerBottom + 'px';
    panelWrapper.style.top = (headerBottom + 8) + 'px';
    overlay.classList.remove('hidden');
    setTimeout(function () {
      input.focus();
    }, 100);
    clearResults();
    showEmptyState();
    clearSuggestion();
    stopObserving();
  }

  function closeSearch() {
    overlay.classList.add('hidden');
    input.value = '';
    clearResults();
    showEmptyState();
    currentPage = 1;
    totalResults = 0;
    currentQuery = '';
    clearSuggestion();
    stopObserving();
  }

  function clearResults() {
    resultsList.innerHTML = '';
    hideAllStates();
    if (loaderMore) loaderMore.classList.add('hidden');
    stopObserving();
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
    if (loaderMore) loaderMore.classList.add('hidden');
  }

  function stopObserving() {
    if (sentinel) scrollObserver.unobserve(sentinel);
  }

  function startObserving() {
    stopObserving();
    if (sentinel && hasMorePages()) {
      scrollObserver.observe(sentinel);
    }
  }

  function setActiveFilter(el) {
    filters.forEach(function (f) {
      f.classList.remove('active', 'bg-dark', 'text-secondary', 'is-suggested');
      f.classList.add('bg-dark/10', 'text-dark');
    });
    el.classList.add('active', 'bg-dark', 'text-secondary');
    el.classList.remove('bg-dark/10', 'text-dark');
    currentFilter = el.getAttribute('data-filter');
    currentPage = 1;
  }

  function fetchResults(append) {
    var query = input.value.trim();
    currentQuery = query;

    if (query.length < 1 && currentFilter === 'todos') {
      if (!append) {
        clearResults();
        showEmptyState();
      }
      isLoadingMore = false;
      return;
    }

    if (!append) {
      showLoading();
    }

    var url = restUrl + '?search=' + encodeURIComponent(query) + '&filter=' + encodeURIComponent(currentFilter) + '&page=' + currentPage;

    fetch(url)
      .then(function (response) { return response.json(); })
      .then(function (data) {
        renderResults(data, append);
      })
      .catch(function () {
        hideAllStates();
        resultsList.innerHTML = '<div class="text-center py-10 text-dark/50 text-lg"><p>' + escapeHtml(strings['search.error'] || 'Error al buscar. Intenta de nuevo.') + '</p></div>';
        isLoadingMore = false;
        if (loaderMore) loaderMore.classList.add('hidden');
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

  function renderResults(data, append) {
    hideAllStates();
    if (loaderMore) loaderMore.classList.add('hidden');

    if (!data || !data.results || data.results.length === 0) {
      if (append) {
        isLoadingMore = false;
        return;
      }
      if (input.value.trim() === '') {
        showEmptyState();
      } else {
        showNoResults(currentQuery);
      }
      isLoadingMore = false;
      return;
    }

    totalResults = data.total || 0;

    if (!append) {
      resultsList.innerHTML = '';
    }

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
        thumbnailHtml = '<div class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-dark/5"><img src="' + escapeHtml(item.thumbnail) + '" alt="' + escapeHtml(decodeEntities(item.title)) + '" class="w-full h-full object-cover" /></div>';
      } else if (item.type === 'producto') {
        thumbnailHtml = '<div class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-dark/5 flex items-center justify-center text-dark/20">' + getIconPlaceholder() + '</div>';
      }

      if (item.type === 'producto') {
        card.className = 'flex items-center gap-4 p-4 rounded-xl transition-colors duration-200 hover:bg-dark/5 group cursor-pointer';
        card.innerHTML = thumbnailHtml +
          '<div class="flex-1 min-w-0">' +
            '<span class="text-sm font-bold text-dark">' + escapeHtml(decodeEntities(item.excerpt || (window.gfLang === 'en' ? 'View product' : 'Ver producto'))) + ' →</span>' +
          '</div>';
      } else {
        card.innerHTML = thumbnailHtml +
          '<div class="flex-1 min-w-0">' +
            '<h4 class="text-base md:text-lg font-bold text-dark transition-colors truncate">' + escapeHtml(decodeEntities(item.title)) + '</h4>' +
            (item.excerpt ? '<p class="text-sm text-dark/60 mt-1 line-clamp-2">' + escapeHtml(decodeEntities(item.excerpt)) + '</p>' : '') +
            (item.category ? '<span class="inline-block mt-1 text-xs text-dark/40">' + escapeHtml(decodeEntities(item.category)) + '</span>' : '') +
            (item.external_url ? '<span class="inline-block mt-2 text-xs font-bold text-dark">' + escapeHtml(decodeEntities(item.external_text || (window.gfLang === 'en' ? 'Open in store' : 'Abrir tienda'))) + ' →</span>' : '') +
          '</div>';
      }

      resultsList.appendChild(card);
    });

    startObserving();
    if (!append) {
      resultsContainer.scrollTop = 0;
    }
    isLoadingMore = false;
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
    currentPage = 1;
    suggestFilter(this.value);
    debounceTimer = setTimeout(function () { fetchResults(false); }, 300);
  });

  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      clearTimeout(debounceTimer);
      currentPage = 1;
      fetchResults(false);
    }
  });

  filters.forEach(function (f) {
    f.addEventListener('click', function () {
      clearSuggestion();
      setActiveFilter(f);
      clearTimeout(debounceTimer);
      currentPage = 1;
      stopObserving();
      fetchResults(false);
    });
  });

  showEmptyState();
  isLoadingMore = false;
});
