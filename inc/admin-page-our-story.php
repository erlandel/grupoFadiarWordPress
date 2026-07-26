<?php
/**
 * Admin page "Texto introductorio" para Nuestra Historia.
 * Se monta como submenú de "Nuestra Historia" (top-level).
 *
 * Guarda tres opciones:
 *   - our_story_title
 *   - our_story_paragraph_1
 *   - our_story_paragraph_2
 */

if (!defined('ABSPATH')) {
    exit;
}

function grupofadiar_register_our_story_settings_hidden_page() {
    add_submenu_page(
        null,
        'Texto introductorio',
        'Texto introductorio',
        'manage_options',
        'our_story_settings',
        'grupofadiar_render_our_story_settings_page'
    );
}
add_action('admin_menu', 'grupofadiar_register_our_story_settings_hidden_page', 30);

function grupofadiar_render_our_story_settings_page() {
    if (
        isset($_POST['grupofadiar_our_story_settings_nonce']) &&
        wp_verify_nonce($_POST['grupofadiar_our_story_settings_nonce'], 'grupofadiar_save_our_story_settings')
    ) {
        update_option('our_story_title', sanitize_text_field($_POST['our_story_title'] ?? 'Nuestra historia'));
        update_option('our_story_paragraph_1', wp_kses_post($_POST['our_story_paragraph_1'] ?? ''));
        update_option('our_story_paragraph_2', wp_kses_post($_POST['our_story_paragraph_2'] ?? ''));
        update_option('our_story_title_en', sanitize_text_field($_POST['our_story_title_en'] ?? 'Our Story'));
        update_option('our_story_paragraph_1_en', wp_kses_post($_POST['our_story_paragraph_1_en'] ?? ''));
        update_option('our_story_paragraph_2_en', wp_kses_post($_POST['our_story_paragraph_2_en'] ?? ''));

        echo '<div class="updated"><p>Configuración guardada exitosamente.</p></div>';
    }

    $title     = get_option('our_story_title', 'Nuestra historia');
    $paragraph_1 = get_option(
        'our_story_paragraph_1',
        'Grupo Fadiar nació en 2023 con la visión de transformar la industria nacional. Partiendo  de un pequeño taller, hemos crecido hasta convertirnos en un grupo empresarial que  integra tres marcas referentes.'
    );
    $paragraph_2 = get_option(
        'our_story_paragraph_2',
        'Nuestros hitos incluyen la apertura de nuestras  instalaciones en Ciudad Libertad, el lanzamiento de nuestras primeras líneas de  productos y las alianzas con distribuidores en todo el país e internacionales. Hoy,  seguimos construyendo el futuro con pasión y responsabilidad.'
    );
    $title_en     = get_option('our_story_title_en', 'Our Story');
    $paragraph_1_en = get_option(
        'our_story_paragraph_1_en',
        'Grupo Fadiar was born in 2023 with the vision of transforming the national industry. Starting from a small workshop, we have grown into a business group that integrates three leading brands.'
    );
    $paragraph_2_en = get_option(
        'our_story_paragraph_2_en',
        'Our milestones include the opening of our facilities in Ciudad Libertad, the launch of our first product lines, and alliances with distributors nationwide and internationally. Today, we continue building the future with passion and responsibility.'
    );
    ?>
    <div class="wrap">
        <h1>Texto introductorio — Nuestra Historia</h1>
        <p>Edita los textos que aparecen en la columna izquierda del bloque "Nuestra Historia" dentro de la página Sobre Nosotros.</p>

        <form method="post" action="">
            <?php wp_nonce_field('grupofadiar_save_our_story_settings', 'grupofadiar_our_story_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="our_story_title">Título</label></th>
                    <td>
                        <input type="text" name="our_story_title" id="our_story_title"
                            value="<?php echo esc_attr($title); ?>" class="regular-text" />
                        <p class="description">Texto del encabezado principal del bloque.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="our_story_title_en">Título (EN)</label></th>
                    <td>
                        <input type="text" name="our_story_title_en" id="our_story_title_en"
                            value="<?php echo esc_attr($title_en); ?>" class="regular-text" />
                        <p class="description">English version of the title.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="our_story_paragraph_1">Párrafo 1</label></th>
                    <td>
                        <textarea name="our_story_paragraph_1" id="our_story_paragraph_1" rows="5" class="large-text"><?php echo esc_textarea($paragraph_1); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="our_story_paragraph_1_en">Párrafo 1 (EN)</label></th>
                    <td>
                        <textarea name="our_story_paragraph_1_en" id="our_story_paragraph_1_en" rows="5" class="large-text"><?php echo esc_textarea($paragraph_1_en); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="our_story_paragraph_2">Párrafo 2</label></th>
                    <td>
                        <textarea name="our_story_paragraph_2" id="our_story_paragraph_2" rows="5" class="large-text"><?php echo esc_textarea($paragraph_2); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="our_story_paragraph_2_en">Párrafo 2 (EN)</label></th>
                    <td>
                        <textarea name="our_story_paragraph_2_en" id="our_story_paragraph_2_en" rows="5" class="large-text"><?php echo esc_textarea($paragraph_2_en); ?></textarea>
                    </td>
                </tr>
            </table>

            <?php submit_button('Guardar'); ?>
        </form>
    </div>
    <?php
}

function grupofadiar_initialize_our_story_options() {
    if (get_option('our_story_title') === false) {
        update_option('our_story_title', 'Nuestra historia');
    }
    if (get_option('our_story_paragraph_1') === false) {
        update_option('our_story_paragraph_1', 'Grupo Fadiar nació en 2023 con la visión de transformar la industria nacional. Partiendo  de un pequeño taller, hemos crecido hasta convertirnos en un grupo empresarial que  integra tres marcas referentes.');
    }
    if (get_option('our_story_paragraph_2') === false) {
        update_option('our_story_paragraph_2', 'Nuestros hitos incluyen la apertura de nuestras  instalaciones en Ciudad Libertad, el lanzamiento de nuestras primeras líneas de  productos y las alianzas con distribuidores en todo el país e internacionales. Hoy,  seguimos construyendo el futuro con pasión y responsabilidad.');
    }
    if (get_option('our_story_title_en') === false) {
        update_option('our_story_title_en', 'Our Story');
    }
    if (get_option('our_story_paragraph_1_en') === false) {
        update_option('our_story_paragraph_1_en', 'Grupo Fadiar was born in 2023 with the vision of transforming the national industry. Starting from a small workshop, we have grown into a business group that integrates three leading brands.');
    }
    if (get_option('our_story_paragraph_2_en') === false) {
        update_option('our_story_paragraph_2_en', 'Our milestones include the opening of our facilities in Ciudad Libertad, the launch of our first product lines, and alliances with distributors nationwide and internationally. Today, we continue building the future with passion and responsibility.');
    }
}
add_action('after_switch_theme', 'grupofadiar_initialize_our_story_options');
add_action('admin_init', 'grupofadiar_initialize_our_story_options');
