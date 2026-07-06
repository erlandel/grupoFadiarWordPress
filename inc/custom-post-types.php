<?php
/**
 * Registro de Custom Post Types para el tema Grupo Fadiar
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_carousel_slide_cpt() {
    $labels = array(
        'name'                  => _x('Diapositivas del Carrusel', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Diapositiva del Carrusel', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Carrusel', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Diapositiva del Carrusel', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nueva', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nueva Diapositiva', 'grupofadiar'),
        'new_item'              => __('Nueva Diapositiva', 'grupofadiar'),
        'edit_item'             => __('Editar Diapositiva', 'grupofadiar'),
        'view_item'             => __('Ver Diapositiva', 'grupofadiar'),
        'all_items'             => __('Todas las Diapositivas', 'grupofadiar'),
        'search_items'          => __('Buscar Diapositivas', 'grupofadiar'),
        'parent_item_colon'     => __('Diapositiva Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron diapositivas.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron diapositivas en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen de Fondo', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer Imagen de Fondo', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar Imagen de Fondo', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como Imagen de Fondo', 'grupofadiar'),
        'archives'              => _x('Archivo de Diapositivas', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en la Diapositiva', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a esta Diapositiva', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Diapositivas', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Diapositivas', 'grupofadiar'),
        'items_list'            => _x('Lista de Diapositivas', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
    );

    register_post_type('carousel_slide', $args);
}

add_action('init', 'grupofadiar_register_carousel_slide_cpt', 0);
