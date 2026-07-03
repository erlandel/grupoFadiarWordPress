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
        <div class="relative overflow-hidden shadow-lg <?php echo esc_attr($layout_config[$i]['wrapper']); ?> <?php echo esc_attr($layout_config[$i]['self']); ?>">
          <img src="<?php echo esc_url($item['src']); ?>" alt="Promoción" class="w-full h-full object-cover" loading="<?php echo $i < 2 ? 'eager' : 'lazy'; ?>" />
          <div class="absolute bottom-4 right-4 z-10">
            <a href="#" class="promo-btn inline-flex items-center gap-2 px-4 py-2 rounded-full text-white font-semibold text-sm tracking-wide bg-dark transition-transform hover:scale-105" >
              Ver producto
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</section>
