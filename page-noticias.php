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
<div class="mx-20 mt-10">

  <!-- Breadcrumb -->
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p>Noticias</p>
  </div>

  <!-- Título -->
  <div class="mt-10">
    <h1 class="text-5xl md:text-5xl font-bold text-dark">
      <?php echo esc_html(get_option('noticias_page_title', 'Noticias')); ?>
    </h1>
  </div>

  <!-- Subtítulo + Filtro -->
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mt-10">
    <p class="text-xl md:text-3xl font-bold text-dark max-w-3xl">
      <?php echo esc_html(get_option('noticias_page_subtitle', 'Mantente al día con las últimas novedades, lanzamientos y eventos de Grupo Fadiar.')); ?>
    </p>

    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
      <button type="button" data-filter-open class="relative flex items-center gap-2 shrink-0 cursor-pointer group">
        <span class="text-2xl font-medium text-dark whitespace-nowrap">Filtrar por:</span>
        <svg class="pointer-events-none h-8 w-8 text-dark transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>

      <div data-filter-modal class="fixed inset-0 z-100 hidden items-center justify-center bg-black/40">
        <div class="bg-[#F8F8F8] rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" role="dialog" aria-modal="true" aria-labelledby="filter-modal-title">
          <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <h2 id="filter-modal-title" class="text-3xl font-bold text-dark">Categorías</h2>
            <button type="button" data-filter-close class="text-dark hover:opacity-70 transition-opacity cursor-pointer" aria-label="Cerrar">
              <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
          </div>

          <form method="get" action="<?php echo esc_url($base_url); ?>" class="px-6 pb-6" data-filter-form>
            <div class="flex flex-col">
              <label class="flex items-center gap-4 py-4 px-2 -mx-2 border-b-3 border-[#EDEDED] cursor-pointer text-2xl text-dark hover:bg-gray-200 rounded transition-colors">
                <input type="radio" name="categoria" value="" <?php checked($current_category, 0); ?> class="h-5 w-5 accent-dark cursor-pointer shrink-0">
                <span class="<?php echo $current_category == 0 ? 'font-bold' : 'font-normal'; ?>">Todas las categorías</span>
              </label>
              <?php foreach ($categories as $cat): ?>
                <label class="flex items-center gap-4 py-4 px-2 -mx-2 border-b-3 border-[#EDEDED] last:border-0 cursor-pointer text-2xl text-dark hover:bg-gray-200 rounded transition-colors">
                  <input type="radio" name="categoria" value="<?php echo esc_attr($cat->term_id); ?>" <?php checked($current_category, $cat->term_id); ?> class="h-5 w-5 accent-dark cursor-pointer shrink-0">
                  <span class="<?php echo $current_category == $cat->term_id ? 'font-bold' : 'font-normal'; ?>"><?php echo esc_html($cat->name); ?></span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">
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
      'prev_text' => '<svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12L12 5M5 12L12 19"/></svg>',
      'next_text' => '<svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M19 12l-7-7M19 12l-7 7"/></svg>',
      'type'      => 'array',
    ));

    if (is_array($pages)) {
      $prev_link = '<div class="w-10 h-10"></div>';
      $next_link = '<div class="w-10 h-10"></div>';
      $number_links = array();

      foreach ($pages as $page) {
        if (strpos($page, 'prev page-numbers') !== false) {
          $prev_link = str_replace('prev page-numbers', 'flex items-center justify-center w-10 h-10 text-dark hover:bg-gray-100 rounded-full transition-colors', $page);
        } elseif (strpos($page, 'next page-numbers') !== false) {
          $next_link = str_replace('next page-numbers', 'flex items-center justify-center w-10 h-10 bg-gray-100 text-dark hover:bg-gray-200 rounded-full transition-colors', $page);
        } else {
          if (strpos($page, 'current') !== false) {
            $page = str_replace('page-numbers current', 'flex items-center justify-center w-10 h-10 bg-dark text-white font-bold rounded-full text-2xl', $page);
          } else {
            $page = str_replace('page-numbers', 'flex items-center justify-center w-10 h-10 text-dark font-bold hover:bg-gray-100 rounded-full transition-colors text-2xl', $page);
          }
          $number_links[] = $page;
        }
      }

      echo '<div class="flex justify-between items-center w-full mt-14 mb-16">';
      echo $prev_link;
      echo '<div class="flex items-center gap-6">';
      echo implode('', $number_links);
      echo '</div>';
      echo $next_link;
      echo '</div>';
    }
    ?>
  <?php else: ?>
    <div class="mt-16 mb-16 text-center text-gray-500">
      <p class="text-lg">No hay noticias publicadas aún.</p>
    </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>