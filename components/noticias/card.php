<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$thumbnail = get_the_post_thumbnail_url($post_id, 'large');
$categories = wp_get_post_terms($post_id, 'categoria_noticia');
$category_label = !empty($categories) ? $categories[0]->name : '';
$intro = get_field('intro_noticia', $post_id);
?>
<a href="<?php echo esc_url($permalink); ?>" class="flex flex-col h-full group">
  <!-- Imagen -->
  <div class="relative overflow-hidden rounded-xl aspect-video ">
    <?php if ($thumbnail): ?>
      <img
        src="<?php echo esc_url($thumbnail); ?>"
        alt="<?php echo esc_attr($title); ?>"
        class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
      />
    <?php else: ?>
      <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-sm">
        Sin imagen
      </div>
    <?php endif; ?>
  </div>

  <!-- Categoría -->
  <span class="inline-block w-fit bg-[#F4F4F4] text-[#8C8C8C] text-2xl tracking-wider px-4 py-2.5 rounded-full mt-4">
    <?php echo $category_label ? esc_html($category_label) : 'Categoría'; ?>
  </span>

  <!-- Contenido -->
  <div class="pt-3 flex flex-col flex-1">
    <h3 class="text-3xl font-bold text-dark leading-tight group-hover:underline">
      <?php echo esc_html($title); ?>
    </h3>
    <?php if ($intro): ?>
      <p class="text-xl text-[#4A4A4A] leading-relaxed mt-3 line-clamp-3">
        <?php echo esc_html($intro); ?>
      </p>
    <?php endif; ?>
  </div>
  <div class="w-full border-t-4 border-[#F4F4F4] mt-5"></div>
</a>