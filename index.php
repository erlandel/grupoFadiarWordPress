<?php get_header(); ?>

<div>
  <div><?php get_template_part('components/hero-carousel/carousel'); ?></div>
  <div class="mt-10 mb-10"><?php get_template_part('components/discover-group/discover-group'); ?></div>
  <div id="ourBrands" class="scroll-mt-20"><?php get_template_part('components/brands/brands'); ?></div>
  <div id="products" class="scroll-mt-20"><?php get_template_part('components/products/products'); ?></div>
  <div><?php get_template_part('components/support-home/support-home'); ?></div>
</div>

<?php get_footer(); ?>
