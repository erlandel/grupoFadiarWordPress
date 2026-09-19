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
<?php
$submenu_background_classes = $is_home_page
  ? 'bg-white/40 backdrop-blur-sm shadow-lg'
  : 'bg-white/30 backdrop-blur-md shadow-lg';
$is_noticias_active = is_singular('noticia') || is_page('noticias');
$is_blog_active = is_post_type_archive('blog') || is_singular('blog');
?>
<div data-news-menu-dropdown class="fixed z-60 hidden min-w-55 overflow-hidden rounded-sm border border-black/10 <?php echo esc_attr($submenu_background_classes); ?>">
  <a href="<?php echo esc_url(home_url('/noticias/')); ?>" class="group/item flex items-center justify-between gap-3 px-4 py-3 <?php echo $is_noticias_active ? 'text-secondary' : 'text-dark hover:bg-dark/10 hover:text-secondary'; ?>">
    <span class="text-lg font-bold"><?php echo esc_html(gf_e('header.menu.current')); ?></span>
    <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 <?php echo $is_noticias_active ? 'border-secondary bg-secondary/20' : 'border-dark group-hover/item:border-secondary'; ?>">
      <span class="h-3 w-3 rounded-full <?php echo $is_noticias_active ? 'bg-secondary' : ''; ?>"></span>
    </span>
  </a>
  <div class="mx-2 border-t border-black/10"></div>
  <a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>" class="group/item flex items-center justify-between gap-3 px-4 py-3 <?php echo $is_blog_active ? 'text-secondary' : 'text-dark hover:bg-dark/10 hover:text-secondary'; ?>">
    <span class="text-lg font-bold"><?php echo esc_html(gf_e('header.menu.blog')); ?></span>
    <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 <?php echo $is_blog_active ? 'border-secondary bg-secondary/10' : 'border-dark group-hover/item:border-secondary'; ?>">
      <span class="h-3 w-3 rounded-full <?php echo $is_blog_active ? 'bg-secondary' : ''; ?>"></span>
    </span>
  </a>
</div>
<?php get_template_part('components/header/menu-mobile'); ?>
<?php get_template_part('components/search/search-overlay'); ?>
