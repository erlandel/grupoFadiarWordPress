<?php
/**
 * Registro de campos ACF para los Pasos del Proceso de Reclamación
 * (Página Soporte y Garantía)
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_warranty_steps_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_warranty_step_fields',
            'title' => 'Información del Paso',
            'fields' => array(
                array(
                    'key' => 'field_ws_step_number',
                    'label' => 'Número de Paso',
                    'name' => 'ws_step_number',
                    'type' => 'number',
                    'instructions' => 'Número del paso en el proceso (1, 2, 3...). Se usa para mostrar el "1." "2." "3." en negrita.',
                    'required' => 1,
                    'default_value' => 1,
                    'min' => 1,
                    'step' => 1,
                ),
                array(
                    'key' => 'field_ws_step_description',
                    'label' => 'Descripción del Paso',
                    'name' => 'ws_step_description',
                    'type' => 'textarea',
                    'instructions' => 'Texto descriptivo del paso. Ej: "Contacta soporte por teléfono, email o WhatsApp."',
                    'required' => 1,
                    'rows' => 3,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'warranty_step',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array('the_content', 'excerpt'),
            'active' => true,
            'description' => 'Campos personalizados para gestionar los pasos del proceso de reclamación en la página Soporte y Garantía.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_warranty_steps_acf_fields');
