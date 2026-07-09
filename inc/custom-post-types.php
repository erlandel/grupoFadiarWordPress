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
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
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
        'show_in_menu'       => true,
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
        'show_in_menu'       => true,
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
        'show_in_menu'       => true,
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
