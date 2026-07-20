<?php

$cards = array();

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
        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $img_alt = get_the_title();

        if (!$img_url) {
            continue;
        }

        $cards[] = array(
            'url' => $img_url,
            'alt' => $img_alt,
        );
    endwhile;
    wp_reset_postdata();
endif;

if (empty($cards)) {
    return;
}
?>

<section class="support-carousel-section overflow-hidden bg-white">
  <style>
    .support-carousel-track {
      animation: support-marquee var(--support-marquee-duration, 40s) linear infinite;
    }
    .support-carousel-section:hover .support-carousel-track,
    .support-carousel-section:focus-within .support-carousel-track {
      animation-play-state: paused;
    }
    @keyframes support-marquee {
      from { transform: translate3d(0, 0, 0); }
      to   { transform: translate3d(var(--support-marquee-to, -50%), 0, 0); }
    }
    @media (prefers-reduced-motion: reduce) {
      .support-carousel-track { animation: none; }
    }
  </style>
  <div class="carousel-track support-carousel-track relative flex gap-10 will-change-transform" style="width: max-content;">
    <div class="carousel-set flex gap-10">
      <?php foreach ($cards as $i => $card) : ?>
        <div class="carousel-card shrink-0 w-96 h-120 rounded-3xl overflow-hidden bg-[#F4F4F4]" style="margin-top: <?php echo $i % 2 === 0 ? 80 : 0; ?>px;">
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
    <div class="carousel-set flex gap-10" aria-hidden="true">
      <?php foreach ($cards as $i => $card) : ?>
        <div class="carousel-card shrink-0 w-96 h-120 rounded-3xl overflow-hidden bg-[#F4F4F4]" style="margin-top: <?php echo $i % 2 === 0 ? 80 : 0; ?>px;">
          <img
            src="<?php echo esc_url($card['url']); ?>"
            alt=""
            class="w-full h-full object-cover"
            loading="eager"
            decoding="async"
            draggable="false"
          />
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
