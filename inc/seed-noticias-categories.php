<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_seed_noticias_categories() {
    if (!taxonomy_exists('categoria_noticia')) {
        return;
    }

    $categories = array(
        'Innovación y Tecnología',
        'RSC y Comunidad',
        'Eventos y Ferias',
        'Lanzamientos',
        'Vida Corporativa',
        'Noticias Generales',
    );

    foreach ($categories as $cat_name) {
        if (!term_exists($cat_name, 'categoria_noticia')) {
            wp_insert_term($cat_name, 'categoria_noticia');
        }
    }
}
add_action('after_switch_theme', 'grupofadiar_seed_noticias_categories');
add_action('admin_init', 'grupofadiar_seed_noticias_categories');
