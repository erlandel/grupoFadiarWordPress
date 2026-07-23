<?php

// Autoload de Composer (si existe vendor/)
$vendor_autoload = get_template_directory() . '/vendor/autoload.php';
if (file_exists($vendor_autoload)) {
    require_once $vendor_autoload;
}

// Incluir registros de Custom Post Types y Campos ACF
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-fields-carousel.php';
require_once get_template_directory() . '/inc/custom-fields-discover-group.php';
require_once get_template_directory() . '/inc/custom-fields-brands.php';
require_once get_template_directory() . '/inc/custom-fields-products.php';
require_once get_template_directory() . '/inc/admin-page-products.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/admin-page-brands.php';
require_once get_template_directory() . '/inc/custom-fields-support-home.php';
require_once get_template_directory() . '/inc/admin-page-support-home.php';
require_once get_template_directory() . '/inc/custom-fields-noticias.php';
require_once get_template_directory() . '/inc/custom-fields-about-us.php';
require_once get_template_directory() . '/inc/admin-page-support-header.php';
require_once get_template_directory() . '/inc/seed-about-us.php';
require_once get_template_directory() . '/inc/custom-fields-our-story.php';
require_once get_template_directory() . '/inc/admin-page-our-story.php';
require_once get_template_directory() . '/inc/admin-page-home.php';
require_once get_template_directory() . '/inc/custom-fields-corporate-pillars.php';
require_once get_template_directory() . '/inc/seed-corporate-pillars.php';
require_once get_template_directory() . '/inc/admin-page-noticias.php';
require_once get_template_directory() . '/inc/seed-noticias-categories.php';
require_once get_template_directory() . '/inc/custom-fields-warranty-contacts.php';
require_once get_template_directory() . '/inc/custom-fields-warranty-steps.php';
require_once get_template_directory() . '/inc/custom-fields-faq.php';
require_once get_template_directory() . '/inc/seed-warranty-contacts.php';
require_once get_template_directory() . '/inc/seed-warranty-steps.php';
require_once get_template_directory() . '/inc/admin-page-contacts.php';
require_once get_template_directory() . '/inc/seed-contact-subjects.php';
require_once get_template_directory() . '/inc/search-endpoint.php';

function grupofadiar_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menu('primary', __('Menú principal', 'grupofadiar'));
}
add_action('after_setup_theme', 'grupofadiar_setup');

function grupofadiar_assets() {
    wp_enqueue_style('grupofadiar-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Open+Sans:wght@400;700&family=Dancing+Script:wght@400;700&family=Satisfy&display=swap', array(), null);

    wp_enqueue_style('grupofadiar-styles', get_stylesheet_uri(), array('grupofadiar-google-fonts'), '1.0.0');

    wp_enqueue_script('grupofadiar-carousel', get_template_directory_uri() . '/assets/js/carousel.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-header-scroll', get_template_directory_uri() . '/assets/js/header-scroll.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-menu-mobile', get_template_directory_uri() . '/assets/js/menu-mobile.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-accordion', get_template_directory_uri() . '/assets/js/accordion.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-products', get_template_directory_uri() . '/assets/js/products.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-faq', get_template_directory_uri() . '/assets/js/faq.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-menu', get_template_directory_uri() . '/assets/js/menu.js', array(), '1.0.3', true);
    wp_enqueue_script('grupofadiar-lang-toggle', get_template_directory_uri() . '/assets/js/lang-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-share', get_template_directory_uri() . '/assets/js/share.js', array(), '1.0.0', true);
wp_enqueue_script('grupofadiar-support-carousel', get_template_directory_uri() . '/assets/js/support-carousel.js', array(), '2.0.0', true);

    wp_enqueue_script('grupofadiar-search', get_template_directory_uri() . '/assets/js/search.js', array(), '1.0.0', true);
    wp_localize_script('grupofadiar-search', 'grupofadiarSearchData', array(
        'restUrl' => rest_url('grupofadiar/v1/search'),
    ));
}
add_action('wp_enqueue_scripts', 'grupofadiar_assets');

function get_icon($name, $class = '') {
    $path = get_template_directory() . '/icons/' . $name . '.php';
    if (file_exists($path)) {
        $svg = file_get_contents($path);
        if (!empty($class)) {
            $svg = str_replace('<svg', '<svg class="' . esc_attr($class) . '"', $svg);
        }
        return $svg;
    }
    return '';
}

add_action('admin_post_nopriv_grupofadiar_contact', 'grupofadiar_handle_contact');
add_action('admin_post_grupofadiar_contact', 'grupofadiar_handle_contact');

function grupofadiar_handle_contact() {
    $redirect_url = isset($_POST['redirect_to'])
        ? esc_url_raw($_POST['redirect_to'])
        : wp_get_referer();
    if (!$redirect_url) {
        $redirect_url = home_url('/contacts/');
    }

    if (!empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('contact', 'ok', $redirect_url));
        exit;
    }

    $nombre    = isset($_POST['nombre'])    ? sanitize_text_field($_POST['nombre'])    : '';
    $correo    = isset($_POST['correo'])    ? sanitize_email($_POST['correo'])          : '';
    $telefono  = isset($_POST['telefono'])  ? sanitize_text_field($_POST['telefono'])  : '';
    $asunto    = isset($_POST['asunto'])    ? sanitize_text_field($_POST['asunto'])    : '';
    $mensaje   = isset($_POST['mensaje'])   ? sanitize_textarea_field($_POST['mensaje']) : '';
    $privacidad = isset($_POST['privacidad']) ? $_POST['privacidad']                    : '';

    $error = false;

    if (!preg_match('/^\S+(?:\s+\S+){2,}$/', trim($nombre))) {
        $error = true;
    }
    if (!is_email($correo)) {
        $error = true;
    }
    // Validar formato telegrama: "+XX 12345678"
    if (!preg_match('/^\+\d{1,4}\s\d{6,15}$/', trim($telefono))) {
        $error = true;
    } elseif (class_exists('\libphonenumber\PhoneNumberUtil')) {
        try {
            $util = \libphonenumber\PhoneNumberUtil::getInstance();
            $proto = $util->parse($telefono, null);
            if (!$util->isValidNumber($proto)) {
                $error = true;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $error = true;
        }
    }
    if ($asunto === '') {
        $error = true;
    }
    if (trim($mensaje) === '') {
        $error = true;
    }
    if ($privacidad !== 'on') {
        $error = true;
    }

    if ($error) {
        wp_safe_redirect(add_query_arg('contact', 'error', $redirect_url));
        exit;
    }

    $to      = get_option('contact_recipient_email', 'delfinoerlan@gmail.com');
    $subject = '[grupofadiar.com] Nuevo contacto — ' . $asunto;

    $body  = '<table width="100%" cellpadding="0" cellspacing="0" style="background:#010A2D;font-family:Montserrat,Open Sans,Arial,sans-serif;">';
    $body .= '<tr><td align="center" style="padding:40px 20px;"><table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">';

    $body .= '<tr><td align="center" style="padding:0 0 10px;">';
    $body .= '<span style="font-size:28px;font-weight:900;color:#D69F03;letter-spacing:4px;">GRUPO FADIAR</span>';
    $body .= '</td></tr>';

    $body .= '<tr><td style="padding:0;"><table width="100%" cellpadding="0" cellspacing="0"><tr><td style="height:2px;background:#D69F03;"></td></tr></table></td></tr>';

    $body .= '<tr><td align="center" style="padding:30px 0 20px;">';
    $body .= '<h1 style="color:#ffffff;font-size:20px;font-weight:700;margin:0;letter-spacing:2px;text-transform:uppercase;">Nuevo mensaje del cliente</h1>';
    $body .= '<p style="color:#ffffff;font-size:18px;font-weight:700;margin:6px 0 0;">' . esc_html($nombre) . '</p>';
    $body .= '</td></tr>';

    $body .= '<tr><td style="padding:20px 0;"><table width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;">';

    $body .= '<tr><td style="padding:25px 30px 15px;">';
    $body .= '<h2 style="color:#D69F03;font-size:14px;font-weight:900;text-transform:uppercase;letter-spacing:1.5px;margin:0 0 15px;">Datos del Cliente</h2>';
    $body .= '<table width="100%" cellpadding="0" cellspacing="0">';
    $body .= '<tr><td style="color:#010A2D;font-size:15px;padding:5px 0;font-weight:600;width:90px;">Nombre</td><td style="color:#010A2D;font-size:15px;padding:5px 0;">' . esc_html($nombre) . '</td></tr>';
    $body .= '<tr><td style="color:#010A2D;font-size:15px;padding:5px 0;font-weight:600;width:90px;">Correo</td><td style="color:#010A2D;font-size:15px;padding:5px 0;">' . esc_html($correo) . '</td></tr>';
    $body .= '<tr><td style="color:#010A2D;font-size:15px;padding:5px 0;font-weight:600;width:90px;">Tel&eacute;fono</td><td style="color:#010A2D;font-size:15px;padding:5px 0;">' . esc_html($telefono) . '</td></tr>';
    $body .= '</table>';
    $body .= '</td></tr>';

    $body .= '<tr><td style="padding:0 30px;"><table width="100%" cellpadding="0" cellspacing="0"><tr><td style="height:1px;background:#EDEDED;"></td></tr></table></td></tr>';

    $body .= '<tr><td style="padding:20px 30px 15px;">';
    $body .= '<h2 style="color:#D69F03;font-size:14px;font-weight:900;text-transform:uppercase;letter-spacing:1.5px;margin:0 0 8px;">Asunto</h2>';
    $body .= '<p style="color:#010A2D;font-size:15px;margin:0;font-weight:600;">' . esc_html($asunto) . '</p>';
    $body .= '</td></tr>';

    $body .= '<tr><td style="padding:0 30px;"><table width="100%" cellpadding="0" cellspacing="0"><tr><td style="height:1px;background:#EDEDED;"></td></tr></table></td></tr>';

    $body .= '<tr><td style="padding:20px 30px 25px;">';
    $body .= '<h2 style="color:#D69F03;font-size:14px;font-weight:900;text-transform:uppercase;letter-spacing:1.5px;margin:0 0 8px;">Mensaje</h2>';
    $body .= '<p style="color:#010A2D;font-size:15px;margin:0;line-height:1.6;">' . nl2br(esc_html($mensaje)) . '</p>';
    $body .= '</td></tr>';

    $body .= '</table></td></tr>';

    $body .= '<tr><td align="center" style="padding:25px 0 10px;">';
    $body .= '<p style="margin:0;line-height:1.8;font-size:12px;">';
    $body .= '<span style="color:#F4F4F499;">Este mensaje fue enviado desde el formulario de contacto</span><br>';
    $body .= '<a href="https://grupofadiar.com" style="color:#D69F03;text-decoration:none;">grupofadiar.com</a>';
    $body .= ' <span style="color:#ffffff;">|</span> ';
    $body .= '<a href="mailto:clientegrupofadiar@gmail.com" style="color:#D69F03;text-decoration:none;">clientegrupofadiar@gmail.com</a>';
    $body .= '</p>';
    $body .= '</td></tr>';

    $body .= '</table></td></tr></table>';

    $headers = array(
        'From: Grupo Fadiar <clientegrupofadiar@gmail.com>',
        'Reply-To: ' . $nombre . ' <' . $correo . '>',
        'Content-Type: text/html; charset=UTF-8',
    );

    $mail_sent = wp_mail($to, $subject, $body, $headers);

    $status = $mail_sent ? 'ok' : 'error';
    wp_safe_redirect(add_query_arg('contact', $status, $redirect_url));
    exit;
}
