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
            'lang' => [
                'required'          => false,
                'sanitize_callback' => 'sanitize_text_field',
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

    global $wpdb;
    $search_queries = [$search];

    // TranslatePress compatibility: find original strings for translated search terms
    $tables = $wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}trp_dictionary_%'");
    if (!empty($tables)) {
        foreach ($tables as $table) {
            // Ignore untranslated tables as they don't have the translations
            if (strpos($table, '_untranslated') !== false) {
                continue;
            }
            $originals = $wpdb->get_col($wpdb->prepare(
                "SELECT original FROM `$table` WHERE translated LIKE %s LIMIT 20",
                '%' . $wpdb->esc_like($search) . '%'
            ));
            if (!empty($originals)) {
                foreach ($originals as $orig) {
                    $clean_orig = trim(wp_strip_all_tags($orig));
                    if (!empty($clean_orig)) {
                        $search_queries[] = $clean_orig;
                    }
                }
            }
        }
    }
    $search_queries = array_unique($search_queries);

    if (count($search_queries) <= 1 && mb_strlen($search) > 5) {
        $words = explode(' ', $search);
        foreach ($words as $w) {
            $w = trim($w);
            if (mb_strlen($w) > 2) {
                $search_queries[] = $w;
            }
        }
        $search_queries = array_unique($search_queries);
    }

    $all_results = [];
    $max_per_type = 20;
    $max_total = 50;

    foreach ($search_queries as $sq) {
        $search_terms = explode(' ', $sq);

        if ($filter === 'todos' || $filter === 'noticias') {
            $all_results = array_merge($all_results, grupofadiar_search_noticias($search_terms, $sq, $max_per_type));
        }
        if ($filter === 'todos' || $filter === 'productos') {
            $all_results = array_merge($all_results, grupofadiar_search_productos($search_terms, $sq, $max_per_type));
        }
        if ($filter === 'todos' || $filter === 'corporativa') {
            $all_results = array_merge($all_results, grupofadiar_search_corporativa($search_terms, $sq, $max_per_type));
        }
        if ($filter === 'todos' || $filter === 'garantias') {
            $all_results = array_merge($all_results, grupofadiar_search_garantias($search_terms, $sq, $max_per_type));
        }
    }

    // Deduplicate results by permalink
    $unique_results = [];
    foreach ($all_results as $res) {
        $unique_results[$res['permalink']] = $res;
    }
    $all_results = array_values($unique_results);

    usort($all_results, function ($a, $b) {
        return strcmp($a['title'], $b['title']);
    });

    $total = count($all_results);
    $all_results = array_slice($all_results, 0, $max_total);

    $lang = trim($request->get_param('lang'));
    if (!empty($lang) && function_exists('trp_translate')) {
        // Map common short codes to TranslatePress defaults if needed
        if ($lang === 'en') $lang = 'en_US';
        if ($lang === 'es') $lang = 'es_ES';

        foreach ($all_results as &$res) {
            $res['title'] = grupofadiar_translate_text(wp_strip_all_tags($res['title']), $lang);
            if (!empty($res['_raw_excerpt'])) {
                $stripped = wp_strip_all_tags($res['_raw_excerpt']);
                $res['excerpt'] = wp_trim_words(grupofadiar_translate_text($stripped, $lang), 25);
                unset($res['_raw_excerpt']);
            } else {
                $res['excerpt'] = grupofadiar_translate_text(wp_strip_all_tags($res['excerpt']), $lang);
            }
            $res['category'] = grupofadiar_translate_text(wp_strip_all_tags($res['category']), $lang);
            if (!empty($res['external_text'])) {
                $res['external_text'] = grupofadiar_translate_text(wp_strip_all_tags($res['external_text']), $lang);
            }
        }
    } else {
        // Fallback cleanup if not translated
        foreach ($all_results as &$res) {
            if (isset($res['_raw_excerpt'])) unset($res['_raw_excerpt']);
        }
    }

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
            $excerpt = wp_strip_all_tags($content);
        }

        $thumbnail = get_the_post_thumbnail_url($id, 'medium');

        $results[] = [
            'type'         => 'noticia',
            'title'        => get_the_title($id),
            'excerpt'      => wp_trim_words($excerpt, 25),
            '_raw_excerpt' => $excerpt,
            'permalink'    => get_permalink($id),
            'thumbnail'    => $thumbnail ?: '',
            'external_url' => '',
            'external_text'=> '',
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

        $stored_button_text = $button_text ?: 'Ver producto';

        $results[] = [
            'type'          => 'producto',
            'title'         => get_the_title($id),
            'excerpt'       => $stored_button_text,
            '_raw_excerpt'  => $stored_button_text,
            'permalink'     => $external_url ?: get_permalink($id),
            'thumbnail'     => $thumbnail,
            'external_url'  => $external_url ?: '',
            'external_text' => $external_url ? ($button_text ?: 'Abrir tienda') : '',
            'category'      => 'Producto',
            'date'          => get_the_date('Y-m-d', $id),
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
                '_raw_excerpt' => $excerpt,
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
                    '_raw_excerpt' => $excerpt,
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
                '_raw_excerpt' => $excerpt,
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
                '_raw_excerpt' => $value,
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

/**
 * Translate text via TranslatePress, with direct TRP dictionary fallback.
 */
function grupofadiar_translate_text($string, $lang) {
    if (empty($string) || !function_exists('trp_translate')) {
        return $string;
    }

    $lang = ($lang === 'en' || $lang === 'en-US') ? 'en_US' : $lang;
    $lang = ($lang === 'es' || $lang === 'es-ES') ? 'es_ES' : $lang;

    $translated = trp_translate($string, $lang, false);
    if ($translated !== $string) {
        return $translated;
    }

    $stripped = wp_strip_all_tags($string);
    if ($stripped !== $string) {
        $translated = trp_translate($stripped, $lang, false);
        if ($translated !== $stripped) {
            return $translated;
        }
    }

    global $wpdb;
    $tables = $wpdb->get_col(
        $wpdb->prepare(
            "SHOW TABLES LIKE %s",
            $wpdb->esc_like($wpdb->prefix . 'trp_dictionary_') . '%'
        )
    );
    foreach ($tables as $table) {
        if (stripos($table, '_untranslated') !== false) continue;
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT translated FROM `$table` WHERE original = %s LIMIT 1",
            $stripped
        ));
        if (!empty($result)) return $result;
    }

    if ($lang === 'en_US') {
        $hardcoded = [
            'Quiénes Somos' => 'Who We Are',
            'Valores Corporativos' => 'Corporate Values',
            'Nuestra Historia' => 'Our Story',
            'Grupo Fadiar' => 'Group Fadiar',
            'Información Corporativa' => 'Corporate Information',
            'Sección' => 'Section',
            'Proceso de Reclamación' => 'Claims Process',
            'Contactos de Garantía' => 'Warranty Contacts',
            'Preguntas Frecuentes' => 'Frequently Asked Questions',
            'Soporte y Garantía' => 'Support and Warranty',
            'Ver producto' => 'View product',
            'Abrir tienda' => 'Open in store',
        ];
        if (isset($hardcoded[$stripped])) {
            return $hardcoded[$stripped];
        }
    }

    if (function_exists('trp_register_string')) {
        $registered = get_option('gf_trp_registered_static_strings', []);
        if (!in_array($stripped, $registered, true)) {
            trp_register_string('grupofadiar_static', $stripped, 'Search Categories');
            $registered[] = $stripped;
            update_option('gf_trp_registered_static_strings', $registered);
        }
    }

    return $string;
}

add_action('init', 'grupofadiar_register_static_strings');
function grupofadiar_register_static_strings() {
    if (!function_exists('trp_register_string')) return;

    $static_strings = [
        'Quiénes Somos', 'Valores Corporativos', 'Nuestra Historia',
        'Grupo Fadiar', 'Información Corporativa', 'Sección',
        'Proceso de Reclamación', 'Contactos de Garantía',
        'Preguntas Frecuentes', 'Soporte y Garantía', 'Garantía',
        'Ver producto', 'Abrir tienda',
    ];

    $registered = get_option('gf_trp_registered_static_strings', []);
    $changed = false;

    foreach ($static_strings as $s) {
        if (!in_array($s, $registered, true)) {
            trp_register_string('grupofadiar_static', $s, 'Search Categories');
            $registered[] = $s;
            $changed = true;
        }
    }

    if ($changed) {
        update_option('gf_trp_registered_static_strings', $registered);
    }
}

add_action('acf/save_post', 'grupofadiar_register_acf_strings', 20);
function grupofadiar_register_acf_strings($post_id) {
    if (!function_exists('trp_register_string')) return;
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;

    $post_type = get_post_type($post_id);
    $translatable_types = ['warranty_step', 'warranty_contact', 'faq_item', 'support_home_item',
                           'support_header_item', 'noticia', 'home_product',
                           'about_us', 'our_story_item', 'pilar_corporativo', 'discover_group'];

    if (!in_array($post_type, $translatable_types, true)) return;

    $fields = get_field_objects($post_id);
    if (!is_array($fields)) return;

    foreach ($fields as $field_name => $field) {
        if (!in_array($field['type'], ['text', 'textarea', 'wysiwyg'], true)) continue;
        $value = $field['value'];
        if (is_string($value) && !empty($value)) {
            trp_register_string($post_type . '_' . $field_name, $value, $post_type . ' - ' . $field['label']);
        }
    }
}
