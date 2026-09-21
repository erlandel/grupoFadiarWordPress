<?php
get_header();

// Datos principales y campos ACF del artículo.
$post_id = get_the_ID();
$title = gf_get_post_title($post_id);
$intro = gf_get_field('intro_blog', $post_id);
$date = gf_get_field('fecha_blog', $post_id) ?: get_the_date('d/m/Y');
$author = get_field('autor_blog', $post_id);
$author_image = get_field('imagen_autor_blog', $post_id);
$main_image = get_field('imagen_principal_blog', $post_id);
$content_blocks = gf_blog_content_builder_blocks($post_id);

// Categoría asociada al artículo.
$category = null;
$category_terms = wp_get_post_terms($post_id, 'categoria_noticia');
if (!empty($category_terms) && !is_wp_error($category_terms)) {
  $category = $category_terms[0];
} else {
  $category_field = get_field('categoria_blog', $post_id);
  if (is_array($category_field) && !empty($category_field)) {
    $category = $category_field[0];
  } elseif (is_object($category_field)) {
    $category = $category_field;
  } elseif (is_numeric($category_field)) {
    $category = get_term((int) $category_field, 'categoria_noticia');
  }
}
$category_label = $category ? gf_get_term_name($category) : '';

// Artículos recomendados al final de la página.
$related = new WP_Query(array('post_type' => 'blog', 'posts_per_page' => 3, 'post__not_in' => array($post_id)));
?>
<main>
  <!-- Ruta de navegación. -->
  <div class="reveal-section mx-6 md:mx-15 xl:mx-30 mt-7">
    <div class="flex text-lg">
      <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
      <?php echo get_icon('chevron-right', 'h-6 w-6 mx-1'); ?>
      <p><a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>"><?php echo esc_html(gf_e('blog.breadcrumb')); ?></a></p>
    </div>
  </div>

  <article class="reveal-section w-full mt-10 mb-20">
    <header class="reveal-item">

      <!-- Título, introducción y separador. -->
      <h1 class="mx-6 md:mx-15 xl:mx-70 text-3xl md:text-4xl font-bold text-dark leading-tight mt-5"><?php echo esc_html($title); ?></h1>
      <?php if ($intro): ?><p class="mx-6 md:mx-15 xl:mx-70 text-xl text-dark leading-relaxed mt-5"><?php echo esc_html($intro); ?></p><?php endif; ?>

      <div class="mx-6 md:mx-15 xl:mx-70 h-1 bg-[#F4F4F4] mt-5"></div>

      <!-- Autor y fecha de publicación. -->
      <div class="mx-6 md:mx-15 xl:mx-70 flex items-center gap-3 mt-5 text-dark">
        <?php if ($author_image && !empty($author_image['url'])): ?>
          <img src="<?php echo esc_url($author_image['url']); ?>" alt="<?php echo esc_attr($author ?: ''); ?>" class="h-10 w-10 rounded-full object-cover shrink-0" />
        <?php endif; ?>
        <div class="flex flex-col">
          <?php if ($author): ?><span class="font-bold"><?php echo esc_html(gf_e('blog.author') . ' ' . $author); ?></span><?php endif; ?>
          <time class="text-sm text-gray-500"><?php echo esc_html($date); ?></time>
        </div>
      </div>

      <!-- Imagen principal con categoría superpuesta. -->
<?php if ($main_image && !empty($main_image['url'])): ?>
  <div class="reveal-item relative mt-10 h-96 w-full md:h-120">
      <img
        src="<?php echo esc_url($main_image['url']); ?>"
        alt="<?php echo esc_attr($main_image['alt'] ?: $title); ?>"
        class="absolute inset-0 h-full w-full object-cover"
      />
      <?php if ($category_label): ?>
        <span class="absolute bottom-6 left-6 z-10 rounded-full bg-[#F4F4F499] px-4 py-3 text-[#8C8C8C] md:left-15 xl:left-30">
          <?php echo esc_html($category_label); ?>
        </span>
      <?php endif; ?>
  </div>
<?php endif; ?>

<!-- Bloques ordenables del cuerpo del artículo. -->
<?php if ($content_blocks): ?>
  <div class="reveal-item mx-6 mt-10 text-lg leading-relaxed text-dark md:mx-15 md:text-xl xl:mx-70 [&_ul]:my-5 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:my-5 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:mb-2">
    <?php foreach ($content_blocks as $content_block): ?>
      <?php
      $block_type = isset($content_block['type']) ? $content_block['type'] : '';
      $block_value_es = isset($content_block['value_es']) ? $content_block['value_es'] : '';
      $block_value_en = isset($content_block['value_en']) ? $content_block['value_en'] : '';
      $block_value = gf_is_en() && $block_value_en !== '' ? $block_value_en : $block_value_es;
      $block_author_es = isset($content_block['author_es']) ? $content_block['author_es'] : '';
      $block_author_en = isset($content_block['author_en']) ? $content_block['author_en'] : '';
      $block_author = gf_is_en() && $block_author_en !== '' ? $block_author_en : $block_author_es;
      $block_caption_es = isset($content_block['caption_es']) ? $content_block['caption_es'] : '';
      $block_caption_en = isset($content_block['caption_en']) ? $content_block['caption_en'] : '';
      $block_caption = gf_is_en() && $block_caption_en !== '' ? $block_caption_en : $block_caption_es;
      $block_image_id = isset($content_block['image_id']) ? absint($content_block['image_id']) : 0;
      ?>

      <?php if ($block_type === 'title' && $block_value !== ''): ?>
        <!-- Título interno. -->
        <h2 class="mt-10 text-2xl font-bold leading-tight md:text-3xl"><?php echo esc_html($block_value); ?></h2>
      <?php elseif ($block_type === 'content' && $block_value !== ''): ?>
        <!-- Texto enriquecido: negritas, enlaces y listas. -->
        <div class="mt-6"><?php echo wpautop(wp_kses_post($block_value)); ?></div>
      <?php elseif ($block_type === 'image' && $block_image_id): ?>
        <!-- Imagen dentro del contenido. -->
        <div class="mt-10">
          <figure class="overflow-hidden">
            <?php echo wp_get_attachment_image($block_image_id, 'full', false, array('class' => 'h-auto w-full object-cover')); ?>
            <?php if ($block_caption !== ''): ?>
              <figcaption class="mt-2 font-open text-md text-dark"><?php echo esc_html($block_caption); ?></figcaption>
            <?php endif; ?>
          </figure>
        </div>
      <?php elseif ($block_type === 'quote' && $block_value !== ''): ?>
        <!-- Frase destacada y su autor o fuente. -->
        <blockquote class="mt-12 border-l-4 border-secondary pl-9 font-montserrat text-3xl leading-[1.3] font-bold italic text-dark">
          <p class="m-0">&ldquo;<?php echo esc_html($block_value); ?>&rdquo;</p>
          <?php if ($block_author !== ''): ?>
            <cite class="mt-6 block text-base font-bold tracking-[0.25em] uppercase not-italic text-[#DBAB23]"><?php echo esc_html($block_author); ?></cite>
          <?php endif; ?>
        </blockquote>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

    </header>

  </article>

  <!-- Artículos relacionados. -->
  <?php if ($related->have_posts()): ?>
    <section class="reveal-section mx-6 md:mx-15 xl:mx-70 mb-20">
      <h2 class="reveal-item text-3xl md:text-4xl font-bold text-dark mb-8"><?php echo esc_html(gf_e('blog.related_title')); ?></h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while ($related->have_posts()): $related->the_post(); ?>
          <?php get_template_part('components/blog/card', null, array('post_id' => get_the_ID())); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </section>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
