<?php

$section_title = gf_get_option('support_home_section_title', 'Soporte y Garantía', 'Support and Warranty');
$section_subtitle = gf_get_option('support_home_section_subtitle', 'POR QUÉ ESCOGER GRUPO FADIAR', 'WHY CHOOSE GRUPO FADIAR');

$support_items = get_posts(array(
    'post_type'      => 'support_home_item',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
));
?>
<section id="supportHome" class="scroll-mt-24 w-full py-16 px-4 bg-white">
  <div class="mx-30">
    <div class="text-center mb-16">
      <h3 class="text-secondary font-semibold text-2xl mb-4"><?php echo esc_html($section_title); ?></h3>
      <h2 class="text-3xl md:text-4xl font-black text-dark mb-8"><?php echo esc_html($section_subtitle); ?></h2>
    </div>
    <?php if ($support_items): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <?php foreach ($support_items as $item):
        $item_id = $item->ID;
        $image = get_field('support_item_image', $item_id);
        $description = gf_get_field('support_item_description', $item_id);
      ?>
        <div class="flex gap-4">
          <div class="w-15 h-15 shrink-0 overflow-hidden rounded">
            <?php if ($image): ?>
              <img src="<?php echo esc_url($image['url']); ?>"
                   alt="<?php echo esc_attr($image['alt'] ?: gf_get_post_title($item_id)); ?>"
                   class="w-full h-full object-contain" />
            <?php endif; ?>
          </div>
          <div class="flex flex-col gap-3 mb-4 mt-4">
            <h3 class="text-2xl font-bold text-dark"><?php echo esc_html(gf_get_post_title($item_id)); ?></h3>
            <?php if ($description): ?>
              <p class="text-gray-700 text-lg leading-relaxed"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
