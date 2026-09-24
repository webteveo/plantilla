<!-- Stats Strip (debajo de servicios) -->
<?php
$stats_strip = [
    ['n' => 10,   'sup' => '+', 'label' => 'Años de experiencia'],
    ['n' => 5000, 'sup' => '+', 'label' => 'Puertas abiertas'],
];
?>
<section class="stats-strip" aria-label="<?= htmlspecialchars(EMPRESA_NOMBRE) ?> en cifras">
  <div class="container stats-strip__inner">
    <?php foreach ($stats_strip as $st): ?>
    <div class="stats-strip__item">
      <div class="stats-strip__num"><span data-count-to="<?= (int)$st['n'] ?>">0</span><?= htmlspecialchars($st['sup']) ?></div>
      <span class="stats-strip__label"><?= htmlspecialchars($st['label']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</section>
