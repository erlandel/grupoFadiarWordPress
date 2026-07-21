<?php
$lat = 23.08525;
$lng = -82.434639;
$zoom = 15;
$map_src = "https://maps.google.com/maps?q={$lat},{$lng}&z={$zoom}&output=embed";
?>

<div class="bg-white  shadow-lg overflow-hidden aspect-6/2">
  <iframe
    src="<?php echo esc_url($map_src); ?>"
    width="100%"
    height="100%"
    style="border:0"
    allowfullscreen
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"
    title="Ubicación de Grupo Fadiar — Ciudad Libertad, Marianao, La Habana, Cuba"
    class="w-full h-full"
  ></iframe>
</div>
