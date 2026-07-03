<?php
$asset_uri = get_template_directory_uri() . '/assets';
$slides = array(
  array(
    'url' => $asset_uri . '/images/carousel/1.png',
    'title' => 'BIENVENIDO',
    'description' => 'Somos un grupo empresarial que mejora la experiencia en el hogar y la industria con soluciones definidas por su calidad e innovación.',
    'titleFont' => 'font-bold',
    'showButtons' => false,
    'descriptionFont' => 'font-open',
    'titleIsImage' => false,
  ),
  array(
    'url' => $asset_uri . '/images/carousel/2.png',
    'title' => $asset_uri . '/images/brands/eon.png',
    'description' => 'Sientete Familiar',
    'titleFont' => 'font-montserrat',
    'descriptionFont' => 'Flatlion Personal Use Only',
    'titleIsImage' => true,
  ),
  array(
    'url' => $asset_uri . '/images/carousel/3.png',
    'title' => $asset_uri . '/images/brands/lammina.png',
    'description' => 'Lo mejor para tu hogar',
    'titleFont' => 'font-montserrat',
    'descriptionFont' => 'Flatlion Personal Use Only',
    'titleIsImage' => true,
  ),
  array(
    'url' => $asset_uri . '/images/carousel/4.png',
    'title' => $asset_uri . '/images/brands/vital.png',
    'description' => 'Placer para todos',
    'titleFont' => 'font-vital',
    'descriptionFont' => 'Flatlion Personal Use Only',
    'titleIsImage' => true,
  ),
);
?>
<section class="hero-carousel h-screen w-full flex flex-col overflow-hidden">
  <div class="relative flex-1">
    <?php foreach ($slides as $index => $slide): ?>
      <div class="carousel-slide absolute inset-0 transition-all duration-1000 ease-out <?php echo $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105'; ?>">
        <div class="absolute inset-0 bg-center bg-cover"
             style="background-image: linear-gradient(to top, #010A2D, #7594D000 50%), url(<?php echo esc_url($slide['url']); ?>);">
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
            <div class="flex gap-4 <?php echo (($slide['showButtons'] ?? true) !== false) ? 'mb-10' : 'mb-5'; ?>">
              <div class="flex-1 flex flex-col justify-end">
                <div class="h-22 flex items-center">
                  <?php if ($slide['titleIsImage']): ?>
                    <img src="<?php echo esc_url($slide['title']); ?>" alt="<?php echo esc_attr($slide['description']); ?>" class="object-contain h-full w-auto" style="max-height:100px;" />
                  <?php else: ?>
                    <h2 class="text-4xl md:text-[70px] font-montserrat <?php echo esc_attr($slide['titleFont']); ?>">
                      <?php echo esc_html($slide['title']); ?>
                    </h2>
                  <?php endif; ?>
                </div>
                <div class="flex items-end gap-8">
                  <?php if (($slide['showButtons'] ?? true) !== false): ?>
                    <div class="space-x-4 shrink-0 mt-8">
                       <button class="border-3 px-6 py-3 rounded-4xl text-3xl cursor-pointer hover:scale-105">Productos</button>
                      <button class="bg-white text-dark font-bold px-8 py-4 rounded-full text-3xl transition-transform hover:scale-105 cursor-pointer">Compra</button>
                    </div>
                  <?php endif; ?>
                  <p class="<?php echo (($slide['showButtons'] ?? true) !== false) ? 'text-5xl pb-3' : 'text-[22px] max-w-200 mt-2'; ?> <?php echo str_starts_with($slide['descriptionFont'], 'font-') ? esc_attr($slide['descriptionFont']) : ''; ?>"
                     style="<?php echo !str_starts_with($slide['descriptionFont'], 'font-') ? 'font-family: \'' . esc_attr($slide['descriptionFont']) . '\';' : ''; ?>">
                    <?php echo esc_html($slide['description']); ?>
                  </p>
                </div>
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
