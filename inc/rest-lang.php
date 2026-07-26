<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('grupofadiar/v1', '/lang', [
        'methods'             => 'GET',
        'callback'            => 'gf_rest_lang_handler',
        'permission_callback' => '__return_true',
    ]);
});

function gf_rest_lang_handler() {
    $lang = gf_current_lang();
    $strings = gf_static_strings();
    $lang_strings = [];
    foreach ($strings as $key => $pair) {
        $lang_strings[$key] = isset($pair[$lang]) ? $pair[$lang] : $pair['es'];
    }

    return new WP_REST_Response([
        'lang'    => $lang,
        'strings' => $lang_strings,
    ], 200);
}
