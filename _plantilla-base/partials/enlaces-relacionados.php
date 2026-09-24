<?php
/** Enlaces relacionados (diseño relacionados). $columnas: [['titulo', 'items' => [['href','label']]]]. Por defecto según el tipo de página. */
$columnas = $columnas ?? null;
if ($columnas === null) {
    $L = $pagina['links'] ?? [];
    $columnas = [];
    if ($pagina['tipo'] === 'servicio-zona') {
        if (!empty($L['otros']))   $columnas[] = ['titulo' => ct('relacionados_otros', 'Más servicios en {zona}'), 'items' => $L['otros']];
        $cerca = array_merge($L['hijas'] ?? [], $L['vecinos'] ?? [], $L['padre'] ?? []);
        if ($cerca) $columnas[] = ['titulo' => ct('relacionados_cerca', 'Cerca de {zona}'), 'items' => $cerca];
    } elseif ($pagina['tipo'] === 'zona') {
        $cerca = array_merge($L['vecinos'] ?? [], $L['padre'] ?? []);
        if ($cerca) $columnas[] = ['titulo' => ct('relacionados_cerca', 'Cerca de {zona}'), 'items' => $cerca];
    } elseif ($pagina['tipo'] === 'servicio') {
        if (!empty($L['otros'])) $columnas[] = ['titulo' => 'Otros servicios de ' . cfg('marca.nombre'), 'items' => $L['otros']];
    }
}
?>
<?php if ($columnas): ?>
<section class="relacionados" aria-label="Enlaces relacionados">
  <div class="container relacionados__inner">
    <?php foreach ($columnas as $col): ?>
    <div class="relacionados__col">
      <h2 class="relacionados__title"><?= e($col['titulo']) ?></h2>
      <ul class="relacionados__list" role="list">
        <?php foreach ($col['items'] as $it): ?>
        <li><a href="<?= e($it['href']) ?>"><?= e($it['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
