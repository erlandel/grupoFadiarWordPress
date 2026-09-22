<?php
// Configuración de layout para cada producto (fijo)
$layout_config = array(
  array('wrapper' => 'rounded-2xl aspect-[3/4] w-[78vw] max-w-72 shrink-0 snap-center md:rounded-r-2xl md:rounded-l-none md:aspect-11/6 md:w-full md:max-w-none', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-[3/4] w-[78vw] max-w-72 shrink-0 snap-center md:aspect-14/8 md:w-[85%] md:max-w-none md:mr-auto', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-[3/4] w-[78vw] max-w-72 shrink-0 snap-center md:aspect-14/8 md:w-[85%] md:max-w-none md:ml-auto md:mt-auto', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-[3/4] w-[78vw] max-w-72 shrink-0 snap-center md:rounded-l-2xl md:rounded-r-none md:aspect-11/6 md:w-full md:max-w-none', 'self' => 'md:self-end when-desk'),
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
<section class="w-full overflow-hidden bg-white py-12 md:py-16">
  <div>
    <h3 class="reveal-item mb-8 ml-6 text-3xl font-black text-gray-900 md:ml-30 md:text-4xl"><?php echo esc_html($section_title); ?></h3>
    <div class="products-carousel flex items-start overflow-hidden px-6 pb-4 md:grid md:w-full md:grid-cols-2 md:items-start md:gap-x-12.5 md:overflow-visible md:px-0 md:pb-0" aria-label="<?php echo esc_attr($section_title); ?>">
      <div class="products-carousel-track flex w-max items-start gap-5 pr-6 will-change-transform md:contents md:gap-0 md:pr-0 md:will-change-auto">
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
            <div class="relative h-full w-full">
              <?php if ($media_type === 'video'): ?>
                <video src="<?php echo esc_url($media_url); ?>" class="product-video h-full w-full object-cover" autoplay muted loop playsinline preload="metadata"></video>
                <button type="button" class="product-video-toggle absolute left-1/2 top-1/2 z-10 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white/70 p-4 text-white md:hidden" aria-label="Reproducir video" aria-pressed="false">
                  <?php echo get_icon('play', 'product-video-play h-8 w-8 fill-current text-white'); ?>
                  <?php echo get_icon('pause', 'product-video-pause hidden h-8 w-8 fill-current text-white'); ?>
                </button>
              <?php else: ?>
                <img src="<?php echo esc_url($media_url); ?>" alt="<?php echo esc_attr(gf_get_post_title($product->ID)); ?>" class="h-full w-full object-cover" loading="<?php echo $i < 2 ? 'eager' : 'lazy'; ?>" />
              <?php endif; ?>
            </div>
          <?php else: ?>
            <!-- Placeholder cuando no hay producto -->
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
              <span class="text-gray-400 text-xl">Producto <?php echo $i + 1; ?></span>
            </div>
          <?php endif; ?>
          
          <?php if ($has_product): ?>
            <div class="absolute bottom-4 right-4 z-10">
              <a href="<?php echo esc_url($button_url); ?>" class="promo-btn inline-flex items-center gap-2 rounded-full bg-dark px-3 py-1 text-base font-semibold tracking-wide text-white transition-transform hover:scale-105 md:px-4 md:py-2 md:text-lg">
                <?php echo esc_html($button_text); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
      </div>
    </div>
  </div>
</section>
