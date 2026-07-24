<?php
/**
 * Helpers generales para el tema Grupo Fadiar
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Procesa una URL para manejar rutas internas y externas.
 *
 * - Si la URL empieza con http:// o https://, se devuelve tal cual (externa).
 * - Si no, se considera ruta interna y se le agrega home_url().
 *
 * @param string $url La URL o ruta a procesar.
 * @return string La URL procesada.
 */
function process_url($url) {
    if (empty($url)) {
        return '';
    }

    // Si empieza con http:// o https://, es externa, devolver tal cual
    if (preg_match('/^https?:\/\//i', $url)) {
        return $url;
    }

    // Es ruta interna, asegurar que empiece con / y agregar home_url()
    $url = '/' . ltrim($url, '/');
    return home_url($url);
}

/**
 * Renderiza el botón de idioma (bandera) enlazado a TranslatePress.
 * Muestra la bandera del idioma contrario; al hacer clic navega a ese idioma.
 *
 * @param string $size Clases Tailwind para el tamaño (ej: 'w-6 h-6').
 * @return string HTML del selector.
 */
function gf_language_switcher($size = 'w-6 h-6') {
    if (!function_exists('trp_custom_language_switcher')) {
        return '';
    }

    $languages = trp_custom_language_switcher();
    if (empty($languages)) {
        return '';
    }

    global $TRP_LANGUAGE;

    $icon_for = static function ($code) {
        if (strpos($code, 'es') === 0) {
            return 'spain';
        }
        if (strpos($code, 'en') === 0) {
            return 'uk';
        }
        return '';
    };

    $target_icon = '';
    $target_url  = '';
    $target_name = '';

    foreach ($languages as $code => $data) {
        if ($code === $TRP_LANGUAGE) {
            continue;
        }

        $icon = $icon_for($code);
        if ($icon === '') {
            continue;
        }

        $target_icon = $icon;
        $target_url  = $data['current_page_url'];
        $target_name = isset($data['language_name']) ? $data['language_name'] : $code;
        break;
    }

    if ($target_icon === '' || $target_url === '') {
        return '';
    }

    $label = sprintf(
        /* translators: %s: language name */
        __('Cambiar a %s', 'grupofadiar'),
        $target_name
    );

    return '<a href="' . esc_url($target_url) . '"'
         . ' class="' . esc_attr($size) . ' rounded-full overflow-hidden block cursor-pointer shrink-0"'
         . ' title="' . esc_attr($target_name) . '"'
         . ' aria-label="' . esc_attr($label) . '"'
         . ' data-no-translation>'
         . get_icon($target_icon, 'w-full h-full')
         . '</a>';
}

/**
 * Indica si un ítem de navegación corresponde a la vista actual.
 * Usa conditionals de WP (fiable con TranslatePress / prefijo /en/).
 *
 * @param string $key home|about-us|noticias|support-warranty|contacts
 * @return bool
 */
function gf_is_nav_active($key) {
    switch ($key) {
        case 'home':
            return is_front_page() || is_home();
        case 'about-us':
            return is_page('about-us');
        case 'noticias':
            return is_page('noticias') || is_singular('noticia');
        case 'support-warranty':
            return is_page('support-warranty');
        case 'contacts':
            return is_page('contacts');
        default:
            return false;
    }
}

/**
 * Clases Tailwind para el estado activo/inactivo de un enlace de nav.
 *
 * @param string $key Ver gf_is_nav_active().
 * @return string
 */
function gf_nav_link_classes($key) {
    return gf_is_nav_active($key)
        ? 'bg-dark text-secondary'
        : 'text-dark hover:text-secondary';
}
