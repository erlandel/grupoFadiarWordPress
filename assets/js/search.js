document.addEventListener('DOMContentLoaded', function () {

  const scrollHash = sessionStorage.getItem('gfScrollTo');
  if (scrollHash) {
    sessionStorage.removeItem('gfScrollTo');
    const targetEl = document.querySelector(scrollHash);
    if (targetEl) {
      setTimeout(function () {
        targetEl.scrollIntoView({ block: 'start', behavior: 'smooth' });
      }, 100);
    }
  }

  const overlay = document.querySelector('.search-overlay');
  const backdrop = document.querySelector('.search-backdrop');
  const panelWrapper = document.querySelector('.search-panel-wrapper');
  if (!overlay || !backdrop || !panelWrapper) return;

  const input = overlay.querySelector('.search-input');
  const resultsList = overlay.querySelector('.search-results-list');
  const resultsContainer = overlay.querySelector('.search-results');
  const emptyState = overlay.querySelector('.search-empty');
  const noResults = overlay.querySelector('.search-no-results');
  const queryText = overlay.querySelector('.search-query-text');
  const loading = overlay.querySelector('.search-loading');
  const loaderMore = overlay.querySelector('.search-loader-more');
  const sentinel = overlay.querySelector('.search-sentinel');
  const filters = overlay.querySelectorAll('.search-filter');
  const closeBtn = overlay.querySelector('.search-close');

  let currentFilter = 'todos';
  let debounceTimer = null;
  const restUrl = window.grupofadiarSearchData ? window.grupofadiarSearchData.restUrl : '/wp-json/grupofadiar/v1/search';
  const strings = window.gfStrings || {};
  let currentPage = 1;
  let totalResults = 0;
  let currentQuery = '';
  let isLoadingMore = false;
  let previousBodyOverflow = '';
  let bodyScrollLocked = false;
  const perPage = 10;
  const visualViewport = window.visualViewport;
  const mobileTabletQuery = window.matchMedia('(max-width: 1279px)');

  const suggestionHint = overlay.querySelector('.search-suggestion-hint');
  const affinityMap = {
    productos: ['producto', 'productos', 'marca', 'marcas', 'eon', 'vital', 'lammina', 'comprar', 'precio', 'oferta', 'tienda'],
    noticias: ['noticia', 'noticias', 'feria', 'evento', 'eventos', 'lanzamiento', 'lanzamientos', 'actualidad', 'novedad', 'novedades'],
    corporativa: ['historia', 'mision', 'vision', 'valores', 'pilar', 'pilares', 'liderazgo', 'equipo', 'nosotros', 'sobre nosotros', 'i+d', 'innovacion', 'compromiso', 'modelo de negocio', 'escuelas', 'proyectos', 'comunidades', 'estrategia'],
    garantias: ['garantia', 'garantias', 'warranty', 'reclamacion', 'reclamar', 'reclamo', 'servicio tecnico', 'reparacion', 'reparar', 'registrar compra', 'numero de serie', 'soporte', 'evaluacion tecnica', 'paso', 'contactar soporte', 'faq', 'preguntas frecuentes']
  };

  const scrollObserver = new IntersectionObserver(function (entries) {
    if (entries[0].isIntersecting && hasMorePages() && !isLoadingMore) {
      isLoadingMore = true;
      if (loaderMore) {
        loaderMore.classList.remove('hidden');
        loaderMore.classList.add('flex');
      }
      currentPage++;
      fetchResults(true);
    }
  }, { root: resultsContainer, rootMargin: '200px' });

  function hasMorePages() {
    return currentPage * perPage < totalResults;
  }

  function decodeEntities(str) {
    if (!str) return '';
    const txt = document.createElement('textarea');
    txt.innerHTML = str;
    return txt.value;
  }

  function normalizeStr(str) {
    return str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  }

  function suggestFilter(query) {
    clearSuggestion();
    if (query.length < 2) return;

    const q = normalizeStr(query);
    let bestFilter = null;
    let bestScore = 0;

    for (const key in affinityMap) {
      let score = 0;
      const words = affinityMap[key];
      for (let i = 0; i < words.length; i++) {
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
      if (suggestionHint && window.matchMedia('(min-width: 768px)').matches) {
        let filterName = '';
        filters.forEach(function (f) {
          if (f.getAttribute('data-filter') === bestFilter) {
            filterName = f.textContent.trim();
          }
        });
        const label = strings['search.suggestion.label'] || 'Sugerencia:';
        suggestionHint.innerHTML = label + ' <strong>' + filterName + '</strong>';
        suggestionHint.classList.remove('hidden');
      }
    }
  }

  function clearSuggestion() {
    filters.forEach(function (f) { f.classList.remove('is-suggested'); });
    if (suggestionHint) suggestionHint.classList.add('hidden');
  }

  function updatePanelPosition() {
    if (mobileTabletQuery.matches) {
      const viewportHeight = visualViewport ? visualViewport.height : window.innerHeight;
      backdrop.style.top = '0px';
      panelWrapper.style.top = '0px';
      panelWrapper.style.setProperty('--search-panel-height', viewportHeight + 'px');
      return;
    }

    const header = document.querySelector('.site-header');
    const headerHeight = header ? header.getBoundingClientRect().height : 80;
    const headerBottom = header ? header.getBoundingClientRect().bottom : 0;
    const panelTop = headerBottom + 8;
    const viewportHeight = visualViewport ? visualViewport.height : window.innerHeight;

    panelWrapper.style.setProperty('--header-h', headerHeight + 'px');
    backdrop.style.top = headerBottom + 'px';
    panelWrapper.style.top = panelTop + 'px';
    panelWrapper.style.setProperty('--search-panel-height', Math.max(0, viewportHeight - panelTop - 8) + 'px');
  }

  function openSearch() {
    updatePanelPosition();
    overlay.classList.remove('hidden');
    if (mobileTabletQuery.matches) {
      previousBodyOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      bodyScrollLocked = true;
    }
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
    if (bodyScrollLocked) {
      document.body.style.overflow = previousBodyOverflow;
      bodyScrollLocked = false;
    }
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
    if (loaderMore) {
      loaderMore.classList.add('hidden');
      loaderMore.classList.remove('flex');
    }
    stopObserving();
  }

  function hideAllStates() {
    if (emptyState) emptyState.classList.add('hidden');
    if (noResults) noResults.classList.add('hidden');
    if (loading) {
      loading.classList.add('hidden');
      loading.classList.remove('flex');
    }
  }

  function showEmptyState() {
    hideAllStates();
    if (emptyState) emptyState.classList.remove('hidden');
  }

  function showLoading() {
    hideAllStates();
    if (loading) {
      loading.classList.remove('hidden');
      loading.classList.add('flex');
    }
  }

  function showNoResults(query) {
    hideAllStates();
    if (noResults) {
      noResults.classList.remove('hidden');
      if (queryText) queryText.textContent = query;
    }
    if (loaderMore) {
      loaderMore.classList.add('hidden');
      loaderMore.classList.remove('flex');
    }
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
    const query = input.value.trim();
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

    const url = restUrl + '?search=' + encodeURIComponent(query) + '&filter=' + encodeURIComponent(currentFilter) + '&page=' + currentPage;

    fetch(url)
      .then(function (response) { return response.json(); })
      .then(function (data) {
        renderResults(data, append);
      })
      .catch(function () {
        hideAllStates();
        resultsList.innerHTML = '<div class="text-center py-10 text-dark/50 text-lg"><p>' + escapeHtml(strings['search.error'] || 'Error al buscar. Intenta de nuevo.') + '</p></div>';
        isLoadingMore = false;
        if (loaderMore) {
          loaderMore.classList.add('hidden');
          loaderMore.classList.remove('flex');
        }
      });
  }

  function hasHashInPath(urlStr) {
    try {
      const u = new URL(urlStr, window.location.origin);
      return u.hash && u.hash.length > 1;
    } catch (e) {
      return false;
    }
  }

  function isSamePage(urlStr) {
    try {
      const u = new URL(urlStr, window.location.origin);
      return u.origin === window.location.origin && u.pathname === window.location.pathname;
    } catch (e) {
      return false;
    }
  }

  function handleCardClick(e) {
    const card = e.target.closest('a');
    if (!card) return;

    const href = card.getAttribute('href');
    if (!href) return;

    if (card.getAttribute('target') === '_blank') return;

    if (!hasHashInPath(href)) return;

    e.preventDefault();

    closeSearch();

    if (isSamePage(href)) {
      const hash = href.indexOf('#') !== -1 ? href.substring(href.indexOf('#')) : '';
      if (hash && hash.length > 1) {
        const el = document.querySelector(hash);
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
    if (loaderMore) {
      loaderMore.classList.add('hidden');
      loaderMore.classList.remove('flex');
    }

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
      const card = document.createElement('a');
      card.className = 'flex items-start gap-3 md:gap-4 p-3 md:p-4 rounded-xl transition-colors duration-200 hover:bg-dark/5 group cursor-pointer';

      if (item.external_url) {
        card.setAttribute('target', '_blank');
        card.setAttribute('rel', 'noopener noreferrer');
        card.href = item.external_url;
      } else {
        card.href = item.permalink;
      }

      let thumbnailHtml = '';
      if (item.thumbnail) {
        thumbnailHtml = '<div class="shrink-0 w-16 h-16 md:w-24 md:h-24 rounded-lg overflow-hidden bg-dark/5"><img src="' + escapeHtml(item.thumbnail) + '" alt="' + escapeHtml(decodeEntities(item.title)) + '" class="w-full h-full object-cover" /></div>';
      } else if (item.type === 'producto') {
        thumbnailHtml = '<div class="shrink-0 w-16 h-16 md:w-24 md:h-24 rounded-lg overflow-hidden bg-dark/5 flex items-center justify-center text-dark/20">' + getIconPlaceholder() + '</div>';
      }

      if (item.type === 'producto') {
        card.className = 'flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-xl transition-colors duration-200 hover:bg-dark/5 group cursor-pointer';
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
    const div = document.createElement('div');
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

  window.addEventListener('resize', function () {
    if (!overlay.classList.contains('hidden')) updatePanelPosition();
  });

  if (visualViewport) {
    visualViewport.addEventListener('resize', function () {
      if (!overlay.classList.contains('hidden')) updatePanelPosition();
    });
  }

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
