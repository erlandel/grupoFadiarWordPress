<?php
$passed_post_id = isset($args['post_id']) ? (int) $args['post_id'] : 0;

$metrics_post_id = $passed_post_id;
if (!$metrics_post_id && post_type_exists('about_us')) {
    $maybe_id = get_posts(array(
        'post_type'      => 'about_us',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'ASC',
    ));
    if (!empty($maybe_id)) {
        $metrics_post_id = (int) $maybe_id[0];
    }
}

$metrics_mobile_image  = $metrics_post_id ? get_field('about_metrics_image_mobile', $metrics_post_id) : null;
$metrics_desktop_image = $metrics_post_id ? get_field('about_metrics_image', $metrics_post_id) : null;
$metrics_default_url   = get_template_directory_uri() . '/assets/images/about/about.png';

$metrics_desktop_image_url = (is_array($metrics_desktop_image) && !empty($metrics_desktop_image['url'])) ? $metrics_desktop_image['url'] : $metrics_default_url;
$metrics_desktop_image_alt = (is_array($metrics_desktop_image) && !empty($metrics_desktop_image['alt'])) ? $metrics_desktop_image['alt'] : 'Sobre Nosotros';
$metrics_mobile_image_url  = (is_array($metrics_mobile_image) && !empty($metrics_mobile_image['url'])) ? $metrics_mobile_image['url'] : $metrics_desktop_image_url;
$metrics_mobile_image_alt  = (is_array($metrics_mobile_image) && !empty($metrics_mobile_image['alt'])) ? $metrics_mobile_image['alt'] : $metrics_desktop_image_alt;

$metrics_value = array(
    1 => '500+',
    2 => '3',
    3 => '1,200+',
    4 => '2.5M+',
);
$metrics_label = array(
    1 => 'colaboradores',
    2 => 'marcas',
    3 => 'productos',
    4 => 'unidades vendidas',
);
for ($i = 1; $i <= 4; $i++) {
    $v = $metrics_post_id ? get_field('about_metric_' . $i . '_value', $metrics_post_id) : '';
    if (!empty($v)) {
        $metrics_value[$i] = $v;
    }
    $l = $metrics_post_id ? gf_get_field('about_metric_' . $i . '_label', $metrics_post_id) : '';
    if (!empty($l)) {
        $metrics_label[$i] = $l;
    }
}

$description_1 = $metrics_post_id ? (string) gf_get_field('about_metrics_description_1', $metrics_post_id) : '';
if ($description_1 === '') {
    $description_1 = 'Grupo Fadiar (Fabricación y Diseño Artesanal) comenzó como un sueño, un reto personal. Se forjó desde los cimientos. Construyendo sus instalaciones, escribiendo lo importante, uniendo personas invaluables. Poco a poco hicimos nuestro camino. En estos tres años, Grupo Fadiar ha crecido con un propósito claro: ofrecer productos que combinan calidad, accesibilidad y responsabilidad social. Nuestra trayectoria se basa en la innovación, la producción nacional y alianzas estratégicas que fortalecen la economía local.';
}

$description_2 = $metrics_post_id ? (string) gf_get_field('about_metrics_description_2', $metrics_post_id) : '';
if ($description_2 === '') {
    $description_2 = 'Nuestra filosofía "Diversidad de Soluciones, Un solo compromiso" refleja lo que somos, nuestra esencia. En Fadiar crecemos profesional y personalmente. Cada equipo, cada objeto que fabricamos y que llega a un hogar o negocio es un reto, un compromiso y, sobre todo, una oportunidad para ser útiles. Y en eso, ponemos lo mejor de nosotros. ¡Más que productos, compartimos experiencias';
}
?>
<section class="mt-5 flex w-full flex-col xl:mt-10">
  <div class="w-full">
    <img src="<?php echo esc_url($metrics_mobile_image_url); ?>" alt="<?php echo esc_attr($metrics_mobile_image_alt); ?>" class="reveal-item block h-auto w-full md:hidden" />
    <img src="<?php echo esc_url($metrics_desktop_image_url); ?>" alt="<?php echo esc_attr($metrics_desktop_image_alt); ?>" class="reveal-item hidden h-auto w-full md:block" />
  </div>

 <div class="mx-8 md:mx-12 xl:mx-30">
  <div class="-mt-16 grid grid-cols-2 gap-4 text-center md:grid-cols-4 md:gap-5 xl:-mt-20 xl:flex xl:justify-between">
    <div class="reveal-item flex min-h-30 flex-col items-center justify-center bg-white px-3 py-5 shadow-xl xl:w-56 xl:px-0 xl:py-6">
      <h2 class="text-3xl font-black text-dark xl:text-4xl"><?php echo esc_html($metrics_value[1]); ?></h2>
      <p class="mt-2 text-sm font-bold leading-tight text-dark md:text-base xl:text-2xl"><?php echo esc_html($metrics_label[1]); ?></p>
    </div>
    <div class="reveal-item flex min-h-30 flex-col items-center justify-center bg-white px-3 py-5 shadow-xl xl:w-56 xl:px-5 xl:py-6">
      <h2 class="text-3xl font-black text-dark xl:text-4xl"><?php echo esc_html($metrics_value[2]); ?></h2>
      <p class="mt-2 text-sm font-bold leading-tight text-dark md:text-base xl:text-2xl"><?php echo esc_html($metrics_label[2]); ?></p>
    </div>
    <div class="reveal-item flex min-h-30 flex-col items-center justify-center bg-white px-3 py-5 shadow-xl xl:w-56 xl:px-5 xl:py-6">
      <h2 class="text-3xl font-black text-dark xl:text-4xl"><?php echo esc_html($metrics_value[3]); ?></h2>
      <p class="mt-2 text-sm font-bold leading-tight text-dark md:text-base xl:text-2xl"><?php echo esc_html($metrics_label[3]); ?></p>
    </div>
    <div class="reveal-item flex min-h-30 flex-col items-center justify-center bg-white px-3 py-5 shadow-xl xl:w-56 xl:px-5 xl:py-6">
      <h2 class="text-3xl font-black text-dark xl:text-4xl"><?php echo esc_html($metrics_value[4]); ?></h2>
      <p class="mt-2 text-sm font-bold leading-tight text-dark md:text-base xl:text-2xl"><?php echo esc_html($metrics_label[4]); ?></p>
    </div>
  </div>
</div>

  <div class="mx-8 mt-10 md:mx-12 xl:mx-30">
    <div class="grid grid-cols-1 gap-4 font-open text-sm leading-snug text-dark md:grid-cols-2 md:gap-10 md:text-base xl:gap-15 xl:text-justify">
      <div>
        <p class="reveal-item"><?php echo esc_html($description_1); ?></p>
      </div>
      <div>
        <p class="reveal-item"><?php echo esc_html($description_2); ?></p>
      </div>
    </div>
  </div>
</section>
