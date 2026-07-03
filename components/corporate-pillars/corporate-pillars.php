<?php
$pillars = array(
  array(
    'id' => 1,
    'title' => 'RESPONSABILIDAD SOCIAL',
    'description' => 'Proporcionar soluciones innovadoras que mejoren la calidad de vida de las familias cubanas, con productos duraderos, eficientes y accesibles.',
    'imageLeft' => false,
  ),
  array(
    'id' => 2,
    'title' => 'ESTRATEGIA',
    'description' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso social.',
    'imageLeft' => true,
  ),
  array(
    'id' => 3,
    'title' => 'I+D+i',
    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit.',
    'imageLeft' => false,
  ),
);
?>
<section class="w-full my-20 px-20 space-y-10">
  <?php foreach ($pillars as $pillar): ?>
    <div class="grid grid-cols-2 gap-10 items-stretch <?php echo $pillar['imageLeft'] ? 'direction-rtl' : ''; ?>">
      <?php if ($pillar['imageLeft']): ?>
        <div class="bg-[#F4F4F4] min-h-56 w-full"></div>
      <?php endif; ?>
      <div class="flex flex-col justify-between">
        <div class="space-y-4">
          <h2 class="text-dark text-[45px] font-black tracking-tight uppercase leading-tight"><?php echo esc_html($pillar['title']); ?></h2>
          <p class="text-dark mt-10 text-xl leading-relaxed"><?php echo esc_html($pillar['description']); ?></p>
        </div>
        <div class="mt-8 w-full border-2 border-dark"></div>
      </div>
      <?php if (!$pillar['imageLeft']): ?>
        <div class="bg-[#F4F4F4] min-h-64 w-full"></div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</section>
