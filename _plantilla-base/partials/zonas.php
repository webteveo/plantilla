<?php
/**
 * Chips de zonas (diseño zonas-home). $items: [['href','label']]. $titulo, $lead, $mas (['href','label']) opcionales.
 * Por defecto (home): zonas raíz; si hay una sola raíz, sus hijas. Enlaza al hub de zona o al servicio principal en la zona.
 */
$items = $items ?? null;
if ($items === null) {
    $items = [];
    $raiz = array_filter(zonas_raiz(), fn($z) => zona_servicios($z['slug']));
    $lista = count($raiz) === 1 ? (zonas_hijas(reset($raiz)['slug']) ?: $raiz) : $raiz;
    foreach ($lista as $z) $items[] = ['href' => url($z['slug']), 'label' => $z['nombre']];
    if (count($raiz) === 1 && zonas_hijas(reset($raiz)['slug'])) $mas = $mas ?? ['href' => url(reset($raiz)['slug']), 'label' => 'Ver todas las zonas de ' . reset($raiz)['nombre']];
}
$titulo = $titulo ?? ct('zonas_titulo', 'Zonas donde trabajamos');
$lead   = $lead ?? ct('zonas_lead');
$mas    = $mas ?? null;
?>
<?php if ($items): ?>
<section class="zonas-home" id="zonas" aria-labelledby="zonas-home-title">
  <div class="container">
    <div class="zonas-home__header">
      <h2 class="zonas-home__title" id="zonas-home-title"><?= e($titulo) ?></h2>
      <?php if ($lead): ?><p class="zonas-home__lead"><?= e($lead) ?></p><?php endif; ?>
    </div>

    <ul class="zonas-home__chips" role="list">
      <?php foreach ($items as $it): ?>
      <li><a href="<?= e($it['href']) ?>" class="zonas-home__chip"><i class="ri-map-pin-2-line" aria-hidden="true"></i><?= e($it['label']) ?></a></li>
      <?php endforeach; ?>
    </ul>

    <?php if ($mas): ?><p class="zonas-home__otras"><a href="<?= e($mas['href']) ?>"><?= e($mas['label']) ?></a></p><?php endif; ?>
  </div>
</section>
<?php endif; ?>
