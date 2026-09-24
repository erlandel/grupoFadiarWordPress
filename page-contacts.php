<?php
/* Template Name: Contactos */
get_header();
?>

<div class="mx-4 mt-6 sm:mx-8 sm:mt-7 md:mx-12 xl:mx-30">
  <div class="flex items-center text-xs sm:text-sm xl:text-base">
    <p><a href="<?php echo home_url('/'); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <svg class="mx-1 h-4 w-4 xl:h-6 xl:w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><?php echo esc_html(gf_e('header.menu.contacts')); ?></p>
  </div>
  <div class="mt-6 sm:mt-7">
    <h1 class="text-3xl font-bold text-dark sm:text-4xl"><?php echo esc_html(gf_get_option('contact_page_title', 'Contáctanos', 'Contact Us')); ?></h1>
    <p class="mt-3 text-lg leading-snug text-dark sm:mt-4 sm:text-xl xl:text-2xl">
      <strong><?php echo esc_html(gf_get_option('contact_page_subtitle', 'Escríbenos, llama o visítanos. Estamos para ayudarte.', 'Write us, call us or visit us. We are here to help you.')); ?></strong>
    </p>
  </div>
</div>

<div class="mx-4 mt-8 max-w-3xl sm:mx-8 sm:mt-10 md:mx-4 lg:mx-auto md:mt-12 xl:mt-12">
  <?php get_template_part('components/contact-form/contact-form'); ?>
</div>

<div class="mx-4 mt-8 mb-8 max-w-2xl sm:mx-8 sm:mt-10 sm:mb-10 md:mx-12 xl:mx-30">
  <?php get_template_part('components/contact-info/contact-info'); ?>
</div>

<div>
  <?php get_template_part('components/contact-map/contact-map'); ?>
</div>

<?php get_footer();
