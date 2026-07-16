<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_seed_pilares_corporativos() {
    if (!post_type_exists('pilar_corporativo')) {
        return;
    }

    $count = wp_count_posts('pilar_corporativo');
    if ($count && isset($count->publish) && (int) $count->publish > 0) {
        return;
    }

    $pillars = array(
        array(
            'post_title'   => 'RESPONSABILIDAD SOCIAL',
            'menu_order'   => 0,
            'subtitle'     => 'Compromiso social y proyectos comunitarios',
            'description'  => '<p>En Grupo Fadiar, creemos que el desarrollo empresarial y el progreso de nuestra comunidad deben ir de la mano. Por eso, impulsamos y participamos en proyectos que generan un impacto positivo.</p><p><strong>Programa "Alma" por Fadiar</strong><br>Nuestra iniciativa bandera de apoyo a proyectos comunitarios. A través de donaciones de productos, voluntariado corporativo y financiamiento de pequeñas iniciativas locales, buscamos ser un agente de cambio allá donde operamos.</p><p><strong>Colaboración con Festival PaCuba</strong><br>Apoyamos la cultura y el deporte cubano. Recientemente, aportamos un kit solar que ayudó a mantener el evento PaCuba con energía estable y sostenible.</p><p><strong>Comunidades Sostenibles</strong><br>Trabajamos con gobiernos municipales y organizaciones de la sociedad civil para desarrollar proyectos de agricultura urbana, sistemas de reciclaje y programas de eficiencia energética en comunidades vulnerables.</p>',
            'image_position' => 'right',
            'image_count'   => '3',
        ),
        array(
            'post_title'   => 'ESTRATEGIA EMPRESARIAL',
            'menu_order'   => 1,
            'subtitle'     => '',
            'description'  => '<p>Nuestra estrategia se asienta sobre tres pilares fundamentales:</p><p><strong>1. Expansión de mercado</strong> — llegar a más clientes en todo el territorio nacional.</p><p><strong>2. Innovación tecnológica</strong> — desarrollo de productos más eficientes y conectados.</p><p><strong>3. Sostenibilidad</strong> — reducción de la huella ambiental y apoyo a la economía circular. Estos pilares nos guían en la toma de decisiones y en la asignación de recursos.</p>',
            'image_position' => 'left',
            'image_count'   => '2',
        ),
        array(
            'post_title'   => 'I+D+i',
            'menu_order'   => 2,
            'subtitle'     => '',
            'description'  => '<p>Invertimos en I+D+i para estar a la vanguardia. Actualmente trabajamos en:</p><ul><li><strong>Electrodomésticos eficientes:</strong> reducción del consumo energético hasta un 30% con motores inverter.</li><li><strong>Materiales sostenibles:</strong> uso de bioplásticos y maderas certificadas.</li><li><strong>Iluminación inteligente:</strong> sistemas controlados por voz y aplicación móvil.</li></ul>',
            'image_position' => 'right',
            'image_count'   => '1',
        ),
    );

    foreach ($pillars as $data) {
        $post_id = wp_insert_post(array(
            'post_type'   => 'pilar_corporativo',
            'post_title'  => $data['post_title'],
            'post_status' => 'publish',
            'menu_order'  => $data['menu_order'],
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_field('pillar_subtitle', $data['subtitle'], $post_id);
            update_field('pillar_subtitle_bold', 0, $post_id);
            update_field('pillar_description', $data['description'], $post_id);
            update_field('pillar_image_position', $data['image_position'], $post_id);
            update_field('pillar_image_count', $data['image_count'], $post_id);

            update_field('pillar_image_1', '', $post_id);
            update_field('pillar_image_2', '', $post_id);
            update_field('pillar_image_3', '', $post_id);
        }
    }
}
add_action('after_switch_theme', 'grupofadiar_seed_pilares_corporativos');
add_action('admin_init', 'grupofadiar_seed_pilares_corporativos');
