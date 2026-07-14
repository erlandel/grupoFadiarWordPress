<?php
$pillars = array(
  array(
    'id' => 1,
    'title' => 'RESPONSABILIDAD SOCIAL',
    'subtitle' => 'Compromiso social y proyectos comunitarios',
    'imagePosition' => 'right',
    'rightLayout' => 'one-plus-two',
    'intro' => 'En Grupo Fadiar, creemos que el desarrollo empresarial y el progreso de nuestra comunidad deben ir de la mano. Por eso, impulsamos y participamos en proyectos que generan un impacto positivo.',
    'images' => array(
      'https://via.placeholder.com/400x300/cccccc/ffffff?text=Image+1',
      'https://via.placeholder.com/400x200/cccccc/ffffff?text=Image+2',
      'https://via.placeholder.com/400x200/cccccc/ffffff?text=Image+3',
    ),
    'items' => array(
      array(
        'title' => 'Programa "Alma" por Fadiar',
        'text' => 'Nuestra iniciativa bandera de apoyo a proyectos comunitarios. A través de donaciones de productos, voluntariado corporativo y financiamiento de pequeñas iniciativas locales, buscamos ser un agente de cambio allá donde operamos.',
        'marker' => null,
      ),
      array(
        'title' => 'Colaboración con Festival PaCuba',
        'text' => 'Apoyamos la cultura y el deporte cubano. Recientemente, aportamos un kit solar que ayudó a mantener el evento PaCuba con energía estable y sostenible.',
        'marker' => null,
      ),
      array(
        'title' => 'Comunidades Sostenibles',
        'text' => 'Descripción:Trabajamos con gobiernos municipales y organizaciones de la sociedad civil para desarrollar proyectos de agricultura urbana, sistemas de reciclaje y programas de eficiencia energética en comunidades vulnerables.',
        'marker' => null,
      ),
    ),
  ),
  array(
    'id' => 2,
    'title' => 'ESTRATEGIA EMPRESARIAL',
    'subtitle' => null,
    'imagePosition' => 'left',
    'rightLayout' => 'single',
    'intro' => 'Nuestra estrategia se asienta sobre tres pilares fundamentales:',
    'image' => 'https://via.placeholder.com/800x600/cccccc/ffffff?text=Single+Image',
    'items' => array(
      array(
        'title' => 'Expansión de mercado',
        'text' => '-llegar a más clientes en todo el territorio nacional.',
        'marker' => 'number',
      ),
      array(
        'title' => 'Innovación tecnológica',
        'text' => '-desarrollo de productos más eficientes y conectados.',
        'marker' => 'number',
      ),
      array(
        'title' => 'Sostenibilidad',
        'text' => '-reducción de la huella ambiental y apoyo a la economía circular. Estos pilares nos guían en la toma de decisiones y en la asignación de recursos.',
        'marker' => 'number',
      ),
    ),
  ),
  array(
    'id' => 3,
    'title' => 'I+D+i',
    'subtitle' => null,
    'imagePosition' => 'right',
    'rightLayout' => 'single',
    'intro' => 'Invertimos en I+D+i para estar a la vanguardia. Actualmente trabajamos en:',
    'image' => 'https://via.placeholder.com/800x600/cccccc/ffffff?text=Single+Image',
    'items' => array(
      array(
        'title' => 'Electrodomésticos eficientes:',
        'text' => 'reducción del consumo energético hasta un 30% con motores inverter.',
        'marker' => 'bullet',
      ),
      array(
        'title' => 'Materiales sostenibles:',
        'text' => 'uso de bioplásticos y maderas certificadas.',
        'marker' => 'bullet',
      ),
      array(
        'title' => 'Iluminación inteligente:',
        'text' => 'sistemas controlados por voz y aplicación móvil.',
        'marker' => 'bullet',
      ),
    ),
  ),
);

function render_pillar_text($pillar) {
  if (!empty($pillar['subtitle'])) {
    echo '<p class="text-dark text-3xl font-bold leading-relaxed">' . esc_html($pillar['subtitle']) . '</p>';
  }
  if (!empty($pillar['intro'])) {
    echo '<p class="text-dark text-xl leading-relaxed mb-6">' . esc_html($pillar['intro']) . '</p>';
  }
  $number_index = 1;
  foreach ($pillar['items'] as $item) {
    $marker = isset($item['marker']) ? $item['marker'] : null;

    if ($pillar['id'] === 1) {
      echo '<p class="text-dark text-xl mb-0">' . esc_html($item['title']) . '</p>';
      echo '<p class="text-dark text-xl leading-relaxed">' . esc_html($item['text']) . '</p>';
      continue;
    }

    $title = '<strong>' . esc_html($item['title']) . '</strong>';
    $text  = esc_html($item['text']);

    if ($marker === 'bullet') {
      echo '<div class="flex gap-3 mb-2">';
      echo '<span class="text-dark text-xl shrink-0">•</span>';
      echo '<span class="text-dark text-xl leading-relaxed">' . $title . $text . '</span>';
      echo '</div>';
    } elseif ($marker === 'number') {
      echo '<div class="flex gap-3 mb-2">';
      echo '<span class="text-dark text-xl font-black shrink-0">' . $number_index . '.</span>';
      echo '<span class="text-dark text-xl leading-relaxed">' . $title . $text . '</span>';
      echo '</div>';
      $number_index++;
    } else {
      echo '<p class="text-dark text-xl leading-relaxed mb-2">' . $title . $text . '</p>';
    }
  }
}

function render_pillar_image($pillar) {
  if (!empty($pillar['rightLayout']) && $pillar['rightLayout'] === 'one-plus-two') {
    echo '<div class="flex flex-col gap-4 h-full">';
    echo '<div class="bg-[#F4F4F4] w-full" style="flex: 1 1 0;"></div>';
    echo '<div class="flex gap-4" style="flex: 1 1 0;">';
    echo '<div class="bg-[#F4F4F4] w-full"></div>';
    echo '<div class="bg-[#F4F4F4] w-full"></div>';
    echo '</div>';
    echo '</div>';
  } else {
    echo '<div class="bg-[#F4F4F4] w-full"></div>';
  }
}
?>
<section class="w-full my-20 px-20 space-y-10">
  <?php foreach ($pillars as $pillar): ?>
    <div class="grid grid-cols-2 gap-10 items-stretch  text-justify <?php echo $pillar['imagePosition'] === 'left' ? 'direction-rtl' : ''; ?>">
      <?php if ($pillar['imagePosition'] === 'left'): ?>
        <?php render_pillar_image($pillar); ?>
        <div class="flex flex-col justify-between" dir="ltr">
          <div class="space-y-4">
            <h2 class="text-dark text-[44px] font-black tracking-tight uppercase leading-tight"><?php echo esc_html($pillar['title']); ?></h2>
            <?php render_pillar_text($pillar); ?>
          </div>
          <div class="mt-8 w-full border-2 border-dark"></div>
        </div>
      <?php else: ?>
        <div class="flex flex-col justify-between">
          <div class="space-y-4">
            <h2 class="text-dark text-[44px] font-black tracking-tight uppercase leading-tight"><?php echo esc_html($pillar['title']); ?></h2>
            <?php render_pillar_text($pillar); ?>
          </div>
          <div class="mt-8 w-full border-2 border-dark"></div>
        </div>
        <?php render_pillar_image($pillar); ?>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</section>
