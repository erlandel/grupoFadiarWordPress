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
<section id="supportHome" class="scroll-mt-24 w-full bg-white px-0  mb-10 xl:px-4 xl:py-16">
  <div class="mx-8 xl:mx-30">
    <div class="mb-8 text-center xl:mb-16">
      <h3 class="reveal-item mb-3 text-lg font-semibold text-secondary xl:mb-4 xl:text-2xl"><?php echo esc_html($section_title); ?></h3>
      <h2 class="reveal-item mb-6 text-2xl font-black leading-tight text-dark xl:mb-8 xl:text-4xl"><?php echo esc_html($section_subtitle); ?></h2>
    </div>
    <?php if ($support_items): ?>
    <div data-reveal-pairs="2" class="grid grid-cols-1 gap-8 md:grid-cols-2">
      <?php foreach ($support_items as $item):
        $item_id = $item->ID;
        $image = get_field('support_item_image', $item_id);
        $description = gf_get_field('support_item_description', $item_id);
      ?>
        <div class="reveal-item grid grid-cols-[2.75rem_1fr] items-center gap-x-4 gap-y-3 xl:flex xl:items-start xl:gap-4">
          <div class="row-start-1 h-11 w-11 shrink-0 overflow-hidden rounded xl:h-15 xl:w-15">
            <?php if ($image): ?>
              <img src="<?php echo esc_url($image['url']); ?>"
                   alt="<?php echo esc_attr($image['alt'] ?: gf_get_post_title($item_id)); ?>"
                   class="w-full h-full object-contain" />
            <?php endif; ?>
          </div>
          <div class="contents xl:mt-4 xl:mb-4 xl:flex xl:flex-col xl:gap-3">
            <h3 class="col-start-2 text-lg font-bold text-dark xl:text-2xl"><?php echo esc_html(gf_get_post_title($item_id)); ?></h3>
            <?php if ($description): ?>
              <p class="col-span-2 text-md leading-tight text-dark xl:text-lg xl:leading-relaxed"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
