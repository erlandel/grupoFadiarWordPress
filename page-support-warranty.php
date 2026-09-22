<?php
/* Template Name: Soporte y Garantía */
get_header(); ?>

<div class="reveal-section mx-4 mt-6 sm:mx-8 md:mx-12 xl:mx-30 xl:mt-7">
  <div class="flex items-center text-xs sm:text-sm xl:text-base">
    <p><a href="<?php echo home_url('/'); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <svg class="mx-1 h-4 w-4 xl:h-6 xl:w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><?php echo esc_html(gf_e('header.menu.support')); ?></p>
  </div>
  <section class="mt-6 grid grid-cols-1 sm:mt-8 xl:mt-7 xl:grid-cols-2 xl:items-end xl:gap-x-8">
    <div class="text-dark font-bold xl:col-start-1 xl:row-start-1 xl:text-2xl">
      <div class="flex flex-col gap-4 sm:gap-6 xl:gap-8">
        <h1 class="reveal-item text-3xl font-bold text-dark sm:text-4xl"><?php echo esc_html(gf_get_option('support_warranty_title', 'Soporte y Garantía', 'Support and Warranty')); ?></h1>
        <h2 class="reveal-item text-base sm:text-lg xl:text-2xl"><?php echo esc_html(gf_get_option('support_warranty_subtitle', 'Atención técnica y reclamaciones', 'Technical support and claims')); ?></h2>
      </div>
    </div>
    <div class="order-3 mt-6 sm:mt-8 xl:col-start-2 xl:row-start-1 xl:order-0 xl:mt-0">
      <?php get_template_part('components/support-header/support-header'); ?>
    </div>
    <div class="reveal-item order-2 mt-6 text-base leading-snug text-dark sm:mt-8 sm:text-lg xl:col-span-2 xl:row-start-2 xl:order-0 xl:mt-10">
      <p><?php $description = gf_get_option('support_warranty_description', '', ''); echo wp_kses_post($description); ?></p>
    </div>
  </section>

  <?php get_template_part('components/warranty-info/warranty-info'); ?>
</div>

<div class="reveal-section my-16 overflow-hidden sm:my-20 xl:my-40">
  <?php get_template_part('components/support-carousel/support-carousel'); ?>
</div>

<div class="reveal-section my-12 sm:my-16 xl:my-20">
  <?php get_template_part('components/faq/faq'); ?>
</div>

<?php get_footer(); ?>
