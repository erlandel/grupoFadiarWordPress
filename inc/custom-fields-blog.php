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
            array('key' => 'field_blog_author_image', 'label' => 'Imagen del autor', 'name' => 'imagen_autor_blog', 'type' => 'image', 'instructions' => 'Imagen mostrada junto al autor antes de la fecha.', 'required' => 0, 'return_format' => 'array', 'preview_size' => 'medium', 'library' => 'all'),
            array('key' => 'field_blog_date', 'label' => 'Fecha', 'name' => 'fecha_blog', 'type' => 'text', 'instructions' => 'Escribe la fecha como quieres que aparezca. Ej: 15 de mayo de 2026.', 'required' => 0),
            array('key' => 'field_blog_date_en', 'label' => 'Date (English)', 'name' => 'fecha_blog_en', 'type' => 'text', 'instructions' => 'English version of the date. Ej: May 15, 2026.', 'required' => 0),
            array('key' => 'field_blog_author', 'label' => 'Autor', 'name' => 'autor_blog', 'type' => 'text', 'instructions' => 'Se usa en español e inglés.', 'required' => 0),
            array('key' => 'field_blog_main_image', 'label' => 'Imagen principal', 'name' => 'imagen_principal_blog', 'type' => 'image', 'instructions' => 'Imagen mostrada debajo del autor y la fecha.', 'required' => 0, 'return_format' => 'array', 'preview_size' => 'large', 'library' => 'all'),
            array('key' => 'field_blog_category', 'label' => 'Categoría', 'name' => 'categoria_blog', 'type' => 'taxonomy', 'instructions' => 'Selecciona la categoría que se mostrará después de la imagen principal.', 'taxonomy' => 'categoria_noticia', 'field_type' => 'select', 'allow_null' => 1, 'add_term' => 0, 'save_terms' => 1, 'load_terms' => 1, 'return_format' => 'object', 'multiple' => 0, 'required' => 0),
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
