<?php
/**
 * Página de configuración de la sección Productos (Inicio)
 * 
 * Se usa la Settings API de WordPress para guardar el título de la sección.
 * Se accede desde: Productos (Inicio) → Título de la sección Productos
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_add_products_settings_page() {
    // Obtener el slug del CPT home_product
    $parent_slug = 'edit.php?post_type=home_product';

    add_submenu_page(
        $parent_slug,
        'Título de la sección Productos',
        'Título de la sección Productos',
        'manage_options',
        'products-section-settings',
        'grupofadiar_render_products_settings_page'
    );
}
add_action('admin_menu', 'grupofadiar_add_products_settings_page', 0);

// Reorganizar el submenú para poner "Título de la sección Productos" primero
function grupofadiar_reorder_products_submenu() {
    global $submenu;
    $parent_slug = 'edit.php?post_type=home_product';

    if (!isset($submenu[$parent_slug])) {
        return;
    }

    // Buscar nuestro item
    $our_item = null;
    $our_key = null;

    foreach ($submenu[$parent_slug] as $key => $item) {
        if (isset($item[2]) && $item[2] === 'products-section-settings') {
            $our_item = $item;
            $our_key = $key;
            break;
        }
    }

    // Mover nuestro item al principio si existe
    if ($our_key !== null && $our_item !== null) {
        unset($submenu[$parent_slug][$our_key]);
        // Reindexar y mover al principio
        $submenu[$parent_slug] = array_values($submenu[$parent_slug]);
        array_unshift($submenu[$parent_slug], $our_item);
    }
}
add_action('admin_menu', 'grupofadiar_reorder_products_submenu', 999);

function grupofadiar_render_products_settings_page() {
    // Guardar los datos si se ha enviado el formulario
    if (isset($_POST['grupofadiar_products_settings_nonce']) && 
        wp_verify_nonce($_POST['grupofadiar_products_settings_nonce'], 'grupofadiar_save_products_settings')) {
        
        update_option('products_section_title', sanitize_text_field($_POST['products_section_title'] ?? 'Productos'));
        
        // Mostrar mensaje de éxito
        echo '<div class="updated"><p>Configuración guardada exitosamente.</p></div>';
    }

    // Obtener valores actuales
    $title = get_option('products_section_title', 'Productos');
    ?>
    <div class="wrap">
        <h1>Título de la sección Productos</h1>
        <p>Aquí puedes editar el título que aparece en la sección de productos de la página de inicio.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_products_settings', 'grupofadiar_products_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="products_section_title">Título de la Sección (H3)</label></th>
                    <td>
                        <input type="text" name="products_section_title" id="products_section_title" 
                            value="<?php echo esc_attr($title); ?>" class="regular-text" />
                        <p class="description">Texto del encabezado de la sección de productos.</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Guardar'); ?>
        </form>
    </div>
    <?php
}

// Inicializar los valores por defecto al activar el tema si no existen
function grupofadiar_initialize_products_options() {
    if (get_option('products_section_title') === false) {
        update_option('products_section_title', 'Productos');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_products_options');
add_action('admin_init', 'grupofadiar_initialize_products_options');
