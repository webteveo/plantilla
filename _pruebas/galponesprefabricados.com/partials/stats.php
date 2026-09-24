<?php /** Cifras (comun.stats). Vacío = no se muestra. */ $stats = (array)($pagina['comun']['stats'] ?? []); ?>
<?php if ($stats): ?>
<section class="stats-strip" aria-label="<?= e(cfg('marca.nombre')) ?> en cifras">
  <div class="container stats-strip__inner">
    <?php foreach ($stats as $st): ?>
    <div class="stats-strip__item">
      <div class="stats-strip__num"><span data-count-to="<?= (int)$st['n'] ?>">0</span><?= e($st['sup'] ?? '') ?></div>
      <span class="stats-strip__label"><?= e($st['label']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
