<?php
// Configuración de layout para cada producto (fijo)
$layout_config = array(
  array('wrapper' => 'rounded-r-2xl aspect-11/6 w-full', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-14/8 w-full md:w-[85%] md:mr-auto', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-14/8  w-full md:w-[85%] md:ml-auto mt-12', 'self' => ''),
  array('wrapper' => 'rounded-l-2xl aspect-11/6 w-full', 'self' => 'self-end when-desk'),
);

// Obtener productos del CPT (máximo 4)
$products = get_posts(array(
  'post_type'      => 'home_product',
  'posts_per_page' => 4,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
  'post_status'    => 'publish',
));

// Título de la sección desde opciones globales
$section_title = gf_get_option('products_section_title', 'Productos', 'Products');

// Orden de aparición: producto 1 → 3 → 2 → 4
$reveal_order_map = array(0, 1, 1, 0);
?>
<section class="w-full py-16 overflow-hidden bg-white">
  <div>
    <h3 class="reveal-item text-4xl font-black mb-8 ml-6 md:ml-30 text-gray-900"><?php echo esc_html($section_title); ?></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12.5 items-start w-full">
      <?php 
      $count = count($products);
      for ($i = 0; $i < 4; $i++): 
        $product = isset($products[$i]) ? $products[$i] : null;
        $has_product = ($product !== null);
        
        // Valores por defecto si no hay producto
        $media_type = 'image';
        $media_url = '';
        $button_url = '#';
        $button_text = 'Ver producto';
        
        if ($has_product) {
          $media_type = get_field('product_media_type', $product->ID);
          $media_file = get_field('product_media_file', $product->ID);
          $button_url_raw = get_field('product_button_url', $product->ID);
          $button_text_raw = gf_get_field('product_button_text', $product->ID);
          
          if (!empty($media_file) && isset($media_file['url'])) {
            $media_url = $media_file['url'];
          }
          
          if (!empty($button_url_raw)) {
            $button_url = process_url($button_url_raw);
          }
          
          if (!empty($button_text_raw)) {
            $button_text = $button_text_raw;
          }
        }
      ?>
        <div class="reveal-item--zoom relative overflow-hidden shadow-lg <?php echo esc_attr($layout_config[$i]['wrapper']); ?> <?php echo esc_attr($layout_config[$i]['self']); ?>" data-reveal-order="<?php echo $reveal_order_map[$i] ?? $i; ?>">
          <?php if ($has_product && !empty($media_url)): ?>
            <?php if ($media_type === 'video'): ?>
              <video src="<?php echo esc_url($media_url); ?>" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
            <?php else: ?>
              <img src="<?php echo esc_url($media_url); ?>" alt="<?php echo esc_attr(gf_get_post_title($product->ID)); ?>" class="w-full h-full object-cover" loading="<?php echo $i < 2 ? 'eager' : 'lazy'; ?>" />
            <?php endif; ?>
          <?php else: ?>
            <!-- Placeholder cuando no hay producto -->
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
              <span class="text-gray-400 text-xl">Producto <?php echo $i + 1; ?></span>
            </div>
          <?php endif; ?>
          
          <?php if ($has_product): ?>
            <div class="absolute bottom-4 right-4 z-10">
              <a href="<?php echo esc_url($button_url); ?>" class="promo-btn inline-flex items-center gap-2 px-4 py-2 rounded-full text-white font-semibold text-lg tracking-wide bg-dark transition-transform hover:scale-105">
                <?php echo esc_html($button_text); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
