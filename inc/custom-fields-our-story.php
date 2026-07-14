<?php
/**
 * Registro de campos ACF para los items del acordeón "Nuestra Historia".
 *
 * Modelo single-shape: cada item puede tener texto plano, bullets, o líderes;
 * our-story.php decide qué pintar según qué sub-campos estén rellenos.
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_our_story_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_our_story_item_fields',
            'title' => 'Contenido del Item',
            'fields' => array(
                array(
                    'key' => 'field_osi_text',
                    'label' => 'Texto del item (párrafo)',
                    'name' => 'osi_text',
                    'type' => 'textarea',
                    'instructions' => 'Párrafo principal del item. Se usa cuando no hay bullets ni líderes.',
                    'rows' => 6,
                    'required' => 0,
                ),

                array(
                    'key' => 'field_osi_bullets',
                    'label' => 'Bullets (lista de valores)',
                    'name' => 'osi_bullets',
                    'type' => 'repeater',
                    'instructions' => 'Lista de bullets. Si tiene elementos, se renderiza en lugar del texto plano.',
                    'required' => 0,
                    'collapsed' => false,
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => 'Añadir bullet',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_osi_bullet',
                            'label' => 'Bullet',
                            'name' => 'bullet',
                            'type' => 'text',
                            'required' => 0,
                        ),
                    ),
                ),

                array(
                    'key' => 'field_osi_leaders',
                    'label' => 'Líderes',
                    'name' => 'osi_leaders',
                    'type' => 'repeater',
                    'instructions' => 'Lista de líderes con foto, nombre, descripción corta y descripción completa. Si tiene elementos, se renderiza en lugar del texto plano y los bullets.',
                    'required' => 0,
                    'collapsed' => false,
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'block',
                    'button_label' => 'Añadir líder',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_osi_leader_name',
                            'label' => 'Nombre',
                            'name' => 'name',
                            'type' => 'text',
                            'required' => 0,
                        ),
                        array(
                            'key' => 'field_osi_leader_image',
                            'label' => 'Imagen',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => 'Foto del líder.',
                            'required' => 0,
                            'return_format' => 'array',
                            'preview_size' => 'medium',
                            'library' => 'all',
                        ),
                        array(
                            'key' => 'field_osi_leader_short_description',
                            'label' => 'Descripción corta',
                            'name' => 'short_description',
                            'type' => 'textarea',
                            'rows' => 2,
                            'required' => 0,
                        ),
                        array(
                            'key' => 'field_osi_leader_full_description',
                            'label' => 'Descripción completa',
                            'name' => 'full_description',
                            'type' => 'textarea',
                            'rows' => 6,
                            'required' => 0,
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'our_story_item',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'author', 'formats', 'page_attributes', 'categories', 'tags', 'send-trackbacks'),
            'active' => true,
            'description' => 'Campos personalizados para los items del acordeón de Nuestra Historia.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_our_story_acf_fields');
