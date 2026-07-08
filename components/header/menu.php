<?php
$current_url = home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));
$home_url = home_url('/');

$links = array(
  array('href' => $home_url, 'label' => 'Inicio', 'icon' => 'home'),
  array('href' => home_url('/about-us/'), 'label' => 'Grupo Fadiar'),
  array('href' => $home_url . '#ourBrands', 'label' => 'Nuestras marcas'),
  array('href' => $home_url . '#products', 'label' => 'Productos', 'class' => 'font-bold'),
  array('href' => home_url('/support-warranty/'), 'label' => 'Soporte y Garantía'),
  array('href' => home_url('/contacts/'), 'label' => 'Contactos', 'icon' => 'phone'),
);

function is_menu_active($href, $current_url, $home_url) {
  if (strpos($href, '#') !== false) {
    return false;
  }
  $path = parse_url($current_url, PHP_URL_PATH);
  $href_path = parse_url($href, PHP_URL_PATH);
  if ($href === $home_url) {
    return $path === '/' || $path === '';
  }
  return $path === $href_path;
}
?>
<div class="font-bold text-lg 2xl:text-xl">
  <ul class="flex items-center lg:space-x-3">
    <?php foreach ($links as $link): ?>
      <li>
        <a href="<?php echo esc_url($link['href']); ?>"
           class="transition-colors px-4 py-2 rounded-md flex items-center justify-center <?php echo isset($link['class']) ? esc_attr($link['class']) : ''; ?> <?php echo is_menu_active($link['href'], $current_url, $home_url) ? 'bg-dark text-secondary' : 'text-dark hover:text-secondary'; ?>">
             <?php if (isset($link['icon'])): ?>
               <?php echo get_icon($link['icon'], $link['icon'] === 'phone' ? 'w-7.5 h-7.5' : 'w-7.5 h-7.5'); ?>
             <?php else: ?>

             <?php echo esc_html($link['label']); ?>
           <?php endif; ?>
         </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
