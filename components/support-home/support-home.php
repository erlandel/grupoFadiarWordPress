<?php
$support_items = array(
  array(
    'id' => 1,
    'title' => 'Funcionalidad',
    'description' => 'Nuestras soluciones están diseñadas con un enfoque preciso en la eficiencia y la consecución de resultados tangibles. Aseguramos que cada producto contribuya directamente a la mejora de vida de nuestros clientes.',
    'icon' => 'cog',
  ),
  array(
    'id' => 2,
    'title' => 'Innovación',
    'description' => 'Nuestra cultura empresarial se cimenta en la búsqueda activa de la vanguardia. Invertimos significativamente en I+D para proporcionar soluciones que anticipen las necesidades futuras de nuestros clientes.',
    'icon' => 'brain',
  ),
  array(
    'id' => 3,
    'title' => 'Calidad',
    'description' => 'Implementamos un riguroso control de calidad en cada etapa, desde la concepción hasta la entrega. Esto se traduce en una fiabilidad excepcional, una vida útil prolongada y un rendimiento consistente.',
    'icon' => 'award',
  ),
  array(
    'id' => 4,
    'title' => 'Garantía',
    'description' => 'Respaldamos la excelencia de nuestros productos con una garantía integral. Este compromiso es la manifestación de nuestra confianza y ofrece a nuestros clientes una seguridad absoluta.',
    'icon' => 'badge-check',
  ),
);
?>
<section class="w-full py-16 px-4 bg-white">
  <div class="mx-20">
    <div class="text-center mb-16">
      <h3 class="text-secondary font-semibold text-3xl mb-4">Soporte y Garantía</h3>
      <h2 class="text-3xl md:text-5xl font-black text-dark mb-8">POR QUÉ ESCOGER GRUPO FADIAR</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <?php foreach ($support_items as $item): ?>
        <div class="flex gap-4">
          <?php if ($item['icon'] === 'cog'): ?>
            <svg class="w-18 h-18 text-dark shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 2v2"/><path d="M12 22v-2"/><path d="m17 20.66-1-1.73"/><path d="M11 10.27 7 3.34"/><path d="m20.66 17-1.73-1"/><path d="m3.34 7 1.73 1"/><path d="M14 12h8"/><path d="M2 12h2"/><path d="m20.66 7-1.73 1"/><path d="m6.34 17 1.73-1"/><path d="m17 3.34-1 1.73"/><path d="m11 13.73-4 6.93"/></svg>
          <?php elseif ($item['icon'] === 'brain'): ?>
            <svg class="w-18 h-18 text-dark shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/><path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/><path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"/><path d="M17.599 6.5a3 3 0 0 0 .399-1.375"/><path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"/><path d="M3.477 10.896a4 4 0 0 1 .585-.396"/><path d="M19.938 10.5a4 4 0 0 1 .585.396"/><path d="M6 18a4 4 0 0 1-1.967-.516"/><path d="M19.967 17.484A4 4 0 0 1 18 18"/></svg>
          <?php elseif ($item['icon'] === 'award'): ?>
            <svg class="w-18 h-18 text-dark shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
          <?php elseif ($item['icon'] === 'badge-check'): ?>
            <svg class="w-18 h-18 text-dark shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
          <?php endif; ?>
          <div class="flex flex-col gap-3 mb-4 mt-4">
            <h3 class="text-3xl font-bold text-dark"><?php echo esc_html($item['title']); ?></h3>
            <p class="text-gray-700 text-xl leading-relaxed"><?php echo esc_html($item['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
