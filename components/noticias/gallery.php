<?php
$images = isset($args['images']) ? $args['images'] : array();
$total = count($images);
if ($total === 0) return;
?>
<div class="relative w-full overflow-hidden rounded-xl" x-data="{ current: 0 }">
  <div class="relative aspect-[16/9] bg-gray-100">
    <?php foreach ($images as $index => $image): ?>
      <div x-show="current === <?php echo $index; ?>" x-cloak class="absolute inset-0 transition-opacity duration-300">
        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" class="w-full h-full object-cover" />
      </div>
    <?php endforeach; ?>

    <?php if ($total > 1): ?>
      <button @click="current = current > 0 ? current - 1 : <?php echo $total - 1; ?>" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2.5 shadow-md transition z-10">
        <svg class="w-5 h-5 text-dark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <button @click="current = current < <?php echo $total - 1; ?> ? current + 1 : 0" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2.5 shadow-md transition z-10">
        <svg class="w-5 h-5 text-dark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </button>

      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/80 rounded-full px-4 py-1.5 text-sm font-bold text-dark shadow z-10" x-text="`${current + 1}/${<?php echo $total; ?>}`"></div>
    <?php endif; ?>
  </div>
</div>