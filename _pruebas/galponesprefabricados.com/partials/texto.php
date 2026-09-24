<?php /** Secciones de texto de la página (H2 + párrafos + lista) con el diseño zona-texto. */ $secs = $pagina['secciones'] ?? []; ?>
<?php if ($secs): ?>
<section class="zona-texto" aria-labelledby="zona-texto-titulo">
  <div class="container zona-texto__inner">
    <?php foreach ($secs as $i => $s): ?>
    <h2 class="zona-texto__title<?= $i > 0 ? ' zona-texto__title--sub' : '' ?>"<?= $i === 0 ? ' id="zona-texto-titulo"' : '' ?>><?= e($s['h2']) ?></h2>
    <?php foreach ($s['parrafos'] as $p): ?>
    <p><?= strip_tags($p, '<a><strong><em>') ?></p>
    <?php endforeach; ?>
    <?php if (!empty($s['lista'])): ?>
    <ul class="zona-texto__lista">
      <?php foreach ($s['lista'] as $l): ?><li><?= strip_tags($l, '<a><strong><em>') ?></li><?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php if (!empty($s['tabla']['filas'])): ?>
    <div class="zona-texto__tabla-wrap">
    <table class="zona-texto__tabla">
      <?php if (!empty($s['tabla']['cabecera'])): ?><thead><tr><?php foreach ($s['tabla']['cabecera'] as $th): ?><th><?= e($th) ?></th><?php endforeach; ?></tr></thead><?php endif; ?>
      <tbody>
      <?php foreach ($s['tabla']['filas'] as $fila): ?><tr><?php foreach ($fila as $td): ?><td><?= strip_tags($td, '<a><strong><em>') ?></td><?php endforeach; ?></tr><?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php if (!empty($s['tabla']['nota'])): ?><p class="zona-texto__nota"><?= e($s['tabla']['nota']) ?></p><?php endif; ?>
    <?php endif; ?>
    <?php foreach ((array)($s['parrafos_despues'] ?? []) as $p): ?>
    <p><?= strip_tags($p, '<a><strong><em>') ?></p>
    <?php endforeach; ?>
    <?php endforeach; ?>
    <?php if (!empty($pagina['seo']['actualizado'])): ?>
    <p class="zona-texto__actualizado">Actualizado: <time datetime="<?= e($pagina['seo']['actualizado']) ?>"><?= e(fecha_legible($pagina['seo']['actualizado'])) ?></time></p>
    <?php endif; ?>
    <a <?= wsp_attrs($pagina) ?> class="zona-texto__link">
      <i class="ri-whatsapp-line" aria-hidden="true"></i> <?= e(ct('texto_cta', 'Escribinos por WhatsApp')) ?>
    </a>
  </div>
</section>
<?php endif; ?>
