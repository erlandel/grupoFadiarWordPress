<?php
/* Template Name: Noticias */
get_header();

$current_category = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$args = array(
  'post_type'      => 'noticia',
  'posts_per_page' => 6,
  'paged'          => $paged,
);

if ($current_category > 0) {
  $args['tax_query'] = array(
    array(
      'taxonomy' => 'categoria_noticia',
      'field'    => 'term_id',
      'terms'    => $current_category,
    ),
  );
}

$query = new WP_Query($args);
$categories = get_terms(array('taxonomy' => 'categoria_noticia', 'hide_empty' => true));
$base_url = get_permalink();
?>


<div class="reveal-section mx-8 mt-7 mb-12 md:mx-15 md:mb-16 xl:mx-30 xl:mb-20">

  <!-- Breadcrumb -->
  <div class="flex items-center text-sm xl:text-base">
    <p><a href="<?php echo home_url('/'); ?>"><?php echo gf_e('noticias.breadcrumb_home'); ?></a></p>
    <svg class="mx-1 h-4 w-4 xl:h-6 xl:w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p>Actualidad</p>
  </div>

  <!-- Título -->
  <div class="mt-6 xl:mt-7">
    <h1 class="reveal-item text-2xl font-bold text-dark md:text-3xl xl:text-4xl">
      <?php echo esc_html(gf_get_option('noticias_page_title', 'Noticias', 'News')); ?>
    </h1>
  </div>

  <!-- Subtítulo + Filtro -->
  <div class="mt-5 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <p class="reveal-item max-w-2xl text-base font-bold text-dark md:text-xl xl:text-2xl">
      <?php echo esc_html(gf_get_option('noticias_page_subtitle', 'Mantente al día con las últimas novedades, lanzamientos y eventos de Grupo Fadiar.', 'Stay up to date with the latest news, launches and events from Grupo Fadiar.')); ?>
    </p>

    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
      <button type="button" data-filter-open class="reveal-item relative flex w-fit shrink-0 self-end cursor-pointer items-center gap-2 group md:self-auto">
        <span class="whitespace-nowrap text-base font-medium text-dark md:text-lg xl:text-2xl"><?php echo esc_html(gf_e('noticias.filter_label')); ?></span>
        <svg class="pointer-events-none h-5 w-5 text-dark transition-transform duration-200 md:h-6 md:w-6 xl:h-8 xl:w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>

      <div data-filter-modal class="fixed inset-0 z-100 hidden items-center justify-center bg-black/40 p-4">
        <div class="max-h-[calc(100dvh-2rem)] w-full max-w-md overflow-x-hidden overflow-y-auto rounded-xl bg-[#F8F8F8] shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="filter-modal-title">
          <div class="flex items-center justify-between px-5 pb-3 pt-5 md:px-6 md:pb-4 md:pt-6">
            <h2 id="filter-modal-title" class="text-xl font-bold text-dark md:text-2xl"><?php echo esc_html(gf_e('noticias.filter_categories')); ?></h2>
            <button type="button" data-filter-close class="text-dark hover:opacity-70 transition-opacity cursor-pointer" aria-label="<?php echo esc_attr(gf_e('noticias.filter_close')); ?>">
              <svg class="h-6 w-6 md:h-7 md:w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
          </div>

          <form method="get" action="<?php echo esc_url($base_url); ?>" class="px-5 pb-5 md:px-6 md:pb-6" data-filter-form>
            <div class="flex flex-col ">
              <label class="-mx-2 flex cursor-pointer items-center gap-3 rounded border-b-3 border-[#EDEDED] px-2 py-3 text-base text-dark transition-colors hover:bg-gray-200 md:gap-4 md:py-4 md:text-xl">
                <input type="radio" name="categoria" value="" <?php checked($current_category, 0); ?> class="h-5 w-5 accent-dark cursor-pointer shrink-0">
                <span class="<?php echo $current_category == 0 ? 'font-bold' : 'font-normal'; ?>"><?php echo esc_html(gf_e('noticias.filter_all')); ?></span>
              </label>
              <?php foreach ($categories as $cat): ?>
                <label class="-mx-2 flex cursor-pointer items-center gap-3 rounded border-b-3 border-[#EDEDED] px-2 py-3 text-base text-dark transition-colors hover:bg-gray-200 last:border-0 md:gap-4 md:py-4 md:text-xl">
                  <input type="radio" name="categoria" value="<?php echo esc_attr($cat->term_id); ?>" <?php checked($current_category, $cat->term_id); ?> class="h-4 w-4 accent-dark cursor-pointer shrink-0">
                  <span class="<?php echo $current_category == $cat->term_id ? 'font-bold' : 'font-normal'; ?>"><?php echo esc_html(gf_get_term_name($cat)); ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
    (function () {
      const modal = document.querySelector('[data-filter-modal]');
      const opener = document.querySelector('[data-filter-open]');
      if (!modal || !opener) return;

      const arrowIcon = opener.querySelector('svg');
      const closers = modal.querySelectorAll('[data-filter-close]');

      const open = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        if (arrowIcon) arrowIcon.classList.add('rotate-180');
      };

      const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        if (arrowIcon) arrowIcon.classList.remove('rotate-180');
      };

      opener.addEventListener('click', open);
      closers.forEach((btn) => btn.addEventListener('click', close));

      modal.addEventListener('click', (e) => {
        if (e.target === modal) close();
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
      });

      const form = document.querySelector('[data-filter-form]');
      const radios = form.querySelectorAll('input[type="radio"]');

      radios.forEach((radio) => {
        radio.addEventListener('click', function () {
          if (this.dataset.wasChecked === 'true') {
            this.checked = false;
            this.dataset.wasChecked = 'false';
            form.submit();
          }
        });

        radio.addEventListener('change', function () {
          radios.forEach((r) => { r.dataset.wasChecked = 'false'; });
          if (this.checked) {
            this.dataset.wasChecked = 'true';
            form.submit();
          }
        });
      });
    })();
  </script>

  <!-- Grid de noticias -->
  <?php if ($query->have_posts()): ?>
    <div class="mt-8 grid grid-cols-1 gap-6 md:mt-10 md:grid-cols-2 md:gap-8 xl:grid-cols-3">
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php get_template_part('components/noticias/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>

    <!-- Paginación -->
    <?php
    $big = 999999999;
    $pages = paginate_links(array(
      'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format'    => '?paged=%#%',
      'current'   => max(1, $paged),
      'total'     => $query->max_num_pages,
      'prev_text' => '<svg class="h-6 w-6 xl:h-8 xl:w-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12L12 5M5 12L12 19"/></svg>',
      'next_text' => '<svg class="h-6 w-6 xl:h-8 xl:w-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M19 12l-7-7M19 12l-7 7"/></svg>',
      'type'      => 'array',
    ));

    if (is_array($pages)) {
      $prev_link = '<div class="h-8 w-8 xl:h-10 xl:w-10"></div>';
      $next_link = '<div class="h-8 w-8 xl:h-10 xl:w-10"></div>';
      $number_links = array();

      foreach ($pages as $page) {
        if (strpos($page, 'prev page-numbers') !== false) {
          $prev_link = str_replace('prev page-numbers', 'flex h-8 w-8 items-center justify-center rounded-full text-dark transition-colors hover:bg-gray-100 xl:h-10 xl:w-10', $page);
        } elseif (strpos($page, 'next page-numbers') !== false) {
          $next_link = str_replace('next page-numbers', 'flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-dark transition-colors hover:bg-gray-200 xl:h-10 xl:w-10', $page);
        } else {
          if (strpos($page, 'current') !== false) {
            $page = str_replace('page-numbers current', 'flex h-8 w-8 items-center justify-center rounded-full bg-dark text-base font-bold text-white xl:h-10 xl:w-10 xl:text-2xl', $page);
          } else {
            $page = str_replace('page-numbers', 'flex h-8 w-8 items-center justify-center rounded-full text-base font-bold text-dark transition-colors hover:bg-gray-100 xl:h-10 xl:w-10 xl:text-2xl', $page);
          }
          $number_links[] = $page;
        }
      }

      echo '<div class="mt-10 mb-8 flex w-full items-center justify-between md:mt-12 md:mb-12 xl:mt-14 xl:mb-16">';
      echo $prev_link;
      echo '<div class="flex min-w-0 items-center gap-1 md:gap-3 xl:gap-6">';
      echo implode('', $number_links);
      echo '</div>';
      echo $next_link;
      echo '</div>';
    }
    ?>
  <?php else: ?>
    <div class="mt-16 mb-16 text-center text-gray-500">
      <p class="text-lg"><?php echo esc_html(gf_e('noticias.none')); ?></p>
    </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
