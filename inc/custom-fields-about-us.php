<?php
/**
 * Registro de campos ACF para la página Sobre Nosotros (Grupo Fadiar)
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_about_us_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_about_us_fields',
            'title' => 'Configuración de Sobre Nosotros',
            'fields' => array(
                array(
                    'key' => 'field_about_page_title',
                    'label' => 'Título H1 de la página',
                    'name' => 'about_page_title',
                    'type' => 'text',
                    'instructions' => 'Texto que aparece como título principal de la página Sobre Nosotros.',
                    'default_value' => 'Grupo Fadiar – Innovación y compromiso social',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_page_title_en',
                    'label' => 'Page Title H1 (English)',
                    'name' => 'about_page_title_en',
                    'type' => 'text',
                    'instructions' => 'English version of the H1 page title.',
                    'default_value' => 'Grupo Fadiar – Innovation and social commitment',
                    'required' => 0,
                ),

                array(
                    'key' => 'field_about_metrics_image',
                    'label' => 'Imagen de fondo del bloque metrics',
                    'name' => 'about_metrics_image',
                    'type' => 'image',
                    'instructions' => 'Imagen superior del bloque metrics. Si se deja vacía, se usa el asset por defecto (assets/images/about/about.png).',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'large',
                    'library' => 'all',
                ),

                array(
                    'key' => 'field_about_metric_1_value',
                    'label' => 'Métrica 1 – número',
                    'name' => 'about_metric_1_value',
                    'type' => 'text',
                    'instructions' => 'Valor numérico de la primera tarjeta (ej: 500+).',
                    'default_value' => '500+',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_1_label',
                    'label' => 'Métrica 1 – etiqueta',
                    'name' => 'about_metric_1_label',
                    'type' => 'text',
                    'instructions' => 'Etiqueta descriptiva debajo del número (ej: colaboradores).',
                    'default_value' => 'colaboradores',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_1_label_en',
                    'label' => 'Metric 1 – label (English)',
                    'name' => 'about_metric_1_label_en',
                    'type' => 'text',
                    'default_value' => 'collaborators',
                    'required' => 0,
                ),

                array(
                    'key' => 'field_about_metric_2_value',
                    'label' => 'Métrica 2 – número',
                    'name' => 'about_metric_2_value',
                    'type' => 'text',
                    'default_value' => '3',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_2_label',
                    'label' => 'Métrica 2 – etiqueta',
                    'name' => 'about_metric_2_label',
                    'type' => 'text',
                    'default_value' => 'marcas',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_2_label_en',
                    'label' => 'Metric 2 – label (English)',
                    'name' => 'about_metric_2_label_en',
                    'type' => 'text',
                    'default_value' => 'brands',
                    'required' => 0,
                ),

                array(
                    'key' => 'field_about_metric_3_value',
                    'label' => 'Métrica 3 – número',
                    'name' => 'about_metric_3_value',
                    'type' => 'text',
                    'default_value' => '1,200+',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_3_label',
                    'label' => 'Métrica 3 – etiqueta',
                    'name' => 'about_metric_3_label',
                    'type' => 'text',
                    'default_value' => 'productos',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_3_label_en',
                    'label' => 'Metric 3 – label (English)',
                    'name' => 'about_metric_3_label_en',
                    'type' => 'text',
                    'default_value' => 'products',
                    'required' => 0,
                ),

                array(
                    'key' => 'field_about_metric_4_value',
                    'label' => 'Métrica 4 – número',
                    'name' => 'about_metric_4_value',
                    'type' => 'text',
                    'default_value' => '2.5M+',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_4_label',
                    'label' => 'Métrica 4 – etiqueta',
                    'name' => 'about_metric_4_label',
                    'type' => 'text',
                    'default_value' => 'unidades vendidas',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metric_4_label_en',
                    'label' => 'Metric 4 – label (English)',
                    'name' => 'about_metric_4_label_en',
                    'type' => 'text',
                    'default_value' => 'units sold',
                    'required' => 0,
                ),

                array(
                    'key' => 'field_about_metrics_description_1',
                    'label' => 'Descripción 1 (párrafo izquierdo)',
                    'name' => 'about_metrics_description_1',
                    'type' => 'textarea',
                    'instructions' => 'Primer párrafo de la sección de descriptions dentro del bloque metrics.',
                    'default_value' => 'Grupo Fadiar (Fabricación y Diseño Artesanal) comenzó como un sueño, un reto personal. Se forjó desde los cimientos. Construyendo sus instalaciones, escribiendo lo importante, uniendo personas invaluables. Poco a poco hicimos nuestro camino. En estos tres años, Grupo Fadiar ha crecido con un propósito claro: ofrecer productos que combinan calidad, accesibilidad y responsabilidad social. Nuestra trayectoria se basa en la innovación, la producción nacional y alianzas estratégicas que fortalecen la economía local.',
                    'rows' => 6,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metrics_description_1_en',
                    'label' => 'Description 1 – Left Paragraph (English)',
                    'name' => 'about_metrics_description_1_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the left paragraph.',
                    'default_value' => 'Grupo Fadiar (Fabricación y Diseño Artesanal) began as a dream, a personal challenge. It was built from the ground up — constructing its facilities, writing down what mattered, bringing together invaluable people. Step by step we made our way. In these three years, Grupo Fadiar has grown with a clear purpose: to offer products that combine quality, accessibility and social responsibility. Our track record is built on innovation, national production and strategic alliances that strengthen the local economy.',
                    'rows' => 6,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metrics_description_2',
                    'label' => 'Descripción 2 (párrafo derecho)',
                    'name' => 'about_metrics_description_2',
                    'type' => 'textarea',
                    'instructions' => 'Segundo párrafo de la sección de descriptions dentro del bloque metrics.',
                    'default_value' => 'Nuestra filosofía "Diversidad de Soluciones, Un solo compromiso" refleja lo que somos, nuestra esencia. En Fadiar crecemos profesional y personalmente. Cada equipo, cada objeto que fabricamos y que llega a un hogar o negocio es un reto, un compromiso y, sobre todo, una oportunidad para ser útiles. Y en eso, ponemos lo mejor de nosotros. ¡Más que productos, compartimos experiencias',
                    'rows' => 6,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_metrics_description_2_en',
                    'label' => 'Description 2 – Right Paragraph (English)',
                    'name' => 'about_metrics_description_2_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the right paragraph.',
                    'default_value' => 'Our philosophy "Diversity of Solutions, One Single Commitment" reflects who we are, our essence. At Fadiar we grow professionally and personally. Every team, every object we manufacture that reaches a home or business is a challenge, a commitment and, above all, an opportunity to be useful. And in that, we give our best. More than products, we share experiences.',
                    'rows' => 6,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_about_post_title_en',
                    'label' => 'Título en Inglés',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'default_value' => 'Grupo Fadiar',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'about_us',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array('the_content', 'excerpt', 'featured_image', 'discussion', 'comments', 'revisions', 'author', 'formats', 'page_attributes', 'categories', 'tags', 'send-trackbacks'),
            'active' => true,
            'description' => 'Campos personalizados para gestionar la cabecera y el bloque metrics de la página Sobre Nosotros.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_about_us_acf_fields');
