<?php

// Incluir registros de Custom Post Types y Campos ACF
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-fields-carousel.php';
require_once get_template_directory() . '/inc/custom-fields-discover-group.php';
require_once get_template_directory() . '/inc/custom-fields-brands.php';
require_once get_template_directory() . '/inc/custom-fields-products.php';
require_once get_template_directory() . '/inc/admin-page-products.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/admin-page-brands.php';
require_once get_template_directory() . '/inc/custom-fields-support-home.php';
require_once get_template_directory() . '/inc/admin-page-support-home.php';
require_once get_template_directory() . '/inc/custom-fields-noticias.php';
require_once get_template_directory() . '/inc/custom-fields-about-us.php';
require_once get_template_directory() . '/inc/admin-page-support-header.php';
require_once get_template_directory() . '/inc/seed-about-us.php';
require_once get_template_directory() . '/inc/custom-fields-our-story.php';
require_once get_template_directory() . '/inc/admin-page-our-story.php';
require_once get_template_directory() . '/inc/admin-page-home.php';
require_once get_template_directory() . '/inc/custom-fields-corporate-pillars.php';
require_once get_template_directory() . '/inc/seed-corporate-pillars.php';
require_once get_template_directory() . '/inc/admin-page-noticias.php';
require_once get_template_directory() . '/inc/seed-noticias-categories.php';
require_once get_template_directory() . '/inc/custom-fields-warranty-contacts.php';
require_once get_template_directory() . '/inc/custom-fields-warranty-steps.php';
require_once get_template_directory() . '/inc/custom-fields-faq.php';
require_once get_template_directory() . '/inc/seed-warranty-contacts.php';
require_once get_template_directory() . '/inc/seed-warranty-steps.php';

function grupofadiar_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menu('primary', __('Menú principal', 'grupofadiar'));
}
add_action('after_setup_theme', 'grupofadiar_setup');

function grupofadiar_assets() {
    wp_enqueue_style('grupofadiar-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Open+Sans:wght@400;700&family=Dancing+Script:wght@400;700&family=Satisfy&display=swap', array(), null);

    wp_enqueue_style('grupofadiar-styles', get_stylesheet_uri(), array('grupofadiar-google-fonts'), '1.0.0');

    wp_enqueue_script('grupofadiar-carousel', get_template_directory_uri() . '/assets/js/carousel.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-header-scroll', get_template_directory_uri() . '/assets/js/header-scroll.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-menu-mobile', get_template_directory_uri() . '/assets/js/menu-mobile.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-accordion', get_template_directory_uri() . '/assets/js/accordion.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-products', get_template_directory_uri() . '/assets/js/products.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-faq', get_template_directory_uri() . '/assets/js/faq.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-menu', get_template_directory_uri() . '/assets/js/menu.js', array(), '1.0.3', true);
    wp_enqueue_script('grupofadiar-lang-toggle', get_template_directory_uri() . '/assets/js/lang-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('grupofadiar-share', get_template_directory_uri() . '/assets/js/share.js', array(), '1.0.0', true);
wp_enqueue_script('grupofadiar-support-carousel', get_template_directory_uri() . '/assets/js/support-carousel.js', array(), '2.0.0', true);
}
add_action('wp_enqueue_scripts', 'grupofadiar_assets');

function get_icon($name, $class = '') {
    $path = get_template_directory() . '/icons/' . $name . '.php';
    if (file_exists($path)) {
        $svg = file_get_contents($path);
        if (!empty($class)) {
            $svg = str_replace('<svg', '<svg class="' . esc_attr($class) . '"', $svg);
        }
        return $svg;
    }
    return '';
}
