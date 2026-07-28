<?php
$args = wp_parse_args($args, array(
  'productImage' => '',
  'productAlt' => '',
  'brandImage' => '',
  'brandAlt' => '',
  'brandWidth' => 96,
  'brandHeight' => 40,
  'description' => '',
  'buttonText' => 'Ver más',
  'buttonUrl' => '#',
));
?>
<div class="group relative flex flex-col w-73 h-90.5 bg-white/5 rounded-xl p-4 overflow-hidden transform transition-transform duration-300 hover:scale-115">
  <div class="shrink-0 group-hover:opacity-0 transition-opacity duration-300">
    <img src="<?php echo esc_url($args['productImage']); ?>" alt="<?php echo esc_attr($args['productAlt']); ?>" class="w-full h-auto object-cover rounded-lg" />
  </div>
  <div class="flex flex-col justify-center items-center grow group-hover:opacity-0 transition-opacity duration-300">
    <img src="<?php echo esc_url($args['brandImage']); ?>" alt="<?php echo esc_attr($args['brandAlt']); ?>" class="max-h-10 w-auto h-auto object-contain" />
  </div>
  <div class="absolute inset-0 bg-white/1 flex flex-col items-center p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl">
    <div class="flex mb-4">
      <img src="<?php echo esc_url($args['brandImage']); ?>" alt="<?php echo esc_attr($args['brandAlt']); ?>" class="max-h-10 w-auto h-auto object-contain" />
    </div>
    <p class="text-white text-lg leading-relaxed overflow-y-auto max-h-50 scrollbar-custom"><?php echo esc_html($args['description']); ?></p>
    <a href="<?php echo esc_url($args['buttonUrl']); ?>" class="mt-auto w-full flex justify-end items-center italic text-xl text-white cursor-pointer hover:opacity-80 transition-opacity">
      <?php echo esc_html($args['buttonText']); ?><?php echo get_icon('chevron-right', 'w-8 h-8'); ?>
    </a>
  </div>
</div>
