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
      <div class="relative flex items-center gap-2 shrink-0 cursor-pointer group" tabindex="0">
        <span class="text-2xl font-medium text-dark whitespace-nowrap">Filtrar por:</span>
        <svg class="pointer-events-none h-8 w-8 text-dark group-focus-within:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        
        <div class="absolute top-full right-0 mt-2 min-w-[280px] bg-[#F8F8F8] rounded-xl opacity-0 invisible group-focus-within:opacity-100 group-focus-within:visible transition-all duration-200 z-50 overflow-hidden flex flex-col shadow-lg border border-gray-100">
          <div class="py-4">
            <a href="<?php echo esc_url($base_url); ?>" class="block px-6 text-2xl font-bold text-dark mb-2 hover:text-opacity-80 transition-colors">Categorías</a>
            <div class="flex flex-col">
              <?php foreach ($categories as $cat): ?>
                <div class="border-b border-gray-200 last:border-0 mx-6">
                  <a href="<?php echo esc_url(add_query_arg('categoria', $cat->term_id, $base_url)); ?>" class="block py-3 px-2 -mx-2 text-lg text-dark hover:bg-gray-200 rounded transition-colors <?php echo $current_category == $cat->term_id ? 'font-bold' : 'font-normal'; ?>">
                    <?php echo esc_html($cat->name); ?>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

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