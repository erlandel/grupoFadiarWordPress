<?php
$faq_items = array(
  array(
    'question' => '¿Cómo activo la garantía de mi producto?',
    'answer' => 'Debe registrar su producto en nuestro sitio web dentro de los 30 días posteriores a la compra, completando el formulario de registro de garantía con los datos de la factura y el número de serie del producto.',
  ),
  array(
    'question' => '¿Qué cubre la garantía?',
    'answer' => 'La garantía cubre defectos de fabricación, fallos de materiales o problemas de funcionamiento no atribuibles al uso incorrecto. No cubre daños por golpes, caídas, instalación inadecuada, uso de accesorios no originales o desgaste normal por uso.',
  ),
  array(
    'question' => '¿Dónde puedo llevar mi producto a reparar?',
    'answer' => 'Puede acudir a nuestros centros de servicio autorizados, cuyas direcciones encontrará en el localizador de tiendas. También puede contactar a nuestro servicio técnico para coordinar la recogida del producto.',
  ),
  array(
    'question' => '¿Cuánto tiempo tarda una reparación?',
    'answer' => 'El plazo estándar de reparación es de 10 a 15 días hábiles, dependiendo de la disponibilidad de piezas. Para productos en garantía, el tiempo comienza a contar desde la recepción del producto en nuestro taller.',
  ),
  array(
    'question' => '¿Qué hago si perdí mi factura?',
    'answer' => 'Puede solicitar un duplicado en el establecimiento donde realizó la compra. También puede presentar el extracto bancario o comprobante de pago electrónico si pagó con tarjeta o transferencia.',
  ),
);
?>
<div class="px-15">
  <h2 class="text-5xl font-bold mb-6 text-dark">Preguntas frecuentes</h2>
  <div class="flex flex-col gap-4 mt-15">
    <?php foreach ($faq_items as $index => $item): ?>
      <div class="faq-item bg-[#F4F4F4] border-l-8 border-l-dark text-dark">
        <div class="faq-question p-10 cursor-pointer">
          <div class="w-full text-4xl flex justify-between items-center text-left font-normal">
            <span><?php echo esc_html($item['question']); ?></span>
            <svg class="faq-chevron h-10 w-10 shrink-0 transition-transform" stroke-width="3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>
        <div class="faq-answer hidden px-10 pb-10 text-2xl"><?php echo esc_html($item['answer']); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
