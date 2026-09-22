<?php
$title           = isset($args['title']) ? (string) $args['title'] : '';
$content         = isset($args['content']) ? (string) $args['content'] : '';
$leaders         = isset($args['leaders']) ? $args['leaders'] : null;
$bullets         = isset($args['bullets']) ? $args['bullets'] : null;
$content_items   = is_array($content) ? $content : array($content);
?>
<div class="reveal-item accordion-item w-full rounded-lg shadow-xl cursor-pointer transition-all duration-300 bg-white text-dark">
  <div class="accordion-header flex items-center justify-between gap-4 p-5 xl:p-6">
    <h3 class="text-base font-bold md:text-lg xl:text-3xl"><?php echo esc_html($title); ?></h3>
    <div class="accordion-icon-down bg-dark p-1 rounded-full">
      <svg class="h-4 w-4 text-white xl:h-7 xl:w-7" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </div>
    <div class="accordion-icon-up bg-white p-1 rounded-full hidden">
      <svg class="h-4 w-4 text-dark xl:h-7 xl:w-7" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
    </div>
  </div>
  <div class="accordion-content hidden p-5 pt-0 text-sm text-white md:text-base xl:p-6 xl:pt-0 xl:text-lg">
    <?php if (!empty($content_items)): ?>
      <div class="flex flex-col gap-2">
        <?php foreach ($content_items as $item): ?>
          <div><?php echo wp_kses_post($item); ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($leaders)): ?>
      <ul class="flex flex-col <?php echo !empty($content_items) ? 'mt-4 divide-y-2 divide-white/20' : 'divide-y-2 divide-white/20'; ?>">
        <?php foreach ($leaders as $leader): ?>
          <li class="flex flex-col py-4 first:pt-0">
            <div class="flex flex-col items-start gap-4 sm:flex-row">
              <img src="<?php echo esc_url($leader['image']); ?>" alt="<?php echo esc_attr($leader['name']); ?>" width="180" height="180" class="aspect-square w-full max-w-45 shrink-0 rounded-md object-cover sm:w-36 xl:w-45" />
              <div class="flex flex-col gap-2">
                <span class="text-lg font-bold xl:text-xl"><?php echo esc_html($leader['name']); ?></span>
                <div class="text-sm text-white/80 md:text-base xl:text-lg"><?php echo wp_kses_post($leader['shortDescription']); ?></div>
              </div>
            </div>
            <div class="mt-4 text-sm text-white/80 md:text-base xl:text-lg"><?php echo wp_kses_post($leader['fullDescription']); ?></div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php elseif (!empty($bullets) && is_array($bullets)): ?>
      <ul class="flex flex-col gap-2">
        <?php foreach ($bullets as $bullet): ?>
          <li><?php echo wp_kses_post($bullet); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>
