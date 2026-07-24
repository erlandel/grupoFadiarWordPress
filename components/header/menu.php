<?php
$home_url = home_url('/');

$links = array(
  array('href' => $home_url, 'label' => 'Inicio', 'icon' => 'home', 'key' => 'home'),
  array('href' => home_url('/about-us/'), 'label' => 'Grupo Fadiar', 'key' => 'about-us'),
  array('href' => $home_url . '#ourBrands', 'label' => 'Nuestras marcas', 'key' => ''),
  array('href' => $home_url . '#products', 'label' => 'Productos', 'class' => 'font-bold', 'key' => ''),
  array('href' => home_url('/noticias/'), 'label' => 'Noticias', 'key' => 'noticias'),
  array('href' => home_url('/support-warranty/'), 'label' => 'Soporte y Garantía', 'key' => 'support-warranty'),
);
?>
<div class="font-bold text-lg 2xl:text-xl">
  <ul class="flex items-center lg:space-x-3">
    <?php foreach ($links as $link): ?>
      <li>
        <a href="<?php echo esc_url($link['href']); ?>"
           class="transition-colors px-4 py-2 rounded-md flex items-center justify-center <?php echo isset($link['class']) ? esc_attr($link['class']) : ''; ?> <?php echo !empty($link['key']) ? esc_attr(gf_nav_link_classes($link['key'])) : 'text-dark hover:text-secondary'; ?>">
             <?php if (isset($link['icon'])): ?>
               <?php echo get_icon($link['icon'], 'w-7.5 h-7.5'); ?>
             <?php else: ?>
             <?php echo esc_html($link['label']); ?>
           <?php endif; ?>
         </a>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php if (is_front_page() || is_home()) : ?>
  <script>
  (function () {
    var hash = window.location.hash.slice(1);
    var sections = ['ourBrands', 'products'];
    if (sections.indexOf(hash) === -1) return;

    document.currentScript.parentElement.querySelectorAll('li a').forEach(function (link) {
      var href = link.getAttribute('href') || '';
      var isActive = href.endsWith('#' + hash);
      link.classList.toggle('bg-dark', isActive);
      link.classList.toggle('text-secondary', isActive);
      link.classList.toggle('text-dark', !isActive);
    });
  })();
  </script>
  <?php endif; ?>
</div>
