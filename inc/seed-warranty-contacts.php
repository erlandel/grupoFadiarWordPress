<?php
/**
 * Seeder idempotente para los contactos telefónicos del
 * componente 'Soporte y Garantía' (página /soporte-y-garantia).
 *
 * Inserta los datos del mockup solo si aún no existen (por título).
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_seed_warranty_contacts() {
    $items = array(
        array(
            'title'    => 'Reportes de garantía',
            'wc_label'    => 'Reportes de garantía:',
            'wc_phone'    => '+53) 63445640',
            'wc_schedule' => 'lunes a viernes 9am a 4pm',
        ),
        array(
            'title'    => 'Atención al cliente',
            'wc_label'    => 'Atención al cliente:',
            'wc_phone'    => '+53) 63513228',
            'wc_schedule' => 'lunes a viernes 9am a 4pm',
        ),
    );

    foreach ($items as $index => $item) {
        $existing = get_page_by_title($item['title'], OBJECT, 'warranty_contact');
        if ($existing) {
            continue;
        }

        $post_id = wp_insert_post(array(
            'post_type'   => 'warranty_contact',
            'post_status' => 'publish',
            'post_title'  => $item['title'],
            'menu_order'  => $index,
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_field('wc_label',     $item['wc_label'],     $post_id);
            update_field('wc_phone',     $item['wc_phone'],     $post_id);
            update_field('wc_schedule',  $item['wc_schedule'],  $post_id);
        }
    }
}
add_action('after_switch_theme', 'grupofadiar_seed_warranty_contacts');
add_action('admin_init',         'grupofadiar_seed_warranty_contacts');
