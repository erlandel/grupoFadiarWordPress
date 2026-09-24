<?php

// Array donde se almacenan las imagenes validas del carrusel.
$cards = array();

// Consulta todas las tarjetas publicadas en el orden definido desde WordPress.
$card_query = new WP_Query(array(
    'post_type'              => 'support_carousel',
    'posts_per_page'         => -1,
    'orderby'                => 'menu_order',
    'order'                  => 'ASC',
    'post_status'            => 'publish',
    'no_found_rows'          => true,
    'update_post_term_cache' => false,
));

if ($card_query->have_posts()) :
    while ($card_query->have_posts()) : $card_query->the_post();
        // Obtiene la imagen destacada y el texto alternativo de cada tarjeta.
        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $img_alt = get_the_title();

        // Las entradas sin imagen no se muestran en este carrusel.
        if (!$img_url) {
            continue;
        }

        // Guarda solo los datos necesarios para pintar cada imagen.
        $cards[] = array(
            'url' => $img_url,
            'alt' => $img_alt,
        );
    endwhile;
    wp_reset_postdata();
endif;

if (empty($cards)) {
    // No renderiza el componente si no hay tarjetas validas.
    return;
}
?>

<!-- Contenedor general del carrusel de soporte. -->
<section class="support-carousel-section group relative overflow-hidden bg-white">
  <style>
    /* El movimiento lo controla support-carousel.js para coordinar autoplay y flechas. */
    .support-carousel-track { transform: translate3d(0, 0, 0); }
    .support-carousel-viewport { overflow: hidden; }
    @media (max-width: 1279px) {
      .support-carousel-viewport {
        touch-action: pan-y;
      }
      .support-carousel-track { user-select: none; }
    }
  </style>

  <!-- Las flechas se muestran solo en PC y se superponen a los extremos. -->
  <button type="button" class="support-carousel-prev pointer-events-none absolute left-3 top-1/2 z-10 hidden h-12 w-12 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-black/45 text-white opacity-0 shadow-lg transition-opacity duration-200 hover:bg-black/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-dark group-hover:pointer-events-auto group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:opacity-100 xl:flex" aria-label="<?php echo esc_attr(gf_current_lang() === 'en' ? 'Previous' : 'Anterior'); ?>">
    <?php echo get_icon('chevron-left', 'h-7 w-7'); ?>
  </button>
  <button type="button" class="support-carousel-next pointer-events-none absolute right-3 top-1/2 z-10 hidden h-12 w-12 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-black/45 text-white opacity-0 shadow-lg transition-opacity duration-200 hover:bg-black/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-dark group-hover:pointer-events-auto group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:opacity-100 xl:flex" aria-label="<?php echo esc_attr(gf_current_lang() === 'en' ? 'Next' : 'Siguiente'); ?>">
    <?php echo get_icon('chevron-right', 'h-7 w-7'); ?>
  </button>

  <!-- Viewport que recorta las tarjetas y permite desplazamiento tactil. -->
  <div class="support-carousel-viewport overflow-x-hidden overflow-y-hidden xl:overflow-hidden">
    <!-- JavaScript clona este conjunto a ambos lados para crear el bucle continuo. -->
    <div class="carousel-track support-carousel-track relative flex gap-4 sm:gap-6 xl:gap-10 will-change-transform" style="width: max-content;">
      <!-- Unica copia accesible; las copias generadas se ocultan a lectores de pantalla. -->
      <div class="carousel-set flex gap-4 sm:gap-6 xl:gap-10" data-support-carousel-main>
      <?php foreach ($cards as $i => $card) : ?>
        <div class="carousel-card h-64 w-48 shrink-0 overflow-hidden rounded-2xl bg-[#F4F4F4] sm:h-80 sm:w-60 sm:rounded-3xl md:h-96 md:w-72 xl:h-95 xl:w-75 <?php echo $i % 2 === 0 ? 'mt-8 xl:mt-20' : ''; ?>">
          <img
            src="<?php echo esc_url($card['url']); ?>"
            alt="<?php echo esc_attr($card['alt']); ?>"
            class="w-full h-full object-cover"
            loading="eager"
            decoding="async"
            draggable="false"
          />
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
