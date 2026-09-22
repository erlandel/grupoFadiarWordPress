<?php
/**
 * Componente de Marcas (Nuestras Marcas)
 *
 * Lee los títulos desde las opciones de WordPress y
 * las marcas desde el CPT 'brand'.
 */

// Obtener títulos de la sección desde las opciones
$section_title    = gf_get_option('brands_section_title', 'Nuestras marcas', 'Our Brands');
$section_subtitle = gf_get_option('brands_section_subtitle', 'Diversidad de soluciones, un solo compromiso', 'Diverse solutions, one single commitment');

// Obtener todas las marcas publicadas
$brands_query = new WP_Query(array(
    'post_type'      => 'brand',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
));
?>

<section class="w-full overflow-hidden bg-linear-to-r from-[#1D3D75] to-dark py-12 xl:overflow-visible xl:py-16">
    <div class="flex items-center justify-center text-white font-bold">
        <div class="px-4 xl:px-0">
            <h2 class="reveal-item text-secondary text-center  xl:text-2xl"><?php echo esc_html($section_title); ?></h2>
            <h3 class="reveal-item mt-4 text-center text-2xl leading-tight xl:text-4xl xl:leading-[1.2]">"<?php echo esc_html($section_subtitle); ?>"</h3>
        </div>
    </div>
    <div class="brands-carousel mt-8 w-full overflow-hidden md:mt-10 xl:mt-10 xl:overflow-visible" tabindex="0" aria-label="<?php echo esc_attr($section_title); ?>">
      <div class="brands-carousel-track flex w-max items-center gap-6 px-6 pr-12 will-change-transform md:gap-8 md:px-8 md:pr-16 xl:w-auto xl:flex-wrap xl:justify-center xl:gap-8 xl:px-0">
        <?php if ($brands_query->have_posts()): ?>
            <?php while ($brands_query->have_posts()): $brands_query->the_post(); ?>
                <?php
                // Obtener campos ACF
                $product_image     = get_the_post_thumbnail_url(get_the_ID(), 'full');
                $product_alt       = gf_get_post_title();
                $brand_logo        = get_field('brand_logo');
                $brand_logo_url    = $brand_logo ? $brand_logo['url'] : '';
                $brand_logo_alt    = $brand_logo ? $brand_logo['alt'] : gf_get_post_title();
                $description       = gf_get_field('brand_description');
                $button_text       = gf_get_field('brand_button_text') ?: (gf_current_lang() === 'en' ? 'View more' : 'Ver más');
                $button_url        = get_field('brand_button_url') ?: '#';

                // Preparar args para card-product.php
                $card_args = array(
                    'productImage' => $product_image,
                    'productAlt'   => $product_alt,
                    'brandImage'   => $brand_logo_url,
                    'brandAlt'     => $brand_logo_alt,
                    'brandWidth'   => 96,
                    'brandHeight'  => 40,
                    'description'  => $description,
                    'buttonText'   => $button_text,
                    'buttonUrl'    => process_url($button_url),
                );
                ?>
                <?php get_template_part('components/brands/card-product', null, $card_args); ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <p class="text-white text-center">No se han encontrado marcas.</p>
        <?php endif; ?>
      </div>
      <!-- Indicadores del carrusel: modifica estas clases para controlar el diseño de los puntos. -->
      <?php if ($brands_query->post_count > 0): ?>
        <div class="brands-carousel-dots flex justify-center gap-4 mt-8 xl:hidden" aria-label="<?php echo esc_attr($section_title); ?>">
          <?php for ($brand_index = 0; $brand_index < $brands_query->post_count; $brand_index++): ?>
            <span class="brands-carousel-dot h-4 w-4 shrink-0 rounded-full <?php echo $brand_index === 0 ? 'bg-secondary' : 'bg-white'; ?> transition-colors duration-200" aria-label="Marca <?php echo esc_attr($brand_index + 1); ?>"></span>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>
</section>
