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

$metrics_image = $metrics_post_id ? get_field('about_metrics_image', $metrics_post_id) : null;
$metrics_image_url = (is_array($metrics_image) && !empty($metrics_image['url'])) ? $metrics_image['url'] : get_template_directory_uri() . '/assets/images/about/about.png';
$metrics_image_alt = (is_array($metrics_image) && !empty($metrics_image['alt'])) ? $metrics_image['alt'] : 'Sobre Nosotros';

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
<section class="mt-10 w-full flex flex-col">
  <div class="w-full">
    <img src="<?php echo esc_url($metrics_image_url); ?>" alt="<?php echo esc_attr($metrics_image_alt); ?>" class="reveal-item w-full h-auto" />
  </div>


 <div class="mx-30">
  <div class="flex justify-between  -mt-20 text-center ">
    <div class="reveal-item w-56 bg-white  py-6 shadow-xl flex flex-col items-center justify-center">
      <h2 class="text-4xl font-black text-dark"><?php echo esc_html($metrics_value[1]); ?></h2>
      <p class="text-2xl font-bold text-dark mt-2"><?php echo esc_html($metrics_label[1]); ?></p>
    </div>
    <div class="reveal-item w-56 bg-white px-5 py-6 shadow-xl flex flex-col items-center justify-center">
      <h2 class="text-4xl font-black text-dark"><?php echo esc_html($metrics_value[2]); ?></h2>
      <p class="text-2xl font-bold text-dark mt-2"><?php echo esc_html($metrics_label[2]); ?></p>
    </div>
    <div class="reveal-item w-56 bg-white px-5 py-6 shadow-xl flex flex-col items-center justify-center">
      <h2 class="text-4xl font-black text-dark"><?php echo esc_html($metrics_value[3]); ?></h2>
      <p class="text-2xl font-bold text-dark mt-2"><?php echo esc_html($metrics_label[3]); ?></p>
    </div>
    <div class="reveal-item w-56 bg-white px-5 py-6 shadow-xl flex flex-col items-center justify-center">
      <h2 class="text-4xl font-black text-dark"><?php echo esc_html($metrics_value[4]); ?></h2>
      <p class="text-2xl font-bold text-dark mt-2"><?php echo esc_html($metrics_label[4]); ?></p>
    </div>
  </div>
</div>


  <div class="mt-10 mx-30">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-15 font-open text-dark  text-justify">
      <div>
        <p class="reveal-item"><?php echo esc_html($description_1); ?></p>
      </div>
      <div>
        <p class="reveal-item"><?php echo esc_html($description_2); ?></p>
      </div>
    </div>
  </div>
</section>
