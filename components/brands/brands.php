<?php
$brands = array(
  array(
    'productImage' => get_template_directory_uri() . '/assets/images/products/olla.png',
    'productAlt' => 'Olla Eon',
    'brandImage' => get_template_directory_uri() . '/assets/images/brands/eon.png',
    'brandAlt' => 'Logo Eon',
    'brandWidth' => 96,
    'brandHeight' => 40,
    'description' => 'Soluciones innovadoras para el hogar con la máxima eficiencia y durabilidad.',
  ),
  array(
    'productImage' => get_template_directory_uri() . '/assets/images/products/mueble.png',
    'productAlt' => 'Mueble Lammina',
    'brandImage' => get_template_directory_uri() . '/assets/images/brands/lammina.png',
    'brandAlt' => 'Logo Lammina',
    'brandWidth' => 170,
    'brandHeight' => 80,
    'description' => 'Diseño y calidad superior en mobiliario para transformar tus espacios.',
  ),
  array(
    'productImage' => get_template_directory_uri() . '/assets/images/products/papel.png',
    'productAlt' => 'Papel Vital',
    'brandImage' => get_template_directory_uri() . '/assets/images/brands/vital.png',
    'brandAlt' => 'Logo Vital',
    'brandWidth' => 96,
    'brandHeight' => 40,
    'description' => 'Suavidad y alta absorción / Producción sostenible / Empaque reciclable',
  ),
);
?>
<section class="w-full bg-linear-to-br from-[#1D3D75] via-dark to-dark py-16">
  <div class="flex items-center justify-center text-white font-bold">
    <div>
      <h2 class="text-secondary text-center text-3xl">Nuestras marcas</h2>
      <h3 class="text-5xl mt-4">"Diversidad de soluciones, un solo compromiso"</h3>
    </div>
  </div>
  <div class="flex flex-wrap justify-center items-center gap-8 mt-20">
    <?php foreach ($brands as $brand): ?>
      <?php get_template_part('components/brands/card-product', null, $brand); ?>
    <?php endforeach; ?>
  </div>
</section>
