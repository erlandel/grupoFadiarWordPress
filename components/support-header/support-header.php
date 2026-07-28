<?php

$support_header_items = get_posts(array(
    'post_type'      => 'support_header_item',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
));

?>
<div class="w-1/2 flex items-center justify-end">
  <div class="flex">
    <?php if ($support_header_items): ?>
        <?php foreach ($support_header_items as $item): ?>
        <div class="px-4 text-center">
          <div class="flex justify-center">
            <?php $image = get_the_post_thumbnail_url($item->ID, 'medium'); ?>
            <?php if ($image): ?>
              <img src="<?php echo esc_url($image); ?>"
                   alt="<?php echo esc_attr(gf_get_post_title($item->ID)); ?>"
                   class="max-h-18 max-w-18 w-auto h-auto object-contain text-dark" />
            <?php endif; ?>
          </div>
          <p class="mt-4 text-lg text-dark font-black tracking-wider"><?php echo esc_html(strtoupper(gf_get_post_title($item->ID))); ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
