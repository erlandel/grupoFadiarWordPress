<?php

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_noticias_landing() {
    add_menu_page(
        'Noticias',
        'Noticias',
        'manage_options',
        'grupofadiar_noticias',
        'grupofadiar_render_noticias_landing',
        'dashicons-admin-post',
        7
    );

    // Submenú duplicado "Vista general" -> lo removemos para dejar solo "Noticias".
    add_submenu_page(
        'grupofadiar_noticias',
        'Vista general',
        'Vista general',
        'manage_options',
        'grupofadiar_noticias',
        'grupofadiar_render_noticias_landing'
    );

    // Páginas registradas con parent ficticio (options.php) => no aparecen en la
    // barra lateral pero son accesibles vía admin.php?page=... desde las tarjetas.
    add_submenu_page(
        'options.php',
        'Títulos de la página de Noticias',
        'Títulos de la página',
        'manage_options',
        'grupofadiar_noticias_titles',
        'grupofadiar_render_noticias_titles_page'
    );

    add_submenu_page(
        'options.php',
        'Categorías de Noticias',
        'Categorías de Noticias',
        'manage_options',
        'grupofadiar_noticias_categories',
        'grupofadiar_render_noticias_categories_page'
    );
}
add_action('admin_menu', 'grupofadiar_register_noticias_landing', 9);

// Limpieza del submenú duplicado "Vista general" (queda solo la entrada "Noticias").
function grupofadiar_remove_noticias_duplicate_submenu() {
    remove_submenu_page('grupofadiar_noticias', 'grupofadiar_noticias');
}
add_action('admin_menu', 'grupofadiar_remove_noticias_duplicate_submenu', 99);

function grupofadiar_render_noticias_landing() {
    ?>
    <div class="wrap">
        <h1>Noticias — Vista general</h1>
        <p>Selecciona una sección para administrar su contenido.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-top:30px;">

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Títulos de la página</h2>
                <p style="flex:1;color:#50575e;">Edita el título y subtítulo del encabezado de la página pública de Noticias.</p>
                <a href="<?php echo esc_url(admin_url('admin.php?page=grupofadiar_noticias_titles')); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;">Administrar títulos</a>
            </div>

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Noticias</h2>
                <p style="flex:1;color:#50575e;">Añade, edita o elimina noticias del sitio.</p>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=noticia')); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;">Administrar noticias</a>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=noticia')); ?>" class="button" style="align-self:flex-start;margin-top:6px;">Añadir nueva noticia</a>
            </div>

            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;display:flex;flex-direction:column;">
                <h2 style="margin-top:0;font-size:18px;">Categorías de Noticias</h2>
                <p style="flex:1;color:#50575e;">Administra las categorías para clasificar las noticias.</p>
                <a href="<?php echo esc_url(admin_url('admin.php?page=grupofadiar_noticias_categories')); ?>" class="button button-primary" style="align-self:flex-start;margin-top:6px;">Administrar categorías</a>
            </div>

        </div>
    </div>
    <?php
}

function grupofadiar_render_noticias_titles_page() {
    if (isset($_POST['grupofadiar_noticias_titles_nonce']) &&
        wp_verify_nonce($_POST['grupofadiar_noticias_titles_nonce'], 'grupofadiar_save_noticias_titles')) {

        update_option('noticias_page_title', sanitize_text_field($_POST['noticias_page_title'] ?? 'Noticias'));
        update_option('noticias_page_subtitle', sanitize_text_field($_POST['noticias_page_subtitle'] ?? ''));
        update_option('noticias_page_title_en', sanitize_text_field($_POST['noticias_page_title_en'] ?? 'News'));
        update_option('noticias_page_subtitle_en', sanitize_text_field($_POST['noticias_page_subtitle_en'] ?? 'Stay up to date with the latest news, launches and events from Grupo Fadiar.'));

        echo '<div class="updated"><p>Títulos guardados exitosamente.</p></div>';
    }

    $title = get_option('noticias_page_title', 'Noticias');
    $subtitle = get_option('noticias_page_subtitle', '');
    $title_en = get_option('noticias_page_title_en', 'News');
    $subtitle_en = get_option('noticias_page_subtitle_en', 'Stay up to date with the latest news, launches and events from Grupo Fadiar.');
    ?>
    <div class="wrap">
        <h1>Títulos de la página de Noticias</h1>
        <p>Edita el título y subtítulo que aparecen en el encabezado de la página pública de Noticias.</p>

        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_noticias_titles', 'grupofadiar_noticias_titles_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="noticias_page_title">Título</label></th>
                    <td>
                        <input type="text" name="noticias_page_title" id="noticias_page_title"
                            value="<?php echo esc_attr($title); ?>"
                            class="regular-text" />
                        <p class="description">Título principal de la página (etiqueta H1).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="noticias_page_title_en">Título (EN)</label></th>
                    <td>
                        <input type="text" name="noticias_page_title_en" id="noticias_page_title_en"
                            value="<?php echo esc_attr($title_en); ?>"
                            class="regular-text" />
                        <p class="description">English version of the title.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="noticias_page_subtitle">Subtítulo</label></th>
                    <td>
                        <input type="text" name="noticias_page_subtitle" id="noticias_page_subtitle"
                            value="<?php echo esc_attr($subtitle); ?>"
                            class="regular-text" style="width:100%;" />
                        <p class="description">Subtítulo que aparece debajo del título.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="noticias_page_subtitle_en">Subtítulo (EN)</label></th>
                    <td>
                        <input type="text" name="noticias_page_subtitle_en" id="noticias_page_subtitle_en"
                            value="<?php echo esc_attr($subtitle_en); ?>"
                            class="regular-text" style="width:100%;" />
                        <p class="description">English version of the subtitle.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Guardar títulos'); ?>
        </form>
    </div>
    <?php
}

function grupofadiar_render_noticias_categories_page() {
    $category_created = false;
    if (isset($_POST['grupofadiar_new_category_nonce']) &&
        wp_verify_nonce($_POST['grupofadiar_new_category_nonce'], 'grupofadiar_add_category')) {
        $cat_name = sanitize_text_field($_POST['new_category_name'] ?? '');
        $cat_name_en = sanitize_text_field($_POST['new_category_name_en'] ?? '');
        if (!empty($cat_name)) {
            $result = wp_insert_term($cat_name, 'categoria_noticia');
            if (!is_wp_error($result)) {
                if (!empty($cat_name_en)) {
                    update_field('name_en', $cat_name_en, 'term_' . $result['term_id']);
                }
                $category_created = true;
            } else {
                echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
            }
        }
    }
    $all_cats = get_terms(array('taxonomy' => 'categoria_noticia', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1>Categorías de Noticias</h1>

        <h2 class="title">Agregar nueva categoría</h2>
        <form method="post" action="" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <?php wp_nonce_field('grupofadiar_add_category', 'grupofadiar_new_category_nonce'); ?>
            <input type="text" name="new_category_name" placeholder="Nombre (ES)" required
                style="padding:4px 8px;border:1px solid #8c8f94;border-radius:4px;width:200px;" />
            <input type="text" name="new_category_name_en" placeholder="Name (EN)"
                style="padding:4px 8px;border:1px solid #8c8f94;border-radius:4px;width:200px;" />
            <?php submit_button('Agregar nueva categoría', 'primary', 'submit', false, array('style' => 'padding:4px 12px;margin:0;')); ?>
        </form>
        <?php if ($category_created): ?>
            <div class="notice notice-success inline" style="margin:12px 0 0;"><p>Categoría agregada exitosamente.</p></div>
        <?php endif; ?>

        <h2 class="title" style="margin-top:30px;">Categorías existentes</h2>
        <?php if (!empty($all_cats) && !is_wp_error($all_cats)): ?>
            <table class="wp-list-table widefat fixed striped" style="margin-top:12px;">
                <thead>
                    <tr>
                        <th>Nombre (ES)</th>
                        <th>Nombre (EN)</th>
                        <th>Noticias</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_cats as $cat): ?>
                        <tr>
                            <td><strong><?php echo esc_html($cat->name); ?></strong></td>
                            <td><?php echo esc_html(get_field('name_en', 'term_' . $cat->term_id) ?: '—'); ?></td>
                            <td><?php echo intval($cat->count); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('term.php?taxonomy=categoria_noticia&post_type=noticia&tag_ID=' . $cat->term_id)); ?>" class="button button-small">Editar</a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('edit-tags.php?action=delete&taxonomy=categoria_noticia&post_type=noticia&tag_ID=' . $cat->term_id), 'delete-tag_' . $cat->term_id)); ?>" class="button button-small" onclick="return confirm('¿Eliminar la categoría «<?php echo esc_js($cat->name); ?>»?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay categorías creadas aún.</p>
        <?php endif; ?>
    </div>
    <?php
}

function grupofadiar_initialize_noticias_options() {
    if (get_option('noticias_page_title') === false) {
        update_option('noticias_page_title', 'Noticias');
    }
    if (get_option('noticias_page_subtitle') === false) {
        update_option('noticias_page_subtitle', 'Mantente al día con las últimas novedades, lanzamientos y eventos de Grupo Fadiar.');
    }
    if (get_option('noticias_page_title_en') === false) {
        update_option('noticias_page_title_en', 'News');
    }
    if (get_option('noticias_page_subtitle_en') === false) {
        update_option('noticias_page_subtitle_en', 'Stay up to date with the latest news, launches and events from Grupo Fadiar.');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_noticias_options');
add_action('admin_init', 'grupofadiar_initialize_noticias_options');
