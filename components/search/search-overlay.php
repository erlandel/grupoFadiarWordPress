<div class="search-overlay hidden">
  <div class="search-backdrop fixed left-0 right-0 bottom-0 z-60"></div>

  <div class="search-panel-wrapper fixed left-0 right-0 z-70 px-4 pointer-events-none">
    <div class="search-panel max-w-2xl mx-auto bg-white/40 backdrop-blur-2xl rounded-xl shadow-2xl pointer-events-auto">

      <div class="flex justify-end p-3 md:p-4">
        <button class="search-close cursor-pointer">
          <?php echo get_icon('close', 'w-7 h-7 text-dark hover:text-secondary transition-colors'); ?>
        </button>
      </div>

      <div class="px-6 md:px-8 pb-6">
        <div class="relative">
          <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
            <?php echo get_icon('search', 'w-6 h-6 text-dark/40'); ?>
          </div>
          <input
            type="text"
            class="search-input w-full pl-14 pr-4 py-4 text-xl md:text-2xl bg-transparent border-b-2 border-dark/20 focus:border-dark outline-none text-dark placeholder:text-dark/30 font-montserrat transition-colors duration-300"
            placeholder="Buscar..."
            autocomplete="off"
          />
        </div>
      </div>

      <div class="px-6 md:px-8 pb-6 flex gap-2 md:gap-3 overflow-x-auto search-filters scrollbar-hide">
        <button class="search-filter active shrink-0 px-4 py-2 rounded-full text-sm md:text-base font-bold transition-colors duration-200 bg-dark text-secondary cursor-pointer" data-filter="todos">Todos</button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full text-sm md:text-base font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="productos">Productos</button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full text-sm md:text-base font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="noticias">Noticias</button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full text-sm md:text-base font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="corporativa">Info corporativa</button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full text-sm md:text-base font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="garantias">Garantías</button>
      </div>

      <div class="search-results px-6 md:px-8 pb-8 max-h-[55vh] overflow-y-auto">
        <div class="search-empty hidden text-center py-10 text-dark/50 text-lg">
          <p>Escribe para comenzar la búsqueda</p>
        </div>
        <div class="search-loading hidden  justify-center py-10">
          <?php echo get_icon('spinner', 'w-8 h-8 text-dark animate-spin'); ?>
        </div>
        <div class="search-results-list space-y-4"></div>
        <div class="search-no-results hidden text-center py-10 text-dark/50 text-lg">
          <p>No se encontraron resultados para <strong class="search-query-text text-dark"></strong></p>
        </div>
      </div>

    </div>
  </div>
</div>
