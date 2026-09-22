<?php
$prefix = isset($args['prefix']) && $args['prefix'] === 'category' ? 'category' : 'filter';
$instance_id = isset($args['instance_id']) ? sanitize_html_class($args['instance_id']) : $prefix . '-category-modal';
$trigger_label = isset($args['trigger_label']) ? (string) $args['trigger_label'] : '';
$modal_title = isset($args['modal_title']) ? (string) $args['modal_title'] : '';
$close_label = isset($args['close_label']) ? (string) $args['close_label'] : '';
$selected_value = isset($args['selected_value']) ? (string) $args['selected_value'] : '';
$options = isset($args['options']) && is_array($args['options']) ? $args['options'] : array();
$mobile_only = !empty($args['mobile_only']);

if ($trigger_label === '' || $modal_title === '' || empty($options)) {
    return;
}

$root_class = $mobile_only ? 'md:hidden' : '';
$trigger_attribute = 'data-' . $prefix . '-open';
$modal_attribute = 'data-' . $prefix . '-modal';
$close_attribute = 'data-' . $prefix . '-close';
$form_attribute = 'data-' . $prefix . '-form';
$title_id = $instance_id . '-title';
$dialog_id = $instance_id . '-dialog';
$radio_name = $instance_id . '-options';
?>
<div data-category-modal-component="<?php echo esc_attr($instance_id); ?>" class="<?php echo esc_attr($root_class); ?>">
  <div class="flex justify-end">
    <button type="button" <?php echo esc_attr($trigger_attribute); ?> aria-expanded="false" aria-controls="<?php echo esc_attr($dialog_id); ?>" class="reveal-item relative flex w-fit shrink-0 cursor-pointer items-center gap-2 group">
      <span class="whitespace-nowrap text-base font-medium text-dark md:text-lg xl:text-2xl"><?php echo esc_html($trigger_label); ?></span>
      <svg class="pointer-events-none h-5 w-5 text-dark transition-transform duration-200 md:h-6 md:w-6 xl:h-8 xl:w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </button>
  </div>

  <div <?php echo esc_attr($modal_attribute); ?> id="<?php echo esc_attr($dialog_id); ?>" class="fixed inset-0 z-100 hidden items-center justify-center bg-black/40 p-4" aria-hidden="true">
    <div class="max-h-[calc(100dvh-2rem)] w-full max-w-md overflow-x-hidden overflow-y-auto rounded-xl bg-[#F8F8F8] shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($title_id); ?>">
      <div class="flex items-center justify-between px-5 pb-3 pt-5 md:px-6 md:pb-4 md:pt-6">
        <h2 id="<?php echo esc_attr($title_id); ?>" class="text-xl font-bold text-dark md:text-2xl"><?php echo esc_html($modal_title); ?></h2>
        <button type="button" <?php echo esc_attr($close_attribute); ?> class="cursor-pointer text-dark transition-opacity hover:opacity-70" aria-label="<?php echo esc_attr($close_label); ?>">
          <svg class="h-6 w-6 md:h-7 md:w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>

      <form <?php echo esc_attr($form_attribute); ?> class="px-5 pb-5 md:px-6 md:pb-6">
        <div class="flex flex-col">
          <?php foreach ($options as $index => $option):
              $value = isset($option['value']) ? (string) $option['value'] : '';
              $label = isset($option['label']) ? (string) $option['label'] : '';
              $url = isset($option['url']) ? (string) $option['url'] : '';
              if ($label === '') {
                  continue;
              }
              $is_selected = $value === $selected_value;
              $input_size = $index === 0 ? 'h-4 w-4' : 'h-4 w-4';
          ?>
            <label class="-mx-2 flex cursor-pointer items-center gap-3 rounded border-b-3 border-[#EDEDED] px-2 py-3 text-base text-dark transition-colors hover:bg-gray-200 last:border-0 md:gap-4 md:py-4 md:text-xl">
              <input type="radio" name="<?php echo esc_attr($radio_name); ?>" value="<?php echo esc_attr($value); ?>" data-modal-url="<?php echo esc_url($url); ?>" <?php checked($is_selected); ?> class="<?php echo esc_attr($input_size); ?> shrink-0 cursor-pointer accent-dark">
              <span class="<?php echo $is_selected ? 'font-bold' : 'font-normal'; ?>"><?php echo esc_html($label); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </form>
    </div>
  </div>
</div>
