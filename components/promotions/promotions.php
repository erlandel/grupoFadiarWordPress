<?php
$asset_uri = get_template_directory_uri() . '/assets';
$items = array(
  array('type' => 'image', 'src' => $asset_uri . '/images/promotions/1.png'),
  array('type' => 'image', 'src' => $asset_uri . '/images/promotions/2.png'),
  array('type' => 'image', 'src' => $asset_uri . '/images/promotions/3.png'),
  array('type' => 'image', 'src' => $asset_uri . '/images/promotions/4.png'),
);
$layout_config = array(
  array('wrapper' => 'rounded-r-2xl aspect-16/6.5 w-full', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-16/8 w-full md:w-[90%] md:mr-auto', 'self' => ''),
  array('wrapper' => 'rounded-2xl aspect-video w-full md:w-[90%] md:ml-auto', 'self' => ''),
  array('wrapper' => 'rounded-l-2xl aspect-16/7.5 w-full', 'self' => 'self-end when-desk'),
);
?>
<section class="w-full py-16 overflow-hidden bg-white">
  <div>
     <h3 class="text-5xl font-black mb-12 ml-6 md:ml-20 text-gray-900">Productos</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 items-start w-full">
      <?php foreach ($items as $i => $item): ?>
        <div class="relative group overflow-hidden shadow-lg <?php echo esc_attr($layout_config[$i]['wrapper']); ?> <?php echo esc_attr($layout_config[$i]['self']); ?>">
          <img src="<?php echo esc_url($item['src']); ?>" alt="Promoción" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="<?php echo $i < 2 ? 'eager' : 'lazy'; ?>" />
          <section class="absolute bottom-4 right-4 flex gap-3 z-10">
             <button class="promo-download p-2.5 bg-black/70 hover:bg-black/90 rounded-full transition-all duration-200 text-white hover:scale-110 cursor-pointer backdrop-blur-sm" title="Descargar" data-src="<?php echo esc_url($item['src']); ?>">
                <?php echo get_icon('download', 'w-5 h-5'); ?>

             </button>
             <button class="promo-fullscreen-btn p-2.5 bg-black/70 hover:bg-black/90 rounded-full transition-all duration-200 text-white hover:scale-110 cursor-pointer backdrop-blur-sm" title="Ver en pantalla completa" data-src="<?php echo esc_url($item['src']); ?>">
                <?php echo get_icon('expand', 'w-5 h-5'); ?>

             </button>
          </section>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="promo-fullscreen-overlay fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4 hidden" style="display:none;">
    <button class="promo-fullscreen-close absolute top-6 right-6 p-2 text-white text-3xl hover:text-gray-300 transition-colors cursor-pointer z-50">✕</button>
    <div class="relative w-full h-full max-w-[90%] max-h-[90vh]">
      <img class="promo-fullscreen-img object-contain rounded-xl w-full h-full" src="" alt="Promoción" />
    </div>
  </div>
</section>
