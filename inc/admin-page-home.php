<?php
/**
 * Admin page "Inicio — Vista general"
 * Landing con tarjetas para gestionar cada sección de la página de inicio.
 * Opción A: no migra los CPTs existentes, solo sobrepone la landing.
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_home_landing() {
add_menu_page(
    'Inicio',
    'Inicio',
    'manage_options',
    'grupofadiar_home',
    'grupofadiar_render_home_landing',
    'dashicons-admin-home',
    5
);

    add_submenu_page(
        'grupofadiar_home',
        'Vista general',
        'Vista general',
        'manage_options',
        'grupofadiar_home',
        'grupofadiar_render_home_landing'
    );
}
add_action('admin_menu', 'grupofadiar_register_home_landing', 20);

function grupofadiar_render_home_landing() {
    $cards = array(
        array(
            'title'       => 'Carrusel',
            'description' => 'Añade, edita, elimina y reordena las diapositivas del carrusel principal con sus imágenes de fondo y enlaces.',
            'url'         => admin_url('edit.php?post_type=carousel_slide'),
            'button'      => 'Administrar carrusel',
        ),
        array(
            'title'       => 'Quiénes Somos',
            'description' => 'Edita el subtítulo, las dos descripciones y el botón con su enlace del bloque "Quiénes Somos" en la portada.',
            'url'         => admin_url('edit.php?post_type=discover_group'),
            'button'      => 'Administrar sección',
        ),
        array(
            'title'       => 'Nuestras Marcas',
            'description' => 'Administra las marcas y los productos que las componen, incluyendo sus imágenes, descripciones y enlaces. También puedes editar los títulos de la sección.',
            'url'         => admin_url('edit.php?post_type=brand'),
            'button'      => 'Administrar marcas',
            'secondary'   => array(
                'url'    => admin_url('admin.php?page=brands-section-settings'),
                'label'  => 'Editar títulos',
            ),
        ),
        array(
            'title'       => 'Productos',
            'description' => 'Configura los 4 productos destacados, sus imágenes, precios y descripciones. También puedes editar el título de la sección.',
            'url'         => admin_url('edit.php?post_type=home_product'),
            'button'      => 'Administrar productos',
            'secondary'   => array(
                'url'    => admin_url('admin.php?page=products-section-settings'),
                'label'  => 'Editar título',
            ),
        ),
        array(
            'title'       => 'Soporte y Garantía',
            'description' => 'Administra los items del bloque (icono + descripción) y edita los títulos de la sección "Soporte y Garantía".',
            'url'         => admin_url('edit.php?post_type=support_home_item'),
            'button'      => 'Administrar items',
            'secondary'   => array(
                'url'    => admin_url('admin.php?page=support-home-section-settings'),
                'label'  => 'Editar títulos',
            ),
        ),
    );
    ?>
    <div class="wrap">
        <h1>Inicio — Vista general</h1>
        <p>Selecciona una sección para administrar su contenido. Las secciones aparecen en el mismo orden que en la página de inicio.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">
            <?php foreach ($cards as $card): ?>
                <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                    <h2 style="margin-top:0;font-size:18px;"><?php echo esc_html($card['title']); ?></h2>
                    <p style="flex:1;color:#50575e;"><?php echo esc_html($card['description']); ?></p>
                    <?php if (isset($card['secondary'])): ?>
                        <a href="<?php echo esc_url($card['secondary']['url']); ?>" class="button" style="align-self:flex-start;margin-top:6px;"><?php echo esc_html($card['secondary']['label']); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($card['url']); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;"><?php echo esc_html($card['button']); ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
