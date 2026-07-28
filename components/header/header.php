<?php
$is_home_page = is_front_page() || is_home();
$header_classes = 'site-header w-full z-50 transition-colors duration-300';
$header_classes .= $is_home_page
  ? ' fixed bg-white/40 backdrop-blur-sm shadow-lg'
  : ' sticky top-0 bg-white/30 backdrop-blur-md shadow-lg';
?>
<header class="<?php echo esc_attr($header_classes); ?>">
  <nav class="w-full flex items-center justify-between py-4 text-sm">
    <div class="flex items-center justify-between w-full px-10 xl:px-30">
      <div class="hidden xl:block">
        <a href="<?php echo home_url('/'); ?>">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Grupo Fadiar Logo" width="125" height="20" />
        </a>
      </div>
      <div class="xl:hidden flex items-center justify-between w-full">
          <div class="cursor-pointer menu-mobile-open">
            <?php echo get_icon('menu', 'w-7 h-7 text-dark'); ?>
          </div>
          <div class="cursor-pointer search-open">
            <?php echo get_icon('search', 'w-7 h-7 text-dark'); ?>
          </div>

      </div>
      <div class="hidden xl:flex gap-6 items-center">
        <?php get_template_part('components/header/menu'); ?>
        <div class="flex items-center gap-6 ">
          <a href="<?php echo home_url('/contacts/'); ?>"
             class="cursor-pointer p-2 rounded-md flex items-center justify-center transition-colors <?php echo esc_attr(gf_nav_link_classes('contacts')); ?>">
            <?php echo get_icon('phone', 'w-7 h-7'); ?>
          </a>
          <div class="cursor-pointer search-open">
            <?php echo get_icon('search', 'w-6 h-6 text-dark'); ?>
          </div>
          <?php echo gf_language_switcher('w-6 h-6'); ?>
        </div>
      </div>
    </div>
  </nav>
</header>
<?php get_template_part('components/header/menu-mobile'); ?>
<?php get_template_part('components/search/search-overlay'); ?>
