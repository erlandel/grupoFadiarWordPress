<?php
get_header();

$post_id = get_the_ID();
$title = get_the_title();
$content = apply_filters('the_content', get_the_content());
$date = get_the_date('d/m/Y');
$author_name = get_the_author();
$categories = wp_get_post_terms($post_id, 'categoria_noticia');

$thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
$gallery_images = array();
if ($thumbnail_url) {
  $gallery_images[] = array(
    'url' => $thumbnail_url,
    'alt' => get_the_title(),
  );
}

while (have_rows('galeria_noticia')): the_row();
  $image = get_sub_field('imagen');
  if ($image) {
    $gallery_images[] = array(
      'url' => $image['url'],
      'alt' => $image['alt'] ?? '',
    );
  }
endwhile;

if (!empty($categories) && !is_wp_error($categories)) {
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
<div class="mx-15 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><a href="<?php echo home_url('/noticias/'); ?>">Noticias</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p class="text-gray-500 truncate max-w-xs"><?php echo esc_html($title); ?></p>
  </div>
</div>

<div class="mx-15 mt-10 grid grid-cols-1 lg:grid-cols-3 gap-10">
  <div class="lg:col-span-2">
    <?php if (!empty($gallery_images)): ?>
      <?php get_template_part('components/noticias/gallery', null, array('images' => $gallery_images)); ?>
    <?php endif; ?>

    <div class="mt-6">
      <?php if ($categories): ?>
        <span class="inline-block bg-primary text-dark text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4"><?php echo esc_html($categories[0]->name); ?></span>
      <?php endif; ?>
      <h1 class="text-4xl font-bold text-dark leading-tight"><?php echo esc_html($title); ?></h1>
      <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
        <span><?php echo esc_html($date); ?></span>
        <?php if ($author_name): ?>
          <span class="flex items-center gap-1">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Por <?php echo esc_html($author_name); ?>
          </span>
        <?php endif; ?>
      </div>
    </div>

    <div class="mt-8 text-dark text-lg leading-relaxed space-y-4">
      <?php echo $content; ?>
    </div>
  </div>

  <aside class="lg:col-span-1">
    <?php
    $all_categories = get_terms(array('taxonomy' => 'categoria_noticia', 'hide_empty' => false));
    if (!empty($all_categories) && !is_wp_error($all_categories)):
    ?>
      <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-dark mb-4">Categorías</h3>
        <ul class="space-y-2">
          <li>
            <a href="<?php echo home_url('/noticias/'); ?>" class="block text-sm text-gray-600 hover:text-secondary transition px-3 py-2 rounded-md hover:bg-white">Todas las Noticias</a>
          </li>
          <?php foreach ($all_categories as $cat): ?>
            <li>
              <a href="<?php echo esc_url(add_query_arg('categoria', $cat->term_id, home_url('/noticias/'))); ?>" class="block text-sm text-gray-600 hover:text-secondary transition px-3 py-2 rounded-md hover:bg-white"><?php echo esc_html($cat->name); ?></a>
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