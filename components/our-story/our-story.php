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
<section id="ourStory" class="mt-10 w-full scroll-mt-20 bg-[#F4F4F4]">
  <div class="w-full px-8 py-10 md:px-12 md:py-12 xl:px-30 xl:py-15">
    <div class="flex flex-col items-start gap-8 xl:flex-row xl:justify-around xl:gap-20">
      <div class="w-full text-sm leading-snug md:text-base xl:w-1/2 xl:text-lg">
        <h2 class="reveal-item text-2xl font-black md:text-3xl xl:text-4xl"><?php echo esc_html($title); ?></h2>
        <?php if ($paragraph_1 !== ''): ?>
          <div class="reveal-item mt-3 xl:mt-6 xl:text-justify"><?php echo wpautop(wp_kses_post($paragraph_1)); ?></div>
        <?php endif; ?>
        <?php if ($paragraph_2 !== ''): ?>
          <div class="reveal-item mt-4 xl:text-justify"><?php echo wpautop(wp_kses_post($paragraph_2)); ?></div>
        <?php endif; ?>
      </div>
      <div class="flex w-full flex-col items-center gap-3 xl:w-1/2 xl:gap-4">
        <?php if (!empty($our_story_items)): ?>
          <?php foreach ($our_story_items as $item_post): ?>
            <?php grupofadiar_render_our_story_item($item_post); ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
