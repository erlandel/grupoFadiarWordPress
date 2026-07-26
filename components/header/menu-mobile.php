<?php
$home_url = home_url('/');
$links = array(
  array('href' => $home_url, 'label' => gf_e('header.menu.home'), 'icon' => 'home', 'key' => 'home'),
  array('href' => home_url('/about-us/'), 'label' => gf_e('header.menu.about'), 'key' => 'about-us'),
  array('href' => $home_url . '#ourBrands', 'label' => gf_e('header.menu.brands'), 'key' => ''),
  array('href' => $home_url . '#products', 'label' => gf_e('header.menu.products'), 'key' => ''),
  array('href' => home_url('/noticias/'), 'label' => gf_e('header.menu.news'), 'key' => 'noticias'),
  array('href' => home_url('/support-warranty/'), 'label' => gf_e('header.menu.support'), 'key' => 'support-warranty'),
  array('href' => home_url('/contacts/'), 'label' => gf_e('header.menu.contacts'), 'icon' => 'phone', 'key' => 'contacts'),
);
?>
<div class="menu-mobile-overlay fixed inset-0 z-40 bg-black/20 hidden" style="display:none;"></div>
<div class="menu-mobile-panel fixed top-0 left-0 w-80 h-auto bg-white/30 backdrop-blur-2xl z-150 p-4 shadow-2xl rounded-xl m-2 hidden" style="display:none;">
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
      <ul class="flex flex-col space-y-2 font-black">
        <?php foreach ($links as $link): ?>
          <li>
            <a href="<?php echo esc_url($link['href']); ?>"
               class="flex items-center gap-4 text-xl transition-colors px-4 py-2 rounded-md <?php echo !empty($link['key']) ? esc_attr(gf_nav_link_classes($link['key'])) : 'text-dark hover:text-secondary'; ?>">
                <?php if (isset($link['icon'])): ?>
                  <?php echo get_icon($link['icon'], $link['icon'] === 'phone' ? 'w-7.5 h-7.5' : 'w-6 h-6'); ?>
                <?php endif; ?>

               <?php echo isset($link['icon']) ? '' : esc_html($link['label']); ?>
             </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>
  <div class="h-px bg-black mt-4"></div>
  <div class="mt-4">
    <?php echo gf_language_switcher('w-9 h-9'); ?>
  </div>
</div>
