<?php

if (!defined('ABSPATH')) {
    exit;
}

function process_url($url) {
    if (empty($url)) {
        return '';
    }

    if (preg_match('/^https?:\/\//i', $url)) {
        return $url;
    }

    $url = '/' . ltrim($url, '/');
    return home_url($url);
}

function gf_language_switcher($size = 'w-6 h-6') {
    $current = gf_current_lang();
    $target  = $current === 'es' ? 'en' : 'es';
    $icon    = $current === 'es' ? 'uk' : 'spain';
    $name    = $current === 'es' ? 'English' : 'Español';

    $url = gf_switch_lang_url($target);

    return '<a href="' . esc_url($url) . '"'
         . ' class="' . esc_attr($size) . ' rounded-full overflow-hidden block cursor-pointer shrink-0"'
         . ' title="' . esc_attr($name) . '"'
         . ' aria-label="' . sprintf(esc_attr__('Cambiar a %s', 'grupofadiar'), $name) . '">'
         . get_icon($icon, 'w-full h-full')
         . '</a>';
}

function gf_switch_lang_url($target_lang = 'en') {
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $parsed = parse_url($current_url);
    $query  = [];

    if (isset($parsed['query'])) {
        parse_str($parsed['query'], $query);
    }

    unset($query['lang']);

    $path = isset($parsed['path']) ? $parsed['path'] : '/';

    $query['lang'] = $target_lang;

    $new_query = http_build_query($query);

    $url = $path;
    if ($new_query) {
        $url .= '?' . $new_query;
    }
    if (isset($parsed['fragment'])) {
        $url .= '#' . $parsed['fragment'];
    }

    return home_url($url);
}

/**
 * Indica si un ítem de navegación corresponde a la vista actual.
 * Usa conditionals de WP.
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
