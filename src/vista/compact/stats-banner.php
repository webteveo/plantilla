<!-- Stats Banner Section (separador con contador animado) -->
<?php
/**
 * Cifras de la empresa. COMPLETAR con datos reales. Vacio = la seccion no se muestra.
 * Cada item: n (numero entero para el contador), sup ('+', '%'), label.
 */
$stats_banner = [
    // ['n' => 500, 'sup' => '+', 'label' => 'Servicios realizados'],
    // ['n' => 10,  'sup' => '+', 'label' => 'Años de experiencia'],
];
$stats_banner_img = null; // relativa a public/images/, ej. 'hero/stats.webp'
?>
<?php if ($stats_banner): ?>
<section class="stats-banner" aria-label="<?= htmlspecialchars(EMPRESA_NOMBRE) ?> en cifras">

  <div class="stats-banner__bg" style="background:#111;">
    <?php if ($stats_banner_img): ?>
    <img src="<?= $ruta ?>/images/<?= htmlspecialchars($stats_banner_img) ?>"
         alt=""
         class="stats-banner__img"
         loading="lazy"
         decoding="async"
         aria-hidden="true">
    <?php endif; ?>
    <div class="stats-banner__overlay"></div>
  </div>

  <div class="container stats-banner__inner">
    <?php foreach ($stats_banner as $i => $st): ?>
      <?php if ($i > 0): ?><div class="stats-banner__divider" aria-hidden="true"></div><?php endif; ?>
      <div class="stats-banner__item">
        <div class="stats-banner__num">
          <span class="stats-banner__count" data-count-to="<?= (int)$st['n'] ?>">0</span><sup><?= htmlspecialchars($st['sup'] ?? '') ?></sup>
        </div>
        <span class="stats-banner__label"><?= htmlspecialchars($st['label']) ?></span>
      </div>
    <?php endforeach; ?>

    <div class="stats-banner__divider" aria-hidden="true"></div>
    <div class="stats-banner__item stats-banner__item--zone">
      <i class="ri-map-pin-fill stats-banner__pin" aria-hidden="true"></i>
      <span class="stats-banner__label stats-banner__label--zone">
        <?= htmlspecialchars(DIRECCION_COMPLETA) ?>
      </span>
    </div>
  </div>
</section>
<?php endif; ?>
