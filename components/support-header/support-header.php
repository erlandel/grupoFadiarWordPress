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
                   alt="<?php echo esc_attr($item->post_title); ?>"
                   class="h-20 w-20 text-dark" />
            <?php endif; ?>
          </div>
          <p class="mt-4 text-xl text-dark font-black tracking-wider"><?php echo esc_html(strtoupper($item->post_title)); ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
