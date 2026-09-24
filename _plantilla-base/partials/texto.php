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
    <?php endforeach; ?>
    <a <?= wsp_attrs($pagina) ?> class="zona-texto__link">
      <i class="ri-whatsapp-line" aria-hidden="true"></i> <?= e(ct('texto_cta', 'Escribinos por WhatsApp')) ?>
    </a>
  </div>
</section>
<?php endif; ?>
