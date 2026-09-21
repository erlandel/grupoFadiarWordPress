<?php
// Valores por defecto (fallback)
$default_title = 'DESCUBRE NUESTRO GRUPO Y SU GENTE';
$default_subtitle = 'Grupo Fadiar: tres años creciendo junto a las familias cubanas.';
$default_description_1 = 'Desde nuestras instalaciones, trabajamos cada día para ofrecer productos duraderos, accesibles y con verdadera calidad. El desarrollo local y la mejora continua son nuestra base. EÓN, Vital y Lámmina reflejan nuestro propósito: unir innovación, producción responsable y una garantía real que respalda cada compra.';
$default_description_2 = 'Detrás de cada producto hay un equipo de profesionales apasionados, en constante evolución, que disfruta superando expectativas.';
$default_button_text = gf_current_lang() === 'en' ? 'Learn more' : 'Ver más';
$default_button_url = process_url('/about-us/');
$default_image = get_template_directory_uri() . '/assets/images/girl.png';

// Obtener el ítem del CPT discover_group
$discover_item = null;
$discover_query = new WP_Query(array(
    'post_type'              => 'discover_group',
    'posts_per_page'         => 1,
    'post_status'            => 'publish',
    'no_found_rows'          => true,
    'update_post_term_cache' => false,
));

if ($discover_query->have_posts()) {
    $discover_query->the_post();
    $discover_item = array(
        'title'         => gf_get_post_title(),
        'subtitle'      => gf_get_field('discover_subtitle'),
        'description_1' => gf_get_field('discover_description_1'),
        'description_2' => gf_get_field('discover_description_2'),
        'button_text'   => gf_get_field('discover_button_text'),
        'button_url'    => get_field('discover_button_url'),
        'image'         => get_the_post_thumbnail_url(null, 'full'),
    );
    wp_reset_postdata();
}

// Asignar valores (usar los del CPT si existen, sino los por defecto)
$title         = !empty($discover_item['title']) ? $discover_item['title'] : $default_title;
$subtitle      = !empty($discover_item['subtitle']) ? $discover_item['subtitle'] : $default_subtitle;
$description_1 = !empty($discover_item['description_1']) ? $discover_item['description_1'] : $default_description_1;
$description_2 = !empty($discover_item['description_2']) ? $discover_item['description_2'] : $default_description_2;
$button_text   = !empty($discover_item['button_text']) ? $discover_item['button_text'] : $default_button_text;
$button_url    = !empty($discover_item['button_url']) ? process_url($discover_item['button_url']) : $default_button_url;
$image         = !empty($discover_item['image']) ? $discover_item['image'] : $default_image;
?>
<section id="discoverGroup" class="scroll-mt-20 flex flex-col mx-8 py-6 gap-2 xl:flex-row xl:justify-center xl:mx-30 xl:py-10 xl:gap-20">

  <div class="flex justify-center w-full xl:w-4/6">
    <div class="w-full  xl:px-0">
      <div>
        <h2 class="reveal-item text-center text-xl leading-tight font-black font-montserrat text-dark xl:text-left xl:text-4xl xl:leading-[1.2]">
          <span class=""><?php echo esc_html($title); ?></span>

        </h2>
        <p class="reveal-item  leading-tight mt-4 font-bold text-dark xl:text-2xl xl:mt-5 xl:leading-[1.2]"><?php echo esc_html($subtitle); ?></p>
      </div>
      <div class="flex flex-col gap-4 mt-5 leading-tight text-gray-800 text-justify xl:gap-10 xl:mt-10 xl:text-xl xl:leading-[1.4]">
        <p class="reveal-item"><?php echo esc_html($description_1); ?></p>
        <p class="reveal-item"><?php echo esc_html($description_2); ?></p>
      </div>
      <?php if (!empty($button_text) && !empty($button_url)) : ?>
      <div class="reveal-item mt-5 xl:mt-10">
        <button onclick="window.location.href='<?php echo esc_url($button_url); ?>'" class="bg-dark text-white text-xs px-3 py-1 rounded font-bold cursor-pointer hover:scale-105 transition-transform xl:text-2xl xl:px-5 xl:rounded-xl"><?php echo esc_html($button_text); ?></button>
      </div>
      <?php endif; ?>
    </div>
  </div>



  <div class="flex items-center justify-start w-full xl:justify-end xl:w-2/6 xl:mr-10">
    <img src="<?php echo esc_url($image); ?>" alt="Girl" class="reveal-item w-full max-w-86 xl:w-100 xl:max-w-none" />
  </div>
</section>
