<?php
/**
 * Página de configuración de la sección Contacto.
 *
 * La vista general y la configuración (títulos, dirección, horario)
 * se renderizan desde este archivo. Los Asuntos se gestionan como
 * CPT contact_subject.
 *
 * Convenciones: se usa update_option nativo (como admin-page-brands.php), ACF no participa aquí.
 */

if (!defined('ABSPATH')) {
    exit;
}

// ---------------------------------------------------------------------------
// Render de la página de configuración (accesible por URL directa)
// ---------------------------------------------------------------------------

function grupofadiar_render_contact_settings_page() {
    if (isset($_POST['grupofadiar_contact_settings_nonce']) &&
        wp_verify_nonce($_POST['grupofadiar_contact_settings_nonce'], 'grupofadiar_save_contact_settings')) {

        update_option('contact_page_title', sanitize_text_field($_POST['contact_page_title'] ?? 'Contáctanos'));
        update_option('contact_page_subtitle', sanitize_text_field($_POST['contact_page_subtitle'] ?? 'Escríbenos, llama o visítanos. Estamos para ayudarte.'));
        update_option('contact_address_label', sanitize_text_field($_POST['contact_address_label'] ?? 'Direcciones:'));
        update_option('contact_main_address', sanitize_textarea_field($_POST['contact_main_address'] ?? ''));
        update_option('contact_schedule_label', sanitize_text_field($_POST['contact_schedule_label'] ?? 'Horario:'));
        update_option('contact_schedule_value', sanitize_text_field($_POST['contact_schedule_value'] ?? 'Lun-Vie 9:00 – 17:00.'));

        echo '<div class="updated"><p>Configuración guardada exitosamente.</p></div>';
    }

    $title          = get_option('contact_page_title', 'Contáctanos');
    $subtitle       = get_option('contact_page_subtitle', 'Escríbenos, llama o visítanos. Estamos para ayudarte.');
    $address_label  = get_option('contact_address_label', 'Direcciones:');
    $main_address   = get_option('contact_main_address', "Calle 29F entre 114 y 114A, Edificio 11413, Almacén 9A (ENAME). Ciudad Libertad, Marianao, La Habana, Cuba.");
    $schedule_label = get_option('contact_schedule_label', 'Horario:');
    $schedule_value = get_option('contact_schedule_value', 'Lun-Vie 9:00 – 17:00.');
    ?>
    <div class="wrap">
        <h1>Editar Configuración — Contacto</h1>
        <p>Edita los títulos de la cabecera, la dirección de la sede y el horario que aparecen en la página Contáctanos.</p>

        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_contact_settings', 'grupofadiar_contact_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="contact_page_title">Título</label></th>
                    <td>
                        <input type="text" name="contact_page_title" id="contact_page_title"
                            value="<?php echo esc_attr($title); ?>" class="regular-text" />
                        <p class="description">Título principal de la página. Ej: "Contáctanos".</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="contact_page_subtitle">Subtítulo</label></th>
                    <td>
                        <input type="text" name="contact_page_subtitle" id="contact_page_subtitle"
                            value="<?php echo esc_attr($subtitle); ?>" class="large-text" />
                        <p class="description">Subtítulo debajo del título. Ej: "Escríbenos, llama o visítanos. Estamos para ayudarte."</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="contact_address_label">Etiqueta de direcciones</label></th>
                    <td>
                        <input type="text" name="contact_address_label" id="contact_address_label"
                            value="<?php echo esc_attr($address_label); ?>" class="regular-text" />
                        <p class="description">Etiqueta en negrita para el bloque de direcciones. Ej: "Direcciones:".</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="contact_main_address">Sede central</label></th>
                    <td>
                        <textarea name="contact_main_address" id="contact_main_address" rows="3" class="large-text"><?php echo esc_textarea($main_address); ?></textarea>
                        <p class="description">Dirección completa de la sede principal.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="contact_schedule_label">Etiqueta de horario</label></th>
                    <td>
                        <input type="text" name="contact_schedule_label" id="contact_schedule_label"
                            value="<?php echo esc_attr($schedule_label); ?>" class="regular-text" />
                        <p class="description">Etiqueta en negrita para el bloque de horario. Ej: "Horario:".</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="contact_schedule_value">Horario</label></th>
                    <td>
                        <input type="text" name="contact_schedule_value" id="contact_schedule_value"
                            value="<?php echo esc_attr($schedule_value); ?>" class="regular-text" />
                        <p class="description">Texto del horario de atención. Ej: "Lun-Vie 9:00 – 17:00."</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Guardar'); ?>
        </form>
    </div>
    <?php
}

function grupofadiar_initialize_contact_options() {
    if (get_option('contact_page_title') === false) {
        update_option('contact_page_title', 'Contáctanos');
    }
    if (get_option('contact_page_subtitle') === false) {
        update_option('contact_page_subtitle', 'Escríbenos, llama o visítanos. Estamos para ayudarte.');
    }
    if (get_option('contact_address_label') === false) {
        update_option('contact_address_label', 'Direcciones:');
    }
    if (get_option('contact_main_address') === false) {
        update_option('contact_main_address', "Calle 29F entre 114 y 114A, Edificio 11413, Almacén 9A (ENAME). Ciudad Libertad, Marianao, La Habana, Cuba.");
    }
    if (get_option('contact_schedule_label') === false) {
        update_option('contact_schedule_label', 'Horario:');
    }
    if (get_option('contact_schedule_value') === false) {
        update_option('contact_schedule_value', 'Lun-Vie 9:00 – 17:00.');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_contact_options');
add_action('admin_init', 'grupofadiar_initialize_contact_options');

// ---------------------------------------------------------------------------
// Menú principal y landing page
// ---------------------------------------------------------------------------

function grupofadiar_register_contact_section_menu() {
    add_menu_page(
        'Contacto',
        'Contacto',
        'manage_options',
        'contact_section',
        'grupofadiar_render_contact_section_landing',
         'dashicons-email',
        9.5
    );
}
add_action('admin_menu', 'grupofadiar_register_contact_section_menu', 0);

function grupofadiar_render_contact_section_landing() {
    ?>
    <div class="wrap">
        <h1>Contacto — Vista general</h1>
        <p>Selecciona una sección para administrar su contenido. Los textos se muestran en la página Contáctanos con el mismo formato del mockup.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Formulario y Datos</h2>
                <p style="flex:1;color:#50575e;">Administra los títulos de la página, la dirección de la sede, el horario y las opciones del &laquo;Asunto&raquo; del formulario que aparecen en la página Contáctanos.</p>
                <div style="display:flex;flex-direction:column;gap:10px;margin-top:6px;">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=contact-section-settings')); ?>" class="button button-primary" style="align-self:flex-start;">Administrar títulos, dirección y horario</a>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=contact_subject')); ?>" class="button" style="align-self:flex-start;">Administrar asuntos</a>
                </div>
            </div>

        </div>
    </div>
    <?php
}
