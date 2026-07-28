<?php
/* Template Name: Soporte y Garantía */
get_header(); ?>

<div class="mx-30 mt-7">
  <div class="flex ">
    <p><a href="<?php echo home_url('/'); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><?php echo esc_html(gf_e('header.menu.support')); ?></p>
  </div>
  <section class="mt-7">
    <div class="flex justify-between items-end mt-2">
      <div class="w-1/2 text-2xl text-dark font-bold">
        <div class="flex flex-col  gap-8">
          <h1 class="text-4xl font-bold text-dark"><?php echo esc_html(gf_get_option('support_warranty_title', 'Soporte y Garantía', 'Support and Warranty')); ?></h1>
          <h2><?php echo esc_html(gf_get_option('support_warranty_subtitle', 'Atención técnica y reclamaciones', 'Technical support and claims')); ?></h2>
        </div>
      </div>
      <?php get_template_part('components/support-header/support-header'); ?>
    </div>
    <div class="mt-10 text-dark text-lg">
      <p><?php $description = gf_get_option('support_warranty_description', '', ''); echo wp_kses_post($description); ?></p>
    </div>
  </section>
</div>

<?php get_template_part('components/warranty-info/warranty-info'); ?>

<div class="my-40 overflow-hidden">
  <?php get_template_part('components/support-carousel/support-carousel'); ?>
</div>

<div class="my-20">
  <?php get_template_part('components/faq/faq'); ?>
</div>

<?php get_footer(); ?>
