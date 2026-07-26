<?php
/**
 * Registro de campos ACF para la sección Quiénes Somos (Inicio)
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_discover_group_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_discover_group_fields',
            'title' => 'Configuración de la Sección',
            'fields' => array(
                array(
                    'key' => 'field_discover_post_title_en',
                    'label' => 'Título en Inglés',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'Título en inglés de la sección.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_subtitle',
                    'label' => 'Subtítulo',
                    'name' => 'discover_subtitle',
                    'type' => 'text',
                    'instructions' => 'Texto del subtítulo que aparece debajo del título principal.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_subtitle_en',
                    'label' => 'Subtitle (English)',
                    'name' => 'discover_subtitle_en',
                    'type' => 'text',
                    'instructions' => 'English version of the subtitle.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_description_1',
                    'label' => 'Descripción 1 (Primer párrafo)',
                    'name' => 'discover_description_1',
                    'type' => 'textarea',
                    'instructions' => 'Primer párrafo de la sección.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_description_1_en',
                    'label' => 'Description 1 (English)',
                    'name' => 'discover_description_1_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the first paragraph.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_description_2',
                    'label' => 'Descripción 2 (Segundo párrafo)',
                    'name' => 'discover_description_2',
                    'type' => 'textarea',
                    'instructions' => 'Segundo párrafo de la sección.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_description_2_en',
                    'label' => 'Description 2 (English)',
                    'name' => 'discover_description_2_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the second paragraph.',
                    'rows' => 4,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_button_text',
                    'label' => 'Texto del Botón',
                    'name' => 'discover_button_text',
                    'type' => 'text',
                    'instructions' => 'Texto que aparecerá en el botón. Deja vacío para ocultar el botón.',
                    'default_value' => 'Ver más',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_button_text_en',
                    'label' => 'Button Text (English)',
                    'name' => 'discover_button_text_en',
                    'type' => 'text',
                    'instructions' => 'English version of the button text.',
                    'default_value' => 'Learn more',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_discover_button_url',
                    'label' => 'URL del Botón',
                    'name' => 'discover_button_url',
                    'type' => 'text',
                    'instructions' => 'Enlace al que apuntará el botón. Escribe la URL completa para enlaces externos (ej: https://www.google.com) o solo la ruta para páginas internas (ej: /about-us/ o about-us).',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_discover_button_text',
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
                        'value' => 'discover_group',
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
            'description' => 'Campos personalizados para gestionar el contenido de la sección Quiénes Somos en la página de inicio.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_discover_group_acf_fields');
