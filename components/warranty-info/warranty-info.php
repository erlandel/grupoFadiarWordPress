<?php
$contacts = array();
if (post_type_exists('warranty_contact')) {
    $contacts = get_posts(array(
        'post_type'      => 'warranty_contact',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));
}

$steps = array();
if (post_type_exists('warranty_step')) {
    $steps = get_posts(array(
        'post_type'      => 'warranty_step',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));
}

?>
<section id="warrantyInfo" class="scroll-mt-20 mx-0 my-10 grid max-w-7xl grid-cols-1 gap-6 sm:my-12 sm:gap-8 xl:mx-30 xl:my-15 xl:grid-cols-2 xl:gap-10 min-[1920px]:mx-auto">

  <div class="reveal-item rounded-2xl bg-[#F4F4F4] p-6 sm:p-8 xl:p-10">
    <h2 class="mb-5 text-2xl font-bold text-dark sm:mb-6 sm:text-3xl"><?php echo esc_html(gf_get_option('warranty_section_left_title', 'Proceso de reclamación', 'Claims Process')); ?></h2>
    <?php if (!empty($steps)): ?>
      <ol class="space-y-4 text-base text-dark sm:text-lg xl:text-xl [&>li]:leading-snug sm:[&>li]:leading-relaxed">
        <?php foreach ($steps as $step):
          $step_number = get_field('ws_step_number', $step->ID);
          if (empty($step_number)) {
              $step_number = '';
          }
          $step_desc = gf_get_field('ws_step_description', $step->ID);
        ?>
          <li id="warranty-step-<?php echo esc_attr($step->post_name); ?>" class="scroll-mt-24">
            <?php if (!empty($step_number)): ?>
              <strong><?php echo esc_html($step_number); ?>.</strong>
            <?php endif; ?>
            <?php echo esc_html($step_desc); ?>
          </li>
        <?php endforeach; ?>
      </ol>
    <?php endif; ?>
  </div>

  <div class="reveal-item rounded-2xl bg-[#F4F4F4] p-6 sm:p-8 xl:p-10">
    <h2 class="mb-5 text-2xl font-bold text-dark sm:mb-6 sm:text-3xl"><?php echo esc_html(gf_get_option('warranty_section_right_title', 'Contactos', 'Contacts')); ?></h2>
    <?php if (!empty($contacts)): ?>
      <ul class="space-y-7 text-base text-dark sm:space-y-8 sm:text-lg xl:space-y-10 xl:text-xl">
        <?php foreach ($contacts as $contact):
          $label    = gf_get_field('wc_label', $contact->ID);
          $phone    = get_field('wc_phone', $contact->ID);
          $schedule = gf_get_field('wc_schedule', $contact->ID);
        ?>
          <li id="warranty-contact-<?php echo esc_attr($contact->post_name); ?>" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 sm:gap-x-6">
              <span class="font-bold xl:inline-block xl:w-72"><span class="mr-2">•</span><?php echo esc_html($label); ?></span>
              <span class="flex items-center gap-2">
                <?php echo get_icon('phone', 'h-5 w-5 text-dark shrink-0 sm:h-6 sm:w-6 xl:h-7.5 xl:w-7.5'); ?>
                <span><?php echo esc_html($phone); ?></span>
              </span>
            </div>
            <?php if (!empty($schedule)): ?>
              <p class="mt-1 text-base text-dark sm:text-lg xl:text-xl sm:text-end">(<?php echo esc_html($schedule); ?>)</p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

</section>
