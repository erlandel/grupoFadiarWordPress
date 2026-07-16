<?php
/**
 * Registro de campos ACF para los items del acordeón "Nuestra Historia".
 *
 * Cada item tiene un selector "Tipo de contenido" (Párrafo / Líder).
 * - Párrafo: un solo WYSIWYG donde el usuario escribe libremente.
 * - Líder: campos planos (intro, nombre, imagen, descripción corta y larga).
 *
 * Compatible con ACF gratis: no se usa repeater ni flexible_content.
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
                    'key' => 'field_tipo_contenido',
                    'label' => 'Tipo de contenido',
                    'name' => 'tipo_contenido',
                    'type' => 'radio',
                    'instructions' => 'Selecciona el tipo de contenido para este item del acordeón.',
                    'required' => 1,
                    'choices' => array(
                        'parrafo' => 'Párrafo',
                        'lider' => 'Líder',
                    ),
                    'default_value' => 'parrafo',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),

                array(
                    'key' => 'field_osi_text',
                    'label' => 'Párrafo',
                    'name' => 'osi_text',
                    'type' => 'wysiwyg',
                    'instructions' => 'Escribe el contenido aquí. Puedes usar viñetas, saltos de línea, párrafos, etc.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_tipo_contenido',
                                'operator' => '==',
                                'value' => 'parrafo',
                            ),
                        ),
                    ),
                ),

                array(
                    'key' => 'field_osi_intro_text',
                    'label' => 'Texto de introducción',
                    'name' => 'osi_intro_text',
                    'type' => 'wysiwyg',
                    'instructions' => 'Párrafo que aparece al inicio del bloque de líder. Puedes usar viñetas, saltos de línea y párrafos.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_tipo_contenido',
                                'operator' => '==',
                                'value' => 'lider',
                            ),
                        ),
                    ),
                ),

                array(
                    'key' => 'field_osi_leader_name',
                    'label' => 'Nombre del líder',
                    'name' => 'osi_leader_name',
                    'type' => 'text',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_tipo_contenido',
                                'operator' => '==',
                                'value' => 'lider',
                            ),
                        ),
                    ),
                ),

                array(
                    'key' => 'field_osi_leader_image',
                    'label' => 'Imagen del líder',
                    'name' => 'osi_leader_image',
                    'type' => 'image',
                    'instructions' => 'Foto del líder.',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_tipo_contenido',
                                'operator' => '==',
                                'value' => 'lider',
                            ),
                        ),
                    ),
                ),

                array(
                    'key' => 'field_osi_leader_short_description',
                    'label' => 'Descripción corta del líder',
                    'name' => 'osi_leader_short_description',
                    'type' => 'textarea',
                    'rows' => 3,
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_tipo_contenido',
                                'operator' => '==',
                                'value' => 'lider',
                            ),
                        ),
                    ),
                ),

                array(
                    'key' => 'field_osi_leader_full_description',
                    'label' => 'Descripción larga del líder',
                    'name' => 'osi_leader_full_description',
                    'type' => 'textarea',
                    'rows' => 6,
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_tipo_contenido',
                                'operator' => '==',
                                'value' => 'lider',
                            ),
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

function grupofadiar_hide_wysiwyg_media_buttons() {
    ?>
    <style>
      .post-type-our_story_item .acf-field .wp-media-buttons,
      .post-type-our_story_item .acf-field .mce-button.mce-wp-media { display: none !important; }
    </style>
    <?php
}
add_action('acf/input/admin_head', 'grupofadiar_hide_wysiwyg_media_buttons');

function grupofadiar_migrate_our_story_items_tipo() {
    if (!post_type_exists('our_story_item')) {
        return;
    }

    $items = get_posts(array(
        'post_type'      => 'our_story_item',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => 'tipo_contenido',
                'compare' => 'NOT EXISTS',
            ),
        ),
    ));

    foreach ($items as $post_id) {
        $old_leaders = get_field('osi_leaders', $post_id);
        if (is_array($old_leaders) && count($old_leaders) > 0) {
            update_field('tipo_contenido', 'lider', $post_id);
        } else {
            update_field('tipo_contenido', 'parrafo', $post_id);
        }
    }
}
add_action('admin_init', 'grupofadiar_migrate_our_story_items_tipo');
