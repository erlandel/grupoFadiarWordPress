<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_add_support_home_settings_page() {
    $parent_slug = 'edit.php?post_type=support_home_item';

    add_submenu_page(
        $parent_slug,
        'Editar Títulos',
        'Títulos Soporte y Garantía',
        'manage_options',
        'support-home-section-settings',
        'grupofadiar_render_support_home_settings_page'
    );
}
add_action('admin_menu', 'grupofadiar_add_support_home_settings_page', 0);

function grupofadiar_reorder_support_home_submenu() {
    global $submenu;
    $parent_slug = 'edit.php?post_type=support_home_item';

    if (!isset($submenu[$parent_slug])) {
        return;
    }

    $our_item = null;
    $our_key = null;

    foreach ($submenu[$parent_slug] as $key => $item) {
        if (isset($item[2]) && $item[2] === 'support-home-section-settings') {
            $our_item = $item;
            $our_key = $key;
            break;
        }
    }

    if ($our_key !== null && $our_item !== null) {
        unset($submenu[$parent_slug][$our_key]);
        $submenu[$parent_slug] = array_values($submenu[$parent_slug]);
        array_unshift($submenu[$parent_slug], $our_item);
    }
}
add_action('admin_menu', 'grupofadiar_reorder_support_home_submenu', 999);

function grupofadiar_render_support_home_settings_page() {
    if (isset($_POST['grupofadiar_support_home_settings_nonce']) &&
        wp_verify_nonce($_POST['grupofadiar_support_home_settings_nonce'], 'grupofadiar_save_support_home_settings')) {

        update_option('support_home_section_title', sanitize_text_field($_POST['support_home_section_title'] ?? 'Soporte y Garantía'));
        update_option('support_home_section_subtitle', sanitize_text_field($_POST['support_home_section_subtitle'] ?? 'POR QUÉ ESCOGER GRUPO FADIAR'));

        echo '<div class="updated"><p>Configuración guardada exitosamente.</p></div>';
    }

    $title = get_option('support_home_section_title', 'Soporte y Garantía');
    $subtitle = get_option('support_home_section_subtitle', 'POR QUÉ ESCOGER GRUPO FADIAR');
    ?>
    <div class="wrap">
        <h1>Editar Títulos de Soporte y Garantía</h1>
        <p>Aquí puedes editar los títulos que aparecen en la sección de Soporte y Garantía de la página de inicio.</p>

        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_support_home_settings', 'grupofadiar_support_home_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="support_home_section_title">Título (H3)</label></th>
                    <td>
                        <input type="text" name="support_home_section_title" id="support_home_section_title"
                            value="<?php echo esc_attr($title); ?>" class="regular-text" />
                        <p class="description">Texto pequeño del encabezado. Se muestra en color secundario y centrado.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="support_home_section_subtitle">Subtítulo (H2)</label></th>
                    <td>
                        <input type="text" name="support_home_section_subtitle" id="support_home_section_subtitle"
                            value="<?php echo esc_attr($subtitle); ?>" class="regular-text" />
                        <p class="description">Texto grande del encabezado que aparece debajo.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Guardar'); ?>
        </form>
    </div>
    <?php
}

function grupofadiar_initialize_support_home_options() {
    if (get_option('support_home_section_title') === false) {
        update_option('support_home_section_title', 'Soporte y Garantía');
    }
    if (get_option('support_home_section_subtitle') === false) {
        update_option('support_home_section_subtitle', 'POR QUÉ ESCOGER GRUPO FADIAR');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_support_home_options');
add_action('admin_init', 'grupofadiar_initialize_support_home_options');
