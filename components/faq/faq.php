<?php
$faq_items = get_posts(array(
  'post_type'      => 'faq_item',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
  'post_status'    => 'publish',
));
?>
<div id="faq" class="scroll-mt-20 px-15">
  <h2 class="text-5xl font-bold mb-6 text-dark"><?php echo esc_html(get_option('faq_section_title', 'Preguntas frecuentes')); ?></h2>
  <?php if ($faq_items): ?>
  <div class="flex flex-col gap-4 mt-15">
    <?php foreach ($faq_items as $item):
      $answer = get_field('faq_answer', $item->ID);
    ?>
      <div id="faq-<?php echo esc_attr($item->post_name); ?>" class="scroll-mt-20 faq-item bg-[#F4F4F4] border-l-8 border-l-dark text-dark">
        <div class="faq-question p-10 cursor-pointer">
          <div class="w-full text-4xl flex justify-between items-center text-left font-normal">
            <span><?php echo esc_html($item->post_title); ?></span>
            <svg class="faq-chevron h-10 w-10 shrink-0 transition-transform" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>
        <div class="faq-answer hidden px-10 pb-10 text-2xl"><?php echo wp_kses_post($answer); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
