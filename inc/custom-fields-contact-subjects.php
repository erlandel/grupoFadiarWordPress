<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_contact_subject_acf_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_contact_subject_fields',
            'title' => 'Campos del Asunto',
            'fields' => array(
                array(
                    'key' => 'field_contact_subject_title_en',
                    'label' => 'Subject (English)',
                    'name' => 'post_title_en',
                    'type' => 'text',
                    'instructions' => 'English version of the subject.',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'contact_subject',
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
        ));

    endif;
}
add_action('acf/init', 'grupofadiar_register_contact_subject_acf_fields');
