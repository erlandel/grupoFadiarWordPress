<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$title = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$permalink = get_permalink($post_id);
$thumbnail = get_the_post_thumbnail_url($post_id, 'large');
$categories = wp_get_post_terms($post_id, 'categoria_noticia');
$category_label = !empty($categories) ? $categories[0]->name : '';
?>
<div class="group relative overflow-hidden rounded-xl bg-white shadow-sm hover:shadow-lg transition-shadow duration-300 border border-gray-300/40 flex flex-col h-full">
  <div class="relative overflow-hidden aspect-[4/3]">
    <?php if ($thumbnail): ?>
      <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
    <?php else: ?>
      <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-sm">Sin imagen</div>
    <?php endif; ?>
    <?php if ($category_label): ?>
      <span class="absolute top-4 left-4 bg-primary text-dark text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider"><?php echo esc_html($category_label); ?></span>
    <?php endif; ?>
  </div>
  <div class="p-6 flex flex-col flex-1">
    <h3 class="text-lg font-bold text-dark leading-tight mb-3 group-hover:text-secondary transition-colors">
      <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
    </h3>
    <?php if ($excerpt): ?>
      <p class="text-sm text-gray-600 line-clamp-3 flex-1"><?php echo esc_html(wp_trim_words($excerpt, 20)); ?></p>
    <?php endif; ?>
  </div>
</div>