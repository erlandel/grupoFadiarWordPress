<?php
/* Template Name: Contactos */
get_header();
?>

<div class="mx-15 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p>Contacto</p>
  </div>
  <div class="mt-10">
    <h1 class="text-5xl font-bold text-dark">Contáctanos</h1>
    <p class="mt-4 text-3xl text-dark">
      <strong>Escríbenos, llama o visítanos. Estamos para ayudarte.</strong>
    </p>
  </div>
</div>

<div class="mx-auto mt-12 max-w-3xl">
  <?php get_template_part('components/contact-form/contact-form'); ?>
</div>

<div class="mx-15 mt-10 mb-20 max-w-2xl">
  <?php get_template_part('components/contact-info/contact-info'); ?>
</div>

<div >
  <?php get_template_part('components/contact-map/contact-map'); ?>
</div>

<?php get_footer();
