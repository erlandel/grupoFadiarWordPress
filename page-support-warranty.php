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
    <div class="flex justify-between items-end mt-2">
      <div class="w-1/2 text-3xl text-dark font-bold">
        <div class="flex flex-col  gap-4">
          <h1 class="text-5xl font-bold text-dark">Soporte y Garantía</h1>
         <h2>Atención técnica y reclamaciones</h2>
        </div>
      </div>
      <?php get_template_part('components/support-header/support-header'); ?>
    </div>
    <div class="mt-15 text-dark text-xl">
      <p>Todos nuestros productos cuentan con garantía contra defectos de fabricación.  Para cada producto existe una garantía específica que puedes consultar y solicitar  cuando recibas tu producto. La garantía y el soporte son valores que nos distinguen.  Cuando compras un producto del Grupo Fadiar, compras tranquilidad y calidad. Nuestro  servicio técnico está listo para atenderte.</p>
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
