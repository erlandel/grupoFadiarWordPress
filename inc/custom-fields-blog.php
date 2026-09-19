<?php
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_blog_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_blog_fields',
        'title' => 'Información del artículo',
        'fields' => array(
            array('key' => 'field_blog_post_title_en', 'label' => 'Título en inglés', 'name' => 'post_title_en', 'type' => 'text', 'instructions' => 'Título mostrado en la versión en inglés.', 'required' => 0),
            array('key' => 'field_blog_intro', 'label' => 'Introducción', 'name' => 'intro_blog', 'type' => 'textarea', 'instructions' => 'Resumen mostrado en el listado y bajo el título.', 'required' => 0, 'rows' => 3),
            array('key' => 'field_blog_intro_en', 'label' => 'Introduction (English)', 'name' => 'intro_blog_en', 'type' => 'textarea', 'instructions' => 'Summary shown in the English version.', 'required' => 0, 'rows' => 3),
            array('key' => 'field_blog_date', 'label' => 'Fecha', 'name' => 'fecha_blog', 'type' => 'text', 'instructions' => 'Escribe la fecha como quieres que aparezca. Ej: 15 de mayo de 2026.', 'required' => 0),
            array('key' => 'field_blog_date_en', 'label' => 'Date (English)', 'name' => 'fecha_blog_en', 'type' => 'text', 'instructions' => 'English version of the date. Ej: May 15, 2026.', 'required' => 0),
            array('key' => 'field_blog_author', 'label' => 'Autor', 'name' => 'autor_blog', 'type' => 'text', 'instructions' => 'Se usa en español e inglés.', 'required' => 0),
            array('key' => 'field_blog_content', 'label' => 'Contenido', 'name' => 'contenido_blog', 'type' => 'wysiwyg', 'instructions' => 'Contenido principal. Puedes añadir texto, listas e imágenes.', 'toolbar' => 'full', 'media_buttons' => 1, 'required' => 0),
            array('key' => 'field_blog_content_en', 'label' => 'Content (English)', 'name' => 'contenido_blog_en', 'type' => 'wysiwyg', 'instructions' => 'English version of the main content.', 'toolbar' => 'full', 'media_buttons' => 1, 'required' => 0),
        ),
        'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'blog'))),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'author', 'formats'),
        'active' => true,
    ));
}
add_action('acf/init', 'grupofadiar_register_blog_acf_fields');
