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
$modal_options = array(
  array(
    'value' => '',
    'label' => gf_e('noticias.filter_all'),
    'url'   => $base_url,
  ),
);
if (!empty($categories) && !is_wp_error($categories)) {
  foreach ($categories as $cat) {
    $modal_options[] = array(
      'value' => (string) $cat->term_id,
      'label' => gf_get_term_name($cat),
      'url'   => add_query_arg('categoria', $cat->term_id, $base_url),
    );
  }
}
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
      <?php get_template_part('components/modalCategory/modalCategory', null, array(
        'prefix' => 'filter',
        'instance_id' => 'noticias-filter',
        'trigger_label' => gf_e('noticias.filter_label'),
        'modal_title' => gf_e('noticias.filter_categories'),
        'close_label' => gf_e('noticias.filter_close'),
        'selected_value' => $current_category > 0 ? (string) $current_category : '',
        'options' => $modal_options,
      )); ?>
    <?php endif; ?>
  </div>

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
