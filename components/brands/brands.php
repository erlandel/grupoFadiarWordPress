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

<section class="w-full bg-linear-to-br from-[#1D3D75] via-dark to-dark py-16">
    <div class="flex items-center justify-center text-white font-bold">
        <div>
            <h2 class="text-secondary text-center text-2xl"><?php echo esc_html($section_title); ?></h2>
            <h3 class="text-4xl mt-4">"<?php echo esc_html($section_subtitle); ?>"</h3>
        </div>
    </div>
    <div class="flex flex-wrap justify-center items-center gap-8 mt-10">
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
</section>
