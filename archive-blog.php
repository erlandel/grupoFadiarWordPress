<?php
get_header();

$paged = max(1, get_query_var('paged'));
$query = new WP_Query(array(
  'post_type' => 'blog',
  'posts_per_page' => 6,
  'paged' => $paged,
));
?>
<main class="reveal-section mx-6 md:mx-15 xl:mx-30 mt-7 mb-20">
  <div class="flex text-lg">
    <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <?php echo get_icon('chevron-right', 'h-6 w-6 mx-1'); ?>
    <p><?php echo esc_html(gf_e('blog.breadcrumb')); ?></p>
  </div>

  <div class="mt-7 max-w-3xl">
    <h1 class="reveal-item text-3xl md:text-4xl font-bold text-dark"><?php echo esc_html(gf_e('blog.title')); ?></h1>
    <p class="reveal-item text-xl md:text-2xl font-bold text-dark mt-5"><?php echo esc_html(gf_e('blog.subtitle')); ?></p>
  </div>

  <?php if ($query->have_posts()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php get_template_part('components/blog/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php
    $pages = paginate_links(array(
      'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
      'format' => '?paged=%#%',
      'current' => $paged,
      'total' => $query->max_num_pages,
      'type' => 'array',
      'prev_text' => '&larr;<span class="sr-only">' . esc_html(gf_e('blog.previous')) . '</span>',
      'next_text' => '<span class="sr-only">' . esc_html(gf_e('blog.next')) . '</span>&rarr;',
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
