<?php
$address_label   = get_option('contact_address_label', 'Direcciones:');
$main_address    = get_option('contact_main_address', 'Calle 29F entre 114 y 114A, Edificio 11413, Almacén 9A (ENAME). Ciudad Libertad, Marianao, La Habana, Cuba.');
$schedule_label  = get_option('contact_schedule_label', 'Horario:');
$schedule_value  = get_option('contact_schedule_value', 'Lun-Vie 9:00 – 17:00.');
?>
<div class="text-3xl text-dark">
  <p><span class="font-bold"><?php echo esc_html($address_label); ?></span></p>
  <p><span class="font-bold">Sede central:</span> <?php echo esc_html($main_address); ?></p>
  <p class="mt-6"><span class="font-bold"><?php echo esc_html($schedule_label); ?></span> <?php echo esc_html($schedule_value); ?></p>
</div>
