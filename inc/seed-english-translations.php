<?php

if (!defined('ABSPATH')) {
    exit;
}

function gf_seed_english_translations() {
    if (get_option('gf_i18n_seeded_v1')) {
        return;
    }

    gf_seed_options();
    gf_seed_cpt_translations();
    gf_seed_extra_noticia_translations();
    gf_seed_category_translations();
    gf_seed_faq_items();

    update_option('gf_i18n_seeded_v1', time());
}
add_action('after_switch_theme', 'gf_seed_english_translations');
add_action('admin_init', 'gf_seed_english_translations');

function gf_seed_options() {
    $pairs = [
        'products_section_title'       => ['es' => 'Productos',                          'en' => 'Products'],
        'brands_section_title'         => ['es' => 'Nuestras marcas',                   'en' => 'Our Brands'],
        'brands_section_subtitle'      => ['es' => 'Diversidad de soluciones, un solo compromiso', 'en' => 'Diverse solutions, one single commitment'],
        'support_home_section_title'   => ['es' => 'Soporte y Garantía',                 'en' => 'Support and Warranty'],
        'support_home_section_subtitle'=> ['es' => 'POR QUÉ ESCOGER GRUPO FADIAR',       'en' => 'WHY CHOOSE GRUPO FADIAR'],
        'support_warranty_title'       => ['es' => 'Soporte y Garantía',                 'en' => 'Support and Warranty'],
        'support_warranty_subtitle'    => ['es' => 'Atención técnica y reclamaciones',   'en' => 'Technical assistance and claims'],
        'support_warranty_description' => ['es' => '',                                   'en' => ''],
        'our_story_title'              => ['es' => 'Nuestra historia',                   'en' => 'Our Story'],
        'our_story_paragraph_1'        => ['es' => 'Grupo Fadiar nació en 2023...',      'en' => 'Grupo Fadiar was born in 2023 with the vision of transforming the national industry. Starting from a small workshop, we have grown into a business group that integrates three leading brands.'],
        'our_story_paragraph_2'        => ['es' => 'Nuestros hitos incluyen...',         'en' => 'Our milestones include the opening of our facilities in Ciudad Libertad, the launch of our first product lines, and alliances with distributors nationwide and internationally. Today, we continue building the future with passion and responsibility.'],
        'contact_page_title'           => ['es' => 'Contáctanos',                         'en' => 'Contact Us'],
        'contact_page_subtitle'        => ['es' => 'Escríbenos, llama o visítanos...',    'en' => 'Write to us, call or visit. We are here to help.'],
        'contact_address_label'        => ['es' => 'Direcciones:',                       'en' => 'Addresses:'],
        'contact_main_address'         => ['es' => 'Calle 29F...',                       'en' => 'Calle 29F between 114 and 114A, Building 11413, Warehouse 9A (ENAME). Ciudad Libertad, Marianao, Havana, Cuba.'],
        'contact_schedule_label'       => ['es' => 'Horario:',                           'en' => 'Schedule:'],
        'contact_schedule_value'       => ['es' => 'Lun-Vie 9:00 – 17:00.',              'en' => 'Mon–Fri 9:00 – 17:00.'],
        'noticias_page_title'          => ['es' => 'Noticias',                            'en' => 'News'],
        'noticias_page_subtitle'       => ['es' => 'Mantente al día...',                  'en' => 'Stay up to date with the latest news, launches and events from Grupo Fadiar.'],
        'warranty_section_left_title'  => ['es' => 'Proceso de reclamación',             'en' => 'Claims Process'],
        'warranty_section_right_title' => ['es' => 'Contactos',                          'en' => 'Contacts'],
        'faq_section_title'            => ['es' => 'Preguntas frecuentes',               'en' => 'Frequently Asked Questions'],
    ];

    foreach ($pairs as $key => $data) {
        $existing_en = get_option($key . '_en');
        if ($existing_en === false || $existing_en === '') {
            update_option($key . '_en', $data['en']);
        }
    }
}

function gf_seed_cpt_translations() {
    global $wpdb;

    $post_translations = gf_get_post_translations();

    foreach ($post_translations as $post_type => $items) {
        $posts = get_posts([
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ]);

        foreach ($posts as $post_id) {
            $title = get_the_title($post_id);

            if (isset($items[$title])) {
                $fields = $items[$title];
            } elseif (isset($items['__default'])) {
                continue;
            } else {
                continue;
            }

            foreach ($fields as $field_name => $en_value) {
                $current = get_field($field_name . '_en', $post_id);
                if (empty($current)) {
                    update_field($field_name . '_en', $en_value, $post_id);
                }
            }
        }
    }

    $body_text_translations = gf_get_body_text_translations();
    foreach ($body_text_translations as $post_type => $items) {
        $posts = get_posts([
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ]);

        foreach ($posts as $post_id) {
            $title = get_the_title($post_id);
            if (isset($items[$title])) {
                $fields = $items[$title];
                foreach ($fields as $field_name => $en_value) {
                    $current = get_field($field_name . '_en', $post_id);
                    if (empty($current)) {
                        update_field($field_name . '_en', $en_value, $post_id);
                    }
                }
            }
        }
    }
}

function gf_seed_faq_items() {
    $faqs = get_posts([
        'post_type'      => 'faq_item',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    ]);

    $map = [
        '¿Cómo activo la garantía de mi producto?' => [
            'post_title'   => 'How do I activate my product warranty?',
            'faq_answer'   => '<p>You can activate your warranty by registering your product at <strong>fadiar.com/warranty</strong> or by contacting us through any of our channels. You will need the purchase receipt and the product serial number.</p>',
        ],
        '¿Qué cubre la garantía?' => [
            'post_title'   => 'What does the warranty cover?',
            'faq_answer'   => '<p>Our warranty covers manufacturing defects during normal use. This includes:</p><ul><li>Electrical or mechanical failures attributable to the factory.</li><li>Parts that deteriorate prematurely under normal conditions.</li></ul><p>It does not cover improper use, accidents or unauthorized modifications.</p>',
        ],
        '¿Dónde reparar mi producto?' => [
            'post_title'   => 'Where can I get my product repaired?',
            'faq_answer'   => '<p>You can bring your product to any of our authorized technical service centers. Once you contact us, we will assign the nearest center and provide you with a service order number for tracking.</p>',
        ],
    ];

    foreach ($faqs as $faq) {
        if (isset($map[$faq->post_title])) {
            $data = $map[$faq->post_title];
            $current_title = get_field('post_title_en', $faq->ID);
            if (empty($current_title)) {
                update_field('post_title_en', $data['post_title'], $faq->ID);
            }
            $current_answer = get_field('faq_answer_en', $faq->ID);
            if (empty($current_answer)) {
                update_field('faq_answer_en', $data['faq_answer'], $faq->ID);
            }
        }
    }
}

function gf_seed_category_translations() {
    $terms = get_terms(['taxonomy' => 'categoria_noticia', 'hide_empty' => false]);
    $map = [
        'Noticias'             => 'News',
        'Eventos'              => 'Events',
        'Eventos y Ferias'     => 'Events and Fairs',
        'Lanzamientos'         => 'Launches',
        'Innovación y Tecnología' => 'Innovation and Technology',
        'Noticias Generales'   => 'General News',
        'RSC y Comunidad'      => 'CSR and Community',
        'Vida Corporativa'     => 'Corporate Life',
    ];
    foreach ($terms as $term) {
        $name_en = get_field('name_en', 'term_' . $term->term_id);
        if (empty($name_en) && isset($map[$term->name])) {
            update_field('name_en', $map[$term->name], 'term_' . $term->term_id);
        }
    }
}

function gf_seed_extra_noticia_translations() {
    $translations = [
        163 => ['intro_noticia' => 'We look forward to seeing you at the 2026 International Fair to discover our latest innovations. Experience how we transform homes and industries. Our booth will feature live demonstrations of the new EON products, as well as samples of Lammina furniture and Vital toilet paper. Do not miss it!'],
        164 => ['intro_noticia' => 'We look forward to seeing you at the 2026 International Fair to discover our latest innovations. Experience how we transform homes and industries.'],
        165 => ['intro_noticia' => 'We look forward to seeing you at the 2026 International Fair to discover our latest innovations. Experience how we transform homes and industries. Our booth will feature live demonstrations of the new EON products, as well as samples of Lammina furniture and Vital toilet paper. Do not miss it!'],
    ];
    foreach ($translations as $post_id => $fields) {
        foreach ($fields as $field_name => $en_value) {
            $current = get_field($field_name . '_en', $post_id);
            if (empty($current)) {
                update_field($field_name . '_en', $en_value, $post_id);
            }
        }
    }
}

function gf_get_post_translations() {
    return [
        'warranty_step' => [
            'Paso 1 - Contactar soporte' => [
                'post_title'         => 'Step 1 - Contact Support',
                'ws_step_description' => 'Contact support by phone, email or WhatsApp.',
            ],
            'Paso 2 - Registrar compra' => [
                'post_title'         => 'Step 2 - Register Purchase',
                'ws_step_description' => 'Register your purchase and serial number.',
            ],
            'Paso 3 - Evaluación técnica' => [
                'post_title'         => 'Step 3 - Technical Assessment',
                'ws_step_description' => 'Technical evaluation and resolution (repair or replacement) within a maximum of 15 business days.',
            ],
        ],
        'warranty_contact' => [
            '__default' => true,
        ],
        'faq_item' => [
            '__default' => true,
        ],
        'contact_subject' => [
            'Consulta general' => ['post_title' => 'General inquiry'],
            'Soporte técnico'  => ['post_title' => 'Technical support'],
            'Garantía'         => ['post_title' => 'Warranty'],
            'Ventas'           => ['post_title' => 'Sales'],
        ],
        'support_home_item' => [
            '__default' => true,
        ],
        'support_header_item' => [
            '__default' => true,
        ],
        'support_carousel' => [
            '__default' => true,
        ],
        'home_product' => [
            '1' => [
                'post_title'            => '1',
                'product_button_text'   => 'View product',
            ],
            '2' => [
                'post_title'            => '2',
                'product_button_text'   => 'View product',
            ],
            '3' => [
                'post_title'            => '3',
                'product_button_text'   => 'View product',
            ],
            '4' => [
                'post_title'            => '4',
                'product_button_text'   => 'View product',
            ],
        ],
        'brand' => [
            'Eon' => [
                'post_title'         => 'Eon',
                'brand_description'  => 'High-performance solutions for the home. Innovation and quality in every product, designed to improve your quality of life.',
                'brand_button_text'  => 'View more',
            ],
            'Vital' => [
                'post_title'         => 'Vital',
                'brand_description'  => 'Softness and high absorption / Sustainable production / Recyclable packaging',
                'brand_button_text'  => 'View more',
            ],
            'Lammina' => [
                'post_title'         => 'Lammina',
                'brand_description'  => 'Modern and functional lighting solutions. Design and technology to illuminate your spaces with efficiency and style.',
                'brand_button_text'  => 'View more',
            ],
        ],
        'carousel_slide' => [
            '1' => [
                'post_title'         => '1',
                'slide_title_text'   => 'WELCOME',
                'slide_subtitle'     => 'We are a business group that improves the home and industry experience with solutions defined by their quality and innovation.',
                'button_1_text'      => 'About Us',
            ],
            '2' => [
                'post_title'         => '2',
                'slide_subtitle'     => 'Functional technology for a more comfortable life',
                'button_1_text'      => 'Products',
                'button_2_text'      => 'Shop',
            ],
            '3' => [
                'post_title'         => '3',
                'slide_subtitle'     => 'Modular furniture with functional design and imported technology',
                'button_1_text'      => 'Products',
                'button_2_text'      => 'Shop',
            ],
            '4' => [
                'post_title'         => '4',
                'slide_subtitle'     => 'Strong with use, soft on your skin',
                'button_1_text'      => 'Products',
                'button_2_text'      => 'Shop',
            ],
            '5' => [
                'post_title'         => '5',
                'slide_title_text'   => 'International Fair 2026',
                'slide_subtitle'     => 'News, launches and our participation in events.',
                'button_1_text'      => 'Read more',
            ],
        ],
    ];
}

function gf_get_body_text_translations() {
    return [
        'pilar_corporativo' => [
            'RESPONSABILIDAD SOCIAL' => [
                'post_title'       => 'SOCIAL RESPONSIBILITY',
                'pillar_subtitle'  => 'Social commitment and community projects',
                'pillar_description' => '<p>At Grupo Fadiar, we believe that business development and community progress must go hand in hand. That is why we promote and participate in projects that generate a positive impact.</p><p><strong>"Alma" by Fadiar Program</strong><br>Our flagship initiative to support community projects. Through product donations, corporate volunteering, and financing of small local initiatives, we seek to be an agent of change wherever we operate.</p><p><strong>Collaboration with PaCuba Festival</strong><br>We support Cuban culture and sports. We recently contributed a solar kit that helped keep the PaCuba event running with stable, sustainable energy.</p><p><strong>Sustainable Communities</strong><br>We work with municipal governments and civil society organizations to develop urban agriculture projects, recycling systems, and energy efficiency programs in vulnerable communities.</p>',
            ],
            'ESTRATEGIA EMPRESARIAL' => [
                'post_title'       => 'BUSINESS STRATEGY',
                'pillar_subtitle'  => '',
                'pillar_description' => '<p>Our strategy rests on three fundamental pillars:</p><p><strong>1. Market expansion</strong> — reaching more customers nationwide.</p><p><strong>2. Technological innovation</strong> — developing more efficient and connected products.</p><p><strong>3. Sustainability</strong> — reducing our environmental footprint and supporting the circular economy. These pillars guide our decision-making and resource allocation.</p>',
            ],
            'I+D+i' => [
                'post_title'       => 'R&D&I',
                'pillar_subtitle'  => '',
                'pillar_description' => '<p>We invest in R&D&I to stay at the forefront. We are currently working on:</p><ul><li><strong>Efficient appliances:</strong> reducing energy consumption by up to 30% with inverter motors.</li><li><strong>Sustainable materials:</strong> using bioplastics and certified woods.</li><li><strong>Smart lighting:</strong> voice and mobile app controlled systems.</li></ul>',
            ],
        ],
        'our_story_item' => [
            'Nuestra Misión' => [
                'post_title' => 'Our Mission',
                'osi_text'   => '<p>To provide innovative solutions that improve the quality of life for Cuban families, with durable, efficient and accessible products.</p>',
            ],
            'Nuestra Visión' => [
                'post_title' => 'Our Vision',
                'osi_text'   => '<p>To be the leading business group in Cuba for home and industry solutions, recognized for our quality, innovation and social commitment.</p>',
            ],
            'Nuestros valores' => [
                'post_title' => 'Our Values',
                'osi_text'   => '<ul><li><strong>Commitment:</strong> to our customers, employees and the country.</li><li><strong>Innovation:</strong> continuous improvement in products and processes.</li><li><strong>Quality:</strong> excellence in every detail.</li><li><strong>Responsibility:</strong> social and environmental.</li><li><strong>Teamwork:</strong> collaboration to grow together.</li></ul>',
            ],
            'Liderazgo' => [
                'post_title' => 'Leadership',
                'osi_intro_text' => '<p>At Grupo Fadiar, governance is exercised with transparency, strategic vision and a firm commitment to ethics. Our management team, led by our General Director, works to align innovation with corporate values, ensuring that every decision contributes to sustainable development and the well-being of our employees and customers.</p>',
                'osi_leader_name' => 'Idián Chávez Fernández',
                'osi_leader_short_description' => 'General Director of Grupo Fadiar / Visionary Cuban partner who drives innovation, efficiency and strategic communication in key sectors.',
                'osi_leader_full_description' => 'He advocates for leadership with purpose and social commitment. At 32 years old, he embodies the spirit of a new generation of business leaders in Cuba: bold, strategic and deeply committed to transformation. He is the founder of Light Vision Creative Agency, where he served as CEO for 6 years.',
            ],
        ],
        'about_us' => [
            'Grupo Fadiar' => [
                'post_title'               => 'Grupo Fadiar',
                'about_page_title'         => 'Grupo Fadiar – Innovation and social commitment',
                'about_metric_1_label'     => 'collaborators',
                'about_metric_2_label'     => 'brands',
                'about_metric_3_label'     => 'products',
                'about_metric_4_label'     => 'units sold',
                'about_metrics_description_1' => 'Grupo Fadiar (Fabricación y Diseño Artesanal) began as a dream, a personal challenge. It was built from the ground up — constructing its facilities, writing down what mattered, bringing together invaluable people. Step by step we made our way. In these three years, Grupo Fadiar has grown with a clear purpose: to offer products that combine quality, accessibility and social responsibility. Our track record is built on innovation, national production and strategic alliances that strengthen the local economy.',
                'about_metrics_description_2' => 'Our philosophy "Diversity of Solutions, One Single Commitment" reflects who we are, our essence. At Fadiar we grow professionally and personally. Every team, every object we manufacture that reaches a home or business is a challenge, a commitment and, above all, an opportunity to be useful. And in that, we give our best. More than products, we share experiences.',
            ],
        ],
        'discover_group' => [
            'DESCUBRE NUESTRO GRUPO  Y SU GENTE' => [
                'post_title'       => 'DISCOVER OUR GROUP AND ITS PEOPLE',
                'discover_subtitle' => 'Grupo Fadiar: three years growing alongside Cuban families.',
                'discover_description_1' => 'From our facilities, we work every day to offer durable, accessible products with true quality. Local development and continuous improvement are our foundation. EÓN, Vital and Lámmina reflect our purpose: to unite innovation, responsible production and a real guarantee that supports every purchase.',
                'discover_description_2' => 'Behind every product there is a team of passionate professionals, constantly evolving, who enjoy exceeding expectations.',
                'discover_button_text' => 'Learn more',
            ],
        ],
        'noticia' => [
            'Grupo Fadiar en la Feria Internacional 2026' => [
                'post_title'  => 'Grupo Fadiar at the International Fair 2026',
                'intro_noticia' => 'We look forward to seeing you at the 2026 International Fair to discover our latest innovations.',
                'fecha_noticia' => 'May 15, 2026',
                'autor' => 'By Emily Castillo. Grupo Fadiar',
            ],
            'Noticia 1' => [
                'post_title'  => 'News 1',
            ],
            'Noticia 2' => [
                'post_title'  => 'News 2',
            ],
        ],
    ];
}
