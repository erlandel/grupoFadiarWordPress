<?php
get_header();

$post_id = get_the_ID();
$title = get_the_title();
$fecha = get_field('fecha_noticia');
$categories = wp_get_post_terms($post_id, 'categoria_noticia');

$thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');

$cat_term_id = 0;
$category_name = '';
$prev_id = null;
$next_id = null;
$current_pos = 1;
$total_in_cat = 1;

if (!empty($categories) && !is_wp_error($categories)) {
  $cat_term_id = $categories[0]->term_id;
  $category_name = $categories[0]->name;

  $all_ids = get_posts(array(
    'post_type'      => 'noticia',
    'posts_per_page' => -1,
    'tax_query'      => array(array(
      'taxonomy' => 'categoria_noticia',
      'field'    => 'term_id',
      'terms'    => $cat_term_id,
    )),
    'orderby'        => 'date',
    'order'          => 'DESC',
    'fields'         => 'ids',
  ));

  $total_in_cat = count($all_ids);
  $current_index = array_search($post_id, $all_ids);

  if ($current_index !== false && $total_in_cat > 1) {
    $prev_index = ($current_index - 1 + $total_in_cat) % $total_in_cat;
    $next_index = ($current_index + 1) % $total_in_cat;
    $prev_id = $all_ids[$prev_index];
    $next_id = $all_ids[$next_index];
    $current_pos = $current_index + 1;
  }

  $related_args = array(
    'post_type'      => 'noticia',
    'posts_per_page' => 3,
    'post__not_in'   => array($post_id),
    'tax_query'      => array(
      array(
        'taxonomy' => 'categoria_noticia',
        'field'    => 'term_id',
        'terms'    => wp_list_pluck($categories, 'term_id'),
      ),
    ),
  );
  $related_query = new WP_Query($related_args);
}
?>
<div class="mx-20 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><a href="<?php echo home_url('/noticias/'); ?>">Noticias</a></p>
  </div>
</div>

<div class="mx-20 mt-10 flex flex-col lg:flex-row gap-10">
  <div class="lg:w-9/12">
    <?php if ($thumbnail_url): ?>
      <div id="hero-share"
           class="relative w-full overflow-hidden rounded-xl aspect-video bg-gray-100"
           data-share-title="<?php echo esc_attr(get_the_title()); ?>"
           data-share-url="<?php echo esc_attr(get_permalink()); ?>"
           data-share-date="<?php echo esc_attr($fecha ?: get_the_date('d/m/Y')); ?>"
           data-share-author="<?php echo esc_attr(get_field('autor') ?: ''); ?>"
           data-share-image="<?php echo esc_url($thumbnail_url); ?>">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" />
        <button data-share-btn type="button" aria-label="Compartir noticia"
                class="absolute z-10 right-4 bottom-4 flex h-12 w-12 items-center justify-center rounded-full bg-white/40 hover:bg-white/60 text-dark transition cursor-pointer">
          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        </button>
      </div>
    <?php endif; ?>

    <div class="mt-6">
      <?php if ($categories): ?>
        <!-- <span class="inline-block bg-primary text-dark text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4"><?php echo esc_html($categories[0]->name); ?></span> -->
      <?php endif; ?>
      <h1 class="text-3xl font-bold text-dark leading-tight"><?php echo esc_html($title); ?></h1>
    
      <div class="flex items-center gap-4 mt-3 text-xl text-dark">
        <span><?php echo esc_html($fecha ?: get_the_date('d/m/Y')); ?></span>
      </div>
      <?php $autor = get_field('autor'); if ($autor): ?>
        <p class="italic font-semibold text-dark text-xl mt-1"><?php echo esc_html($autor); ?></p>
      <?php endif; ?>
    </div>

    <div class="mt-8 text-dark text-xl leading-relaxed space-y-4">
      <?php the_field('descripcion'); ?>
    </div>

    <?php if ($category_name): ?>
      <div class="flex items-center justify-between mt-10">
        <span class="inline-block w-fit bg-[#F4F4F4] text-[#8C8C8C] text-2xl tracking-wider px-4 py-3 rounded-full">
          <?php echo esc_html($category_name); ?>
        </span>

        <div class="flex items-center gap-6">
          <?php if ($prev_id): ?>
            <a href="<?php echo get_permalink($prev_id); ?>" aria-label="Noticia anterior"
               class="flex items-center justify-center w-12 h-12 text-dark hover:opacity-70 transition-opacity">
              <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12L12 5M5 12L12 19"/></svg>
            </a>
          <?php endif; ?>
          <span class="text-2xl font-medium text-dark"><?php echo $current_pos; ?>/<?php echo $total_in_cat; ?></span>
          <?php if ($next_id): ?>
            <a href="<?php echo get_permalink($next_id); ?>" aria-label="Noticia siguiente"
               class="flex items-center justify-center w-12 h-12 bg-gray-100 text-dark hover:bg-gray-200 rounded-full transition-colors">
              <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M19 12l-7-7M19 12l-7 7"/></svg>
            </a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <aside class="lg:w-3/12">
    <?php
    $all_categories = get_terms(array('taxonomy' => 'categoria_noticia', 'hide_empty' => true));
    $first_by_cat = array();
    if (!empty($all_categories) && !is_wp_error($all_categories)):
      foreach ($all_categories as $sidebar_cat) {
        $first_post = get_posts(array(
          'post_type'      => 'noticia',
          'posts_per_page' => 1,
          'tax_query'      => array(array(
            'taxonomy' => 'categoria_noticia',
            'field'    => 'term_id',
            'terms'    => $sidebar_cat->term_id,
          )),
          'orderby'  => 'date',
          'order'    => 'DESC',
          'fields'   => 'ids',
        ));
        $first_by_cat[$sidebar_cat->term_id] = !empty($first_post) ? get_permalink($first_post[0]) : '#';
      }
    ?>
      <div class="bg-[#F8F8F8] w-full rounded-xl p-6 pb-10 border border-gray-200">
        <h3 class="text-3xl font-bold text-dark mb-4">Categorías</h3>
        <ul class="space-y-2">
          <li class="border-b-3 border-[#EDEDED]">
            <a href="<?php echo home_url('/noticias/'); ?>" class="block text-xl text-dark transition px-3 py-2 rounded-md hover:bg-gray-200 <?php echo empty($categories) ? 'font-bold' : ''; ?>">Todas las Noticias</a>
          </li>
          <?php foreach ($all_categories as $cat): ?>
            <li class="border-b-3 border-[#EDEDED]">
              <a href="<?php echo esc_url($first_by_cat[$cat->term_id] ?? '#'); ?>" class="block text-xl text-dark transition px-3 py-2 rounded-md hover:bg-gray-200 <?php echo (isset($categories[0]) && $cat->term_id == $categories[0]->term_id) ? 'font-bold' : ''; ?>"><?php echo esc_html($cat->name); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>

</div>

<?php if (isset($related_query) && $related_query->have_posts()): ?>
  <div class="mx-15 mt-20 mb-20">
    <h2 class="text-3xl font-bold text-dark mb-8">También te puede interesar</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
        <?php get_template_part('components/noticias/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
<?php endif; ?>

<?php get_footer(); ?>
