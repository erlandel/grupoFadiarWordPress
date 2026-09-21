<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$show_date = empty($args['hide_date']);
$title = gf_get_post_title($post_id);
$permalink = get_permalink($post_id);
$main_image = get_field('imagen_principal_blog', $post_id);
$thumbnail = $main_image && !empty($main_image['url'])
  ? $main_image['url']
  : get_the_post_thumbnail_url($post_id, 'large');
$image_alt = $main_image && !empty($main_image['alt']) ? $main_image['alt'] : $title;
$intro = gf_get_field('intro_blog', $post_id);
$date = gf_get_field('fecha_blog', $post_id);
$category = null;
$category_terms = wp_get_post_terms($post_id, 'categoria_noticia');
if (!empty($category_terms) && !is_wp_error($category_terms)) {
  $category = $category_terms[0];
} else {
  $category_field = get_field('categoria_blog', $post_id);
  if (is_array($category_field) && !empty($category_field)) {
    $category = $category_field[0];
  } elseif (is_object($category_field)) {
    $category = $category_field;
  } elseif (is_numeric($category_field)) {
    $category = get_term((int) $category_field, 'categoria_noticia');
  }
}
$category_label = $category ? gf_get_term_name($category) : '';
?>
<a href="<?php echo esc_url($permalink); ?>" class="reveal-item flex flex-col h-full group">
  <div class="relative overflow-hidden rounded-xl aspect-video bg-gray-200">
    <?php if ($thumbnail): ?>
      <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
    <?php endif; ?>
  </div>
  <div class="pt-4 flex flex-col flex-1">
    <div class="flex items-center justify-between gap-4 text-sm text-[#8C8C8C]">
      <?php if ($category_label): ?>
        <span class="inline-block w-fit bg-[#EBE8E8] text-[#727272] tracking-wider px-3 py-2 rounded-full"><?php echo esc_html($category_label); ?></span>
      <?php endif; ?>
      <?php if ($show_date && $date): ?><time><?php echo esc_html($date); ?></time><?php endif; ?>
    </div>
    <h2 class="text-2xl font-bold text-dark leading-tight mt-4 group-hover:underline"><?php echo esc_html($title); ?></h2>
    <?php if ($intro): ?><p class="text-[#4A4A4A] leading-relaxed mt-3 line-clamp-3"><?php echo esc_html($intro); ?></p><?php endif; ?>
  </div>
  <div class="w-full border-t-4 border-[#F4F4F4] mt-5"></div>
</a>
