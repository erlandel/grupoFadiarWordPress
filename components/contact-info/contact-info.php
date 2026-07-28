<?php
$address_label   = gf_get_option('contact_address_label', 'Direcciones:', 'Addresses:');
$main_address    = gf_get_option('contact_main_address', 'Calle 29F entre 114 y 114A, Edificio 11413, Almacén 9A (ENAME). Ciudad Libertad, Marianao, La Habana, Cuba.', 'Calle 29F between 114 and 114A, Building 11413, Warehouse 9A (ENAME). Ciudad Libertad, Marianao, Havana, Cuba.');
$schedule_label  = gf_get_option('contact_schedule_label', 'Horario:', 'Schedule:');
$schedule_value  = gf_get_option('contact_schedule_value', 'Lun-Vie 9:00 – 17:00.', 'Mon–Fri 9:00 – 17:00.');
?>
<div class="text-xl text-dark">
  <p><span class="font-bold"><?php echo esc_html($address_label); ?></span></p>
  <p><span class="font-bold">Sede central:</span> <?php echo esc_html($main_address); ?></p>
  <p class="mt-4"><span class="font-bold"><?php echo esc_html($schedule_label); ?></span> <?php echo esc_html($schedule_value); ?></p>
</div>
