<?php
/**
 * Registro de campos ACF para la sección Nuestras Marcas (Inicio)
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_brands_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        // Grupo de campos para la sección (títulos globales) - CPT brands_section
        acf_add_local_field_group(array(
            'key' => 'group_brands_section_settings',
            'title' => 'Configuración de Títulos de la Sección',
            'fields' => array(
                array(
                    'key' => 'field_brands_section_title',
                    'label' => 'Título (H2)',
                    'name' => 'brands_section_title',
                    'type' => 'text',
                    'instructions' => 'Texto pequeño del encabezado. Se muestra en color secundario y centrado.',
                    'default_value' => 'Nuestras marcas',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_brands_section_subtitle',
                    'label' => 'Subtítulo (H3)',
                    'name' => 'brands_section_subtitle',
                    'type' => 'text',
                    'instructions' => 'Texto grande del encabezado que aparece debajo.',
                    'default_value' => 'Diversidad de soluciones, un solo compromiso',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'brands_section',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
            'description' => 'Configura los títulos globales de la sección Nuestras Marcas.',
        ));

        // Grupo de campos para cada marca individual - CPT brand
        acf_add_local_field_group(array(
            'key' => 'group_brand_fields',
            'title' => 'Información de la Marca',
            'fields' => array(
                array(
                    'key' => 'field_brand_logo',
                    'label' => 'Logo de la Marca',
                    'name' => 'brand_logo',
                    'type' => 'image',
                    'instructions' => 'Sube el logo de la marca. Se muestra en la card y en el hover.',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_brand_description',
                    'label' => 'Descripción',
                    'name' => 'brand_description',
                    'type' => 'textarea',
                    'instructions' => 'Descripción que aparece al hacer hover sobre la card.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_brand_description_en',
                    'label' => 'Description (English)',
                    'name' => 'brand_description_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the description.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_brand_button_text',
                    'label' => 'Texto del Botón',
                    'name' => 'brand_button_text',
                    'type' => 'text',
                    'instructions' => 'Texto del botón "Ver más". Deja vacío para ocultar el botón.',
                    'default_value' => 'Ver más',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_brand_button_text_en',
                    'label' => 'Button Text (English)',
                    'name' => 'brand_button_text_en',
                    'type' => 'text',
                    'instructions' => 'English version of the button text.',
                    'default_value' => 'View more',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_brand_button_url',
                    'label' => 'URL del Botón',
                    'name' => 'brand_button_url',
                    'type' => 'text',
                    'instructions' => 'Enlace al que apuntará el botón. Escribe la URL completa para enlaces externos o solo la ruta para páginas internas.',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_brand_button_text',
                                'operator' => '!=empty',
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
                        'value' => 'brand',
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
            'description' => 'Campos personalizados para gestionar cada marca de la sección Nuestras Marcas en la página de inicio.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_brands_acf_fields');
