<?php
/* Template Name: Sobre Nosotros */
get_header(); ?>

<div class="mx-20 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p>Grupo Fadiar</p>
  </div>
  <div class="mt-10">
    <h1 class="text-5xl font-bold text-dark">Grupo Fadiar</h1>
  </div>
</div>

<div><?php get_template_part('components/metrics/metrics'); ?></div>
<div><?php get_template_part('components/our-story/our-story'); ?></div>
<div><?php get_template_part('components/corporate-pillars/corporate-pillars'); ?></div>

<?php get_footer(); ?>
