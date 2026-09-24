<?php
/** "Por qué elegirnos". Usa los beneficios del servicio si la página tiene servicio y beneficios; si no, comun.diferenciadores. */
$items = [];
if (!empty($pagina['beneficios'])) {
    $iconos = ['ri-price-tag-3-line', 'ri-map-pin-line', 'ri-shield-check-line', 'ri-time-line'];
    foreach ($pagina['beneficios'] as $i => $b) $items[] = ['icono' => $iconos[$i % 4], 'titulo' => $b['titulo'], 'texto' => $b['texto']];
} else {
    foreach ((array)($pagina['comun']['diferenciadores'] ?? []) as $d) $items[] = ['icono' => $d['icono'] ?? 'ri-check-line', 'titulo' => t($d['titulo'], $pagina['vars']), 'texto' => t($d['texto'], $pagina['vars']), 'href' => $d['href'] ?? ''];
}
?>
<?php if ($items): ?>
<section class="diferenciadores" id="por-que-nosotros" aria-labelledby="dif-titulo">
  <div class="container">
    <div class="diferenciadores__header">
      <h2 class="diferenciadores__title" id="dif-titulo"><?= e(ct('diferenciadores_titulo', 'Por qué elegirnos')) ?></h2>
      <?php if (ct('diferenciadores_lead')): ?><p class="diferenciadores__lead"><?= e(ct('diferenciadores_lead')) ?></p><?php endif; ?>
    </div>

    <div class="diferenciadores__grid">
      <?php foreach ($items as $d): ?>
      <article class="dif-item">
        <div class="dif-item__icon" aria-hidden="true"><i class="<?= e($d['icono']) ?>"></i></div>
        <h3 class="dif-item__title"><?php if (!empty($d['href'])): ?><a href="<?= e(url($d['href'])) ?>"><?= e($d['titulo']) ?></a><?php else: ?><?= e($d['titulo']) ?><?php endif; ?></h3>
        <p class="dif-item__desc"><?= e($d['texto']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
