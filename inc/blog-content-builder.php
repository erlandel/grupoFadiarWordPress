<?php
if (!defined('ABSPATH')) {
    exit;
}

function gf_blog_content_builder_blocks($post_id) {
    $blocks = get_post_meta($post_id, 'gf_blog_content_blocks', true);
    if (is_array($blocks)) {
        return $blocks;
    }

    $blocks_es = get_post_meta($post_id, 'gf_blog_content_blocks_es', true);
    $blocks_en = get_post_meta($post_id, 'gf_blog_content_blocks_en', true);
    $blocks_es = is_array($blocks_es) ? array_values($blocks_es) : array();
    $blocks_en = is_array($blocks_en) ? array_values($blocks_en) : array();
    $blocks = array();

    for ($index = 0; $index < max(count($blocks_es), count($blocks_en)); $index++) {
        $block_es = isset($blocks_es[$index]) ? $blocks_es[$index] : array();
        $block_en = isset($blocks_en[$index]) ? $blocks_en[$index] : array();
        $type = isset($block_es['type']) ? $block_es['type'] : (isset($block_en['type']) ? $block_en['type'] : 'content');

        $blocks[] = array(
            'type' => $type,
            'value_es' => isset($block_es['value']) ? $block_es['value'] : '',
            'value_en' => isset($block_en['value']) ? $block_en['value'] : '',
            'image_id' => isset($block_es['image_id']) ? absint($block_es['image_id']) : (isset($block_en['image_id']) ? absint($block_en['image_id']) : 0),
        );
    }

    return $blocks;
}

function gf_blog_content_builder_render_language_field($type, $language, $name, $author_name, $value, $author, $editor_id) {
    $label = $language === 'es' ? 'Español' : 'English';
    ?>
    <div class="gf-blog-content-block__language-field">
        <label><?php echo esc_html($label); ?></label>
        <?php if ($type === 'content'): ?>
            <textarea id="<?php echo esc_attr($editor_id); ?>" class="widefat gf-blog-content-block__editor" rows="6" name="<?php echo esc_attr($name); ?>"><?php echo esc_textarea($value); ?></textarea>
        <?php elseif ($type === 'quote'): ?>
            <textarea class="widefat" rows="3" name="<?php echo esc_attr($name); ?>"><?php echo esc_textarea($value); ?></textarea>
            <label class="gf-blog-content-block__author-label"><?php echo esc_html($language === 'es' ? 'Autor o fuente' : 'Author or source'); ?></label>
            <input type="text" class="widefat" name="<?php echo esc_attr($author_name); ?>" value="<?php echo esc_attr($author); ?>">
        <?php else: ?>
            <input type="text" class="widefat" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>">
        <?php endif; ?>
    </div>
    <?php
}

function gf_blog_content_builder_render_block($index, $block) {
    $type = isset($block['type']) ? $block['type'] : 'content';
    $value_es = isset($block['value_es']) ? $block['value_es'] : '';
    $value_en = isset($block['value_en']) ? $block['value_en'] : '';
    $author_es = isset($block['author_es']) ? $block['author_es'] : '';
    $author_en = isset($block['author_en']) ? $block['author_en'] : '';
    $caption_es = isset($block['caption_es']) ? $block['caption_es'] : '';
    $caption_en = isset($block['caption_en']) ? $block['caption_en'] : '';
    $image_id = isset($block['image_id']) ? absint($block['image_id']) : 0;
    $name = 'gf_blog_content_blocks[' . $index . ']';
    ?>
    <div class="gf-blog-content-block" data-block-type="<?php echo esc_attr($type); ?>">
        <div class="gf-blog-content-block__header">
            <span class="dashicons dashicons-move gf-blog-content-block__handle" title="Arrastrar para reordenar"></span>
            <strong class="gf-blog-content-block__label"></strong>
            <button type="button" class="button-link-delete gf-blog-content-block__remove">Eliminar</button>
        </div>
        <input type="hidden" class="gf-blog-content-block__type" name="<?php echo esc_attr($name . '[type]'); ?>" value="<?php echo esc_attr($type); ?>">

        <?php if ($type === 'image'): ?>
            <div class="gf-blog-content-block__image-field">
                <input type="hidden" class="gf-blog-content-block__image-id" name="<?php echo esc_attr($name . '[image_id]'); ?>" value="<?php echo esc_attr($image_id); ?>">
                <div class="gf-blog-content-block__image-preview">
                    <?php if ($image_id): ?>
                        <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="button gf-blog-content-block__select-image">Seleccionar imagen</button>
                <button type="button" class="button-link-delete gf-blog-content-block__remove-image"<?php echo $image_id ? '' : ' hidden'; ?>>Eliminar imagen</button>
                <div class="gf-blog-content-block__text-fields">
                    <div class="gf-blog-content-block__language-field">
                        <label>Pie de imagen (Español)</label>
                        <input type="text" class="widefat" name="<?php echo esc_attr($name . '[caption_es]'); ?>" value="<?php echo esc_attr($caption_es); ?>">
                    </div>
                    <div class="gf-blog-content-block__language-field">
                        <label>Caption (English)</label>
                        <input type="text" class="widefat" name="<?php echo esc_attr($name . '[caption_en]'); ?>" value="<?php echo esc_attr($caption_en); ?>">
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="gf-blog-content-block__text-fields">
                <?php gf_blog_content_builder_render_language_field($type, 'es', $name . '[value_es]', $name . '[author_es]', $value_es, $author_es, 'gf-blog-content-es-' . $index); ?>
                <?php gf_blog_content_builder_render_language_field($type, 'en', $name . '[value_en]', $name . '[author_en]', $value_en, $author_en, 'gf-blog-content-en-' . $index); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

function gf_blog_content_builder_metabox($post) {
    wp_nonce_field('gf_blog_content_builder_save', 'gf_blog_content_builder_nonce');
    ?>
    <p>Agrega y ordena los bloques del artículo. Cada bloque vincula su contenido en español e inglés.</p>
    <div class="gf-blog-content-builder">
        <div class="gf-blog-content-builder__blocks">
            <?php foreach (gf_blog_content_builder_blocks($post->ID) as $index => $block): ?>
                <?php gf_blog_content_builder_render_block($index, $block); ?>
            <?php endforeach; ?>
        </div>
        <div class="gf-blog-content-builder__actions">
            <button type="button" class="button button-secondary" data-add-block="title">+ Título</button>
            <button type="button" class="button button-secondary" data-add-block="content">+ Contenido</button>
            <button type="button" class="button button-secondary" data-add-block="image">+ Imagen</button>
            <button type="button" class="button button-secondary" data-add-block="quote">+ Frase</button>
        </div>
    </div>
    <?php
}

function gf_blog_content_builder_add_metabox() {
    add_meta_box('gf-blog-content-builder', 'Contenido del artículo', 'gf_blog_content_builder_metabox', 'blog', 'normal', 'high');
}
add_action('add_meta_boxes_blog', 'gf_blog_content_builder_add_metabox');

function gf_blog_content_builder_save($post_id) {
    if (!isset($_POST['gf_blog_content_builder_nonce']) || !wp_verify_nonce($_POST['gf_blog_content_builder_nonce'], 'gf_blog_content_builder_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id) || get_post_type($post_id) !== 'blog') {
        return;
    }

    $submitted_blocks = isset($_POST['gf_blog_content_blocks']) && is_array($_POST['gf_blog_content_blocks'])
        ? $_POST['gf_blog_content_blocks']
        : array();
    $blocks = array();

    foreach ($submitted_blocks as $block) {
        $type = isset($block['type']) ? sanitize_key($block['type']) : '';
        if (!in_array($type, array('title', 'content', 'image', 'quote'), true)) {
            continue;
        }

        $value_es = isset($block['value_es']) ? wp_unslash($block['value_es']) : '';
        $value_en = isset($block['value_en']) ? wp_unslash($block['value_en']) : '';
        $author_es = isset($block['author_es']) ? sanitize_text_field(wp_unslash($block['author_es'])) : '';
        $author_en = isset($block['author_en']) ? sanitize_text_field(wp_unslash($block['author_en'])) : '';
        $caption_es = isset($block['caption_es']) ? sanitize_text_field(wp_unslash($block['caption_es'])) : '';
        $caption_en = isset($block['caption_en']) ? sanitize_text_field(wp_unslash($block['caption_en'])) : '';
        if ($type === 'content') {
            $value_es = wp_kses_post($value_es);
            $value_en = wp_kses_post($value_en);
        } else {
            $value_es = sanitize_textarea_field($value_es);
            $value_en = sanitize_textarea_field($value_en);
        }

        $image_id = isset($block['image_id']) ? absint($block['image_id']) : 0;
        if (($type === 'image' && !$image_id) || ($type !== 'image' && $value_es === '' && $value_en === '')) {
            continue;
        }

        $blocks[] = array(
            'type' => $type,
            'value_es' => $value_es,
            'value_en' => $value_en,
            'author_es' => $author_es,
            'author_en' => $author_en,
            'caption_es' => $caption_es,
            'caption_en' => $caption_en,
            'image_id' => $image_id,
        );
    }

    update_post_meta($post_id, 'gf_blog_content_blocks', $blocks);
}
add_action('save_post_blog', 'gf_blog_content_builder_save');

function gf_blog_content_builder_admin_assets($hook) {
    $screen = get_current_screen();
    if (!in_array($hook, array('post.php', 'post-new.php'), true) || !$screen || $screen->post_type !== 'blog') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_editor();
    wp_enqueue_script('gf-blog-content-builder', get_template_directory_uri() . '/assets/js/blog-content-builder.js', array('jquery', 'jquery-ui-sortable', 'wp-editor'), '1.0.0', true);
    wp_enqueue_style('gf-blog-content-builder', get_template_directory_uri() . '/assets/css/blog-content-builder.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'gf_blog_content_builder_admin_assets');
