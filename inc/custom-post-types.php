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
        'show_in_menu'       => false,
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

function grupofadiar_register_discover_group_cpt() {
    $labels = array(
        'name'                  => _x('Quiénes Somos (Inicio)', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Quiénes Somos (Inicio)', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Quiénes Somos (Inicio)', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Quiénes Somos (Inicio)', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nuevo', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nuevo Item', 'grupofadiar'),
        'new_item'              => __('Nuevo Item', 'grupofadiar'),
        'edit_item'             => __('Editar Item', 'grupofadiar'),
        'view_item'             => __('Ver Item', 'grupofadiar'),
        'all_items'             => __('Todos los Items', 'grupofadiar'),
        'search_items'          => __('Buscar Items', 'grupofadiar'),
        'parent_item_colon'     => __('Item Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron items.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron items en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen Lateral', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer Imagen Lateral', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar Imagen Lateral', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como Imagen Lateral', 'grupofadiar'),
        'archives'              => _x('Archivo de Items', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en el Item', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a este Item', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Items', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Items', 'grupofadiar'),
        'items_list'            => _x('Lista de Items', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'capabilities'       => array(
            'create_posts' => 'do_not_allow',
            'delete_post'  => 'do_not_allow',
        ),
        'map_meta_cap'       => true,
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-info',
        'supports'           => array('title', 'thumbnail'),
    );

    register_post_type('discover_group', $args);
}

add_action('init', 'grupofadiar_register_discover_group_cpt', 0);

function grupofadiar_register_brand_cpt() {
    $labels = array(
        'name'                  => _x('Nuestras Marcas (Inicio)', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Marca', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Nuestras Marcas (Inicio)', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Marca', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nueva', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nueva Marca', 'grupofadiar'),
        'new_item'              => __('Nueva Marca', 'grupofadiar'),
        'edit_item'             => __('Editar Marca', 'grupofadiar'),
        'view_item'             => __('Ver Marca', 'grupofadiar'),
        'all_items'             => __('Todas las Marcas', 'grupofadiar'),
        'search_items'          => __('Buscar Marcas', 'grupofadiar'),
        'parent_item_colon'     => __('Marca Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron marcas.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron marcas en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen del Producto', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer Imagen del Producto', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar Imagen del Producto', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como Imagen del Producto', 'grupofadiar'),
        'archives'              => _x('Archivo de Marcas', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en la Marca', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a esta Marca', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Marcas', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Marcas', 'grupofadiar'),
        'items_list'            => _x('Lista de Marcas', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-awards',
        'supports'           => array('title', 'thumbnail'),
    );

    register_post_type('brand', $args);
}

add_action('init', 'grupofadiar_register_brand_cpt', 0);

function grupofadiar_register_home_product_cpt() {
    $labels = array(
        'name'                  => _x('Productos (Inicio)', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Producto', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Productos (Inicio)', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Producto', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nuevo', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nuevo Producto', 'grupofadiar'),
        'new_item'              => __('Nuevo Producto', 'grupofadiar'),
        'edit_item'             => __('Editar Producto', 'grupofadiar'),
        'view_item'             => __('Ver Producto', 'grupofadiar'),
        'all_items'             => __('Todos los Productos', 'grupofadiar'),
        'search_items'          => __('Buscar Productos', 'grupofadiar'),
        'parent_item_colon'     => __('Producto Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron productos.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron productos en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen del Producto', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer Imagen del Producto', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar Imagen del Producto', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como Imagen del Producto', 'grupofadiar'),
        'archives'              => _x('Archivo de Productos', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en el Producto', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a este Producto', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Productos', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Productos', 'grupofadiar'),
        'items_list'            => _x('Lista de Productos', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-cart',
        'supports'           => array('title'),
    );

    register_post_type('home_product', $args);
}

add_action('init', 'grupofadiar_register_home_product_cpt', 0);

function grupofadiar_register_support_home_item_cpt() {
    $labels = array(
        'name'                  => _x('Soporte y Garantía (Inicio)', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Item', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Soporte y Garantía (Inicio)', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Item', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nuevo', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nuevo Item', 'grupofadiar'),
        'new_item'              => __('Nuevo Item', 'grupofadiar'),
        'edit_item'             => __('Editar Item', 'grupofadiar'),
        'view_item'             => __('Ver Item', 'grupofadiar'),
        'all_items'             => __('Todos los Items', 'grupofadiar'),
        'search_items'          => __('Buscar Items', 'grupofadiar'),
        'parent_item_colon'     => __('Item Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron items.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron items en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen del Icono', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer Imagen del Icono', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar Imagen del Icono', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como Imagen del Icono', 'grupofadiar'),
        'archives'              => _x('Archivo de Items', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en el Item', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a este Item', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Items', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Items', 'grupofadiar'),
        'items_list'            => _x('Lista de Items', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 9,
        'menu_icon'          => 'dashicons-shield',
        'supports'           => array('title', 'page-attributes'),
    );

    register_post_type('support_home_item', $args);
}

add_action('init', 'grupofadiar_register_support_home_item_cpt', 0);

function grupofadiar_register_noticia_cpt() {
    $labels = array(
        'name'                  => _x('Noticias', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Noticia', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Noticias', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Noticia', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nueva', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nueva Noticia', 'grupofadiar'),
        'new_item'              => __('Nueva Noticia', 'grupofadiar'),
        'edit_item'             => __('Editar Noticia', 'grupofadiar'),
        'view_item'             => __('Ver Noticia', 'grupofadiar'),
        'all_items'             => __('Todas las Noticias', 'grupofadiar'),
        'search_items'          => __('Buscar Noticias', 'grupofadiar'),
        'parent_item_colon'     => __('Noticia Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron noticias.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron noticias en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen Destacada', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer Imagen Destacada', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar Imagen Destacada', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como Imagen Destacada', 'grupofadiar'),
        'archives'              => _x('Archivo de Noticias', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en la Noticia', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a esta Noticia', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Noticias', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Noticias', 'grupofadiar'),
        'items_list'            => _x('Lista de Noticias', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'noticias'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 10,
        'menu_icon'          => 'dashicons-admin-post',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'taxonomies'         => array('categoria_noticia'),
        'show_in_rest'       => true,
    );

    register_post_type('noticia', $args);
}
add_action('init', 'grupofadiar_register_noticia_cpt', 0);

function grupofadiar_register_about_us_cpt() {
    $labels = array(
        'name'                  => _x('Grupo Fadiar', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Grupo Fadiar', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Grupo Fadiar', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Grupo Fadiar', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nuevo', 'grupofadiar'),
        'add_new_item'          => __('Banner métricas', 'grupofadiar'),
        'new_item'              => __('Banner métricas', 'grupofadiar'),
        'edit_item'             => __('Banner métricas', 'grupofadiar'),
        'view_item'             => __('Ver Banner métricas', 'grupofadiar'),
        'all_items'             => __('Todas las configuraciones', 'grupofadiar'),
        'search_items'          => __('Buscar configuraciones', 'grupofadiar'),
        'parent_item_colon'     => __('Configuración Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron configuraciones.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron configuraciones en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Banner', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer banner', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar banner', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como banner', 'grupofadiar'),
        'archives'              => _x('Archivo de configuraciones', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en la configuración', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a esta configuración', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de configuraciones', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de configuraciones', 'grupofadiar'),
        'items_list'            => _x('Lista de configuraciones', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'capabilities'       => array(
            'create_posts' => 'do_not_allow',
            'delete_post'  => 'do_not_allow',
        ),
        'map_meta_cap'       => true,
        'has_archive'        => false,
        'hierarchical'       => false,
        'supports'           => array('title'),
    );

    register_post_type('about_us', $args);
}
add_action('init', 'grupofadiar_register_about_us_cpt', 0);

function grupofadiar_register_our_story_item_cpt() {
    $labels = array(
        'name'                  => _x('Items de Nuestra Historia', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Item de Nuestra Historia', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Items del acordeón', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Item de Nuestra Historia', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nuevo', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nuevo Item', 'grupofadiar'),
        'new_item'              => __('Nuevo Item', 'grupofadiar'),
        'edit_item'             => __('Editar Item', 'grupofadiar'),
        'view_item'             => __('Ver Item', 'grupofadiar'),
        'all_items'             => __('Todos los Items', 'grupofadiar'),
        'search_items'          => __('Buscar Items', 'grupofadiar'),
        'parent_item_colon'     => __('Item Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron items.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron items en la papelera.', 'grupofadiar'),
        'featured_image'        => _x('Imagen del líder', 'Overrides the "Featured Image" phrase for this post type.', 'grupofadiar'),
        'set_featured_image'    => _x('Establecer imagen del líder', 'grupofadiar'),
        'remove_featured_image' => _x('Eliminar imagen del líder', 'grupofadiar'),
        'use_featured_image'    => _x('Usar como imagen del líder', 'grupofadiar'),
        'archives'              => _x('Archivo de Items', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en el Item', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a este Item', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Items', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Items', 'grupofadiar'),
        'items_list'            => _x('Lista de Items', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-list-view',
        'supports'           => array('title', 'page-attributes'),
    );

    register_post_type('our_story_item', $args);
}
add_action('init', 'grupofadiar_register_our_story_item_cpt', 0);

function grupofadiar_register_pilar_corporativo_cpt() {
    $labels = array(
        'name'                  => _x('Valores corporativos', 'Post type general name', 'grupofadiar'),
        'singular_name'         => _x('Pilar corporativo', 'Post type singular name', 'grupofadiar'),
        'menu_name'             => _x('Valores corporativos', 'Admin Menu text', 'grupofadiar'),
        'name_admin_bar'        => _x('Pilar corporativo', 'Add New on Toolbar', 'grupofadiar'),
        'add_new'               => __('Añadir Nuevo', 'grupofadiar'),
        'add_new_item'          => __('Añadir Nuevo Pilar', 'grupofadiar'),
        'new_item'              => __('Nuevo Pilar', 'grupofadiar'),
        'edit_item'             => __('Editar Pilar', 'grupofadiar'),
        'view_item'             => __('Ver Pilar', 'grupofadiar'),
        'all_items'             => __('Todos los Pilares', 'grupofadiar'),
        'search_items'          => __('Buscar Pilares', 'grupofadiar'),
        'parent_item_colon'     => __('Pilar Padre:', 'grupofadiar'),
        'not_found'             => __('No se encontraron pilares.', 'grupofadiar'),
        'not_found_in_trash'    => __('No se encontraron pilares en la papelera.', 'grupofadiar'),
        'archives'              => _x('Archivo de Pilares', 'The post type archive label used in nav menus.', 'grupofadiar'),
        'insert_into_item'      => _x('Insertar en el Pilar', 'grupofadiar'),
        'uploaded_to_this_item' => _x('Subido a este Pilar', 'grupofadiar'),
        'filter_items_list'     => _x('Filtrar lista de Pilares', 'grupofadiar'),
        'items_list_navigation' => _x('Navegación de Pilares', 'grupofadiar'),
        'items_list'            => _x('Lista de Pilares', 'grupofadiar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-building',
        'supports'           => array('title', 'page-attributes'),
    );

    register_post_type('pilar_corporativo', $args);
}
add_action('init', 'grupofadiar_register_pilar_corporativo_cpt', 0);

function grupofadiar_get_pilares_corporativos() {
    return get_posts(array(
        'post_type'      => 'pilar_corporativo',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ));
}

function grupofadiar_get_about_us_post_id() {
    $posts = get_posts(array(
        'post_type'      => 'about_us',
        'posts_per_page' => 1,
        'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ));
    return !empty($posts) ? (int) $posts[0] : 0;
}

function grupofadiar_register_grupo_fadiar_landing() {
    add_menu_page(
        'Grupo Fadiar',
        'Grupo Fadiar',
        'manage_options',
        'grupofadiar_about',
        'grupofadiar_render_grupo_fadiar_landing',
        'dashicons-groups',
        6
    );

    add_submenu_page(
        'grupofadiar_about',
        'Vista general',
        'Vista general',
        'manage_options',
        'grupofadiar_about',
        'grupofadiar_render_grupo_fadiar_landing'
    );
}
add_action('admin_menu', 'grupofadiar_register_grupo_fadiar_landing', 20);

function grupofadiar_render_grupo_fadiar_landing() {
    $post_id = grupofadiar_get_about_us_post_id();
    ?>
    <div class="wrap">
        <h1>Grupo Fadiar — Vista general</h1>
        <p>Selecciona una sección para administrar su contenido. Las secciones aparecen en el mismo orden que en la página Sobre Nosotros.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Banner métricas</h2>
                <p style="flex:1;color:#50575e;">Configura el banner principal, las 4 métricas (colaboradores, marcas, productos, unidades vendidas) y las dos descripciones del bloque de métricas de la página Sobre Nosotros.</p>
                <?php if ($post_id): ?>
                    <a href="<?php echo esc_url(admin_url('post.php?post=' . $post_id . '&action=edit')); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;">Editar banner</a>
                <?php else: ?>
                    <p style="color:#d63638;">No se encontró la configuración de banner. Re-activa el tema.</p>
                <?php endif; ?>
            </div>

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Nuestra Historia</h2>
                <p style="flex:1;color:#50575e;">Administra el texto introductorio (título y 2 párrafos) y los items del acordeón (Misión, Visión, Valores, Liderazgo y más).</p>
                <a href="<?php echo esc_url(admin_url('admin.php?page=our_story_settings')); ?>" class="button" style="align-self:flex-start;margin-top:6px;">Editar texto introductorio</a>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=our_story_item')); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;">Administrar items</a>
            </div>

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Valores corporativos</h2>
                <p style="flex:1;color:#50575e;">Administra los 3 pilares —Responsabilidad Social, Estrategia Empresarial, I+D+i—: título, subtítulo, descripción enriquecida y selección de 1, 2 o 3 imágenes con layout automático.</p>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=pilar_corporativo')); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;">Administrar pilares</a>
            </div>

        </div>
    </div>
    <?php
}

function grupofadiar_register_categoria_noticia_taxonomy() {
    $labels = array(
        'name'              => _x('Categorías de Noticias', 'taxonomy general name', 'grupofadiar'),
        'singular_name'     => _x('Categoría de Noticia', 'taxonomy singular name', 'grupofadiar'),
        'search_items'      => __('Buscar Categorías', 'grupofadiar'),
        'all_items'         => __('Todas las Categorías', 'grupofadiar'),
        'parent_item'       => __('Categoría Padre', 'grupofadiar'),
        'parent_item_colon' => __('Categoría Padre:', 'grupofadiar'),
        'edit_item'         => __('Editar Categoría', 'grupofadiar'),
        'update_item'       => __('Actualizar Categoría', 'grupofadiar'),
        'add_new_item'      => __('Añadir Nueva Categoría', 'grupofadiar'),
        'new_item_name'     => __('Nombre de la Nueva Categoría', 'grupofadiar'),
        'menu_name'         => __('Categorías', 'grupofadiar'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'          => true,
        'rewrite'           => array('slug' => 'categoria-noticia'),
        'show_in_rest'      => true,
    );

    register_taxonomy('categoria_noticia', array('noticia'), $args);
}
add_action('init', 'grupofadiar_register_categoria_noticia_taxonomy', 0);

function grupofadiar_limit_home_products($new_status, $old_status, $post) {
    if ($post->post_type !== 'home_product') return;
    if ($new_status !== 'publish') return;

    // Obtener todos los productos publicados (excluyendo el actual si se está editando)
    $published = get_posts(array(
        'post_type'      => 'home_product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post__not_in'   => array($post->ID),
    ));

    // Si ya hay 4 o más publicados, despublicar este post
    if (count($published) >= 4) {
        // Despublicar el post actual (mover a borrador)
        wp_update_post(array(
            'ID'          => $post->ID,
            'post_status' => 'draft',
        ));

        // Agregar un mensaje de error para mostrar en el admin
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('product_limit_error', '1', $location);
        });
    }
}
add_action('transition_post_status', 'grupofadiar_limit_home_products', 10, 3);

// Mostrar mensaje de error cuando se excede el límite
function grupofadiar_home_product_limit_notice() {
    if (!isset($_GET['product_limit_error'])) return;
    ?>
    <div class="notice notice-error">
        <p><?php _e('Solo se permiten 4 productos publicados en esta sección. El producto fue guardado como borrador. Elimina un producto existente antes de publicar otro.', 'grupofadiar'); ?></p>
    </div>
    <?php
}
add_action('admin_notices', 'grupofadiar_home_product_limit_notice');

function grupofadiar_home_product_admin_notice() {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'home_product') return;

    $count = wp_count_posts('home_product');
    $published = isset($count->publish) ? $count->publish : 0;
    ?>
    <div class="notice notice-info">
        <p><?php printf(__('Límite: %d/4 productos creados. Solo se permiten 4 productos en esta sección.', 'grupofadiar'), intval($published)); ?></p>
    </div>
    <?php
}
add_action('admin_notices', 'grupofadiar_home_product_admin_notice');
