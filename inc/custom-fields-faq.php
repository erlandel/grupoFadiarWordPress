<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_faq_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_faq_item_fields',
            'title' => 'Respuesta',
            'fields' => array(
                array(
                    'key' => 'field_faq_answer',
                    'label' => 'Respuesta',
                    'name' => 'faq_answer',
                    'type' => 'wysiwyg',
                    'instructions' => 'Escribe la respuesta aquí. Puedes usar negritas, viñetas, párrafos, enlaces, etc.',
                    'toolbar' => 'basic',
                    'media_buttons' => 0,
                    'teeny' => true,
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'faq_item',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array('the_content', 'excerpt', 'discussion', 'comments', 'revisions', 'author', 'formats', 'categories', 'tags', 'send-trackbacks'),
            'active' => true,
            'description' => 'Campo de respuesta para las preguntas frecuentes.',
        ));

    endif;
}

add_action('acf/init', 'grupofadiar_register_faq_acf_fields');

function grupofadiar_hide_wysiwyg_media_buttons_faq() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'faq_item') {
        ?>
        <style>
          .post-type-faq_item .acf-field .wp-media-buttons,
          .post-type-faq_item .acf-field .mce-button.mce-wp-media { display: none !important; }
        </style>
        <?php
    }
}
add_action('acf/input/admin_head', 'grupofadiar_hide_wysiwyg_media_buttons_faq');
