<?php

add_action('rest_api_init', function () {
    register_rest_route('grupofadiar/v1', '/search', [
        'methods'             => 'GET',
        'callback'            => 'gf_search_handler',
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
            'page' => [
                'required'          => false,
                'sanitize_callback' => 'absint',
                'default'           => 1,
            ],
        ],
    ]);
});

function gf_search_handler(WP_REST_Request $request) {
    $search = trim($request->get_param('search'));
    $filter = strtolower(trim($request->get_param('filter')));
    $page   = max(1, (int) $request->get_param('page'));
    $lang   = gf_current_lang();
    $per_page = 10;

    if ($search === '' && $filter === 'todos') {
        return new WP_REST_Response(['results' => [], 'total' => 0, 'query' => '', 'page' => 1], 200);
    }

    if (!in_array($filter, ['todos', 'productos', 'noticias', 'corporativa', 'garantias'], true)) {
        $filter = 'todos';
    }

    $all_results = [];
    $max_per_type = 20;

    if ($filter === 'todos' || $filter === 'noticias') {
        $all_results = array_merge($all_results, gf_search_noticias($search, $lang, $max_per_type));
    }
    if ($filter === 'todos' || $filter === 'productos') {
        $all_results = array_merge($all_results, gf_search_productos($search, $lang, $max_per_type));
    }
    if ($filter === 'todos') {
        $all_results = array_merge($all_results, gf_search_brand($search, $lang, $max_per_type));
    }
    if ($filter === 'todos' || $filter === 'corporativa') {
        $all_results = array_merge($all_results, gf_search_corporativa($search, $lang, $max_per_type));
        $all_results = array_merge($all_results, gf_search_carousel($search, $lang, $max_per_type));
    }
    if ($filter === 'todos' || $filter === 'garantias') {
        $all_results = array_merge($all_results, gf_search_garantias($search, $lang, $max_per_type));
    }
    if ($filter === 'todos') {
        $all_results = array_merge($all_results, gf_search_options($search, $lang, $max_per_type));
    }

    $unique_results = [];
    foreach ($all_results as $res) {
        $uf = $res['permalink'] . '|' . ($res['type'] ?? '') . '|' . $res['title'];
        if (!isset($unique_results[$uf])) {
            $unique_results[$uf] = $res;
        }
    }
    $all_results = array_values($unique_results);

    usort($all_results, function ($a, $b) use ($search) {
        $order = ($b['order'] ?? 0) - ($a['order'] ?? 0);
        if ($order !== 0) return $order;
        $q = gf_aq_normalize($search);
        $tA = gf_aq_normalize($a['title']);
        $tB = gf_aq_normalize($b['title']);
        $titleA = strpos($tA, $q) !== false ? 1 : 0;
        $titleB = strpos($tB, $q) !== false ? 1 : 0;
        if ($titleA !== $titleB) return $titleB - $titleA;
        return strcmp($a['title'], $b['title']);
    });

    $total = count($all_results);
    $offset = ($page - 1) * $per_page;
    $paginated = array_slice($all_results, $offset, $per_page);

    return new WP_REST_Response([
        'results' => $paginated,
        'total'   => $total,
        'query'   => $search,
        'page'    => $page,
    ], 200);
}

function gf_normalize_entities($str) {
    if (!is_string($str) || $str === '') return $str;
    $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $str;
}

function gf_aq_normalize($text) {
    if (!is_string($text)) return '';
    $text = wp_strip_all_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $unwanted = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n','Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ü'=>'U','Ñ'=>'N'];
    $text = strtr($text, $unwanted);
    $text = strtolower(trim(preg_replace('/\s+/u', ' ', $text)));
    return $text;
}

function gf_text_matches($query, $text) {
    $q = gf_aq_normalize($query);
    $t = gf_aq_normalize($text);
    if ($q === '' || $t === '') return false;

    if (strpos($t, $q) !== false) {
        return true;
    }

    $tokens = preg_split('/\s+/', $q);
    foreach ($tokens as $token) {
        if (strlen($token) < 2) continue;
        if (strpos($t, $token) === false) {
            return false;
        }
    }
    return true;
}

function gf_match_rank($query, $text) {
    $q = gf_aq_normalize($query);
    $t = gf_aq_normalize($text);
    if ($q === '' || $t === '') return 0;

    if (strpos($t, $q) !== false) {
        return 2;
    }

    $tokens = preg_split('/\s+/', $q);
    $all_present = false;
    $token_count = 0;
    foreach ($tokens as $token) {
        if (strlen($token) < 2) continue;
        $token_count++;
        if (strpos($t, $token) === false) {
            $all_present = false;
            break;
        }
        $all_present = true;
    }
    return ($token_count > 0 && $all_present) ? 1 : 0;
}

function gf_aq_score_text($query, $text) {
    return gf_match_rank($query, $text);
}

function gf_search_noticias($search, $lang, $limit) {
    $results = [];
    $base_fields = ['intro_noticia', 'descripcion', 'autor', 'fecha_noticia'];
    $en_fields = ['intro_noticia_en', 'descripcion_en', 'autor_en', 'fecha_noticia_en'];
    $fields_to_scan = ($lang === 'en') ? array_unique(array_merge($base_fields, $en_fields)) : $base_fields;

    $matched = gf_search_in_cpt('noticia', $search, $fields_to_scan, $lang, $limit);

    foreach ($matched as $item) {
        $id = $item['id'];
        $best_rank = $item['rank'];

        $categories = wp_get_post_terms($id, 'categoria_noticia');
        $category_name = !empty($categories) ? gf_get_term_name($categories[0]) : '';

        $excerpt = gf_get_field('intro_noticia', $id);
        if (!$excerpt) {
            $content = get_post_field('post_content', $id);
            $excerpt = wp_strip_all_tags($content);
        }

        $thumbnail = get_the_post_thumbnail_url($id, 'medium');

        $results[] = [
            'type'          => 'noticia',
            'title'         => gf_normalize_entities(gf_get_post_title($id)),
            'excerpt'       => gf_normalize_entities(wp_trim_words($excerpt, 25)),
            'permalink'     => get_permalink($id),
            'thumbnail'     => $thumbnail ?: '',
            'external_url'  => '',
            'external_text' => '',
            'category'      => gf_normalize_entities($category_name ?: gf_get_category_name('noticia')),
            'date'          => gf_get_field('fecha_noticia', $id) ?: get_the_date('Y-m-d', $id),
            'order'         => $best_rank,
        ];
    }

    return $results;
}

function gf_search_productos($search, $lang, $limit) {
    $results = [];
    $base_fields = ['product_button_text'];
    $en_fields = ['product_button_text_en'];
    $fields_to_scan = ($lang === 'en') ? array_unique(array_merge($base_fields, $en_fields)) : $base_fields;

    $matched = gf_search_in_cpt('home_product', $search, $fields_to_scan, $lang, $limit);

    foreach ($matched as $item) {
        $id = $item['id'];
        $best_rank = $item['rank'];

        $media_file = get_field('product_media_file', $id);
        $thumbnail = '';
        if ($media_file && is_array($media_file) && isset($media_file['url'])) {
            $thumbnail = $media_file['url'];
        } elseif ($media_file && is_string($media_file)) {
            $thumbnail = $media_file;
        }

        $external_url = get_field('product_button_url', $id);
        $button_text  = gf_get_field('product_button_text', $id);
        $stored_button_text = $button_text ?: ($lang === 'en' ? 'View product' : 'Ver producto');

        $results[] = [
            'type'          => 'producto',
            'title'         => gf_normalize_entities(gf_get_post_title($id)),
            'excerpt'       => gf_normalize_entities($stored_button_text),
            'permalink'     => $external_url ?: get_permalink($id),
            'thumbnail'     => $thumbnail,
            'external_url'  => $external_url ?: '',
            'external_text' => gf_normalize_entities($external_url ? ($button_text ?: ($lang === 'en' ? 'Open in store' : 'Abrir tienda')) : ''),
            'category'      => gf_normalize_entities(gf_get_category_name('producto')),
            'date'          => get_the_date('Y-m-d', $id),
            'order'         => $best_rank,
        ];
    }

    return $results;
}

function gf_search_brand($search, $lang, $limit) {
    $results = [];
    $base_fields = ['brand_description', 'brand_button_text'];
    $en_fields = ['brand_description_en', 'brand_button_text_en'];
    $fields_to_scan = ($lang === 'en') ? array_unique(array_merge($base_fields, $en_fields)) : $base_fields;

    $matched = gf_search_in_cpt('brand', $search, $fields_to_scan, $lang, $limit);

    foreach ($matched as $item) {
        $id = $item['id'];
        $best_rank = $item['rank'];

        $description = gf_get_field('brand_description', $id);

        $results[] = [
            'type'          => 'brand',
            'title'         => gf_normalize_entities(gf_get_post_title($id)),
            'excerpt'       => gf_normalize_entities($description ?: ''),
            'permalink'     => home_url('/#ourBrands'),
            'thumbnail'     => get_the_post_thumbnail_url($id, 'medium') ?: '',
            'external_url'  => '',
            'external_text' => '',
            'category'      => gf_normalize_entities(gf_get_category_name('brand')),
            'date'          => get_the_date('Y-m-d', $id),
            'order'         => $best_rank,
        ];
    }

    return $results;
}

function gf_search_carousel($search, $lang, $limit) {
    $results = [];
    $base_fields = ['slide_title_text', 'slide_subtitle', 'slide_description'];
    $en_fields = ['slide_title_text_en', 'slide_subtitle_en', 'slide_description_en'];
    $fields_to_scan = ($lang === 'en') ? array_unique(array_merge($base_fields, $en_fields)) : $base_fields;

    $matched = gf_search_in_cpt('carousel_slide', $search, $fields_to_scan, $lang, $limit);

    foreach ($matched as $item) {
        $id = $item['id'];
        $best_rank = $item['rank'];

        $slide_subtitle = gf_get_field('slide_subtitle', $id);
        $slide_title    = gf_get_field('slide_title_text', $id);
        $slide_desc     = gf_get_field('slide_description', $id);
        $excerpt = $slide_subtitle ?: $slide_title ?: $slide_desc ?: '';

        $results[] = [
            'type'          => 'carousel',
            'title'         => gf_normalize_entities(gf_get_post_title($id)),
            'excerpt'       => gf_normalize_entities(wp_trim_words($excerpt, 25)),
            'permalink'     => home_url('/#heroCarousel'),
            'thumbnail'     => get_the_post_thumbnail_url($id, 'medium') ?: '',
            'external_url'  => '',
            'external_text' => '',
            'category'      => gf_normalize_entities(gf_get_category_name('carousel')),
            'date'          => get_the_date('Y-m-d', $id),
            'order'         => $best_rank,
        ];
    }

    return $results;
}

function gf_search_corporativa($search, $lang, $limit) {
    $results = [];

    $base_map = [
        'about_us'        => ['about_page_title', 'about_metrics_description_1', 'about_metrics_description_2'],
        'our_story_item'  => ['osi_text', 'osi_intro_text', 'osi_leader_name', 'osi_leader_short_description', 'osi_leader_full_description'],
        'pilar_corporativo' => ['pillar_subtitle', 'pillar_description'],
        'discover_group'  => ['discover_subtitle', 'discover_description_1', 'discover_description_2'],
    ];
    $en_map = [];
    foreach ($base_map as $cpt => $fields) {
        $en_map[$cpt] = array_map(function ($f) { return $f . '_en'; }, $fields);
    }

    foreach ($base_map as $cpt => $base_fields) {
        $en_fields = $en_map[$cpt];
        $fields_to_scan = ($lang === 'en') ? array_unique(array_merge($base_fields, $en_fields)) : $base_fields;

        $matched = gf_search_in_cpt($cpt, $search, $fields_to_scan, $lang, $limit);
        foreach ($matched as $item) {
            $id = $item['id'];
            $best_rank = $item['rank'];

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

            $excerpt = gf_get_first_acf_text($id, array_merge($base_fields, $en_fields));

            $results[] = [
                'type'          => 'corporativa',
                'title'         => gf_normalize_entities(gf_get_post_title($id)),
                'excerpt'       => gf_normalize_entities(wp_trim_words(wp_strip_all_tags($excerpt), 25)),
                'permalink'     => $permalink,
                'thumbnail'     => get_the_post_thumbnail_url($id, 'medium') ?: '',
                'external_url'  => '',
                'category'      => gf_normalize_entities(gf_corporativa_category($cpt)),
                'date'          => get_the_date('Y-m-d', $id),
                'order'         => $best_rank,
            ];
        }
    }

    return $results;
}

function gf_search_garantias($search, $lang, $limit) {
    $results = [];

    $base_map = [
        'warranty_step'      => ['ws_step_description'],
        'warranty_contact'   => ['wc_label', 'wc_schedule', 'wc_phone'],
        'faq_item'           => ['faq_answer'],
        'support_home_item'  => ['support_item_description'],
        'support_header_item' => [],
    ];

    foreach ($base_map as $cpt => $base_fields) {
        $en_fields = array_map(function ($f) { return $f . '_en'; }, $base_fields);
        $fields_to_scan = ($lang === 'en') ? array_unique(array_merge($base_fields, $en_fields)) : $base_fields;

        $matched = gf_search_in_cpt($cpt, $search, $fields_to_scan, $lang, $limit);
        foreach ($matched as $item) {
            $id = $item['id'];
            $best_rank = $item['rank'];

            $post = get_post($id);
            $anchor = '';

            if ($cpt === 'warranty_step') {
                $anchor = '#warranty-step-' . $post->post_name;

                $step_number = get_field('ws_step_number', $id);
                $step_desc   = gf_get_field('ws_step_description', $id);
                $step_desc_clean = wp_strip_all_tags($step_desc);

                $constructed_title = $step_number
                    ? sprintf('%d. %s', $step_number, wp_trim_words($step_desc_clean, 10, ''))
                    : wp_trim_words($step_desc_clean, 10, '');

                $results[] = [
                    'type'          => 'garantia',
                    'title'         => gf_normalize_entities($constructed_title),
                    'excerpt'       => wp_trim_words($step_desc_clean, 30),
                    'permalink'     => home_url('/support-warranty/' . $anchor),
                    'thumbnail'     => get_the_post_thumbnail_url($id, 'medium') ?: '',
                    'external_url'  => '',
                    'category'      => gf_normalize_entities(gf_garantias_category($cpt)),
                    'date'          => get_the_date('Y-m-d', $id),
                    'order'         => $best_rank,
                ];
                continue;
            } elseif ($cpt === 'warranty_contact') {
                $anchor = '#warranty-contact-' . $post->post_name;
            } elseif ($cpt === 'faq_item') {
                $anchor = '#faq-' . $post->post_name;
            } elseif ($cpt === 'support_home_item') {
                $base_url = home_url('/');
                $anchor = '#supportHome';

                $excerpt = gf_get_first_acf_text($id, $fields_to_scan);

                $results[] = [
                    'type'          => 'garantia',
                    'title'         => gf_normalize_entities(gf_get_post_title($id)),
                    'excerpt'       => gf_normalize_entities(wp_trim_words(wp_strip_all_tags($excerpt), 25)),
                    'permalink'    => $base_url . $anchor,
                    'thumbnail'    => get_the_post_thumbnail_url($id, 'medium') ?: '',
                    'external_url' => '',
                    'category'     => gf_normalize_entities(gf_garantias_category($cpt)),
                    'date'         => get_the_date('Y-m-d', $id),
                    'order'        => $best_rank,
                ];
                continue;
            }

            $excerpt = gf_get_first_acf_text($id, $fields_to_scan);

            $results[] = [
                'type'          => 'garantia',
                'title'         => gf_normalize_entities(gf_get_post_title($id)),
                'excerpt'       => gf_normalize_entities(wp_trim_words(wp_strip_all_tags($excerpt), 25)),
                'permalink'     => home_url('/support-warranty/' . $anchor),
                'thumbnail'     => get_the_post_thumbnail_url($id, 'medium') ?: '',
                'external_url'  => '',
                'category'      => gf_normalize_entities(gf_garantias_category($cpt)),
                'date'          => get_the_date('Y-m-d', $id),
                'order'         => $best_rank,
            ];
        }
    }

    return $results;
}

function gf_search_options($search, $lang, $limit) {
    $results = [];

    $options_map = [
        ['products_section_title',       home_url('/#products'),                  'Productos',           'Products',           null],
        ['brands_section_title',         home_url('/#ourBrands'),                 'Nuestras marcas',     'Our Brands',         'brands_section_subtitle'],
        ['brands_section_subtitle',      home_url('/#ourBrands'),                 'Nuestras marcas',     'Our Brands',         null],
        ['support_home_section_title',   home_url('/#supportHome'),               'Soporte y Garantía',  'Support and Warranty','support_home_section_subtitle'],
        ['support_home_section_subtitle',home_url('/#supportHome'),               'Soporte y Garantía',  'Support and Warranty',null],
        ['our_story_title',              home_url('/about-us/#ourStory'),         'Nuestra historia',    'Our Story',          'our_story_paragraph_1'],
        ['our_story_paragraph_1',        home_url('/about-us/#ourStory'),         'Nuestra historia',    'Our Story',          null],
        ['our_story_paragraph_2',        home_url('/about-us/#ourStory'),         'Nuestra historia',    'Our Story',          null],
        ['contact_page_title',           home_url('/contacts'),                   'Contacto',            'Contact',            'contact_page_subtitle'],
        ['contact_page_subtitle',        home_url('/contacts'),                   'Contacto',            'Contact',            null],
        ['contact_address_label',        home_url('/contacts'),                   'Contacto',            'Contact',            'contact_main_address'],
        ['contact_main_address',         home_url('/contacts'),                   'Contacto',            'Contact',            null],
        ['contact_schedule_label',       home_url('/contacts'),                   'Contacto',            'Contact',            'contact_schedule_value'],
        ['contact_schedule_value',       home_url('/contacts'),                   'Contacto',            'Contact',            null],
        ['noticias_page_title',          home_url('/noticias'),                   'Noticias',            'News',               'noticias_page_subtitle'],
        ['noticias_page_subtitle',       home_url('/noticias'),                   'Noticias',            'News',               null],
        ['support_warranty_title',       home_url('/support-warranty'),           'Soporte y Garantía',  'Support and Warranty','support_warranty_subtitle'],
        ['support_warranty_subtitle',    home_url('/support-warranty'),           'Soporte y Garantía',  'Support and Warranty',null],
        ['support_warranty_description', home_url('/support-warranty'),           'Soporte y Garantía',  'Support and Warranty',null],
        ['warranty_section_left_title',  home_url('/support-warranty#warrantyInfo'), 'Garantías',       'Warranties',         null],
        ['warranty_section_right_title', home_url('/support-warranty#warrantyInfo'), 'Garantías',       'Warranties',         null],
        ['faq_section_title',            home_url('/support-warranty#faq'),       'Preguntas frecuentes','Frequently Asked Questions', null],
    ];

    foreach ($options_map as $entry) {
        if (count($results) >= $limit) break;

        list($key, $url, $cat_es, $cat_en, $excerpt_key) = $entry;

        if ($lang === 'en') {
            $value = get_option($key . '_en');
        } else {
            $value = get_option($key);
        }
        if ($value === false || $value === '') continue;

        $rank = gf_match_rank($search, $value);
        if ($rank < 1) continue;

        $excerpt = '';
        if ($excerpt_key) {
            $excerpt_raw = ($lang === 'en') ? get_option($excerpt_key . '_en') : get_option($excerpt_key);
            if ($excerpt_raw && is_string($excerpt_raw)) {
                $excerpt = wp_trim_words($excerpt_raw, 25);
            }
        }

        $category = ($lang === 'en') ? $cat_en : $cat_es;

        $results[] = [
            'type'          => 'opcion',
            'title'         => gf_normalize_entities($value),
            'excerpt'       => gf_normalize_entities($excerpt),
            'permalink'     => $url,
            'thumbnail'     => '',
            'external_url'  => '',
            'external_text' => '',
            'category'      => gf_normalize_entities($category),
            'date'          => '',
            'order'         => $rank,
        ];
    }

    return $results;
}

function gf_search_in_cpt($cpt, $search, $fields_to_scan, $lang, $limit = 20) {
    if (empty($search)) {
        $all = get_posts([
            'post_type'      => $cpt,
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);
        $result = [];
        foreach ($all as $id) {
            $result[] = ['id' => $id, 'rank' => 0];
        }
        return $result;
    }

    $all_posts = get_posts([
        'post_type'      => $cpt,
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    $matched = [];

    foreach ($all_posts as $id) {
        $best_rank = 0;

        $title = get_the_title($id);
        $rank = gf_match_rank($search, $title);
        if ($rank > $best_rank) $best_rank = $rank;

        foreach ($fields_to_scan as $field) {
            if (empty($field)) continue;
            $value = get_field($field, $id);
            if (!is_string($value) && !is_numeric($value)) continue;
            $rank = gf_match_rank($search, (string)$value);
            if ($rank > $best_rank) $best_rank = $rank;
        }

        $content = get_post_field('post_content', $id);
        if (is_string($content) && $content !== '') {
            $rank = gf_match_rank($search, $content);
            if ($rank > $best_rank) $best_rank = $rank;
        }

        $excerpt = get_post_field('post_excerpt', $id);
        if (is_string($excerpt) && $excerpt !== '') {
            $rank = gf_match_rank($search, $excerpt);
            if ($rank > $best_rank) $best_rank = $rank;
        }

        $all_meta = get_post_meta($id);
        foreach ($all_meta as $key => $values) {
            if (strpos($key, '_') === 0) continue;
            if ($lang !== 'en' && substr($key, -3) === '_en') continue;
            foreach ((array)$values as $v) {
                if (!is_string($v) || $v === '') continue;
                $rank = gf_match_rank($search, $v);
                if ($rank > $best_rank) $best_rank = $rank;
            }
        }

        if ($best_rank >= 1) {
            $matched[] = ['id' => $id, 'rank' => $best_rank];
        }
    }

    usort($matched, function ($a, $b) {
        return ($b['rank'] - $a['rank']);
    });

    return array_slice($matched, 0, $limit);
}

function gf_get_first_acf_text($post_id, $acf_fields) {
    foreach ($acf_fields as $field) {
        $value = get_field($field, $post_id);
        if (!empty($value) && is_string($value)) {
            return $value;
        }
    }
    return get_post_field('post_excerpt', $post_id) ?: get_post_field('post_content', $post_id);
}

function gf_corporativa_category($cpt) {
    $lang = gf_current_lang();
    $map = [
        'about_us'        => ['es' => 'Grupo Fadiar',               'en' => 'Grupo Fadiar'],
        'our_story_item'  => ['es' => 'Nuestra Historia',           'en' => 'Our Story'],
        'pilar_corporativo' => ['es' => 'Valores Corporativos',     'en' => 'Corporate Values'],
        'discover_group'  => ['es' => 'Quiénes Somos',              'en' => 'Who We Are'],
    ];
    return isset($map[$cpt][$lang]) ? $map[$cpt][$lang] : 'Información Corporativa';
}

function gf_garantias_category($cpt) {
    $lang = gf_current_lang();
    $map = [
        'warranty_step'      => ['es' => 'Proceso de Reclamación',   'en' => 'Claims Process'],
        'warranty_contact'   => ['es' => 'Contactos de Garantía',    'en' => 'Warranty Contacts'],
        'faq_item'           => ['es' => 'Preguntas Frecuentes',     'en' => 'Frequently Asked Questions'],
        'support_home_item'  => ['es' => 'Soporte y Garantía',       'en' => 'Support and Warranty'],
        'support_header_item' => ['es' => 'Soporte y Garantía',      'en' => 'Support and Warranty'],
    ];
    return isset($map[$cpt][$lang]) ? $map[$cpt][$lang] : 'Garantía';
}
