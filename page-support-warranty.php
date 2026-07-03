<?php
/* Template Name: Soporte y Garantía */
get_header(); ?>

<div class="mx-15 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p>Soporte y Garantía</p>
  </div>
  <section class="mt-10">
    <h1 class="text-5xl font-bold text-dark">Soporte y Garantía</h1>
    <div class="flex justify-between items-end mt-2">
      <div class="w-1/2 text-xl">
        <p>En Grupo Fadiar, comprendemos la importancia de cada inversión y nos presentamos como la opción estratégica por estas razones fundamentales</p>
      </div>
      <?php get_template_part('components/support-header/support-header'); ?>
    </div>
  </section>
</div>

<div class="my-20">
  <?php get_template_part('components/faq/faq'); ?>
</div>

<?php get_footer(); ?>
