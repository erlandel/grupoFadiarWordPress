<?php
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

        foreach (array(1, 2) as $button_number) {
            $button_text = gf_get_field("button_{$button_number}_text", $slide_id);
            $button_url = process_url(get_field("button_{$button_number}_url", $slide_id));
            $button_style_type = get_field("button_{$button_number}_style", $slide_id);

            if (empty($button_text)) {
                continue;
            }

            $button_class = '';
            if ($button_style_type === 'primary') {
                $button_class = 'bg-white text-[#010A2D] font-bold px-5 py-1 rounded-full text-xl transition-transform hover:scale-105 cursor-pointer';
            } elseif ($button_style_type === 'secondary') {
                $button_class = 'border-2 border-white px-5 py-1 rounded-full text-xl cursor-pointer hover:scale-105';
            }
            // Si el primer botón de la primera diapositiva es "Conócenos" y es primario, se le da un tamaño mayor
            if ($slide_layout === 'simple' && count($slides) === 0 && count($current_slide['buttons']) === 0 && $button_text === 'Conócenos' && $button_style_type === 'primary') {
                $button_class = 'bg-white text-[#010A2D] font-bold px-5 py-1 rounded-full text-xl transition-transform hover:scale-105 cursor-pointer';
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
<section id="heroCarousel" class="hero-carousel h-screen w-full flex flex-col overflow-hidden">
  <div class="relative flex-1">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="carousel-slide absolute inset-0 transition-all duration-1000 ease-out <?php echo $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105'; ?>">
        <div class="absolute inset-0 bg-center bg-cover"
             style="background-image: linear-gradient(to top, #010A2D, #7594D000 50%), url(<?php echo esc_url($slide['url']); ?>);">
        </div>
      </div>
    <?php endforeach; ?>
    <div class="relative h-full flex items-end text-white mx-20 pb-8 gap-5 ">
      <div class="flex flex-col gap-y-4 justify-end pb-2">
          <a href="#" aria-label="Instagram" class="p-2 rounded-full hover:scale-110 transition-colors">
            <?php echo get_icon('instagram', 'w-8 h-8 text-white'); ?>
          </a>
          <a href="#" aria-label="Facebook" class="p-2 rounded-full hover:scale-110 transition-colors">
            <?php echo get_icon('facebook', 'w-8 h-8 text-white'); ?>
          </a>

      </div>
      <div class="flex flex-col w-full">
        <?php foreach ($slides as $index => $slide): ?>
          <div class="carousel-content <?php echo $index === 0 ? '' : 'hidden'; ?>" data-index="<?php echo $index; ?>">
            <div class="flex gap-4 <?php echo !empty($slide['buttons']) ? 'mb-5' : 'mb-5'; ?>">
              <div class="flex-1 flex flex-col justify-end items-start">
                <?php if ($slide['layout'] === 'simple'): ?>
                  <!-- DISEÑO SIMPLE -->
                  <div >
                    <h2 class="text-5xl md:text-[50px]   <?php echo esc_attr($slide['title_font_class']); ?>" style="<?php echo $slide['title_font_style']; ?>">
                      <?php echo esc_html($slide['title']); ?>
                    </h2>
                  </div>
                  
                  <?php if (!empty($slide['subtitle'])): ?>
                    <p class="text-lg font-open mt-1 max-w-3xl">
                      <?php echo esc_html($slide['subtitle']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['buttons'])): ?>
                    <div class="flex gap-4 mt-4 ">
                      <?php foreach ($slide['buttons'] as $button): ?>
                        <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo esc_attr($button['class']); ?>"><?php echo esc_html($button['text']); ?></a>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                <?php else: ?>
                  <!-- DISEÑO DE MARCA -->
                  <div class=>
                    <img src="<?php echo esc_url($slide['title']); ?>" alt="<?php echo esc_attr($slide['subtitle']); ?>" class="object-contain h-auto w-auto" style="max-height:80px;" />
                  </div>
                  
                  <?php if (!empty($slide['description'])): ?>
                    <p class="text-4xl pb-4 mt-6 <?php echo esc_attr($slide['description_font_class']); ?>" style="<?php echo $slide['description_font_style']; ?>">
                      <?php echo esc_html($slide['description']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['subtitle'])): ?>
                    <p class="text-2xl font-open mt-4  font-bold">
                      <?php echo esc_html($slide['subtitle']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['buttons'])): ?>
                    <div class="flex gap-4 mt-6 ">
                      <?php foreach ($slide['buttons'] as $button): ?>
                        <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo esc_attr($button['class']); ?>"><?php echo esc_html($button['text']); ?></a>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="flex items-center w-full">
          <div class="grow h-1 bg-white mr-10"></div>
          <div class="flex gap-x-8">
              <button class="carousel-prev group p-2 rounded-full border border-white hover:bg-white transition-all duration-300 cursor-pointer">
                <?php echo get_icon('chevron-left', 'w-6 h-6 text-white group-hover:text-blue-900 transition-colors'); ?>
              </button>
              <button class="carousel-next group p-2 rounded-full border border-white hover:bg-white transition-all duration-300 cursor-pointer">
                <?php echo get_icon('chevron-right', 'w-6 h-6 text-white group-hover:text-blue-900 transition-colors'); ?>
              </button>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>