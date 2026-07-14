<?php
$title = (string) get_option('our_story_title', 'Nuestra historia');
if ($title === '') {
    $title = 'Nuestra historia';
}

$paragraph_1 = (string) get_option(
    'our_story_paragraph_1',
    'Grupo Fadiar nació en 2023 con la visión de transformar la industria nacional. Partiendo  de un pequeño taller, hemos crecido hasta convertirnos en un grupo empresarial que  integra tres marcas referentes.'
);
$paragraph_2 = (string) get_option(
    'our_story_paragraph_2',
    'Nuestros hitos incluyen la apertura de nuestras  instalaciones en Ciudad Libertad, el lanzamiento de nuestras primeras líneas de  productos y las alianzas con distribuidores en todo el país e internacionales. Hoy,  seguimos construyendo el futuro con pasión y responsabilidad.'
);

$our_story_items = array();
if (post_type_exists('our_story_item')) {
    $our_story_items = get_posts(array(
        'post_type'      => 'our_story_item',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ));
}

function grupofadiar_render_our_story_item($post) {
    $args = array(
        'title' => $post->post_title,
    );

    $leaders = get_field('osi_leaders', $post->ID);
    if (is_array($leaders) && count($leaders) > 0) {
        $mapped = array();
        foreach ($leaders as $leader) {
            $image_url = '';
            if (!empty($leader['image'])) {
                $image = $leader['image'];
                if (is_array($image) && !empty($image['url'])) {
                    $image_url = $image['url'];
                } elseif (is_numeric($image)) {
                    $image_url = wp_get_attachment_url((int) $image);
                } elseif (is_string($image)) {
                    $image_url = $image;
                }
            }
            $mapped[] = array(
                'name'              => isset($leader['name']) ? (string) $leader['name'] : '',
                'image'             => (string) $image_url,
                'shortDescription'  => isset($leader['short_description']) ? (string) $leader['short_description'] : '',
                'fullDescription'   => isset($leader['full_description']) ? (string) $leader['full_description'] : '',
            );
        }
        $args['leaders'] = $mapped;
    } else {
        $bullets = get_field('osi_bullets', $post->ID);
        if (is_array($bullets) && count($bullets) > 0) {
            $mapped = array();
            foreach ($bullets as $row) {
                if (isset($row['bullet'])) {
                    $mapped[] = '• ' . (string) $row['bullet'];
                }
            }
            $args['bullets'] = $mapped;
        } else {
            $text = (string) get_field('osi_text', $post->ID);
            if ($text === '') {
                $text = '·';
            }
            $args['content'] = $text;
        }
    }

    get_template_part('components/accordion-item/accordion-item', null, $args);
}
?>
<section class="mt-10 w-full bg-[#F4F4F4]">
  <div class="w-full p-20">
    <div class="flex justify-around items-start gap-20">
      <div class="w-1/2 text-xl">
        <h2 class="text-5xl font-black"><?php echo esc_html($title); ?></h2>
        <?php if ($paragraph_1 !== ''): ?>
          <p class="mt-6 text-justify"><?php echo esc_html($paragraph_1); ?></p>
        <?php endif; ?>
        <?php if ($paragraph_2 !== ''): ?>
          <p class="mt-4 text-justify"><?php echo esc_html($paragraph_2); ?></p>
        <?php endif; ?>
      </div>
      <div class="flex flex-col items-center gap-4 w-1/2">
        <?php if (!empty($our_story_items)): ?>
          <?php foreach ($our_story_items as $item_post): ?>
            <?php grupofadiar_render_our_story_item($item_post); ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
