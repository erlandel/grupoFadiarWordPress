<?php
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_noticias_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_noticia_fields',
            'title' => 'Información de la Noticia',
            'fields' => array(
                array(
                    'key' => 'field_noticia_post_title_en',
                    'label' => 'Título en Inglés',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'Título de la noticia en inglés.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_noticia_intro',
                    'label' => 'Introducción',
                    'name' => 'intro_noticia',
                    'type' => 'textarea',
                    'instructions' => 'Texto que aparece entre el título y la fecha en la página de la noticia.',
                    'default_value' => '',
                    'placeholder' => '',
                    'required' => 0,
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_noticia_intro_en',
                    'label' => 'Introduction (English)',
                    'name' => 'intro_noticia_en',
                    'type' => 'textarea',
                    'instructions' => 'English version of the introduction.',
                    'default_value' => '',
                    'placeholder' => '',
                    'required' => 0,
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_noticia_fecha',
                    'label' => 'Fecha de la noticia',
                    'name' => 'fecha_noticia',
                    'type' => 'text',
                    'instructions' => 'Escribe la fecha como quieres que aparezca. Ej: 15 de mayo de 2026',
                    'default_value' => '',
                    'placeholder' => '15 de mayo de 2026',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_noticia_fecha_en',
                    'label' => 'Date (English)',
                    'name' => 'fecha_noticia_en',
                    'type' => 'text',
                    'instructions' => 'English version of the date. Ej: "May 15, 2026".',
                    'default_value' => '',
                    'placeholder' => 'May 15, 2026',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_noticia_autor',
                    'label' => 'Autor',
                    'name' => 'autor',
                    'type' => 'text',
                    'instructions' => 'Nombre del autor o fuente de la noticia. Aparecerá debajo de la fecha.',
                    'default_value' => '',
                    'placeholder' => 'Grupo Fadiar',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_noticia_autor_en',
                    'label' => 'Author (English)',
                    'name' => 'autor_en',
                    'type' => 'text',
                    'instructions' => 'English version of the author name.',
                    'default_value' => '',
                    'placeholder' => 'Grupo Fadiar',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_noticia_descripcion',
                    'label' => 'Descripción',
                    'name' => 'descripcion',
                    'type' => 'wysiwyg',
                    'instructions' => 'Contenido principal de la noticia. Puedes usar negritas, viñetas, párrafos, etc.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
                ),
                array(
                    'key' => 'field_noticia_descripcion_en',
                    'label' => 'Description (English)',
                    'name' => 'descripcion_en',
                    'type' => 'wysiwyg',
                    'instructions' => 'English version of the news content.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
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
            'hide_on_screen' => array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'author', 'formats'),
            'active' => true,
            'description' => 'Campos personalizados para noticias.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_noticias_acf_fields');

function grupofadiar_hide_wysiwyg_media_buttons_noticias() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'noticia') {
        ?>
        <style>
          .post-type-noticia .acf-field .wp-media-buttons,
          .post-type-noticia .acf-field .mce-button.mce-wp-media { display: none !important; }
        </style>
        <?php
    }
}
add_action('acf/input/admin_head', 'grupofadiar_hide_wysiwyg_media_buttons_noticias');
