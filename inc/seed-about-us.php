<?php
/**
 * Seeder del CPT 'about_us' y de los items iniciales del acordeón "Nuestra Historia".
 * Garantiza que SIEMPRE exista exactamente:
 *   - Un post semilla en CPT about_us (idempotente).
 *   - 4 ítems `our_story_item` (sólo si la tabla está vacía).
 *   - Una copia del asset `lider1.png` registrada en la librería de medios.
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_seed_about_us_post() {
    if (!post_type_exists('about_us')) {
        return;
    }

    $existing = get_posts(array(
        'post_type'      => 'about_us',
        'posts_per_page' => 1,
        'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ));

    if (!empty($existing)) {
        return;
    }

    wp_insert_post(array(
        'post_type'   => 'about_us',
        'post_status' => 'publish',
        'post_title'  => 'Grupo Fadiar',
        'post_name'   => 'grupo-fadiar',
    ));
}

function grupofadiar_block_about_us_creation_and_deletion($check, $capability, $user_id, $args) {
    if (!$args || !is_array($args) || !isset($args[0])) {
        return $check;
    }

    $post_id = (int) $args[0];
    $post    = get_post($post_id);
    if (!$post || $post->post_type !== 'about_us') {
        return $check;
    }

    if (in_array($capability, array('delete_post', 'delete_published_posts', 'delete_private_posts'), true)) {
        return false;
    }

    if (in_array($capability, array('create_posts', 'create_about_us'), true)) {
        return false;
    }

    return $check;
}

function grupofadiar_block_about_us_admin_actions() {
    global $post;
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if ($screen && isset($screen->post_type) && $screen->post_type === 'about_us' && $post && $post->post_type === 'about_us') {
        remove_post_type_support('about_us', 'title');
        remove_post_type_support('about_us', 'editor');
        remove_post_type_support('about_us', 'thumbnail');
        remove_post_type_support('about_us', 'excerpt');
        remove_post_type_support('about_us', 'comments');
        remove_post_type_support('about_us', 'author');

        add_filter('page_row_actions', 'grupofadiar_remove_about_us_row_actions', 10, 2);
        add_filter('post_row_actions', 'grupofadiar_remove_about_us_row_actions', 10, 2);
    }
}

function grupofadiar_remove_about_us_row_actions($actions, $post) {
    if ($post->post_type !== 'about_us') {
        return $actions;
    }
    unset($actions['trash']);
    unset($actions['delete']);
    unset($actions['inline']);
    unset($actions['edit']);
    unset($actions['view']);
    return $actions;
}

function grupofadiar_block_about_us_manual_creation() {
    if (!is_admin()) {
        return;
    }

    global $pagenow;

    if ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'about_us') {
        wp_safe_redirect(admin_url('admin.php?page=grupofadiar_about'));
        exit;
    }

    if ($pagenow === 'post.php' && isset($_GET['post']) && (int) $_GET['post'] > 0) {
        $maybe_post = get_post((int) $_GET['post']);
        $action     = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
        if ($maybe_post && $maybe_post->post_type === 'about_us' && in_array($action, array('trash', 'delete'), true)) {
            wp_die(
                __('El post de Grupo Fadiar no puede ser eliminado porque se necesita uno permanente.', 'grupofadiar'),
                __('Acción bloqueada', 'grupofadiar'),
                array('back_link' => true)
            );
        }
    }
}

function grupofadiar_ensure_lider1_attachment() {
    if (!function_exists('wp_insert_attachment') || !function_exists('wp_generate_attachment_metadata')) {
        return 0;
    }

    $existing = get_posts(array(
        'post_type'   => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'fields'      => 'ids',
        'meta_query'  => array(
            array(
                'key'     => '_wp_attached_file',
                'value'   => 'lider1',
                'compare' => 'LIKE',
            ),
        ),
    ));

    if (!empty($existing)) {
        return (int) $existing[0];
    }

    $source = get_template_directory() . '/assets/images/about/lider1.png';
    if (!file_exists($source)) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $upload = wp_upload_dir();
    if (!empty($upload['error'])) {
        return 0;
    }

    $filename = 'lider1-copy.png';
    $target   = trailingslashit($upload['path']) . $filename;

    if (!@copy($source, $target)) {
        return 0;
    }

    $wp_filetype = wp_check_filetype(basename($target), null);
    $attachment  = array(
        'guid'           => trailingslashit($upload['url']) . $filename,
        'post_mime_type' => $wp_filetype['type'] !== false ? $wp_filetype['type'] : 'image/png',
        'post_title'     => 'Líder Grupo Fadiar',
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $attach_id = wp_insert_attachment($attachment, $target);
    if (is_wp_error($attach_id) || !$attach_id) {
        return 0;
    }

    $meta = wp_generate_attachment_metadata($attach_id, $target);
    if (!empty($meta)) {
        wp_update_attachment_metadata($attach_id, $meta);
    }

    return (int) $attach_id;
}

function grupofadiar_seed_our_story_items() {
    if (!post_type_exists('our_story_item')) {
        return;
    }

    $existing = get_posts(array(
        'post_type'      => 'our_story_item',
        'posts_per_page' => 1,
        'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
        'fields'         => 'ids',
    ));

    if (!empty($existing)) {
        return;
    }

    $lider_image_id = grupofadiar_ensure_lider1_attachment();

    $items = array(
        array(
            'title'    => 'Nuestra Misión',
            'menu_order' => 1,
            'data'     => array(
                'osi_text' => 'Proporcionar soluciones innovadoras que mejoren la calidad de vida de las familias cubanas, con productos duraderos, eficientes y accesibles.',
            ),
        ),
        array(
            'title'    => 'Nuestra Visión',
            'menu_order' => 2,
            'data'     => array(
                'osi_text' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso social.',
            ),
        ),
        array(
            'title'    => 'Nuestros valores',
            'menu_order' => 3,
            'data'     => array(
                'osi_text' => '',
                'osi_bullets' => array(
                    array('bullet' => 'Compromiso: con nuestros clientes, trabajadores y el país.'),
                    array('bullet' => 'Innovación: mejora continua en productos y procesos.'),
                    array('bullet' => 'Calidad: excelencia en cada detalle.'),
                    array('bullet' => 'Responsabilidad: social y medioambiental.'),
                    array('bullet' => 'Trabajo en equipo: colaboración para crecer juntos.'),
                ),
            ),
        ),
        array(
            'title'    => 'Liderazgo',
            'menu_order' => 4,
            'data'     => array(
                'osi_text' => 'En Grupo Fadiar, la gobernanza se ejerce con transparencia, visión  estratégica y un firme compromiso con la ética. Nuestro equipo directivo, liderado por el  Director General, trabaja para alinear la innovación con los valores corporativos,  asegurando que cada decisión contribuya al desarrollo sostenible y al bienestar de  nuestros trabajadores y clientes.',
                'osi_leaders' => array(
                    array(
                        'name'              => 'Idián Chávez Fernández',
                        'image'             => $lider_image_id,
                        'short_description' => 'Director General de Grupo Fadiar / Socio  Visionario cubano que impulsa la innovación, la eficiencia y la comunicación estratégica  en sectores clave. ',
                        'full_description'  => 'Defiende el liderazgo con propósito y compromiso social. A sus 32 años, encarna el espíritu de una nueva generación de líderes empresariales en Cuba: audaces, estratégicos y profundamente comprometidos con la transformación. Es fundador de Light Vision Agencia Creativa, donde fue CEO durante 6 años.',
                    ),
                ),
            ),
        ),
    );

    foreach ($items as $item) {
        $post_id = wp_insert_post(array(
            'post_type'   => 'our_story_item',
            'post_status' => 'publish',
            'post_title'  => $item['title'],
            'menu_order'  => (int) $item['menu_order'],
        ));

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        foreach ($item['data'] as $key => $value) {
            if (function_exists('update_field')) {
                update_field($key, $value, $post_id);
            }
        }
    }
}

function grupofadiar_seed_all() {
    grupofadiar_seed_about_us_post();
    grupofadiar_seed_discover_group_post();
    grupofadiar_seed_our_story_items();
}

add_action('after_switch_theme', 'grupofadiar_seed_all');
add_action('admin_init', 'grupofadiar_seed_all');

add_filter('user_has_cap', 'grupofadiar_block_about_us_creation_and_deletion', 10, 4);
add_filter('map_meta_cap', 'grupofadiar_block_about_us_creation_and_deletion', 10, 4);

add_action('admin_head', 'grupofadiar_block_about_us_admin_actions');
add_action('admin_menu', 'grupofadiar_block_about_us_manual_creation', 1);

function grupofadiar_seed_discover_group_post() {
    if (!post_type_exists('discover_group')) {
        return;
    }

    $existing = get_posts(array(
        'post_type'      => 'discover_group',
        'posts_per_page' => 1,
        'post_status'    => array('publish', 'draft', 'pending', 'future', 'private'),
        'fields'         => 'ids',
    ));

    if (!empty($existing)) {
        return;
    }

    wp_insert_post(array(
        'post_type'   => 'discover_group',
        'post_status' => 'publish',
        'post_title'  => 'Descubre nuestro grupo y su gente',
    ));
}

function grupofadiar_block_discover_group_manual_creation() {
    if (!is_admin()) {
        return;
    }

    global $pagenow;

    if ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'discover_group') {
        wp_safe_redirect(admin_url('admin.php?page=grupofadiar_home'));
        exit;
    }
}
add_action('admin_menu', 'grupofadiar_block_discover_group_manual_creation', 1);

function grupofadiar_block_discover_group_admin_actions() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ($screen && isset($screen->post_type) && $screen->post_type === 'discover_group') {
        add_filter('page_row_actions', 'grupofadiar_remove_discover_group_row_actions', 10, 2);
        add_filter('post_row_actions', 'grupofadiar_remove_discover_group_row_actions', 10, 2);
    }
}
add_action('admin_head', 'grupofadiar_block_discover_group_admin_actions');

function grupofadiar_remove_discover_group_row_actions($actions, $post) {
    if ($post->post_type !== 'discover_group') {
        return $actions;
    }
    unset($actions['trash']);
    unset($actions['delete']);
    unset($actions['inline']);
    return $actions;
}

function grupofadiar_block_discover_group_deletion($check, $capability, $user_id, $args) {
    if (!$args || !is_array($args) || !isset($args[0])) {
        return $check;
    }

    $post_id = (int) $args[0];
    $post    = get_post($post_id);
    if (!$post || $post->post_type !== 'discover_group') {
        return $check;
    }

    if (in_array($capability, array('delete_post', 'delete_published_posts', 'delete_private_posts'), true)) {
        return false;
    }

    if (in_array($capability, array('create_posts', 'create_discover_group'), true)) {
        return false;
    }

    return $check;
}
add_filter('user_has_cap', 'grupofadiar_block_discover_group_deletion', 10, 4);
add_filter('map_meta_cap', 'grupofadiar_block_discover_group_deletion', 10, 4);
