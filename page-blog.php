<?php
get_header();

$paged = max(1, get_query_var('paged'));
$current_category = isset($_GET['categoria']) ? absint(wp_unslash($_GET['categoria'])) : 0;

$blog_ids = get_posts(array(
  'post_type'      => 'blog',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'fields'         => 'ids',
  'no_found_rows'  => true,
));

$categories = !empty($blog_ids) ? get_terms(array(
  'taxonomy'   => 'categoria_noticia',
  'hide_empty' => true,
  'object_ids' => $blog_ids,
)) : array();

$query_args = array(
  'post_type'      => 'blog',
  'post_status'    => 'publish',
  'posts_per_page' => 6,
  'paged'          => $paged,
);

if ($current_category > 0) {
  $query_args['tax_query'] = array(array(
    'taxonomy' => 'categoria_noticia',
    'field'    => 'term_id',
    'terms'    => $current_category,
  ));
}

$modal_options = array(
  array(
    'value' => '',
    'label' => gf_e('blog.filter_all'),
    'url'   => get_post_type_archive_link('blog'),
  ),
);

if (!empty($categories) && !is_wp_error($categories)) {
  foreach ($categories as $category) {
    $modal_options[] = array(
      'value' => (string) $category->term_id,
      'label' => gf_get_term_name($category),
      'url'   => add_query_arg('categoria', $category->term_id, get_post_type_archive_link('blog')),
    );
  }
}

$query = new WP_Query($query_args);
?>
<main class="reveal-section mx-6 md:mx-15 xl:mx-30 mt-7 mb-20">
  <div class="flex items-center text-sm xl:text-base">
    <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <?php echo get_icon('chevron-right', 'mx-1 h-4 w-4 xl:h-6 xl:w-6'); ?>
    <p><?php echo esc_html(gf_e('blog.breadcrumb')); ?></p>
  </div>

  <div class="mt-7 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div class="max-w-3xl">
      <h1 class="reveal-item text-3xl font-bold text-dark md:text-4xl"><?php echo esc_html(gf_e('blog.title')); ?></h1>
      <p class="reveal-item mt-5 text-xl font-bold text-dark md:text-2xl"><?php echo esc_html(gf_e('blog.subtitle')); ?></p>
    </div>
    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
      <?php get_template_part('components/modalCategory/modalCategory', null, array(
        'prefix' => 'filter',
        'instance_id' => 'blog-filter',
        'trigger_label' => gf_e('blog.filter_label'),
        'modal_title' => gf_e('blog.filter_categories'),
        'close_label' => gf_e('blog.filter_close'),
        'selected_value' => $current_category > 0 ? (string) $current_category : '',
        'options' => $modal_options,
      )); ?>
    <?php endif; ?>
  </div>

  <?php if ($query->have_posts()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php get_template_part('components/blog/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php
    $pages = paginate_links(array(
       'base' => str_replace(999999999, '%#%', esc_url(remove_query_arg('categoria', get_pagenum_link(999999999)))),
      'format' => '?paged=%#%',
      'current' => $paged,
      'total' => $query->max_num_pages,
      'type' => 'array',
      'prev_text' => '&larr;<span class="sr-only">' . esc_html(gf_e('blog.previous')) . '</span>',
       'next_text' => '<span class="sr-only">' . esc_html(gf_e('blog.next')) . '</span>&rarr;',
       'add_args' => $current_category > 0 ? array('categoria' => $current_category) : array(),
    ));
    if (is_array($pages)): ?>
      <nav class="flex justify-center items-center gap-3 mt-14" aria-label="<?php echo esc_attr(gf_e('blog.title')); ?>">
        <?php foreach ($pages as $page): ?>
          <?php echo str_replace(array('page-numbers', 'current'), array('flex items-center justify-center min-w-10 h-10 px-3 rounded-full font-bold text-dark hover:bg-gray-100 transition-colors', 'bg-dark text-white hover:bg-dark'), $page); ?>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>
  <?php else: ?>
    <p class="mt-16 mb-16 text-center text-lg text-gray-500"><?php echo esc_html(gf_e('blog.none')); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
