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
                    'key' => 'field_slide_background_mobile',
                    'label' => '1. Imagen de Fondo para Móvil y Tablet',
                    'name' => 'slide_background_mobile',
                    'type' => 'image',
                    'instructions' => 'Imagen de fondo utilizada en móvil y tablet, hasta el breakpoint XL (1280px). Si se deja vacía, se utilizará la imagen destacada para PC.',
                    'required' => 0,
                    'return_format' => 'id',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_slide_background_desktop',
                    'label' => '2. Imagen de Fondo para PC',
                    'name' => 'slide_background_desktop',
                    'type' => 'image',
                    'instructions' => 'Imagen de fondo utilizada desde el breakpoint XL (1280px). Las imágenes destacadas existentes se copiarán automáticamente a este campo.',
                    'required' => 0,
                    'return_format' => 'id',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
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
                    'key' => 'field_slide_title_text_en',
                    'label' => 'Title Text (English)',
                    'name' => 'slide_title_text_en',
                    'type' => 'text',
                    'instructions' => 'English version of the title text for simple layout.',
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
                    'key' => 'field_slide_description_en',
                    'label' => 'Main Description (English)',
                    'name' => 'slide_description_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the main description for brand layout.',
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
                    'key' => 'field_slide_subtitle_en',
                    'label' => 'Subtitle (English)',
                    'name' => 'slide_subtitle_en',
                    'type' => 'text',
                    'instructions' => 'English version of the subtitle.',
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
                    'key' => 'field_button_1_text_en',
                    'label' => 'Button 1 Text (English)',
                    'name' => 'button_1_text_en',
                    'type' => 'text',
                    'instructions' => 'English version of the first button text.',
                    'placeholder' => 'e.g. Learn More',
                ),
                array(
                    'key' => 'field_button_1_url',
                    'label' => 'URL del Botón 1',
                    'name' => 'button_1_url',
                    'type' => 'text',
                    'instructions' => 'La dirección web a la que apunta el primer botón. Escribe la URL completa para enlaces externos (ej: https://www.google.com) o solo la ruta para páginas internas (ej: /about-us/ o about-us).',
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
                    'key' => 'field_button_2_text_en',
                    'label' => 'Button 2 Text (English)',
                    'name' => 'button_2_text_en',
                    'type' => 'text',
                    'instructions' => 'English version of the second button text.',
                    'placeholder' => 'e.g. View Menu',
                ),
                array(
                    'key' => 'field_carousel_post_title_en',
                    'label' => 'Título en Inglés',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'Título de la diapositiva en inglés.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_button_2_url',
                    'label' => 'URL del Botón 2',
                    'name' => 'button_2_url',
                    'type' => 'text',
                    'instructions' => 'La dirección web a la que apunta el segundo botón. Escribe la URL completa para enlaces externos (ej: https://www.google.com) o solo la ruta para páginas internas (ej: /about-us/ o about-us).',
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

// Migra la imagen destacada existente al nuevo campo de PC sin sobrescribir datos del cliente.
function grupofadiar_migrate_carousel_desktop_images() {
    if (!function_exists('update_field')) {
        return;
    }

    $slide_ids = get_posts(array(
        'post_type'      => 'carousel_slide',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));

    foreach ($slide_ids as $slide_id) {
        $desktop_image = get_field('slide_background_desktop', $slide_id);
        $desktop_image_id = is_array($desktop_image) && isset($desktop_image['ID'])
            ? (int) $desktop_image['ID']
            : (int) $desktop_image;

        if ($desktop_image_id > 0) {
            update_post_meta($slide_id, '_grupofadiar_carousel_desktop_migrated', '1');
            continue;
        }

        if (get_post_meta($slide_id, '_grupofadiar_carousel_desktop_migrated', true)) {
            continue;
        }

        $featured_image_id = (int) get_post_thumbnail_id($slide_id);
        if ($featured_image_id > 0) {
            update_field('slide_background_desktop', $featured_image_id, $slide_id);
            update_post_meta($slide_id, '_grupofadiar_carousel_desktop_migrated', '1');
        }
    }
}

add_action('acf/init', 'grupofadiar_migrate_carousel_desktop_images', 20);
