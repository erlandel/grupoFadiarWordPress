<?php
// Valores por defecto (fallback)
$default_title = 'DESCUBRE NUESTRO GRUPO Y SU GENTE';
$default_subtitle = 'Grupo Fadiar: tres años creciendo junto a las familias cubanas.';
$default_description_1 = 'Desde nuestras instalaciones, trabajamos cada día para ofrecer productos duraderos, accesibles y con verdadera calidad. El desarrollo local y la mejora continua son nuestra base. EÓN, Vital y Lámmina reflejan nuestro propósito: unir innovación, producción responsable y una garantía real que respalda cada compra.';
$default_description_2 = 'Detrás de cada producto hay un equipo de profesionales apasionados, en constante evolución, que disfruta superando expectativas.';
$default_button_text = 'Ver más';
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
        'title'         => get_the_title(),
        'subtitle'      => get_field('discover_subtitle'),
        'description_1' => get_field('discover_description_1'),
        'description_2' => get_field('discover_description_2'),
        'button_text'   => get_field('discover_button_text'),
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
<section id="discoverGroup" class="scroll-mt-20 flex justify-center mx-20 py-10 gap-25">

  <div class="flex justify-center  w-3/5 ">
    <div>
      <div>
        <h2 class="text-[45px] font-black font-montserrat text-dark ">
          <span class=""><?php echo esc_html($title); ?></span>

        </h2>
        <p class="text-[25px] mt-5 font-bold text-dark"><?php echo esc_html($subtitle); ?></p>
      </div>
      <div class="flex flex-col gap-10 mt-15 text-xl text-gray-800  text-justify ">
        <p><?php echo esc_html($description_1); ?></p>
        <p><?php echo esc_html($description_2); ?></p>
      </div>
      <?php if (!empty($button_text) && !empty($button_url)) : ?>
      <div class="mt-15">
        <button onclick="window.location.href='<?php echo esc_url($button_url); ?>'" class="bg-dark text-white text-2xl px-8 py-2 rounded-xl font-bold cursor-pointer hover:scale-105 transition-transform"><?php echo esc_html($button_text); ?></button>
      </div>
      <?php endif; ?>
    </div>
  </div>




  <div class="flex items-center justify-end  w-2/5">
    <img src="<?php echo esc_url($image); ?>" alt="Girl" width="640" height="800" class="w-full max-w-[550px] h-auto object-contain" />
  </div>
</section>
