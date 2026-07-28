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
<section id="warrantyInfo" class="scroll-mt-20 mx-30 min-[1920px]:mx-auto  my-15 grid grid-cols-1 md:grid-cols-2 gap-10 max-w-7xl ">

  <div class="bg-[#F4F4F4] p-10 rounded-2xl">
    <h2 class="text-3xl font-bold text-dark mb-6"><?php echo esc_html(gf_get_option('warranty_section_left_title', 'Proceso de reclamación', 'Claims Process')); ?></h2>
    <?php if (!empty($steps)): ?>
      <ol class="text-xl text-dark space-y-4 [&>li]:leading-relaxed">
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

  <div class="bg-[#F4F4F4] p-10 rounded-2xl">
    <h2 class="text-3xl font-bold text-dark mb-6"><?php echo esc_html(gf_get_option('warranty_section_right_title', 'Contactos', 'Contacts')); ?></h2>
    <?php if (!empty($contacts)): ?>
      <ul class="text-xl text-dark space-y-10">
        <?php foreach ($contacts as $contact):
          $label    = gf_get_field('wc_label', $contact->ID);
          $phone    = get_field('wc_phone', $contact->ID);
          $schedule = gf_get_field('wc_schedule', $contact->ID);
        ?>
          <li id="warranty-contact-<?php echo esc_attr($contact->post_name); ?>" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
              <span class="font-bold inline-block w-72"><span class="mr-2">•</span><?php echo esc_html($label); ?></span>
              <span class="flex items-center gap-2">
                <?php echo get_icon('phone', 'h-7.5 w-7.5 text-dark shrink-0'); ?>
                <span><?php echo esc_html($phone); ?></span>
              </span>
            </div>
            <?php if (!empty($schedule)): ?>
              <p class="text-xl text-dark mt-1 text-end">(<?php echo esc_html($schedule); ?>)</p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

</section>
