<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_add_support_header_settings_page() {
    $parent_slug = 'edit.php?post_type=support_header_item';

    add_submenu_page(
        $parent_slug,
        'Editar Encabezado y Descripción',
        'Configuración de Encabezado',
        'manage_options',
        'support-warranty-settings',
        'grupofadiar_render_support_header_settings_page'
    );
}
add_action('admin_menu', 'grupofadiar_add_support_header_settings_page', 0);

function grupofadiar_render_support_header_settings_page() {
    if (isset($_POST['grupofadiar_support_header_settings_nonce']) &&
        wp_verify_nonce($_POST['grupofadiar_support_header_settings_nonce'], 'grupofadiar_save_support_header_settings')) {

        update_option('support_warranty_title', sanitize_text_field($_POST['support_warranty_title'] ?? 'Soporte y Garantía'));
        update_option('support_warranty_subtitle', sanitize_text_field($_POST['support_warranty_subtitle'] ?? 'Atención técnica y reclamaciones'));
        update_option('support_warranty_description', wp_kses_post($_POST['support_warranty_description'] ?? ''));

        echo '<div class="updated"><p>Configuración guardada exitosamente.</p></div>';
    }

    $title = get_option('support_warranty_title', 'Soporte y Garantía');
    $subtitle = get_option('support_warranty_subtitle', 'Atención técnica y reclamaciones');
    $description = get_option('support_warranty_description', '');
    ?>
    <div class="wrap">
        <h1>Editar Encabezado y Descripción - Soporte y Garantía</h1>
        <p>Edita el título, subtítulo y descripción que aparecen en la página Soporte y Garantía.</p>

        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_support_header_settings', 'grupofadiar_support_header_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="support_warranty_title">Título (H1)</label></th>
                    <td>
                        <input type="text" name="support_warranty_title" id="support_warranty_title"
                            value="<?php echo esc_attr($title); ?>" class="regular-text" />
                        <p class="description">El título principal de la sección Soporte y Garantía.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="support_warranty_subtitle">Subtítulo (H2)</label></th>
                    <td>
                        <input type="text" name="support_warranty_subtitle" id="support_warranty_subtitle"
                            value="<?php echo esc_attr($subtitle); ?>" class="regular-text" />
                        <p class="description">El subtítulo que aparece debajo del título.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="support_warranty_description">Descripción</label></th>
                    <td>
                        <textarea name="support_warranty_description" id="support_warranty_description" rows="5" class="large-text"><?php echo esc_textarea($description); ?></textarea>
                        <p class="description">La descripción que aparece debajo del subtítulo.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Guardar'); ?>
        </form>
    </div>
    <?php
}

function grupofadiar_initialize_support_header_options() {
    if (get_option('support_warranty_title') === false) {
        update_option('support_warranty_title', 'Soporte y Garantía');
    }
    if (get_option('support_warranty_subtitle') === false) {
        update_option('support_warranty_subtitle', 'Atención técnica y reclamaciones');
    }
    if (get_option('support_warranty_description') === false) {
        update_option('support_warranty_description', '');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_support_header_options');
add_action('admin_init', 'grupofadiar_initialize_support_header_options');
