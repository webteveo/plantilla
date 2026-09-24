<!-- Enlaces relacionados (solo landings): otros servicios en el barrio, barrios cercanos y landing general -->
<?php
/** Requiere: $lp_servicio (slug), $lp_servicio_nombre, $lp_zona_slug, $lp_zona (nombre), $lp_cerca (string "A, B y C") */
$rlServicios = Local_Controller::servicios();
$rlZonas     = Local_Controller::zonasTodas();

// Otros servicios en este barrio
$rlOtros = [];
foreach ($rlServicios as $rlK => $rlN) {
    if ($rlK === $lp_servicio) continue;
    $rlOtros[] = ['href' => $url . 'local/' . $rlK . '-' . $lp_zona_slug, 'label' => $rlN . ' en ' . $lp_zona];
}

// Barrios cercanos con el mismo servicio (a partir del campo 'cerca')
$rlPorNombre = [];
foreach ($rlZonas as $rlK => $rlZ) $rlPorNombre[mb_strtolower($rlZ['nombre'])] = $rlK;
$rlCercanos = [];
foreach (preg_split('/,\s*|\s+y\s+/u', $lp_cerca ?? '') as $rlNombre) {
    $rlNombre = trim($rlNombre);
    $rlSlug = $rlPorNombre[mb_strtolower($rlNombre)] ?? null;
    if ($rlSlug && $rlSlug !== $lp_zona_slug) $rlCercanos[] = ['href' => $url . 'local/' . $lp_servicio . '-' . $rlSlug, 'label' => $lp_servicio_nombre . ' en ' . $rlZonas[$rlSlug]['nombre']];
}
if ($lp_zona_slug !== 'montevideo') {
    $rlCercanos[] = ['href' => $url . 'local/' . $lp_servicio . '-montevideo', 'label' => $lp_servicio_nombre . ' en Montevideo'];
}
?>
<section class="relacionados" aria-label="Enlaces relacionados">
  <div class="container relacionados__inner">
    <div class="relacionados__col">
      <h2 class="relacionados__title">Más servicios en <?= htmlspecialchars($lp_zona) ?></h2>
      <ul class="relacionados__list" role="list">
        <?php foreach ($rlOtros as $rl): ?>
        <li><a href="<?= htmlspecialchars($rl['href']) ?>"><?= htmlspecialchars($rl['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php if ($rlCercanos): ?>
    <div class="relacionados__col">
      <h2 class="relacionados__title">Cerca de <?= htmlspecialchars($lp_zona) ?></h2>
      <ul class="relacionados__list" role="list">
        <?php foreach ($rlCercanos as $rl): ?>
        <li><a href="<?= htmlspecialchars($rl['href']) ?>"><?= htmlspecialchars($rl['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
  </div>
</section>
