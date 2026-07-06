<?php
$asset_uri = get_template_directory_uri() . '/assets';
$slides = array(
  array(
    'layout' => 'simple',
    'url' => $asset_uri . '/images/carousel/1.png',
    'title' => 'BIENVENIDO',
    'subtitle' => 'Somos un grupo empresarial que mejora la experiencia en el hogar y la industria con soluciones definidas por su calidad e innovación.',
    'titleFont' => 'font-bold',
    'buttons' => array(
      array('text' => 'Conócenos', 'class' => 'bg-white text-[#010A2D] font-bold px-8 py-2 rounded-full text-2xl transition-transform hover:scale-105 cursor-pointer')
    )
  ),
  array(
    'layout' => 'brand',
    'url' => $asset_uri . '/images/carousel/2.png',
    'title' => $asset_uri . '/images/brands/eon.png',
    'description' => 'Siéntete familiar',
    'subtitle' => 'Tecnología funcional para una vida más cómoda',
    'descriptionFont' => 'Flatlion Personal Use Only',
    'buttons' => array(
      array('text' => 'Productos', 'class' => 'border-2 border-white px-6 py-2 rounded-full text-xl cursor-pointer hover:scale-105'),
      array('text' => 'Compra', 'class' => 'bg-white text-[#010A2D] font-bold px-8 py-2 rounded-full text-xl transition-transform hover:scale-105 cursor-pointer')
    )
  ),
  array(
    'layout' => 'brand',
    'url' => $asset_uri . '/images/carousel/3.png',
    'title' => $asset_uri . '/images/brands/lammina.png',
    'description' => 'Para diseñar espacios que cuenten tu historia',
    'subtitle' => 'Mobiliario modular con diseño funcional y tecnología importada',
    'descriptionFont' => 'Flatlion Personal Use Only',
    'buttons' => array(
      array('text' => 'Productos', 'class' => 'border-2 border-white px-6 py-2 rounded-full text-xl cursor-pointer hover:scale-105'),
      array('text' => 'Compra', 'class' => 'bg-white text-[#010A2D] font-bold px-8 py-2 rounded-full text-xl transition-transform hover:scale-105 cursor-pointer')
    )
  ),
  array(
    'layout' => 'brand',
    'url' => $asset_uri . '/images/carousel/4.png',
    'title' => $asset_uri . '/images/brands/vital.png',
    'description' => 'Placer para todos',
    'subtitle' => 'Fuerte con el uso, suave con tu piel',
    'descriptionFont' => 'Flatlion Personal Use Only',
    'buttons' => array(
      array('text' => 'Productos', 'class' => 'border-2 border-white px-6 py-2 rounded-full text-xl cursor-pointer hover:scale-105'),
      array('text' => 'Compra', 'class' => 'bg-white text-[#010A2D] font-bold px-8 py-2 rounded-full text-xl transition-transform hover:scale-105 cursor-pointer')
    )
  ),
  array(
    'layout' => 'simple',
    'url' => $asset_uri . '/images/carousel/5.png',
    'title' => 'Feria Internacional 2026',
    'subtitle' => 'Novedades, lanzamientos y nuestra participación en eventos.',
    'titleFont' => 'font-bold',
    'buttons' => array(
      array('text' => 'Leer más', 'class' => 'bg-white text-[#010A2D] font-bold px-8 py-2 rounded-full text-2xl transition-transform hover:scale-105 cursor-pointer')
    )
  ),
);
?>
<section class="hero-carousel h-screen w-full flex flex-col overflow-hidden">
  <div class="relative flex-1">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="carousel-slide absolute inset-0 transition-all duration-1000 ease-out <?php echo $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105'; ?>">
        <div class="absolute inset-0 bg-center bg-cover"
             style="background-image: linear-gradient(to top, #010A2D, #7594D000 70%), url(<?php echo esc_url($slide['url']); ?>);">
        </div>
      </div>
    <?php endforeach; ?>
    <div class="relative h-full flex items-end text-white mx-20 pb-8 gap-5">
      <div class="flex flex-col gap-y-4 justify-end pb-2">
          <a href="#" aria-label="Instagram" class="p-2 rounded-full hover:scale-110 transition-colors">
            <?php echo get_icon('instagram', 'w-10 h-10 text-white'); ?>
          </a>
          <a href="#" aria-label="Facebook" class="p-2 rounded-full hover:scale-110 transition-colors">
            <?php echo get_icon('facebook', 'w-9 h-9 text-white'); ?>
          </a>

      </div>
      <div class="flex flex-col w-full">
        <?php foreach ($slides as $index => $slide): ?>
          <div class="carousel-content <?php echo $index === 0 ? '' : 'hidden'; ?>" data-index="<?php echo $index; ?>">
            <div class="flex gap-4 <?php echo !empty($slide['buttons']) ? 'mb-5' : 'mb-5'; ?>">
              <div class="flex-1 flex flex-col justify-end items-start">
                <?php if ($slide['layout'] === 'simple'): ?>
                  <!-- DISEÑO SIMPLE (Diapositivas 1 y 5) -->
                  <div class="mb-2">
                    <h2 class="text-5xl md:text-[50px] font-montserrat <?php echo esc_attr($slide['titleFont']); ?>">
                      <?php echo esc_html($slide['title']); ?>
                    </h2>
                  </div>
                  
                  <?php if (!empty($slide['subtitle'])): ?>
                    <p class="text-[22px] font-open mt-1 max-w-3xl">
                      <?php echo esc_html($slide['subtitle']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['buttons'])): ?>
                    <div class="flex gap-4 mt-4 ">
                      <?php foreach ($slide['buttons'] as $button): ?>
                        <button class="<?php echo esc_attr($button['class']); ?>"><?php echo esc_html($button['text']); ?></button>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                <?php else: ?>
                  <!-- DISEÑO DE MARCA (Diapositivas 2, 3 y 4) -->
                  <div class="mb-6">
                    <img src="<?php echo esc_url($slide['title']); ?>" alt="<?php echo esc_attr($slide['subtitle']); ?>" class="object-contain h-auto w-auto" style="max-height:100px;" />
                  </div>
                  
                  <?php if (!empty($slide['description'])): ?>
                    <p class="text-5xl pb-4 mt-3 <?php echo str_starts_with($slide['descriptionFont'], 'font-') ? esc_attr($slide['descriptionFont']) : ''; ?>"
                       style="<?php echo !str_starts_with($slide['descriptionFont'], 'font-') ? 'font-family: \'' . esc_attr($slide['descriptionFont']) . '\';' : ''; ?>">
                      <?php echo esc_html($slide['description']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['subtitle'])): ?>
                    <p class="text-3xl font-open mt-8 mb-2 ">
                      <?php echo esc_html($slide['subtitle']); ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($slide['buttons'])): ?>
                    <div class="flex gap-4 mt-6 ">
                      <?php foreach ($slide['buttons'] as $button): ?>
                        <button class="<?php echo esc_attr($button['class']); ?>"><?php echo esc_html($button['text']); ?></button>
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
