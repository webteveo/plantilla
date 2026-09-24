<?php
/**
 * Grilla de servicios (diseño de cards de la home).
 * $items: [['titulo', 'href', 'img' (relativa a assets/img/), 'wsp' (mensaje), 'btn']]. Por defecto: todos los servicios.
 */
$items = $items ?? null;
if ($items === null) {
    $items = [];
    $zonaSlug = $pagina['zona']['slug'] ?? null;
    foreach (servicios() as $s) {
        if ($zonaSlug && !combinacion_existe($s['slug'], $zonaSlug)) continue;
        if (($pagina['servicio']['slug'] ?? '') === $s['slug'] && $pagina['tipo'] === 'servicio-zona') continue; // no enlazar la página actual
        $z = $zonaSlug ? $pagina['zona'] : null;
        $items[] = [
            'titulo' => $s['nombre_h1'] . ($z ? ' en ' . $z['nombre'] : ''),
            'href'   => url($s['slug'] . ($z ? '/' . $z['slug'] : '')),
            'img'    => $s['imagen'] ? 'servicios/' . $s['imagen'] : '',
            'alt'    => t($s['imagen_alt'] ?: $s['nombre_h1'], vars_de($s, $z)),
            'wsp'    => wsp_mensaje($s, $z),
            'btn'    => ct('servicios_btn', 'Pedir presupuesto'),
            'desc'   => $s['descripcion'],
        ];
    }
}
$titulo = $titulo ?? ct('servicios_titulo', 'Nuestros servicios');
$lead   = $lead ?? ct('servicios_lead');
?>
<?php if ($items): ?>
<section class="servicios" id="servicios" aria-labelledby="servicios-titulo">
  <div class="container">

    <div class="servicios__header">
      <h2 class="servicios__title" id="servicios-titulo"><?= strip_tags($titulo, '<em>') ?></h2>
      <?php if ($lead): ?><p class="servicios__lead"><?= strip_tags($lead, '<a><strong>') ?></p><?php endif; ?>
    </div>

    <div class="servicios__grid">
      <?php foreach ($items as $sv): ?>
      <?php $svImg = !empty($sv['img']) && is_file(BASE_DIR . '/assets/img/' . $sv['img']) ? url('assets/img/' . $sv['img']) : ''; ?>
      <article class="servicio-card" aria-label="<?= e($sv['titulo']) ?>">
        <?php if ($svImg): ?>
        <img src="<?= e($svImg) ?>" alt="<?= e($sv['alt'] ?? $sv['titulo']) ?>" class="servicio-card__bg" width="1200" height="675" loading="lazy" decoding="async">
        <?php endif; ?>
        <div class="servicio-card__body">
          <h3 class="servicio-card__title"><a href="<?= e($sv['href']) ?>"><?= e($sv['titulo']) ?></a></h3>
          <a <?= wsp_attrs($pagina, $sv['wsp']) ?> class="servicio-card__btn">
            <i class="ri-whatsapp-line" aria-hidden="true"></i>
            <?= e($sv['btn']) ?>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="servicios__cta">
      <a <?= wsp_attrs($pagina) ?> class="servicios__cta-btn">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= e(cfg('contacto.cta_label')) ?>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>
