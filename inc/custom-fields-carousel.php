<?php
/**
 * Registro de campos ACF para el Carousel del Home
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_carousel_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_carousel_slide_fields',
            'title' => 'Configuración de la Diapositiva',
            'fields' => array(
                array(
                    'key' => 'field_slide_layout',
                    'label' => 'Layout de la Diapositiva',
                    'name' => 'slide_layout',
                    'type' => 'select',
                    'instructions' => 'Selecciona el diseño visual para esta diapositiva. El tipo "Simple usa un título de texto, mientras que "Marca" usa una imagen de logo y una descripción.',
                    'required' => 1,
                    'choices' => array(
                        'simple' => 'Diseño Simple',
                        'brand' => 'Diseño de Marca',
                    ),
                    'default_value' => 'simple',
                    'ui' => 1,
                    'return_format' => 'value',
                ),
                // Campos específicos para layout brand
                array(
                    'key' => 'field_slide_title_image',
                    'label' => 'Imagen del Título (Logo)',
                    'name' => 'slide_title_image',
                    'type' => 'image',
                    'instructions' => 'Sube la imagen del logo o título para el diseño de marca.',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_slide_layout',
                                'operator' => '==',
                                'value' => 'brand',
                            ),
                        ),
                    ),
                ),
                // Campos específicos para layout simple
                array(
                    'key' => 'field_slide_title_text',
                    'label' => 'Título (Texto)',
                    'name' => 'slide_title_text',
                    'type' => 'text',
                    'instructions' => 'El texto del título principal para el diseño simple.',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_slide_layout',
                                'operator' => '==',
                                'value' => 'simple',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_slide_description',
                    'label' => 'Descripción Principal',
                    'name' => 'slide_description',
                    'type' => 'textarea',
                    'instructions' => 'Texto descriptivo principal que aparece en el diseño de marca (debajo del logo).',
                    'rows' => 4,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_slide_layout',
                                'operator' => '==',
                                'value' => 'brand',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_slide_subtitle',
                    'label' => 'Subtítulo',
                    'name' => 'slide_subtitle',
                    'type' => 'text',
                    'instructions' => 'Texto secundario que aparece debajo del título principal (en diseño simple) o debajo de la descripción (en diseño de marca).',
                    'conditional_logic' => 0,
                ),
                array(
                    'key' => 'field_slide_title_font_class',
                    'label' => 'Clase de Fuente del Título',
                    'name' => 'slide_title_font_class',
                    'type' => 'select',
                    'instructions' => 'Selecciona la fuente tipográfica para el título o la descripción principal.',
                    'choices' => array(
                        'default' => 'Por Defecto (Sans-serif)',
                        'font-montserrat' => 'Montserrat',
                        'font-montserrat font-bold' => 'Montserrat Bold',
                        'font-montserrat font-black' => 'Montserrat Black',
                        'font-open' => 'Open Sans',
                        'font-satisfy' => 'Satisfy (Script)',
                        'font-dancing-script' => 'Dancing Script (Script)',
                        'font-flatlion' => 'Flatlion Personal Use Only',
                    ),
                    'default_value' => 'font-montserrat',
                    'ui' => 1,
                    'return_format' => 'value',
                ),
                // Campos del Botón 1
                array(
                    'key' => 'field_button_1_text',
                    'label' => 'Texto del Botón 1',
                    'name' => 'button_1_text',
                    'type' => 'text',
                    'instructions' => 'Texto visible en el primer botón. Déjalo vacío para omitir este botón.',
                    'placeholder' => 'Ej: Conócenos',
                ),
                array(
                    'key' => 'field_button_1_url',
                    'label' => 'URL del Botón 1',
                    'name' => 'button_1_url',
                    'type' => 'url',
                    'instructions' => 'La dirección web a la que apunta el primer botón.',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_button_1_text',
                                'operator' => '!=empty',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_button_1_style',
                    'label' => 'Estilo del Botón 1',
                    'name' => 'button_1_style',
                    'type' => 'select',
                    'instructions' => 'Selecciona el estilo visual del primer botón.',
                    'choices' => array(
                        'primary' => 'Primario (Fondo blanco, texto oscuro)',
                        'secondary' => 'Secundario (Borde blanco, texto blanco)',
                    ),
                    'default_value' => 'primary',
                    'ui' => 1,
                    'return_format' => 'value',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_button_1_text',
                                'operator' => '!=empty',
                            ),
                        ),
                    ),
                ),
                // Campos del Botón 2
                array(
                    'key' => 'field_button_2_text',
                    'label' => 'Texto del Botón 2',
                    'name' => 'button_2_text',
                    'type' => 'text',
                    'instructions' => 'Texto visible en el segundo botón. Déjalo vacío para omitir este botón.',
                    'placeholder' => 'Ej: Ver Menú',
                ),
                array(
                    'key' => 'field_button_2_url',
                    'label' => 'URL del Botón 2',
                    'name' => 'button_2_url',
                    'type' => 'url',
                    'instructions' => 'La dirección web a la que apunta el segundo botón.',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_button_2_text',
                                'operator' => '!=empty',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_button_2_style',
                    'label' => 'Estilo del Botón 2',
                    'name' => 'button_2_style',
                    'type' => 'select',
                    'instructions' => 'Selecciona el estilo visual del segundo botón.',
                    'choices' => array(
                        'primary' => 'Primario (Fondo blanco, texto oscuro)',
                        'secondary' => 'Secundario (Borde blanco, texto blanco)',
                    ),
                    'default_value' => 'secondary',
                    'ui' => 1,
                    'return_format' => 'value',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_button_2_text',
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
                        'value' => 'carousel_slide',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => 'Campos personalizados para gestionar el contenido de cada diapositiva del carrusel principal.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_carousel_acf_fields');
