<?php
// Carga las diapositivas publicadas en el orden configurado en WordPress.
$asset_uri = get_template_directory_uri() . '/assets';
$slides = array();

$slide_query = new WP_Query(array(
    'post_type'              => 'carousel_slide',
    'posts_per_page'         => -1,
    'orderby'                => 'menu_order',
    'order'                  => 'ASC',
    'post_status'            => 'publish',
    'no_found_rows'          => true,
    'update_post_term_cache' => false,
));

if ($slide_query->have_posts()) :
    while ($slide_query->have_posts()) : $slide_query->the_post();
        $slide_id = get_the_ID();
        $slide_layout = get_field('slide_layout', $slide_id);

        $current_slide = array(
            'layout'    => $slide_layout,
            'url'       => get_the_post_thumbnail_url($slide_id, 'full'),
            'subtitle'  => gf_get_field('slide_subtitle', $slide_id),
            'buttons'   => array(),
        );

        // Procesar la fuente seleccionada para compatibilidad con estilos
        $font_value = get_field('slide_title_font_class', $slide_id);
        if ($font_value == 'font-satisfy') {
            $current_slide['title_font_class'] = '';
            $current_slide['title_font_style'] = 'font-family: \'Satisfy\', cursive;';
        } elseif ($font_value == 'font-dancing-script') {
            $current_slide['title_font_class'] = '';
            $current_slide['title_font_style'] = 'font-family: \'Dancing Script\', cursive;';
        } elseif ($font_value == 'font-flatlion') {
            $current_slide['title_font_class'] = '';
            $current_slide['title_font_style'] = 'font-family: \'Flatlion Personal Use Only\', sans-serif;';
        } else {
            $current_slide['title_font_class'] = esc_attr($font_value);
            $current_slide['title_font_style'] = '';
        }

        // Cada diapositiva puede usar texto simple o una identidad de marca.
        if ($slide_layout === 'simple') {
            $current_slide['title'] = gf_get_field('slide_title_text', $slide_id);
        } else { // 'brand'
            $current_slide['title'] = get_field('slide_title_image', $slide_id);
            $current_slide['description'] = gf_get_field('slide_description', $slide_id);
            if ($font_value == 'font-flatlion') {
                $current_slide['description_font_class'] = '';
                $current_slide['description_font_style'] = 'font-family: \'Flatlion Personal Use Only\', sans-serif;';
            } else {
                $current_slide['description_font_class'] = $current_slide['title_font_class'];
                $current_slide['description_font_style'] = $current_slide['title_font_style'];
            }
        }

        // Cada diapositiva admite hasta dos llamadas a la acción.
        foreach (array(1, 2) as $button_number) {
            $button_text = gf_get_field("button_{$button_number}_text", $slide_id);
            $button_url = process_url(get_field("button_{$button_number}_url", $slide_id));
            $button_style_type = get_field("button_{$button_number}_style", $slide_id);

            if (empty($button_text)) {
                continue;
            }

            $button_class = '';
            if ($button_style_type === 'primary') {
                $button_class = 'bg-white text-[#010A2D] font-bold px-4 py-1 rounded-full text-sm sm:text-base md:px-5 md:text-lg xl:text-xl transition-transform hover:scale-105 cursor-pointer';
            } elseif ($button_style_type === 'secondary') {
                $button_class = 'border-2 border-white px-4 py-1 rounded-full text-sm sm:text-base md:px-5 md:text-lg xl:text-xl cursor-pointer hover:scale-105';
            }
            // Si el primer botón de la primera diapositiva es "Conócenos" y es primario, se le da un tamaño mayor
            if ($slide_layout === 'simple' && count($slides) === 0 && count($current_slide['buttons']) === 0 && $button_text === 'Conócenos' && $button_style_type === 'primary') {
                $button_class = 'bg-white text-[#010A2D] font-bold px-4 py-1 rounded-full text-sm sm:text-base md:px-5 md:text-lg xl:text-xl transition-transform hover:scale-105 cursor-pointer';
            }

            $current_slide['buttons'][] = array(
                'text'  => $button_text,
                'url'   => $button_url,
                'class' => $button_class,
            );
        }

        $slides[] = $current_slide;
    endwhile;
    wp_reset_postdata();
endif;
?>
<!-- Hero: ocupa el alto visible del navegador en móvil. -->
<section id="heroCarousel" class="hero-carousel reveal-section h-svh w-full flex flex-col overflow-hidden xl:h-screen">
  <div class="relative flex-1">
    <!-- Fondos de las diapositivas; JavaScript alterna cuál está activa. -->
    <?php foreach ($slides as $index => $slide): ?>
      <div class="carousel-slide absolute inset-0 transition-all duration-1000 ease-out <?php echo $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105'; ?>">
        <div class="absolute inset-0 bg-center bg-cover <?php echo $index === 0 ? 'first-slide-bg' : ''; ?>"
             style="background-image: linear-gradient(to top, #010A2D, #7594D000 50%), url(<?php echo esc_url($slide['url']); ?>);">
        </div>
      </div>
    <?php endforeach; ?>
    <!-- Contenido superpuesto sobre el fondo activo. -->
    <div class="relative h-full flex flex-col items-start justify-end gap-3 px-4 pb-5 text-white sm:px-8 md:px-10 md:pb-8 lg:px-14 xl:flex-row xl:items-end xl:gap-5 xl:px-20 xl:pb-8">
      <!-- Redes laterales para tablet y escritorio. -->
      <div class="reveal-item hidden flex-col gap-y-2 pb-0 md:flex xl:gap-y-4 xl:pb-2">
        <a href="<?php echo esc_url(get_option('social_url_instagram', '#')); ?>" aria-label="Instagram" class="p-2 rounded-full hover:scale-110 transition-colors">
            <?php echo get_icon('instagram', 'w-6 h-6 text-white md:w-7 md:h-7 xl:w-8 xl:h-8'); ?>
          </a>
          <a href="<?php echo esc_url(get_option('social_url_facebook', '#')); ?>" aria-label="Facebook" class="p-2 rounded-full hover:scale-110 transition-colors">
            <?php echo get_icon('facebook', 'w-6 h-6 text-white md:w-7 md:h-7 xl:w-8 xl:h-8'); ?>
          </a>

      </div>
      <div class="flex flex-col w-full">
        <!-- Solo se muestra el contenido de la diapositiva seleccionada. -->
        <?php foreach ($slides as $index => $slide): ?>
          <div class="carousel-content <?php echo $index === 0 ? '' : 'hidden'; ?>" data-index="<?php echo $index; ?>" data-reveal-chunk>
            <div class="flex gap-4 <?php echo !empty($slide['buttons']) ? 'mb-3 md:mb-5' : 'mb-3 md:mb-5'; ?>">
              <div class="flex-1 flex flex-col justify-end items-start">
                <?php if ($slide['layout'] === 'simple'): ?>
                  <!-- DISEÑO SIMPLE -->
                  <div >
                    <h2 class="reveal-item text-4xl leading-tight  xl:text-[50px] <?php echo esc_attr($slide['title_font_class']); ?>" style="<?php echo $slide['title_font_style']; ?>">
                      <?php echo esc_html($slide['title']); ?>
                    </h2>
                  </div>
                  
                  <?php if (!empty($slide['subtitle'])): ?>
                    <p class="reveal-item mt-1 max-w-3xl text-sm font-open sm:text-base xl:text-lg">
                      <?php echo esc_html($slide['subtitle']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['buttons'])): ?>
                    <!-- En móvil, Instagram queda al borde derecho del botón y Facebook debajo. -->
                    <div class="reveal-item mt-4 flex w-full items-start justify-between gap-3 md:block md:w-auto">
                      <div class="flex min-w-0 flex-wrap gap-2 md:gap-4">
                        <?php foreach ($slide['buttons'] as $button): ?>
                          <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo esc_attr($button['class']); ?>"><?php echo esc_html($button['text']); ?></a>
                        <?php endforeach; ?>
                      </div>
                      <div class="flex shrink-0 flex-col gap-y-2 md:hidden">
                        <a href="<?php echo esc_url(get_option('social_url_instagram', '#')); ?>" aria-label="Instagram" class="p-2 rounded-full hover:scale-110 transition-colors">
                          <?php echo get_icon('instagram', 'w-6 h-6 text-white'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_option('social_url_facebook', '#')); ?>" aria-label="Facebook" class="p-2 rounded-full hover:scale-110 transition-colors">
                          <?php echo get_icon('facebook', 'w-6 h-6 text-white'); ?>
                        </a>
                      </div>
                    </div>
                  <?php endif; ?>

                <?php else: ?>
                  <!-- DISEÑO DE MARCA -->
                  <div class=>
                    <img src="<?php echo esc_url($slide['title']); ?>" alt="<?php echo esc_attr($slide['subtitle']); ?>" class="reveal-item h-auto max-h-14 max-w-[75vw] w-auto object-contain md:max-h-16 xl:max-h-20 xl:max-w-none" />
                  </div>
                  
                  <?php if (!empty($slide['description'])): ?>
                    <p class="reveal-item mt-4 pb-2 text-2xl leading-tight md:mt-6 md:pb-4 md:text-3xl xl:text-4xl <?php echo esc_attr($slide['description_font_class']); ?>" style="<?php echo $slide['description_font_style']; ?>">
                      <?php echo esc_html($slide['description']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['subtitle'])): ?>
                    <p class="reveal-item mt-3 text-base font-open font-bold md:mt-4 md:text-xl xl:text-2xl">
                      <?php echo esc_html($slide['subtitle']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['buttons'])): ?>
                    <!-- En móvil, Instagram queda al borde derecho del botón y Facebook debajo. -->
                    <div class="reveal-item mt-4 flex w-full items-start justify-between gap-3 md:mt-6 md:block md:w-auto">
                      <div class="flex min-w-0 flex-wrap gap-2 md:gap-4">
                        <?php foreach ($slide['buttons'] as $button): ?>
                          <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo esc_attr($button['class']); ?>"><?php echo esc_html($button['text']); ?></a>
                        <?php endforeach; ?>
                      </div>
                      <div class="flex shrink-0 flex-col gap-y-2 md:hidden">
                        <a href="<?php echo esc_url(get_option('social_url_instagram', '#')); ?>" aria-label="Instagram" class="p-2 rounded-full hover:scale-110 transition-colors">
                          <?php echo get_icon('instagram', 'w-6 h-6 text-white'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_option('social_url_facebook', '#')); ?>" aria-label="Facebook" class="p-2 rounded-full hover:scale-110 transition-colors">
                          <?php echo get_icon('facebook', 'w-6 h-6 text-white'); ?>
                        </a>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <!-- Navegación manual visible desde tablet; en móvil cambia automáticamente. -->
        <div class="hidden w-full items-center md:flex">
          <div class="mr-3 h-1 grow bg-white md:mr-6 xl:mr-10"></div>
          <div class="flex gap-x-3 md:gap-x-5 xl:gap-x-8">
              <button class="carousel-prev group rounded-full border border-white p-1.5 transition-all duration-300 cursor-pointer hover:bg-white md:p-2">
                <?php echo get_icon('chevron-left', 'w-5 h-5 text-white transition-colors group-hover:text-blue-900 md:w-6 md:h-6'); ?>
              </button>
              <button class="carousel-next group rounded-full border border-white p-1.5 transition-all duration-300 cursor-pointer hover:bg-white md:p-2">
                <?php echo get_icon('chevron-right', 'w-5 h-5 text-white transition-colors group-hover:text-blue-900 md:w-6 md:h-6'); ?>
              </button>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
