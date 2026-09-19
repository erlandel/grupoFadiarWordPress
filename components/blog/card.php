<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$title = gf_get_post_title($post_id);
$permalink = get_permalink($post_id);
$thumbnail = get_the_post_thumbnail_url($post_id, 'large');
$intro = gf_get_field('intro_blog', $post_id);
$date = gf_get_field('fecha_blog', $post_id);
?>
<a href="<?php echo esc_url($permalink); ?>" class="reveal-item flex flex-col h-full group">
  <div class="relative overflow-hidden rounded-xl aspect-video bg-gray-200">
    <?php if ($thumbnail): ?>
      <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
    <?php endif; ?>
  </div>
  <div class="pt-4 flex flex-col flex-1">
    <div class="flex items-center justify-between gap-4 text-sm text-[#8C8C8C]">
      <span class="inline-block w-fit bg-[#F4F4F4] tracking-wider px-3 py-2 rounded-full"><?php echo esc_html(gf_e('blog.tag')); ?></span>
      <?php if ($date): ?><time><?php echo esc_html($date); ?></time><?php endif; ?>
    </div>
    <h2 class="text-2xl font-bold text-dark leading-tight mt-4 group-hover:underline"><?php echo esc_html($title); ?></h2>
    <?php if ($intro): ?><p class="text-[#4A4A4A] leading-relaxed mt-3 line-clamp-3"><?php echo esc_html($intro); ?></p><?php endif; ?>
  </div>
  <div class="w-full border-t-4 border-[#F4F4F4] mt-5"></div>
</a>
