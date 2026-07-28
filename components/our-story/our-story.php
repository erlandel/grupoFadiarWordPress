<?php
$title = (string) gf_get_option('our_story_title', 'Nuestra historia', 'Our Story');
if ($title === '') {
    $title = gf_current_lang() === 'en' ? 'Our Story' : 'Nuestra historia';
}

$paragraph_1 = (string) gf_get_option(
    'our_story_paragraph_1',
    'Grupo Fadiar nació en 2023 con la visión de transformar la industria nacional. Partiendo  de un pequeño taller, hemos crecido hasta convertirnos en un grupo empresarial que  integra tres marcas referentes.',
    'Grupo Fadiar was born in 2023 with the vision of transforming the national industry.'
);
$paragraph_2 = (string) gf_get_option(
    'our_story_paragraph_2',
    'Nuestros hitos incluyen la apertura de nuestras  instalaciones en Ciudad Libertad, el lanzamiento de nuestras primeras líneas de  productos y las alianzas con distribuidores en todo el país e internacionales. Hoy,  seguimos construyendo el futuro con pasión y responsabilidad.',
    'Our milestones include the opening of our facilities in Ciudad Libertad, the launch of our first product lines, and partnerships with distributors nationwide and internationally.'
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
        'title' => gf_get_post_title($post->ID),
    );

    $tipo = get_field('tipo_contenido', $post->ID);

    if ($tipo === 'lider') {
        $intro = gf_get_field('osi_intro_text', $post->ID);
        if (!empty($intro)) {
            $args['content'] = wp_kses_post($intro);
        }

        $name              = (string) gf_get_field('osi_leader_name', $post->ID);
        $image             = get_field('osi_leader_image', $post->ID);
        $short_description = (string) gf_get_field('osi_leader_short_description', $post->ID);
        $full_description  = (string) gf_get_field('osi_leader_full_description', $post->ID);

        if ($name !== '' || !empty($image) || $short_description !== '' || $full_description !== '') {
            $image_url = '';
            if (!empty($image)) {
                if (is_array($image) && !empty($image['url'])) {
                    $image_url = $image['url'];
                } elseif (is_numeric($image)) {
                    $image_url = wp_get_attachment_url((int) $image);
                } elseif (is_string($image)) {
                    $image_url = $image;
                }
            }

            $args['leaders'] = array(array(
                'name'              => $name,
                'image'             => (string) $image_url,
                'shortDescription'  => wp_kses_post($short_description),
                'fullDescription'   => wp_kses_post($full_description),
            ));
        }
    } else {
        $text = gf_get_field('osi_text', $post->ID);
        if (empty($text)) {
            $text = '';
        }
        $args['content'] = wp_kses_post($text);
    }

    get_template_part('components/accordion-item/accordion-item', null, $args);
}
?>
<section id="ourStory" class="scroll-mt-20 mt-10 w-full bg-[#F4F4F4]">
  <div class="w-full py-15 px-30">
    <div class="flex justify-around items-start gap-20">
      <div class="w-1/2 text-lg">
        <h2 class="text-4xl font-black"><?php echo esc_html($title); ?></h2>
        <?php if ($paragraph_1 !== ''): ?>
          <div class="mt-6 text-justify"><?php echo wpautop(wp_kses_post($paragraph_1)); ?></div>
        <?php endif; ?>
        <?php if ($paragraph_2 !== ''): ?>
          <div class="mt-4 text-justify"><?php echo wpautop(wp_kses_post($paragraph_2)); ?></div>
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
