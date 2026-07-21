<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_seed_contact_subjects() {
    $defaults = array('Consulta general', 'Soporte técnico', 'Garantía', 'Ventas');

    $existing = get_posts(array(
        'post_type'      => 'contact_subject',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ));

    if (!empty($existing)) {
        return;
    }

    foreach ($defaults as $index => $subject) {
        wp_insert_post(array(
            'post_type'   => 'contact_subject',
            'post_status' => 'publish',
            'post_title'  => $subject,
            'menu_order'  => $index,
        ));
    }
}
add_action('after_switch_theme', 'grupofadiar_seed_contact_subjects');
add_action('admin_init',         'grupofadiar_seed_contact_subjects');
