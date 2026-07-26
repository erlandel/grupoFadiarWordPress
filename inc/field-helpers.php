<?php

if (!defined('ABSPATH')) {
    exit;
}

function gf_current_lang() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'], true)) {
        $lang = $_GET['lang'];
        if (!isset($_COOKIE['gf_lang']) || $_COOKIE['gf_lang'] !== $lang) {
            setcookie('gf_lang', $lang, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), false);
            $_COOKIE['gf_lang'] = $lang;
        }
        return $lang;
    }

    if (isset($_COOKIE['gf_lang']) && in_array($_COOKIE['gf_lang'], ['es', 'en'], true)) {
        return $_COOKIE['gf_lang'];
    }

    return 'es';
}

function gf_is_en() {
    return gf_current_lang() === 'en';
}

function gf_get_field($name, $post_id = null) {
    $lang = gf_current_lang();
    if ($lang === 'en') {
        $value_en = get_field($name . '_en', $post_id);
        if (!empty($value_en)) {
            return $value_en;
        }
        $value_en_legacy = get_field($name . '_en', $post_id, false);
        if (!empty($value_en_legacy)) {
            return $value_en_legacy;
        }
    }
    return get_field($name, $post_id);
}

function gf_get_option($key, $default_es = '', $default_en = '') {
    $lang = gf_current_lang();
    $key_en = $key . '_en';
    if ($lang === 'en') {
        $value = get_option($key_en);
        if ($value !== false && $value !== '') {
            return $value;
        }
        if ($default_en !== '') {
            return $default_en;
        }
    }
    $value = get_option($key);
    if ($value !== false) {
        return $value;
    }
    return $default_es;
}

function gf_get_post_title($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $lang = gf_current_lang();
    if ($lang === 'en') {
        $title_en = get_field('post_title_en', $post_id);
        if (!empty($title_en)) {
            return $title_en;
        }
    }
    return get_the_title($post_id);
}

function gf_get_term_name($term) {
    if (is_object($term)) {
        $term_id = $term->term_id;
        $taxonomy = $term->taxonomy;
    } elseif (is_int($term)) {
        $term_id = $term;
        $term_obj = get_term($term_id);
        $taxonomy = $term_obj ? $term_obj->taxonomy : '';
    } else {
        return is_string($term) ? $term : '';
    }

    $lang = gf_current_lang();
    if ($lang === 'en') {
        $name_en = get_field('name_en', 'term_' . $term_id);
        if (!empty($name_en)) {
            return $name_en;
        }
    }
    $name_es = get_field('name_es', 'term_' . $term_id);
    if (!empty($name_es)) {
        return $name_es;
    }
    if (is_object($term)) {
        return $term->name;
    }
    $t = get_term($term_id);
    return $t ? $t->name : '';
}

function gf_get_category_name($type) {
    $map = [
        'noticia'    => ['es' => 'Noticia',          'en' => 'News'],
        'producto'   => ['es' => 'Producto',         'en' => 'Product'],
        'corporativa' => ['es' => 'Info Corporativa', 'en' => 'Corporate Info'],
        'garantia'   => ['es' => 'Garantía',         'en' => 'Warranty'],
        'seccion'    => ['es' => 'Sección',          'en' => 'Section'],
        'brand'      => ['es' => 'Marca',            'en' => 'Brand'],
        'carousel'   => ['es' => 'Hero',             'en' => 'Hero'],
    ];
    $lang = gf_current_lang();
    return isset($map[$type][$lang]) ? $map[$type][$lang] : $type;
}

function gf_e($key) {
    $strings = gf_static_strings();
    $lang = gf_current_lang();
    if (isset($strings[$key][$lang])) {
        return $strings[$key][$lang];
    }
    if (isset($strings[$key]['es'])) {
        return $strings[$key]['es'];
    }
    return $key;
}

function gf_render_e($key) {
    echo esc_html(gf_e($key));
}
