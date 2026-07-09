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
<div class="mx-15 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p>Noticias</p>
  </div>
  <section class="mt-10">
    <h1 class="text-5xl font-bold text-dark">Noticias</h1>
    <p class="text-xl text-gray-600 mt-2 max-w-2xl">Mantente al día con las últimas novedades, lanzamientos y eventos de Grupo Fadiar.</p>
  </section>

  <?php if (!empty($categories) && !is_wp_error($categories)): ?>
    <div class="mt-8 flex items-center gap-4">
      <label for="categoria-filter" class="text-sm font-semibold text-dark uppercase tracking-wide">Filtrar por:</label>
      <select id="categoria-filter" class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/50" onchange="if(this.value) window.location.href=this.value;">
        <option value="<?php echo esc_url($base_url); ?>">Todas las categorías</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo esc_url(add_query_arg('categoria', $cat->term_id, $base_url)); ?>" <?php selected($current_category, $cat->term_id); ?>>
            <?php echo esc_html($cat->name); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <?php if ($query->have_posts()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php get_template_part('components/noticias/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>

    <div class="flex justify-center items-center gap-2 mt-12">
      <?php
      $big = 999999999;
      echo paginate_links(array(
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => max(1, $paged),
        'total'     => $query->max_num_pages,
        'prev_text' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
        'next_text' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
        'type'      => 'plain',
        'before_page_number' => '<span class="px-3 py-1.5 text-sm font-bold rounded-lg hover:bg-gray-100 transition">',
        'after_page_number'  => '</span>',
      ));
      ?>
    </div>
  <?php else: ?>
    <div class="mt-16 text-center text-gray-500">
      <p class="text-lg">No hay noticias publicadas aún.</p>
    </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>