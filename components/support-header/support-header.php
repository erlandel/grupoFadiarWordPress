<?php

$support_header_items = get_posts(array(
    'post_type'      => 'support_header_item',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
));

?>
<div class="flex w-full items-center justify-center xl:justify-end">
  <div class="flex w-full items-start justify-between gap-2 sm:gap-4 xl:gap-6">
    <?php if ($support_header_items): ?>
        <?php foreach ($support_header_items as $item): ?>
        <div class="reveal-item flex shrink-0 flex-col items-center text-center">
          <div class="flex justify-center">
            <?php $image = get_the_post_thumbnail_url($item->ID, 'medium'); ?>
            <?php if ($image): ?>
              <img src="<?php echo esc_url($image); ?>"
                   alt="<?php echo esc_attr(gf_get_post_title($item->ID)); ?>"
                    class="h-auto w-auto max-h-12 max-w-12 object-contain text-dark sm:max-h-14 sm:max-w-14 xl:max-h-18 xl:max-w-18" />
            <?php endif; ?>
          </div>
          <p class="mt-2 whitespace-nowrap text-[10px] font-black uppercase leading-tight tracking-normal text-dark sm:mt-3 sm:text-xs xl:mt-4 xl:text-base 2xl:text-lg"><?php echo esc_html(gf_get_post_title($item->ID)); ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
