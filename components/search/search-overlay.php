<div class="search-overlay hidden">
  <div class="search-backdrop fixed inset-0 z-60"></div>
  <style>
    .search-panel-wrapper button:focus,
    .search-panel-wrapper button:focus-visible,
    .search-panel-wrapper a:focus,
    .search-panel-wrapper a:focus-visible {
      outline: none;
      box-shadow: none;
    }
  </style>

  <div class="search-panel-wrapper fixed left-0 right-0 z-70 px-4 pointer-events-none bottom-4 md:bottom-6 max-h-[min(65vh,calc(100vh-var(--header-h)-2rem))] overflow-hidden">
    <div class="search-panel max-w-2xl mx-auto bg-white/40 backdrop-blur-2xl rounded-xl shadow-2xl pointer-events-auto flex flex-col max-h-full overflow-hidden">

      <div class="flex justify-end p-3 md:p-4 shrink-0">
        <button class="search-close cursor-pointer">
          <?php echo get_icon('close', 'w-7 h-7 text-dark hover:text-secondary transition-colors'); ?>
        </button>
      </div>

      <div class="px-6 md:px-8 pb-6 shrink-0">
        <div class="relative">
          <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
            <?php echo get_icon('search', 'w-6 h-6 text-dark/40'); ?>
          </div>
          <input
            type="text"
            class="search-input w-full pl-14 pr-4 py-4 text-xl  bg-transparent border-b-2 border-dark/20 focus:border-dark outline-none text-dark placeholder:text-dark/30 font-montserrat transition-colors duration-300"
            placeholder="<?php echo esc_attr(gf_e('search.placeholder')); ?>"
            autocomplete="off"
          />
        </div>
      </div>

      <div class="px-6 md:px-8 pb-6 shrink-0 flex gap-2 md:gap-3 overflow-x-auto search-filters scrollbar-hide">
        <button class="search-filter active shrink-0 px-4 py-2 rounded-full font-bold transition-colors duration-200 bg-dark text-secondary cursor-pointer" data-filter="todos"><?php gf_render_e('search.filter.all'); ?></button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="productos"><?php gf_render_e('search.filter.products'); ?></button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="noticias"><?php gf_render_e('search.filter.news'); ?></button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="corporativa"><?php gf_render_e('search.filter.corporate'); ?></button>
        <button class="search-filter shrink-0 px-4 py-2 rounded-full font-bold transition-colors duration-200 bg-dark/10 text-dark hover:bg-dark hover:text-secondary cursor-pointer" data-filter="garantias"><?php gf_render_e('search.filter.warranty'); ?></button>
      </div>
      <div class="search-suggestion-hint hidden px-6 md:px-8 pb-4 text-sm text-dark/50" role="status"></div>

      <div class="search-results px-6 md:px-8 pb-8 flex-1 min-h-0 overflow-y-auto">
        <div class="search-empty hidden text-center py-10 text-dark/50 text-lg">
          <p><?php gf_render_e('search.empty'); ?></p>
        </div>
        <div class="search-loading hidden  justify-center py-10">
          <?php echo get_icon('spinner', 'w-8 h-8 text-dark animate-spin'); ?>
        </div>
        <div class="search-results-list space-y-4"></div>
        <div class="search-sentinel h-4"></div>
        <div class="search-loader-more hidden flex justify-center py-4">
          <?php echo get_icon('spinner', 'w-6 h-6 text-dark/50 animate-spin'); ?>
        </div>
        <div class="search-no-results hidden text-center py-10 text-dark/50 text-lg">
          <p><?php gf_render_e('search.no_results'); ?> <strong class="search-query-text text-dark"></strong></p>
        </div>
      </div>

    </div>
  </div>
</div>
