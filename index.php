<?php get_header(); ?>

<div>
  <div class="reveal-section"><?php get_template_part('components/hero-carousel/carousel'); ?></div>
  <div class="reveal-section mt-10 mb-10"><?php get_template_part('components/discover-group/discover-group'); ?></div>
  <div id="ourBrands" class="reveal-section scroll-mt-20"><?php get_template_part('components/brands/brands'); ?></div>
  <div id="products" class="reveal-section scroll-mt-20"><?php get_template_part('components/products/products'); ?></div>
  <div class="reveal-section"><?php get_template_part('components/support-home/support-home'); ?></div>
</div>

<?php get_footer(); ?>
