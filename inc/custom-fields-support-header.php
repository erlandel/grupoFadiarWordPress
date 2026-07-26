<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_support_header_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_support_header_fields',
            'title' => 'Title (English)',
            'fields' => array(
                array(
                    'key' => 'field_support_header_post_title_en',
                    'label' => 'Title (English)',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'English version of the title.',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'support_header_item',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(),
            'active' => true,
            'description' => 'Campos personalizados para items del encabezado de soporte.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_support_header_acf_fields');
