<!-- Pasos Section -->
<?php
$pasos_items = [
    ['icono' => 'ri-whatsapp-line',    'titulo' => 'Llamá o escribí',       'desc' => 'Atendemos las 24 horas. Contanos qué pasó y en qué barrio estás. Una foto de la cerradura ayuda.'],
    ['icono' => 'ri-money-dollar-circle-line', 'titulo' => 'Presupuesto al instante', 'desc' => 'Te decimos el precio y el tiempo de llegada antes de salir. Sin sorpresas ni cargos ocultos.'],
    ['icono' => 'ri-key-2-line',       'titulo' => 'Abrimos o resolvemos',  'desc' => 'Llegamos a tu puerta, hacemos el trabajo y probamos que todo cierre y abra bien antes de irnos.'],
];
?>
<section class="pasos" aria-label="Como trabajamos">
  <div class="container">

    <div class="pasos__header">
      <h2 class="pasos__title">Cómo trabajamos</h2>
      <p class="pasos__lead">Tres pasos, sin vueltas. Desde que escribís hasta que tu puerta queda funcionando.</p>
    </div>

    <ol class="pasos__list" role="list">
      <?php foreach ($pasos_items as $i => $paso): ?>
      <li class="pasos__item">
        <div class="pasos__icon">
          <i class="<?= htmlspecialchars($paso['icono']) ?>" aria-hidden="true"></i>
        </div>
        <div class="pasos__content">
          <h3 class="pasos__step-title"><?= htmlspecialchars($paso['titulo']) ?></h3>
          <p class="pasos__desc"><?= htmlspecialchars($paso['desc']) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>

    <div class="pasos__cta">
      <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="pasos__cta-btn" target="_blank" rel="noopener">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
      </a>
    </div>

  </div>
</section>
