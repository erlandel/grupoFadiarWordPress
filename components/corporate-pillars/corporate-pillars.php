<?php
$pillars = array();
if (post_type_exists('pilar_corporativo')) {
    $pillars = grupofadiar_get_pilares_corporativos();
}
$pillar_count = count($pillars);

function grupofadiar_get_pillar_image_url($image) {
    if (empty($image)) {
        return '';
    }
    if (is_array($image) && !empty($image['url'])) {
        return $image['url'];
    }
    if (is_numeric($image)) {
        $url = wp_get_attachment_url((int) $image);
        return $url ?: '';
    }
    if (is_string($image)) {
        return $image;
    }
    return '';
}

function render_pillar_text($post_id, $item_class = '') {
    $subtitle      = gf_get_field('pillar_subtitle', $post_id);
    $subtitle_bold = (bool) get_field('pillar_subtitle_bold', $post_id);
    if (!empty($subtitle)) {
        $subtitle_class = 'mt-4 mb-3 text-sm leading-snug text-dark xl:mt-8 xl:mb-5 xl:text-2xl xl:leading-relaxed' . ($subtitle_bold ? ' font-bold' : '');
        echo '<p class="' . esc_attr($subtitle_class . ($item_class ? ' ' . esc_attr($item_class) : '')) . '">' . esc_html($subtitle) . '</p>';
    }

    $description = gf_get_field('pillar_description', $post_id);
    if (!empty($description)) {
        echo '<div class="pillar-description text-sm leading-snug text-dark sm:[&_p]:mb-4 [&_p:last-child]:mb-0 xl:text-lg xl:leading-relaxed xl:text-justify' . ($item_class ? ' ' . esc_attr($item_class) : '') . '">' . wp_kses_post($description) . '</div>';
    }
}

function render_pillar_image($post_id, $item_class = '') {
    $image_count = get_field('pillar_image_count', $post_id);
    if (empty($image_count)) {
        $image_count = '1';
    }

    $image_1 = get_field('pillar_image_1', $post_id);
    $image_2 = get_field('pillar_image_2', $post_id);
    $image_3 = get_field('pillar_image_3', $post_id);

    $url_1 = grupofadiar_get_pillar_image_url($image_1);
    $ic = $item_class ? ' ' . esc_attr($item_class) : '';

    if ($image_count === '3') {
        $url_2 = grupofadiar_get_pillar_image_url($image_2);
        $url_3 = grupofadiar_get_pillar_image_url($image_3);
        echo '<div class="flex h-66 flex-col gap-4 md:h-96 xl:h-full">';
        if ($url_1) {
            echo '<div class="bg-[#F4F4F4] w-full overflow-hidden' . $ic . '" style="flex: 1 1 0;"><img src="' . esc_url($url_1) . '" alt="" class="w-full h-full object-cover"></div>';
        } else {
            echo '<div class="bg-[#F4F4F4] w-full' . $ic . '" style="flex: 1 1 0;"></div>';
        }
        echo '<div class="flex gap-4" style="flex: 1 1 0;">';
        if ($url_2) {
            echo '<div class="bg-[#F4F4F4] w-full overflow-hidden' . $ic . '"><img src="' . esc_url($url_2) . '" alt="" class="w-full h-full object-cover"></div>';
        } else {
            echo '<div class="bg-[#F4F4F4] w-full' . $ic . '"></div>';
        }
        if ($url_3) {
            echo '<div class="bg-[#F4F4F4] w-full overflow-hidden' . $ic . '"><img src="' . esc_url($url_3) . '" alt="" class="w-full h-full object-cover"></div>';
        } else {
            echo '<div class="bg-[#F4F4F4] w-full' . $ic . '"></div>';
        }
        echo '</div>';
        echo '</div>';
    } elseif ($image_count === '2') {
        $url_2 = grupofadiar_get_pillar_image_url($image_2);
        echo '<div class="flex h-66 flex-col gap-4 md:h-96 xl:h-full">';
        if ($url_1) {
            echo '<div class="bg-[#F4F4F4] w-full overflow-hidden' . $ic . '" style="flex: 1 1 0;"><img src="' . esc_url($url_1) . '" alt="" class="w-full h-full object-cover"></div>';
        } else {
            echo '<div class="bg-[#F4F4F4] w-full' . $ic . '" style="flex: 1 1 0;"></div>';
        }
        if ($url_2) {
            echo '<div class="bg-[#F4F4F4] w-full overflow-hidden' . $ic . '" style="flex: 1 1 0;"><img src="' . esc_url($url_2) . '" alt="" class="w-full h-full object-cover"></div>';
        } else {
            echo '<div class="bg-[#F4F4F4] w-full' . $ic . '" style="flex: 1 1 0;"></div>';
        }
        echo '</div>';
    } else {
        if ($url_1) {
            echo '<div class="h-66 w-full overflow-hidden bg-[#F4F4F4] md:h-96 xl:h-full' . $ic . '"><img src="' . esc_url($url_1) . '" alt="" class="w-full h-full object-cover"></div>';
        } else {
            echo '<div class="h-66 w-full bg-[#F4F4F4] md:h-96 xl:h-full' . $ic . '"></div>';
        }
    }
}
?>
<section class="my-10 w-full space-y-8 px-8 md:px-12 xl:my-20 xl:space-y-10 xl:px-30">
  <?php foreach ($pillars as $pillar_index => $pillar):
    $post_id = $pillar->ID;
    $image_position = get_field('pillar_image_position', $post_id);
    if (empty($image_position)) {
        $image_position = 'right';
    }
  ?>
      <div id="pillar-<?php echo esc_attr($pillar->post_name); ?>" class="grid scroll-mt-20 grid-cols-1 items-stretch gap-6 xl:grid-cols-2 xl:gap-10">
      <?php if ($image_position === 'left'): ?>
        <div class="order-2 h-full xl:order-1">
          <?php render_pillar_image($post_id, 'reveal-item'); ?>
        </div>
        <div class="order-1 flex flex-col justify-between xl:order-2">
          <div class="space-y-4">
            <h2 class="reveal-item text-lg font-black uppercase leading-tight tracking-tight text-dark md:text-2xl xl:text-4xl"><?php echo esc_html(gf_get_post_title($post_id)); ?></h2>
            <?php render_pillar_text($post_id, 'reveal-item'); ?>
          </div>
          <div class="reveal-item mt-8 hidden w-full border-2 border-dark xl:block"></div>
        </div>
      <?php else: ?>
        <div class="flex flex-col justify-between">
          <div class="space-y-4">
            <h2 class="reveal-item text-lg font-black uppercase leading-tight tracking-tight text-dark md:text-2xl xl:text-4xl"><?php echo esc_html(gf_get_post_title($post_id)); ?></h2>
            <?php render_pillar_text($post_id, 'reveal-item'); ?>
          </div>
          <div class="reveal-item mt-8 hidden w-full border-2 border-dark xl:block"></div>
        </div>
        <div class="h-full">
          <?php render_pillar_image($post_id, 'reveal-item'); ?>
        </div>
      <?php endif; ?>
      <?php if ($pillar_index < $pillar_count - 1): ?>
        <div class="reveal-item order-3 w-full border border-dark xl:hidden"></div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</section>
