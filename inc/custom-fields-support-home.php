<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_support_home_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_support_item_fields',
            'title' => 'Información del Item',
            'fields' => array(
                array(
                    'key' => 'field_support_home_post_title_en',
                    'label' => 'Title (English)',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'English version of the title.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_support_item_image',
                    'label' => 'Imagen del Icono',
                    'name' => 'support_item_image',
                    'type' => 'image',
                    'instructions' => 'Sube la imagen del icono. Se mostrará en un contenedor de 72x72px.',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_support_item_description',
                    'label' => 'Descripción',
                    'name' => 'support_item_description',
                    'type' => 'textarea',
                    'instructions' => 'Descripción del item.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_support_item_description_en',
                    'label' => 'Description (English)',
                    'name' => 'support_item_description_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the description.',
                    'rows' => 4,
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'support_home_item',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array('the_content', 'excerpt'),
            'active' => true,
            'description' => 'Campos personalizados para gestionar cada item de la sección Soporte y Garantía en la página de inicio.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_support_home_acf_fields');
