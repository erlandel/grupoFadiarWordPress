<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_corporate_pillars_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_pilar_corporativo_fields',
            'title' => 'Contenido del Pilar',
            'fields' => array(
                array(
                    'key' => 'field_pillar_post_title_en',
                    'label' => 'Título en Inglés',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'Título del pilar corporativo en inglés.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_pillar_subtitle',
                    'label' => 'Subtítulo',
                    'name' => 'pillar_subtitle',
                    'type' => 'text',
                    'instructions' => 'Texto que aparece debajo del título del pilar (opcional).',
                    'default_value' => '',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_pillar_subtitle_en',
                    'label' => 'Subtitle (English)',
                    'name' => 'pillar_subtitle_en',
                    'type' => 'text',
                    'instructions' => 'English version of the subtitle.',
                    'default_value' => '',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_pillar_subtitle_bold',
                    'label' => 'Subtítulo en negrita',
                    'name' => 'pillar_subtitle_bold',
                    'type' => 'true_false',
                    'instructions' => 'Márcalo para resaltar el subtítulo en negrita.',
                    'required' => 0,
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => 'Sí',
                    'ui_off_text' => 'No',
                ),
                array(
                    'key' => 'field_pillar_description',
                    'label' => 'Descripción',
                    'name' => 'pillar_description',
                    'type' => 'wysiwyg',
                    'instructions' => 'Contenido principal del pilar. Puedes usar negritas, viñetas, párrafos, etc.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_pillar_description_en',
                    'label' => 'Description (English)',
                    'name' => 'pillar_description_en',
                    'type' => 'wysiwyg',
                    'instructions' => 'English version of the pillar description.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_pillar_image_position',
                    'label' => 'Posición de la imagen',
                    'name' => 'pillar_image_position',
                    'type' => 'radio',
                    'instructions' => '¿La imagen se muestra a la izquierda o a la derecha del texto?',
                    'required' => 1,
                    'choices' => array(
                        'right' => 'Derecha',
                        'left' => 'Izquierda',
                    ),
                    'default_value' => 'right',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_pillar_image_count',
                    'label' => 'Cantidad de imágenes',
                    'name' => 'pillar_image_count',
                    'type' => 'radio',
                    'instructions' => 'Selecciona cuántas imágenes mostrar en este pilar.',
                    'required' => 1,
                    'choices' => array(
                        '1' => '1 imagen',
                        '2' => '2 imágenes',
                        '3' => '3 imágenes',
                    ),
                    'default_value' => '1',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_pillar_image_1',
                    'label' => 'Imagen 1',
                    'name' => 'pillar_image_1',
                    'type' => 'image',
                    'instructions' => 'Imagen principal del pilar.',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'large',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_pillar_image_2',
                    'label' => 'Imagen 2',
                    'name' => 'pillar_image_2',
                    'type' => 'image',
                    'instructions' => 'Segunda imagen (solo visible si seleccionaste 2 o 3 imágenes).',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'large',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_pillar_image_count',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_pillar_image_3',
                    'label' => 'Imagen 3',
                    'name' => 'pillar_image_3',
                    'type' => 'image',
                    'instructions' => 'Tercera imagen (solo visible si seleccionaste 3 imágenes).',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'large',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_pillar_image_count',
                                'operator' => '==',
                                'value' => '3',
                            ),
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'pilar_corporativo',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'author', 'formats', 'categories', 'tags', 'send-trackbacks'),
            'active' => true,
            'description' => 'Campos personalizados para los pilares corporativos.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_corporate_pillars_acf_fields');

function grupofadiar_hide_wysiwyg_media_buttons_pilares() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'pilar_corporativo') {
        ?>
        <style>
          .post-type-pilar_corporativo .acf-field .wp-media-buttons,
          .post-type-pilar_corporativo .acf-field .mce-button.mce-wp-media { display: none !important; }
        </style>
        <?php
    }
}
add_action('acf/input/admin_head', 'grupofadiar_hide_wysiwyg_media_buttons_pilares');
