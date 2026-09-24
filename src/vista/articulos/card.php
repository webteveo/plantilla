<?php /* Card de articulo. Espera $r (array de articulo), $url, $ruta. */ ?>
<a href="<?= $url ?>articulos/<?= htmlspecialchars($r['slug']) ?>" class="ar-card" aria-label="Leer: <?= htmlspecialchars($r['titulo']) ?>">
  <?php if (!empty($r['imagen'])): ?>
  <div class="ar-card__media">
    <img src="<?= $ruta ?>/images/<?= htmlspecialchars($r['imagen']) ?>" alt="<?= htmlspecialchars($r['imagen_alt'] ?? $r['titulo']) ?>" width="400" height="250" loading="lazy" decoding="async">
  </div>
  <?php endif; ?>
  <div class="ar-card__body">
    <span class="ar-card__cat"><?= htmlspecialchars($r['categoria'] ?? 'Artículos') ?></span>
    <h3 class="ar-card__title"><?= htmlspecialchars($r['titulo']) ?></h3>
    <p class="ar-card__desc"><?= htmlspecialchars($r['description'] ?? '') ?></p>
    <div class="ar-card__meta">
      <time datetime="<?= $r['fecha'] ?>"><?= Articulos::fechaLegible($r['fecha']) ?></time>
      <span class="ar-card__link">Leer <i class="ri-arrow-right-line" aria-hidden="true"></i></span>
    </div>
  </div>
</a>
