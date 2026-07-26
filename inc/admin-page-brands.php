<?php
/**
 * Página de configuración de la sección Nuestras Marcas
 * 
 * Se usa la Settings API de WordPress para guardar los títulos de la sección.
 * Se accede desde: Nuestras Marcas (Inicio) → Títulos Nuestras Marcas
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_add_brands_settings_page() {
    // Obtener el slug del CPT brand
    $parent_slug = 'edit.php?post_type=brand';

    add_submenu_page(
        $parent_slug,
        'Editar Títulos',
        'Títulos Nuestras Marcas',
        'manage_options',
        'brands-section-settings',
        'grupofadiar_render_brands_settings_page'
    );
}
add_action('admin_menu', 'grupofadiar_add_brands_settings_page', 0);

// Reorganizar el submenú para poner "Títulos Nuestras Marcas" primero
function grupofadiar_reorder_brands_submenu() {
    global $submenu;
    $parent_slug = 'edit.php?post_type=brand';

    if (!isset($submenu[$parent_slug])) {
        return;
    }

    // Buscar nuestro item
    $our_item = null;
    $our_key = null;

    foreach ($submenu[$parent_slug] as $key => $item) {
        if (isset($item[2]) && $item[2] === 'brands-section-settings') {
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
add_action('admin_menu', 'grupofadiar_reorder_brands_submenu', 999);

function grupofadiar_render_brands_settings_page() {
    // Guardar los datos si se ha enviado el formulario
    if (isset($_POST['grupofadiar_brands_settings_nonce']) && 
        wp_verify_nonce($_POST['grupofadiar_brands_settings_nonce'], 'grupofadiar_save_brands_settings')) {
        
        update_option('brands_section_title', sanitize_text_field($_POST['brands_section_title'] ?? 'Nuestras marcas'));
        update_option('brands_section_title_en', sanitize_text_field($_POST['brands_section_title_en'] ?? 'Our Brands'));
        update_option('brands_section_subtitle', sanitize_text_field($_POST['brands_section_subtitle'] ?? 'Diversidad de soluciones, un solo compromiso'));
        update_option('brands_section_subtitle_en', sanitize_text_field($_POST['brands_section_subtitle_en'] ?? 'Diverse solutions, one single commitment'));
        
        // Mostrar mensaje de éxito
        echo '<div class="updated"><p>Configuración guardada exitosamente.</p></div>';
    }

    // Obtener valores actuales
    $title = get_option('brands_section_title', 'Nuestras marcas');
    $title_en = get_option('brands_section_title_en', 'Our Brands');
    $subtitle = get_option('brands_section_subtitle', 'Diversidad de soluciones, un solo compromiso');
    $subtitle_en = get_option('brands_section_subtitle_en', 'Diverse solutions, one single commitment');
    ?>
    <div class="wrap">
        <h1>Editar Títulos</h1>
        <p>Aquí puedes editar los títulos que aparecen en la sección de marcas de la página de inicio.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_brands_settings', 'grupofadiar_brands_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="brands_section_title">Título (H2) – Español</label></th>
                    <td>
                        <input type="text" name="brands_section_title" id="brands_section_title" 
                            value="<?php echo esc_attr($title); ?>" class="regular-text" />
                        <p class="description">Texto pequeño del encabezado. Se muestra en color secundario y centrado.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brands_section_title_en">Title (H2) – English</label></th>
                    <td>
                        <input type="text" name="brands_section_title_en" id="brands_section_title_en"
                            value="<?php echo esc_attr($title_en); ?>" class="regular-text" />
                        <p class="description">Small header text in English. Centered above the subtitle.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brands_section_subtitle">Subtítulo (H3) – Español</label></th>
                    <td>
                        <input type="text" name="brands_section_subtitle" id="brands_section_subtitle" 
                            value="<?php echo esc_attr($subtitle); ?>" class="regular-text" />
                        <p class="description">Texto grande del encabezado que aparece debajo.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brands_section_subtitle_en">Subtitle (H3) – English</label></th>
                    <td>
                        <input type="text" name="brands_section_subtitle_en" id="brands_section_subtitle_en"
                            value="<?php echo esc_attr($subtitle_en); ?>" class="regular-text" />
                        <p class="description">Large header text that appears below the title in English.</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Guardar'); ?>
        </form>
    </div>
    <?php
}

// Inicializar los valores por defecto al activar el tema si no existen
function grupofadiar_initialize_brands_options() {
    if (get_option('brands_section_title') === false) {
        update_option('brands_section_title', 'Nuestras marcas');
    }
    if (get_option('brands_section_title_en') === false) {
        update_option('brands_section_title_en', 'Our Brands');
    }
    if (get_option('brands_section_subtitle') === false) {
        update_option('brands_section_subtitle', 'Diversidad de soluciones, un solo compromiso');
    }
    if (get_option('brands_section_subtitle_en') === false) {
        update_option('brands_section_subtitle_en', 'Diverse solutions, one single commitment');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_brands_options');
add_action('admin_init', 'grupofadiar_initialize_brands_options');
