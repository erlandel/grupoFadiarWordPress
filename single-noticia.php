<?php
get_header();

$post_id = get_the_ID();
$title = gf_get_post_title();
$fecha = gf_get_field('fecha_noticia');
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
  $category_name = gf_get_term_name($categories[0]);

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

$all_categories = get_terms(array('taxonomy' => 'categoria_noticia', 'hide_empty' => true));
$first_by_cat = array();
if (!empty($all_categories) && !is_wp_error($all_categories)) {
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
}
?>
<div class="reveal-section mx-8 mt-7 md:mx-15 xl:mx-30 xl:mt-10">
  <div class="flex items-center text-sm xl:text-base">
    <p><a href="<?php echo home_url('/'); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <svg class="mx-1 h-4 w-4 xl:h-6 xl:w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><a href="<?php echo home_url('/noticias/'); ?>">Actualidad</a></p>
  </div>

  <?php if (!empty($all_categories) && !is_wp_error($all_categories)): ?>
    <div class="mt-4">
      <?php
      $modal_options = array(
        array(
          'value' => '',
          'label' => gf_e('noticias.filter_all'),
          'url'   => home_url('/noticias/'),
        ),
      );
      foreach ($all_categories as $cat) {
        $modal_options[] = array(
          'value' => (string) $cat->term_id,
          'label' => gf_get_term_name($cat),
          'url'   => $first_by_cat[$cat->term_id] ?? '#',
        );
      }
      get_template_part('components/modalCategory/modalCategory', null, array(
        'prefix' => 'category',
        'instance_id' => 'single-noticia-categories',
        'trigger_label' => gf_e('noticias.category_label'),
        'modal_title' => gf_e('noticias.sidebar_title'),
        'close_label' => gf_e('noticias.filter_close'),
        'selected_value' => isset($categories[0]) ? (string) $categories[0]->term_id : '',
        'options' => $modal_options,
        'mobile_only' => true,
      ));
      ?>
    </div>
  <?php endif; ?>
</div>

<div class="reveal-section mx-8 mt-8 mb-14 flex flex-col gap-8 md:mx-15 md:mt-10 md:mb-16 xl:mx-30 xl:mb-20 xl:flex-row xl:gap-10">
  <div class="w-full xl:w-9/12">
    <?php if ($thumbnail_url): ?>
      <div id="hero-share"
           class="reveal-item relative w-full overflow-hidden rounded-xl aspect-video bg-gray-100"
           data-share-title="<?php echo esc_attr(gf_get_post_title()); ?>"
           data-share-url="<?php echo esc_attr(get_permalink()); ?>"
           data-share-date="<?php echo esc_attr($fecha ?: get_the_date('d/m/Y')); ?>"
           data-share-author="<?php echo esc_attr(gf_get_field('autor') ?: ''); ?>"
           data-share-image="<?php echo esc_url($thumbnail_url); ?>">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" />
        <button data-share-btn type="button" aria-label="Compartir noticia"
                class="absolute bottom-3 right-3 z-10 flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-white/40 text-dark transition hover:bg-white/60 xl:bottom-4 xl:right-4 xl:h-12 xl:w-12">
          <svg class="h-5 w-5 xl:h-6 xl:w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        </button>
      </div>
    <?php endif; ?>

    <div class="reveal-item mt-6">
      <?php if ($categories): ?>
        <!-- <span class="inline-block bg-primary text-dark text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4"><?php echo esc_html($categories[0]->name); ?></span> -->
      <?php endif; ?>
      <h1 class="text-3xl font-bold leading-tight text-dark md:text-4xl"><?php echo esc_html($title); ?></h1>
    
      <div class="mt-3 flex flex-wrap items-center gap-4 text-base text-dark md:text-lg xl:text-xl">
        <span><?php echo esc_html($fecha ?: get_the_date('d/m/Y')); ?></span>
      </div>
      <?php $autor = gf_get_field('autor'); if ($autor): ?>
        <p class="mt-1 text-base font-semibold italic text-dark md:text-lg xl:text-xl"><?php echo esc_html($autor); ?></p>
      <?php endif; ?>
    </div>

    <div class="reveal-item mt-8 space-y-4 text-base leading-relaxed text-dark [wrap-anywhere] [&_a]:underline [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-bold [&_img]:h-auto [&_img]:max-w-full [&_ol]:my-5 [&_ol]:list-decimal [&_ol]:pl-6 [&_ul]:my-5 [&_ul]:list-disc [&_ul]:pl-6 [&_li]:mb-2 md:text-lg xl:text-xl">
      <?php echo wp_kses_post(gf_get_field('descripcion', $post_id)); ?>
    </div>

    <?php if ($category_name): ?>
      <div class="reveal-item mt-8 flex flex-col items-start gap-4 sm:mt-10 sm:flex-row sm:items-center sm:justify-between">
        <span class="inline-block w-fit rounded-full bg-[#F4F4F4] px-3 py-2 text-sm tracking-wider text-[#8C8C8C] md:px-4 md:py-3 md:text-base">
          <?php echo esc_html($category_name); ?>
        </span>

        <div class="flex w-full items-center justify-between gap-2 sm:w-auto sm:justify-start sm:gap-4 xl:gap-6">
          <?php if ($prev_id): ?>
            <a href="<?php echo get_permalink($prev_id); ?>" aria-label="Noticia anterior"
                class="flex h-11 w-11 items-center justify-center text-dark transition-opacity hover:opacity-70 xl:h-12 xl:w-12">
               <svg class="h-6 w-6 xl:h-8 xl:w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12L12 5M5 12L12 19"/></svg>
            </a>
          <?php endif; ?>
          <span class="text-lg font-medium text-dark xl:text-2xl"><?php echo $current_pos; ?>/<?php echo $total_in_cat; ?></span>
          <?php if ($next_id): ?>
            <a href="<?php echo get_permalink($next_id); ?>" aria-label="Noticia siguiente"
                class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-dark transition-colors hover:bg-gray-200 xl:h-12 xl:w-12">
               <svg class="h-6 w-6 xl:h-8 xl:w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M19 12l-7-7M19 12l-7 7"/></svg>
            </a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <aside class="reveal-item hidden w-full xl:block xl:w-3/12">
    <?php if (!empty($all_categories) && !is_wp_error($all_categories)): ?>
      <div class="w-full rounded-xl border border-gray-200 bg-[#F8F8F8] p-5 pb-6 md:p-6 md:pb-8 xl:pb-10">
        <h3 class="mb-4 text-xl font-bold text-dark xl:text-2xl"><?php echo esc_html(gf_e('noticias.sidebar_title')); ?></h3>
        <ul class="grid grid-cols-1 gap-2 md:grid-cols-2 xl:block xl:space-y-2">
          <li class="border-b-3 border-[#EDEDED]">
            <a href="<?php echo home_url('/noticias/'); ?>" class="block rounded-md px-3 py-2 text-base text-dark transition hover:bg-gray-200 md:text-lg xl:text-xl <?php echo empty($categories) ? 'font-bold' : ''; ?>"><?php echo esc_html(gf_e('noticias.sidebar_all')); ?></a>
          </li>
          <?php foreach ($all_categories as $cat): ?>
            <li class="border-b-3 border-[#EDEDED]">
              <a href="<?php echo esc_url($first_by_cat[$cat->term_id] ?? '#'); ?>" class="block rounded-md px-3 py-2 text-base text-dark transition hover:bg-gray-200 md:text-lg xl:text-xl <?php echo (isset($categories[0]) && $cat->term_id == $categories[0]->term_id) ? 'font-bold' : ''; ?>"><?php echo esc_html(gf_get_term_name($cat)); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>

</div>

<?php if (isset($related_query) && $related_query->have_posts()): ?>
  <div class="reveal-section mx-8 mb-14 md:mx-15 md:mb-16 xl:mx-15 xl:mb-20">
    <h2 class="reveal-item mb-6 text-3xl font-bold text-dark md:mb-8 md:text-4xl"><?php echo esc_html(gf_e('noticias.related_title')); ?></h2>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
      <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
        <?php get_template_part('components/noticias/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
<?php endif; ?>

<?php get_footer(); ?>
