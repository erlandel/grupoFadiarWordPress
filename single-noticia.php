<?php
get_header();

$post_id = get_the_ID();
$title = get_the_title();
$fecha = get_field('fecha_noticia');
$categories = wp_get_post_terms($post_id, 'categoria_noticia');

$thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');

$cat_term_id = 0;
$category_name = '';
$prev_id = null;
$next_id = null;
$current_pos = 1;
$total_in_cat = 1;

if (!empty($categories) && !is_wp_error($categories)) {
  $cat_term_id = $categories[0]->term_id;
  $category_name = $categories[0]->name;

  $all_ids = get_posts(array(
    'post_type'      => 'noticia',
    'posts_per_page' => -1,
    'tax_query'      => array(array(
      'taxonomy' => 'categoria_noticia',
      'field'    => 'term_id',
      'terms'    => $cat_term_id,
    )),
    'orderby'        => 'date',
    'order'          => 'DESC',
    'fields'         => 'ids',
  ));

  $total_in_cat = count($all_ids);
  $current_index = array_search($post_id, $all_ids);

  if ($current_index !== false && $total_in_cat > 1) {
    $prev_index = ($current_index - 1 + $total_in_cat) % $total_in_cat;
    $next_index = ($current_index + 1) % $total_in_cat;
    $prev_id = $all_ids[$prev_index];
    $next_id = $all_ids[$next_index];
    $current_pos = $current_index + 1;
  }

  $related_args = array(
    'post_type'      => 'noticia',
    'posts_per_page' => 3,
    'post__not_in'   => array($post_id),
    'tax_query'      => array(
      array(
        'taxonomy' => 'categoria_noticia',
        'field'    => 'term_id',
        'terms'    => wp_list_pluck($categories, 'term_id'),
      ),
    ),
  );
  $related_query = new WP_Query($related_args);
}
?>
<div class="mx-20 mt-10">
  <div class="flex text-xl">
    <p><a href="<?php echo home_url('/'); ?>">Inicio</a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><a href="<?php echo home_url('/noticias/'); ?>">Noticias</a></p>
  </div>
</div>

<div class="mx-20 mt-10 flex flex-col lg:flex-row gap-10">
  <div class="lg:w-9/12">
    <?php if ($thumbnail_url):
      $share_url = urlencode(get_permalink());
      $share_title = urlencode(get_the_title());
    ?>
      <div id="hero-share" class="relative w-full overflow-hidden rounded-xl aspect-video bg-gray-100" data-share-url="<?php echo esc_attr(get_permalink()); ?>" data-share-title="<?php echo esc_attr(get_the_title()); ?>">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" />

        <button data-share-toggle aria-haspopup="true" aria-expanded="false" aria-label="Compartir noticia"
                class="absolute z-10 right-4 bottom-4 flex h-12 w-12 items-center justify-center rounded-full bg-white/90 backdrop-blur shadow-md text-dark hover:bg-white transition">
          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
        </button>

        <div data-share-menu class="absolute z-20 right-4 bottom-20 hidden w-56 rounded-xl bg-white shadow-lg ring-1 ring-black/5 p-2">
          <p class="text-sm font-semibold text-gray-500 px-3 py-2">Compartir en</p>
          <a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer"
             class="flex items-center gap-3 w-full px-3 py-2.5 text-sm text-dark rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer"
             class="flex items-center gap-3 w-full px-3 py-2.5 text-sm text-dark rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            Facebook
          </a>
          <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer"
             class="flex items-center gap-3 w-full px-3 py-2.5 text-sm text-dark rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            X (Twitter)
          </a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer"
             class="flex items-center gap-3 w-full px-3 py-2.5 text-sm text-dark rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            LinkedIn
          </a>
          <button data-share-copy type="button"
                  class="flex items-center gap-3 w-full px-3 py-2.5 text-sm text-dark rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            <span>Copiar enlace</span>
            <span data-share-feedback class="hidden ml-auto text-xs text-green-600 font-medium">¡Copiado!</span>
          </button>
        </div>
      </div>
    <?php endif; ?>

    <div class="mt-6">
      <?php if ($categories): ?>
        <!-- <span class="inline-block bg-primary text-dark text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4"><?php echo esc_html($categories[0]->name); ?></span> -->
      <?php endif; ?>
      <h1 class="text-3xl font-bold text-dark leading-tight"><?php echo esc_html($title); ?></h1>
    
      <div class="flex items-center gap-4 mt-3 text-xl text-dark">
        <span><?php echo esc_html($fecha ?: get_the_date('d/m/Y')); ?></span>
      </div>
      <?php $autor = get_field('autor'); if ($autor): ?>
        <p class="italic font-semibold text-dark text-xl mt-1"><?php echo esc_html($autor); ?></p>
      <?php endif; ?>
    </div>

    <div class="mt-8 text-dark text-xl leading-relaxed space-y-4">
      <?php the_field('descripcion'); ?>
    </div>

    <?php if ($category_name): ?>
      <div class="flex items-center justify-between mt-10">
        <span class="inline-block w-fit bg-[#F4F4F4] text-[#8C8C8C] text-2xl tracking-wider px-4 py-3 rounded-full">
          <?php echo esc_html($category_name); ?>
        </span>

        <div class="flex items-center gap-6">
          <?php if ($prev_id): ?>
            <a href="<?php echo get_permalink($prev_id); ?>" aria-label="Noticia anterior"
               class="flex items-center justify-center w-12 h-12 text-dark hover:opacity-70 transition-opacity">
              <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M5 12L12 5M5 12L12 19"/></svg>
            </a>
          <?php endif; ?>
          <span class="text-2xl font-medium text-dark"><?php echo $current_pos; ?>/<?php echo $total_in_cat; ?></span>
          <?php if ($next_id): ?>
            <a href="<?php echo get_permalink($next_id); ?>" aria-label="Noticia siguiente"
               class="flex items-center justify-center w-12 h-12 bg-gray-100 text-dark hover:bg-gray-200 rounded-full transition-colors">
              <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M19 12l-7-7M19 12l-7 7"/></svg>
            </a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <aside class="lg:w-3/12">
    <?php
    $all_categories = get_terms(array('taxonomy' => 'categoria_noticia', 'hide_empty' => true));
    $first_by_cat = array();
    if (!empty($all_categories) && !is_wp_error($all_categories)):
      foreach ($all_categories as $sidebar_cat) {
        $first_post = get_posts(array(
          'post_type'      => 'noticia',
          'posts_per_page' => 1,
          'tax_query'      => array(array(
            'taxonomy' => 'categoria_noticia',
            'field'    => 'term_id',
            'terms'    => $sidebar_cat->term_id,
          )),
          'orderby'  => 'date',
          'order'    => 'DESC',
          'fields'   => 'ids',
        ));
        $first_by_cat[$sidebar_cat->term_id] = !empty($first_post) ? get_permalink($first_post[0]) : '#';
      }
    ?>
      <div class="bg-[#F8F8F8] w-full rounded-xl p-6 pb-10 border border-gray-200">
        <h3 class="text-3xl font-bold text-dark mb-4">Categorías</h3>
        <ul class="space-y-2">
          <li class="border-b-3 border-[#EDEDED]">
            <a href="<?php echo home_url('/noticias/'); ?>" class="block text-xl text-dark transition px-3 py-2 rounded-md hover:bg-gray-200 <?php echo empty($categories) ? 'font-bold' : ''; ?>">Todas las Noticias</a>
          </li>
          <?php foreach ($all_categories as $cat): ?>
            <li class="border-b-3 border-[#EDEDED]">
              <a href="<?php echo esc_url($first_by_cat[$cat->term_id] ?? '#'); ?>" class="block text-xl text-dark transition px-3 py-2 rounded-md hover:bg-gray-200 <?php echo (isset($categories[0]) && $cat->term_id == $categories[0]->term_id) ? 'font-bold' : ''; ?>"><?php echo esc_html($cat->name); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>

</div>

<?php if (isset($related_query) && $related_query->have_posts()): ?>
  <div class="mx-15 mt-20 mb-20">
    <h2 class="text-3xl font-bold text-dark mb-8">También te puede interesar</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
        <?php get_template_part('components/noticias/card', null, array('post_id' => get_the_ID())); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
<?php endif; ?>

<?php get_footer(); ?>

<script>
(function(){
  var root = document.getElementById('hero-share');
  if (!root) return;
  var btn  = root.querySelector('[data-share-toggle]');
  var menu = root.querySelector('[data-share-menu]');
  var copy = root.querySelector('[data-share-copy]');
  var fb   = root.querySelector('[data-share-feedback]');
  var copyText = root.querySelector('[data-share-copy] span');

  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    var open = menu.classList.toggle('hidden') === false;
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  copy.addEventListener('click', function() {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(root.dataset.shareUrl).then(function() {
        fb.classList.remove('hidden');
        setTimeout(function() { fb.classList.add('hidden'); }, 2000);
      });
    }
  });

  document.addEventListener('click', function(e) {
    if (!root.contains(e.target)) {
      menu.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      menu.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>