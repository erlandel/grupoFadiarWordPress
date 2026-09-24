<?php
// URL de la pagina de inicio del sitio.
$home_url = home_url('/');

// Detecta si estamos en la portada para aplicar un fondo distinto al menu.
$is_home_page = is_front_page() || is_home();

// Clases Tailwind del fondo del panel y del submenu movil.
$mobile_menu_background_classes = $is_home_page
  ? 'bg-white/40 backdrop-blur-sm'
  : 'bg-white/30 backdrop-blur-md';

// Clases que se aplican al enlace o seccion actualmente activa.
$mobile_active_link_classes = 'bg-dark text-secondary';

// Comprueba si la seccion de noticias o blog corresponde a la pagina actual.
$is_noticias_active = is_post_type_archive('noticias') || is_singular('noticia') || is_page('noticias');
$is_blog_active = is_post_type_archive('blog') || is_singular('blog');
$is_news_section_active = $is_noticias_active || $is_blog_active;

// Lista de enlaces del menu. Los elementos con type => news renderizan el submenu de noticias.
// mobile_nav identifica los enlaces que navegan a una seccion de la portada mediante JavaScript.
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
<!-- Capa semitransparente que cubre el contenido cuando el menu esta abierto. -->
<div class="menu-mobile-overlay fixed inset-0 z-40 bg-black/20 hidden"></div>
<!-- Panel principal del menu movil. La clase hidden se elimina mediante JavaScript al abrirlo. -->
<div class="menu-mobile-panel fixed top-0 left-0 w-80 h-auto <?php echo esc_attr($mobile_menu_background_classes); ?> z-150 p-4 shadow-2xl rounded-xl m-2 hidden">
  <!-- Cabecera del panel: logo y boton para cerrarlo. -->
  <div class="flex items-center justify-between">
    <!-- El logo enlaza siempre con la pagina de inicio. -->
    <a href="<?php echo esc_url($home_url); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="Grupo Fadiar Logo" width="130" height="20" />
    </a>
    <!-- Boton controlado por JavaScript para cerrar el menu. -->
    <button class="menu-mobile-close cursor-pointer">
      <svg class="h-7 w-7 text-dark" stroke-width="2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
  </div>
  <!-- Separador visual entre la cabecera y los enlaces. -->
  <div class="h-px bg-black mt-4"></div>
  <div class="mt-4">
    <!-- Navegacion principal del menu movil. -->
    <nav>
      <ul class="flex flex-col space-y-2 font-bold">
        <?php foreach ($links as $link): ?>
          <li>
            <!-- El elemento de noticias abre un submenu desplegable. -->
            <?php if (isset($link['type']) && $link['type'] === 'news'): ?>
              <details class="group rounded-md <?php echo $is_news_section_active ? esc_attr($mobile_active_link_classes) : 'text-dark hover:text-secondary'; ?>" data-mobile-news-menu>
                <summary class="flex items-center gap-2 text-lg cursor-pointer px-4 py-2 list-none" data-mobile-news-menu-trigger>
                  <span><?php echo esc_html(gf_e('header.menu.news')); ?></span>
                  <?php echo get_icon('chevron-down-bold', 'w-5 h-5 shrink-0 transition-transform group-open:rotate-180'); ?>
                </summary>
              </details>
            <?php else: ?>
              <!-- Enlace normal del menu, con estado activo y posible icono. -->
              <a href="<?php echo esc_url($link['href']); ?>"
                 <?php if (isset($link['mobile_nav'])): ?>data-mobile-nav-link="<?php echo esc_attr($link['mobile_nav']); ?>"<?php endif; ?>
                 class="flex items-center gap-4 text-lg transition-colors px-4 py-2 rounded-md <?php echo !empty($link['key']) && gf_is_nav_active($link['key']) ? esc_attr($mobile_active_link_classes) : 'text-dark hover:text-secondary'; ?>">
                <!-- Algunos enlaces muestran un icono en lugar de texto. -->
                <?php if (isset($link['icon'])): ?>
                  <?php echo get_icon($link['icon'], $link['icon'] === 'phone' ? 'w-7.5 h-7.5' : 'w-6 h-6'); ?>
                <?php endif; ?>

                <!-- Los enlaces con icono no imprimen una etiqueta adicional. -->
               <?php echo isset($link['icon']) ? '' : esc_html($link['label']); ?>
              </a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>
</div>
<!-- Submenu flotante con las opciones de noticias y blog. -->
<div data-mobile-news-menu-dropdown class="fixed z-150 hidden min-w-55 overflow-hidden rounded-sm border border-black/10 <?php echo esc_attr($mobile_menu_background_classes); ?>">
  <!-- Enlace a la seccion de noticias actuales. -->
  <a href="<?php echo esc_url(home_url('/noticias/')); ?>" class="group/item flex items-center justify-between gap-3 px-4 py-3 <?php echo $is_noticias_active ? 'text-secondary' : 'text-dark hover:bg-dark/10 hover:text-secondary'; ?>">
    <span class="text-lg font-bold"><?php echo esc_html(gf_e('header.menu.current')); ?></span>
    <!-- Radio visual de Noticias: se rellena cuando esta seccion esta activa. -->
    <span class="flex h-4 w-4 items-center justify-center rounded-full border-2 <?php echo $is_noticias_active ? 'border-secondary bg-secondary/20' : 'border-dark group-hover/item:border-secondary'; ?>">
      <span class="h-2 w-2 rounded-full <?php echo $is_noticias_active ? 'bg-secondary' : ''; ?>"></span>
    </span>
  </a>
  <div class="mx-2 border-t border-black/10"></div>
  <!-- Enlace al archivo de entradas del blog. -->
  <a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>" class="group/item flex items-center justify-between gap-3 px-4 py-3 <?php echo $is_blog_active ? 'text-secondary' : 'text-dark hover:bg-dark/10 hover:text-secondary'; ?>">
    <span class="text-lg font-bold"><?php echo esc_html(gf_e('header.menu.blog')); ?></span>
    <!-- Radio visual de Blog: se rellena cuando esta seccion esta activa. -->
    <span class="flex h-4 w-4 items-center justify-center rounded-full border-2 <?php echo $is_blog_active ? 'border-secondary bg-secondary/10' : 'border-dark group-hover/item:border-secondary'; ?>">
      <span class="h-2 w-2 rounded-full <?php echo $is_blog_active ? 'bg-secondary' : ''; ?>"></span>
    </span>
  </a>
</div>
