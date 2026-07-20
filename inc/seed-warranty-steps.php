<?php
/**
 * Seeder idempotente para los pasos del Proceso de Reclamación
 * del componente 'Soporte y Garantía' (página /soporte-y-garantia).
 *
 * Inserta los datos del mockup solo si aún no existen (por título).
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_seed_warranty_steps() {
    $items = array(
        array(
            'title'               => 'Paso 1 - Contactar soporte',
            'ws_step_number'      => 1,
            'ws_step_description' => 'Contacta soporte por teléfono, email o WhatsApp.',
        ),
        array(
            'title'               => 'Paso 2 - Registrar compra',
            'ws_step_number'      => 2,
            'ws_step_description' => 'Registra tu compra y número de serie.',
        ),
        array(
            'title'               => 'Paso 3 - Evaluación técnica',
            'ws_step_number'      => 3,
            'ws_step_description' => 'Evaluación técnica y resolución (reparación o sustitución) en un plazo máximo de 15 días hábiles.',
        ),
    );

    foreach ($items as $index => $item) {
        $existing = get_page_by_title($item['title'], OBJECT, 'warranty_step');
        if ($existing) {
            continue;
        }

        $post_id = wp_insert_post(array(
            'post_type'   => 'warranty_step',
            'post_status' => 'publish',
            'post_title'  => $item['title'],
            'menu_order'  => $index,
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_field('ws_step_number',      $item['ws_step_number'],      $post_id);
            update_field('ws_step_description', $item['ws_step_description'], $post_id);
        }
    }
}
add_action('after_switch_theme', 'grupofadiar_seed_warranty_steps');
add_action('admin_init',         'grupofadiar_seed_warranty_steps');
