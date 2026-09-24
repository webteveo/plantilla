<!-- Zonas (bloque compacto de la home): barrios de Montevideo con landing propia -->
<?php
require_once 'src/controlador/Local_Controller.php';
$zhServicio = $zhServicio ?? (array_key_first(Local_Controller::CITY_SERVICES) ?: 'cerrajero');
$zhBarrios = [];
foreach (Local_Controller::ciudades() as $zhK => $zhC) {
    if (($zhC['depto'] ?? '') === 'Montevideo') $zhBarrios[$zhK] = $zhC['nombre'];
}
asort($zhBarrios);
?>
<section class="zonas-home" id="zonas" aria-labelledby="zonas-home-title">
  <div class="container">
    <div class="zonas-home__header">
      <h2 class="zonas-home__title" id="zonas-home-title"><?= htmlspecialchars($zhTitulo ?? 'Cerrajero en todos los barrios de Montevideo') ?></h2>
      <p class="zonas-home__lead">De Carrasco a Paso de la Arena y del Centro a Colón. Elegí tu barrio.</p>
    </div>

    <ul class="zonas-home__chips" role="list">
      <?php foreach ($zhBarrios as $zhK => $zhN): ?>
      <li><a href="<?= $url ?>local/<?= $zhServicio ?>-<?= $zhK ?>" class="zonas-home__chip"><i class="ri-map-pin-2-line" aria-hidden="true"></i><?= htmlspecialchars($zhN) ?></a></li>
      <?php endforeach; ?>
    </ul>

    <p class="zonas-home__otras"><a href="<?= $url ?>paginas/zonas">Ver todos los barrios de Montevideo</a></p>
  </div>
</section>
