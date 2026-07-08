<?php
/**
 * Registro de campos ACF para la sección Productos (Inicio)
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_products_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        // Grupo de campos para cada producto individual - CPT home_product
        acf_add_local_field_group(array(
            'key' => 'group_product_fields',
            'title' => 'Información del Producto',
            'fields' => array(
                array(
                    'key' => 'field_product_media_type',
                    'label' => 'Tipo de Medio',
                    'name' => 'product_media_type',
                    'type' => 'select',
                    'instructions' => 'Selecciona si este producto mostrará una imagen o un video.',
                    'required' => 1,
                    'choices' => array(
                        'image' => 'Imagen',
                        'video' => 'Video',
                    ),
                    'default_value' => 'image',
                    'ui' => 1,
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_product_media_file',
                    'label' => 'Archivo del Producto',
                    'name' => 'product_media_file',
                    'type' => 'file',
                    'instructions' => 'Sube una imagen o un video para este producto. Se mostrará según el tipo de medio seleccionado arriba.',
                    'required' => 0,
                    'return_format' => 'array',
                    'library' => 'all',
                    'mime_types' => 'jpg,jpeg,png,gif,webp,mp4,webm,ogv',
                ),
                array(
                    'key' => 'field_product_button_text',
                    'label' => 'Texto del Botón',
                    'name' => 'product_button_text',
                    'type' => 'text',
                    'instructions' => 'Texto del botón "Ver producto". Deja vacío para usar el texto por defecto.',
                    'default_value' => 'Ver producto',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_product_button_url',
                    'label' => 'URL del Botón',
                    'name' => 'product_button_url',
                    'type' => 'text',
                    'instructions' => 'Enlace al que apuntará el botón. Escribe la URL completa para enlaces externos o solo la ruta para páginas internas.',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'home_product',
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
            'description' => 'Campos personalizados para gestionar cada producto de la sección Productos en la página de inicio.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_products_acf_fields');
