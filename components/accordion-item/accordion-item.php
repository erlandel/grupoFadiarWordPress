<?php
$title = isset($args['title']) ? $args['title'] : '';
$content = isset($args['content']) ? $args['content'] : '';
$leaders = isset($args['leaders']) ? $args['leaders'] : null;
$items = is_array($content) ? $content : array($content);
?>
<div class="accordion-item w-full rounded-lg shadow-xl cursor-pointer transition-all duration-300 bg-white text-dark">
  <div class="accordion-header flex justify-between items-center p-6">
    <h3 class="text-4xl font-bold"><?php echo esc_html($title); ?></h3>
    <div class="accordion-icon-down bg-dark p-1.5 rounded-full">
      <svg class="h-7 w-7 text-white" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </div>
    <div class="accordion-icon-up bg-white p-1.5 rounded-full hidden">
      <svg class="h-7 w-7 text-dark" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
    </div>
  </div>
  <div class="accordion-content hidden p-6 pt-0 text-white text-xl ">
    <?php if (!empty($content) && !is_array($content)): ?>
      <p class="mb-6"><?php echo esc_html($content); ?></p>
    <?php endif; ?>
    <?php if ($leaders): ?>
      <ul class="flex flex-col divide-y-2 divide-white/20">
        <?php foreach ($leaders as $leader): ?>
          <li class="flex flex-col py-4 first:pt-0">
            <div class="flex items-start gap-4">
              <img src="<?php echo esc_url($leader['image']); ?>" alt="<?php echo esc_attr($leader['name']); ?>" width="180" height="180" class="rounded-md object-cover shrink-0" />
              <div class="flex flex-col gap-2">
                <span class="font-bold text-2xl"><?php echo esc_html($leader['name']); ?></span>
                <p class="text-xl text-white/80"><?php echo esc_html($leader['shortDescription']); ?></p>
              </div>
            </div>
            <p class="mt-4 text-xl text-white/80"><?php echo esc_html($leader['fullDescription']); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php elseif (!empty($items)): ?>
      <ul class="flex flex-col gap-2">
        <?php foreach ($items as $item): ?>
          <li><?php echo esc_html($item); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>
