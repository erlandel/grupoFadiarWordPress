<?php
$faq_items = get_posts(array(
  'post_type'      => 'faq_item',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
  'post_status'    => 'publish',
));
?>
<div id="faq" class="scroll-mt-20 px-4 sm:px-8 md:px-12 xl:px-30">
  <h2 class="reveal-item mb-6 text-2xl font-bold text-dark sm:text-3xl xl:text-4xl"><?php echo esc_html(gf_get_option('faq_section_title', 'Preguntas frecuentes', 'Frequently Asked Questions')); ?></h2>
  <?php if ($faq_items): ?>
  <div class="mt-8 flex flex-col gap-4 sm:mt-10 xl:mt-15">
    <?php foreach ($faq_items as $item):
      $answer = gf_get_field('faq_answer', $item->ID);
    ?>
      <div id="faq-<?php echo esc_attr($item->post_name); ?>" class="reveal-item scroll-mt-20 faq-item border-l-4 border-l-dark bg-[#F4F4F4] text-dark sm:border-l-6 xl:border-l-8">
        <div class="faq-question cursor-pointer px-6 py-4 sm:px-8 sm:py-5 xl:p-10">
          <div class="flex w-full items-center justify-between text-left text-base font-normal sm:text-lg xl:text-2xl">
            <span><?php echo esc_html(gf_get_post_title($item->ID)); ?></span>
            <svg class="faq-chevron h-6 w-6 shrink-0 transition-transform sm:h-7 sm:w-7 xl:h-10 xl:w-10" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>
        <div class="faq-answer hidden px-6 pb-5 text-base sm:px-8 sm:pb-6 sm:text-lg xl:px-10 xl:pb-10"><?php echo wp_kses_post($answer); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
