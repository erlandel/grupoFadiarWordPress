<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$title = gf_get_post_title($post_id);
$permalink = get_permalink($post_id);
$thumbnail = get_the_post_thumbnail_url($post_id, 'large');
$categories = wp_get_post_terms($post_id, 'categoria_noticia');
$category_label = !empty($categories) ? gf_get_term_name($categories[0]) : '';
$intro = gf_get_field('intro_noticia', $post_id);
?>
<a href="<?php echo esc_url($permalink); ?>" class="reveal-item flex flex-col h-full group">
  <!-- Imagen -->
  <div class="relative aspect-video overflow-hidden rounded-xl">
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
  <span class="mt-4 inline-block w-fit rounded-full bg-[#F4F4F4] px-3 py-2 text-sm tracking-wider text-[#8C8C8C] md:px-4 md:py-3 md:text-base">
    <?php echo $category_label ? esc_html($category_label) : 'Categoría'; ?>
  </span>

  <!-- Contenido -->
  <div class="flex flex-1 flex-col pt-3">
    <h3 class="text-2xl font-bold leading-tight text-dark group-hover:underline">
      <?php echo esc_html($title); ?>
    </h3>
    <?php if ($intro): ?>
      <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-[#4A4A4A] md:text-base">
        <?php echo esc_html($intro); ?>
      </p>
    <?php endif; ?>
  </div>
  <div class="mt-5 w-full border-t-2 border-[#F4F4F4] xl:border-t-4"></div>
</a>
