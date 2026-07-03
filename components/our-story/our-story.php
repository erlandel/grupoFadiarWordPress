<section class="mt-30 w-full bg-[#F4F4F4]">
  <div class="w-full p-20">
    <div class="flex justify-around items-start gap-20">
      <div class="w-1/2 text-xl">
        <h2 class="text-5xl font-black">Nuestra historia</h2>
        <p class="mt-6">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
        <p class="mt-4">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
      </div>
      <div class="flex flex-col items-center gap-4 w-1/2">
        <?php
        get_template_part('components/accordion-item/accordion-item', null, array(
          'title' => 'Nuestra Misión',
          'content' => 'Proporcionar soluciones innovadoras que mejoren la calidad de vida de las familias cubanas, con productos duraderos, eficientes y accesibles.',
        ));
        get_template_part('components/accordion-item/accordion-item', null, array(
          'title' => 'Nuestra Visión',
          'content' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso social.',
        ));
        get_template_part('components/accordion-item/accordion-item', null, array(
          'title' => 'Nuestros valores',
          'content' => array(
            '• Compromiso: con nuestros clientes, trabajadores y el país.',
            '• Innovación: mejora continua en productos y procesos.',
            '• Calidad: excelencia en cada detalle.',
            '• Responsabilidad: social y medioambiental.',
            '• Trabajo en equipo: colaboración para crecer juntos.',
          ),
        ));
        get_template_part('components/accordion-item/accordion-item', null, array(
          'title' => 'Liderazgo',
          'content' => '',
          'leaders' => array(
            array(
              'name' => 'Idián Chávez Fernández',
              'image' => get_template_directory_uri() . '/assets/images/about/lider1.png',
              'shortDescription' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso.',
              'fullDescription' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso social.',
            ),
            array(
              'name' => 'José Osmani Castillo Mató',
              'image' => get_template_directory_uri() . '/assets/images/about/lider1.png',
              'shortDescription' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso.',
              'fullDescription' => 'Ser el grupo empresarial líder en Cuba en soluciones para el hogar y la industria, reconocido por nuestra calidad, innovación y compromiso social.',
            ),
          ),
        ));
        ?>
      </div>
    </div>
  </div>
</section>
