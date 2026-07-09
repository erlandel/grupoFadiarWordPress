<?php
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_noticias_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_noticia_gallery',
            'title' => 'Galería de Imágenes',
            'fields' => array(
                array(
                    'key' => 'field_noticia_gallery',
                    'label' => 'Galería de la Noticia',
                    'name' => 'galeria_noticia',
                    'type' => 'gallery',
                    'instructions' => 'Añade imágenes adicionales para la galería. La imagen destacada se usará como primera imagen.',
                    'required' => 0,
                    'return_format' => 'array',
                    'library' => 'all',
                    'min' => 0,
                    'max' => 10,
                    'preview_size' => 'medium',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'noticia',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_noticias_acf_fields');