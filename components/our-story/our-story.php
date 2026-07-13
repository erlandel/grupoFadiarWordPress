<section class="mt-30 w-full bg-[#F4F4F4]">
  <div class="w-full p-20">
    <div class="flex justify-around items-start gap-20">
      <div class="w-1/2 text-xl">
        <h2 class="text-5xl font-black">Nuestra historia</h2>
        <p class="mt-6">Grupo Fadiar nació en 2023 con la visión de transformar la industria nacional. Partiendo  de un pequeño taller, hemos crecido hasta convertirnos en un grupo empresarial que  integra tres marcas referentes.</p>
        <p class="mt-4">Nuestros hitos incluyen la apertura de nuestras  instalaciones en Ciudad Libertad, el lanzamiento de nuestras primeras líneas de  productos y las alianzas con distribuidores en todo el país e internacionales. Hoy,  seguimos construyendo el futuro con pasión y responsabilidad.</p>
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
          'content' => 'En Grupo Fadiar, la gobernanza se ejerce con transparencia, visión  estratégica y un firme compromiso con la ética. Nuestro equipo directivo, liderado por el  Director General, trabaja para alinear la innovación con los valores corporativos,  asegurando que cada decisión contribuya al desarrollo sostenible y al bienestar de  nuestros trabajadores y clientes.',
          'leaders' => array(
            array(
              'name' => 'Idián Chávez Fernández',
              'image' => get_template_directory_uri() . '/assets/images/about/lider1.png',
              'shortDescription' => 'Director General de Grupo Fadiar / Socio  Visionario cubano que impulsa la innovación, la eficiencia y la comunicación estratégica  en sectores clave. ',
              'fullDescription' => 'Defiende el liderazgo con propósito y compromiso social. A sus 32 años, encarna el espíritu de una nueva generación de líderes empresariales en Cuba: audaces, estratégicos y profundamente comprometidos con la transformación. Es fundador de Light Vision Agencia Creativa, donde fue CEO durante 6 años.',
            ),
          ),
        ));
        ?>
      </div>
    </div>
  </div>
</section>
