<?php
get_header();

$post_id = get_the_ID();
$title = gf_get_post_title($post_id);
$intro = gf_get_field('intro_blog', $post_id);
$date = gf_get_field('fecha_blog', $post_id) ?: get_the_date('d/m/Y');
$author = get_field('autor_blog', $post_id);
$content = gf_get_field('contenido_blog', $post_id);
$thumbnail = get_the_post_thumbnail_url($post_id, 'full');
$related = new WP_Query(array('post_type' => 'blog', 'posts_per_page' => 3, 'post__not_in' => array($post_id)));
?>
<main>
  <div class="reveal-section mx-6 md:mx-15 xl:mx-30 mt-7">
    <div class="flex text-lg">
      <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
      <?php echo get_icon('chevron-right', 'h-6 w-6 mx-1'); ?>
      <p><a href="<?php echo esc_url(get_post_type_archive_link('blog')); ?>"><?php echo esc_html(gf_e('blog.breadcrumb')); ?></a></p>
    </div>
  </div>

  <article class="reveal-section max-w-4xl mx-6 md:mx-auto mt-10 mb-20">
    <header class="reveal-item">
      <span class="inline-block bg-[#F4F4F4] text-[#8C8C8C] tracking-wider px-4 py-3 rounded-full"><?php echo esc_html(gf_e('blog.tag')); ?></span>
      <h1 class="text-3xl md:text-5xl font-bold text-dark leading-tight mt-5"><?php echo esc_html($title); ?></h1>
      <?php if ($intro): ?><p class="text-xl md:text-2xl text-dark leading-relaxed mt-5"><?php echo esc_html($intro); ?></p><?php endif; ?>
      <div class="flex flex-wrap gap-x-4 gap-y-1 mt-5 text-dark">
        <?php if ($author): ?><span class="font-bold"><?php echo esc_html(gf_e('blog.author') . ' ' . $author); ?></span><?php endif; ?>
        <time><?php echo esc_html($date); ?></time>
      </div>
    </header>

    <?php if ($thumbnail): ?>
      <div class="reveal-item mt-8 overflow-hidden rounded-xl aspect-video bg-gray-100">
        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" />
      </div>
    <?php endif; ?>

    <?php if ($content): ?>
      <div class="reveal-item blog-content mt-10 text-dark text-lg md:text-xl leading-relaxed">
        <?php echo wp_kses_post($content); ?>
      </div>
    <?php endif; ?>
  </article>

  <?php if ($related->have_posts()): ?>
    <section class="reveal-section mx-6 md:mx-15 xl:mx-30 mb-20">
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
