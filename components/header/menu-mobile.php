<?php
$home_url = home_url('/');
$is_home_page = is_front_page() || is_home();
$mobile_menu_background_classes = $is_home_page
  ? 'bg-white/40 backdrop-blur-sm'
  : 'bg-white/30 backdrop-blur-md';
$mobile_active_link_classes = 'bg-dark text-secondary';
$is_noticias_active = is_post_type_archive('noticias') || is_singular('noticia') || is_page('noticias');
$is_blog_active = is_post_type_archive('blog') || is_singular('blog');
$is_news_section_active = $is_noticias_active || $is_blog_active;
$links = array(
  array('href' => $home_url, 'label' => gf_e('header.menu.home'), 'icon' => 'home', 'key' => 'home', 'mobile_nav' => 'home'),
  array('href' => home_url('/about-us/'), 'label' => gf_e('header.menu.about'), 'key' => 'about-us'),
  array('href' => $home_url . '#ourBrands', 'label' => gf_e('header.menu.brands'), 'key' => '', 'mobile_nav' => 'ourBrands'),
  array('href' => $home_url . '#products', 'label' => gf_e('header.menu.products'), 'key' => '', 'mobile_nav' => 'products'),
  array('type' => 'news'),
  array('href' => home_url('/support-warranty/'), 'label' => gf_e('header.menu.support'), 'key' => 'support-warranty'),
  array('href' => home_url('/contacts/'), 'label' => gf_e('header.menu.contacts'), 'icon' => 'phone', 'key' => 'contacts'),
);
?>
<div class="menu-mobile-overlay fixed inset-0 z-40 bg-black/20 hidden"></div>
<div class="menu-mobile-panel fixed top-0 left-0 w-80 h-auto <?php echo esc_attr($mobile_menu_background_classes); ?> z-150 p-4 shadow-2xl rounded-xl m-2 hidden">
  <div class="flex items-center justify-between">
    <a href="<?php echo esc_url($home_url); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Grupo Fadiar Logo" width="130" height="20" />
    </a>
    <button class="menu-mobile-close cursor-pointer">
      <svg class="h-7 w-7 text-dark" stroke-width="2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
  </div>
  <div class="h-px bg-black mt-4"></div>
  <div class="mt-4">
    <nav>
      <ul class="flex flex-col space-y-2 font-bold">
        <?php foreach ($links as $link): ?>
          <li>
            <?php if (isset($link['type']) && $link['type'] === 'news'): ?>
              <details class="group rounded-md <?php echo $is_news_section_active ? esc_attr($mobile_active_link_classes) : 'text-dark hover:text-secondary'; ?>" data-mobile-news-menu>
                <summary class="flex items-center gap-2 text-xl cursor-pointer px-4 py-2 list-none" data-mobile-news-menu-trigger>
                  <span><?php echo esc_html(gf_e('header.menu.news')); ?></span>
                  <?php echo get_icon('chevron-down-bold', 'w-5 h-5 shrink-0 transition-transform group-open:rotate-180'); ?>
                </summary>
              </details>
            <?php else: ?>
             <a href="<?php echo esc_url($link['href']); ?>"
                <?php if (isset($link['mobile_nav'])): ?>data-mobile-nav-link="<?php echo esc_attr($link['mobile_nav']); ?>"<?php endif; ?>
                class="flex items-center gap-4 text-xl transition-colors px-4 py-2 rounded-md <?php echo !empty($link['key']) && gf_is_nav_active($link['key']) ? esc_attr($mobile_active_link_classes) : 'text-dark hover:text-secondary'; ?>">
                <?php if (isset($link['icon'])): ?>
                  <?php echo get_icon($link['icon'], $link['icon'] === 'phone' ? 'w-7.5 h-7.5' : 'w-6 h-6'); ?>
                <?php endif; ?>

               <?php echo isset($link['icon']) ? '' : esc_html($link['label']); ?>
              </a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>
</div>
<div data-mobile-news-menu-dropdown class="fixed z-150 hidden min-w-55 overflow-hidden rounded-sm border border-black/10 <?php echo esc_attr($mobile_menu_background_classes); ?>">
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
