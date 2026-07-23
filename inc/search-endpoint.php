<?php

add_action('rest_api_init', function () {
    register_rest_route('grupofadiar/v1', '/search', [
        'methods'             => 'GET',
        'callback'            => 'grupofadiar_search_handler',
        'permission_callback' => '__return_true',
        'args'                => [
            'search' => [
                'required'          => false,
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'filter' => [
                'required'          => false,
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'todos',
            ],
        ],
    ]);
});

function grupofadiar_search_handler(WP_REST_Request $request) {
    $search = trim($request->get_param('search'));
    $filter = strtolower(trim($request->get_param('filter')));

    if ($search === '' && $filter === 'todos') {
        return new WP_REST_Response(['results' => [], 'total' => 0, 'query' => ''], 200);
    }

    if (!in_array($filter, ['todos', 'productos', 'noticias', 'corporativa', 'garantias'], true)) {
        $filter = 'todos';
    }

    $all_results = [];
    $max_per_type = 20;
    $max_total = 50;

    $search_terms = explode(' ', $search);

    if ($filter === 'todos' || $filter === 'noticias') {
        $all_results = array_merge($all_results, grupofadiar_search_noticias($search_terms, $search, $max_per_type));
    }
    if ($filter === 'todos' || $filter === 'productos') {
        $all_results = array_merge($all_results, grupofadiar_search_productos($search_terms, $search, $max_per_type));
    }
    if ($filter === 'todos' || $filter === 'corporativa') {
        $all_results = array_merge($all_results, grupofadiar_search_corporativa($search_terms, $search, $max_per_type));
    }
    if ($filter === 'todos' || $filter === 'garantias') {
        $all_results = array_merge($all_results, grupofadiar_search_garantias($search_terms, $search, $max_per_type));
    }

    usort($all_results, function ($a, $b) {
        return strcmp($a['title'], $b['title']);
    });

    $total = count($all_results);
    $all_results = array_slice($all_results, 0, $max_total);

    return new WP_REST_Response([
        'results' => $all_results,
        'total'   => $total,
        'query'   => $search,
    ], 200);
}

function grupofadiar_search_noticias($search_terms, $raw_search, $limit) {
    $post_ids = grupofadiar_search_in_cpt('noticia', $raw_search, [
        'intro_noticia',
        'descripcion',
        'autor',
        'fecha_noticia',
    ], $limit);

    $results = grupofadiar_search_options($raw_search, [
        ['key' => 'noticias_page_title',    'page' => '/noticias/',    'anchor' => '',        'type' => 'noticia',  'category' => 'Noticias'],
        ['key' => 'noticias_page_subtitle', 'page' => '/noticias/',    'anchor' => '',        'type' => 'noticia',  'category' => 'Noticias'],
    ]);
    foreach ($post_ids as $id) {
        $categories = wp_get_post_terms($id, 'categoria_noticia');
        $category_name = !empty($categories) ? $categories[0]->name : '';

        $excerpt = get_field('intro_noticia', $id);
        if (!$excerpt) {
            $content = get_post_field('post_content', $id);
            $excerpt = wp_trim_words(wp_strip_all_tags($content), 25);
        }

        $thumbnail = get_the_post_thumbnail_url($id, 'medium');

        $results[] = [
            'type'         => 'noticia',
            'title'        => get_the_title($id),
            'excerpt'      => wp_trim_words($excerpt, 25),
            'permalink'    => get_permalink($id),
            'thumbnail'    => $thumbnail ?: '',
            'external_url' => '',
            'category'     => $category_name,
            'date'         => get_field('fecha_noticia', $id) ?: get_the_date('Y-m-d', $id),
        ];
    }

    return $results;
}

function grupofadiar_search_productos($search_terms, $raw_search, $limit) {
    $post_ids = grupofadiar_search_in_cpt('home_product', $raw_search, [], $limit);

    $results = [];
    foreach ($post_ids as $id) {
        $media_file = get_field('product_media_file', $id);
        $thumbnail = '';
        if ($media_file && is_array($media_file) && isset($media_file['url'])) {
            $thumbnail = $media_file['url'];
        } elseif ($media_file && is_string($media_file)) {
            $thumbnail = $media_file;
        }

        $external_url = get_field('product_button_url', $id);
        $button_text = get_field('product_button_text', $id);

        $results[] = [
            'type'         => 'producto',
            'title'        => get_the_title($id),
            'excerpt'      => $button_text ?: 'Ver producto',
            'permalink'    => $external_url ?: get_permalink($id),
            'thumbnail'    => $thumbnail,
            'external_url' => $external_url ?: '',
            'category'     => 'Producto',
            'date'         => get_the_date('Y-m-d', $id),
        ];
    }

    return $results;
}

function grupofadiar_search_corporativa($search_terms, $raw_search, $limit) {
    $results = grupofadiar_search_options($raw_search, [
        ['key' => 'our_story_title',        'page' => '/about-us/',    'anchor' => '#ourStory',    'type' => 'corporativa', 'category' => 'Nuestra Historia'],
        ['key' => 'brands_section_title',    'page' => '/',             'anchor' => '#ourBrands',   'type' => 'corporativa', 'category' => 'Sección'],
        ['key' => 'brands_section_subtitle', 'page' => '/',             'anchor' => '#ourBrands',   'type' => 'corporativa', 'category' => 'Sección'],
        ['key' => 'products_section_title',  'page' => '/',             'anchor' => '#products',    'type' => 'corporativa', 'category' => 'Sección'],
        ['key' => 'contact_page_title',      'page' => '/contacts/',   'anchor' => '',              'type' => 'corporativa', 'category' => 'Contactos'],
        ['key' => 'contact_page_subtitle',   'page' => '/contacts/',   'anchor' => '',              'type' => 'corporativa', 'category' => 'Contactos'],
    ]);

    $corporativa_cpts = [
        'about_us'        => ['about_page_title', 'about_metrics_description_1', 'about_metrics_description_2'],
        'our_story_item'  => ['osi_text', 'osi_intro_text', 'osi_leader_name', 'osi_leader_short_description', 'osi_leader_full_description'],
        'pilar_corporativo' => ['pillar_subtitle', 'pillar_description'],
        'discover_group'  => ['discover_subtitle', 'discover_description_1', 'discover_description_2'],
    ];

    foreach ($corporativa_cpts as $cpt => $acf_fields) {
        $post_ids = grupofadiar_search_in_cpt($cpt, $raw_search, $acf_fields, $limit);
        foreach ($post_ids as $id) {
            $post = get_post($id);
            $anchor = '';

            if ($cpt === 'pilar_corporativo') {
                $anchor = '#pillar-' . $post->post_name;
            } elseif ($cpt === 'our_story_item') {
                $anchor = '#ourStory';
            } elseif ($cpt === 'about_us') {
                $anchor = '#metrics';
            } elseif ($cpt === 'discover_group') {
                $anchor = '#discoverGroup';
            }

            $base_url = ($cpt === 'discover_group') ? home_url('/') : home_url('/about-us/');
            $permalink = $base_url . $anchor;

            $excerpt = grupofadiar_get_first_acf_text($id, $acf_fields);

            $results[] = [
                'type'         => 'corporativa',
                'title'        => get_the_title($id),
                'excerpt'      => wp_trim_words(wp_strip_all_tags($excerpt), 25),
                'permalink'    => $permalink,
                'thumbnail'    => get_the_post_thumbnail_url($id, 'medium') ?: '',
                'external_url' => '',
                'category'     => grupofadiar_corporativa_category($cpt),
                'date'         => get_the_date('Y-m-d', $id),
            ];
        }
    }

    return $results;
}

function grupofadiar_search_garantias($search_terms, $raw_search, $limit) {
    $results = grupofadiar_search_options($raw_search, [
        ['key' => 'support_warranty_title',         'page' => '/support-warranty/', 'anchor' => '',              'type' => 'garantia', 'category' => 'Soporte y Garantía'],
        ['key' => 'support_warranty_subtitle',       'page' => '/support-warranty/', 'anchor' => '',              'type' => 'garantia', 'category' => 'Soporte y Garantía'],
        ['key' => 'support_warranty_description',    'page' => '/support-warranty/', 'anchor' => '',              'type' => 'garantia', 'category' => 'Soporte y Garantía'],
        ['key' => 'warranty_section_left_title',     'page' => '/support-warranty/', 'anchor' => '#warrantyInfo', 'type' => 'garantia', 'category' => 'Proceso de Reclamación'],
        ['key' => 'warranty_section_right_title',    'page' => '/support-warranty/', 'anchor' => '#warrantyInfo', 'type' => 'garantia', 'category' => 'Contactos de Garantía'],
        ['key' => 'faq_section_title',               'page' => '/support-warranty/', 'anchor' => '#faq',          'type' => 'garantia', 'category' => 'Preguntas Frecuentes'],
        ['key' => 'support_home_section_title',      'page' => '/',                   'anchor' => '#supportHome', 'type' => 'garantia', 'category' => 'Soporte y Garantía'],
        ['key' => 'support_home_section_subtitle',   'page' => '/',                   'anchor' => '#supportHome', 'type' => 'garantia', 'category' => 'Soporte y Garantía'],
    ]);

    $garantias_cpts = [
        'warranty_step'     => ['ws_step_description'],
        'warranty_contact'  => ['wc_label', 'wc_phone', 'wc_schedule'],
        'faq_item'          => ['faq_answer'],
        'support_home_item' => ['support_item_description'],
        'support_header_item' => [],
    ];

    foreach ($garantias_cpts as $cpt => $acf_fields) {
        $post_ids = grupofadiar_search_in_cpt($cpt, $raw_search, $acf_fields, $limit);
        foreach ($post_ids as $id) {
            $post = get_post($id);
            $anchor = '';

            if ($cpt === 'warranty_step') {
                $anchor = '#warranty-step-' . $post->post_name;
            } elseif ($cpt === 'warranty_contact') {
                $anchor = '#warranty-contact-' . $post->post_name;
            } elseif ($cpt === 'faq_item') {
                $anchor = '#faq-' . $post->post_name;
            } elseif ($cpt === 'support_home_item') {
                $base_url = home_url('/');
                $anchor = '#supportHome';

                $excerpt = grupofadiar_get_first_acf_text($id, $acf_fields);

                $results[] = [
                    'type'         => 'garantia',
                    'title'        => get_the_title($id),
                    'excerpt'      => wp_trim_words(wp_strip_all_tags($excerpt), 25),
                    'permalink'    => $base_url . $anchor,
                    'thumbnail'    => get_the_post_thumbnail_url($id, 'medium') ?: '',
                    'external_url' => '',
                    'category'     => grupofadiar_garantias_category($cpt),
                    'date'         => get_the_date('Y-m-d', $id),
                ];
                continue;
            }

            $excerpt = grupofadiar_get_first_acf_text($id, $acf_fields);

            $results[] = [
                'type'         => 'garantia',
                'title'        => get_the_title($id),
                'excerpt'      => wp_trim_words(wp_strip_all_tags($excerpt), 25),
                'permalink'    => home_url('/support-warranty/' . $anchor),
                'thumbnail'    => get_the_post_thumbnail_url($id, 'medium') ?: '',
                'external_url' => '',
                'category'     => grupofadiar_garantias_category($cpt),
                'date'         => get_the_date('Y-m-d', $id),
            ];
        }
    }

    return $results;
}

function grupofadiar_search_options($raw_search, $option_configs) {
    $results = [];
    foreach ($option_configs as $cfg) {
        $value = get_option($cfg['key'], '');
        if ($value !== '' && grupofadiar_str_contains($value, $raw_search)) {
            $results[] = [
                'type'         => $cfg['type'],
                'title'        => $value,
                'excerpt'      => wp_trim_words(wp_strip_all_tags($value), 25),
                'permalink'    => home_url($cfg['page'] . $cfg['anchor']),
                'thumbnail'    => '',
                'external_url' => '',
                'category'     => $cfg['category'],
                'date'         => '',
            ];
        }
    }
    return $results;
}

function grupofadiar_search_in_cpt($cpt, $search, $acf_fields = [], $limit = 20) {
    $post_ids = [];

    $main_query = new WP_Query([
        'post_type'      => $cpt,
        's'              => $search,
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);
    $post_ids = $main_query->posts;

    foreach ($acf_fields as $field_name) {
        $meta_ids = get_posts([
            'post_type'      => $cpt,
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'meta_query'     => [
                [
                    'key'     => $field_name,
                    'value'   => $search,
                    'compare' => 'LIKE',
                ],
            ],
        ]);
        $post_ids = array_merge($post_ids, $meta_ids);
    }

    $post_ids = array_unique($post_ids);
    return array_slice($post_ids, 0, $limit);
}

function grupofadiar_get_first_acf_text($post_id, $acf_fields) {
    foreach ($acf_fields as $field) {
        $value = get_field($field, $post_id);
        if (!empty($value) && is_string($value)) {
            return $value;
        }
    }
    return get_post_field('post_excerpt', $post_id) ?: get_post_field('post_content', $post_id);
}

function grupofadiar_corporativa_category($cpt) {
    $map = [
        'about_us'        => 'Grupo Fadiar',
        'our_story_item'  => 'Nuestra Historia',
        'pilar_corporativo' => 'Valores Corporativos',
        'discover_group'  => 'Quiénes Somos',
    ];
    return isset($map[$cpt]) ? $map[$cpt] : 'Información Corporativa';
}

function grupofadiar_garantias_category($cpt) {
    $map = [
        'warranty_step'      => 'Proceso de Reclamación',
        'warranty_contact'   => 'Contactos de Garantía',
        'faq_item'           => 'Preguntas Frecuentes',
        'support_home_item'  => 'Soporte y Garantía',
        'support_header_item' => 'Soporte y Garantía',
    ];
    return isset($map[$cpt]) ? $map[$cpt] : 'Garantía';
}

if (!function_exists('grupofadiar_str_contains')) {
    function grupofadiar_str_contains($haystack, $needle) {
        return mb_stripos($haystack, $needle) !== false;
    }
}
