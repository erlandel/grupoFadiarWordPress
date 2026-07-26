<?php
/**
 * Registro de campos ACF para los Contactos de Garantía (Página Soporte y Garantía)
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_warranty_contacts_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_warranty_contact_fields',
            'title' => 'Información del Contacto',
            'fields' => array(
                array(
                    'key' => 'field_wc_label',
                    'label' => 'Etiqueta',
                    'name' => 'wc_label',
                    'type' => 'text',
                    'instructions' => 'Etiqueta que identifica el contacto. Ej: "Reportes de garantía:".',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_wc_label_en',
                    'label' => 'Label (English)',
                    'name' => 'wc_label_en',
                    'type' => 'text',
                    'instructions' => 'English version of the label.',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_wc_phone',
                    'label' => 'Teléfono',
                    'name' => 'wc_phone',
                    'type' => 'text',
                    'instructions' => 'Número de teléfono. Ej: "+53) 63445640".',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_wc_schedule',
                    'label' => 'Horario',
                    'name' => 'wc_schedule',
                    'type' => 'text',
                    'instructions' => 'Horario de atención. Ej: "lunes a viernes 9am a 4pm".',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_wc_schedule_en',
                    'label' => 'Schedule (English)',
                    'name' => 'wc_schedule_en',
                    'type' => 'text',
                    'instructions' => 'English version of the schedule.',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'warranty_contact',
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
            'description' => 'Campos personalizados para gestionar los contactos telefónicos mostrados en la sección "Contactos" de la página Soporte y Garantía.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_warranty_contacts_acf_fields');
