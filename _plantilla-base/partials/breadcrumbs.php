<?php /** Migas de pan visibles (el schema BreadcrumbList sale de lib/schema.php con los mismos datos). */ ?>
<?php if (!empty($pagina['migas']) && count($pagina['migas']) > 1): ?>
<nav class="migas" aria-label="Ubicación en el sitio">
  <ol class="container migas__list">
    <?php foreach ($pagina['migas'] as $m): ?>
    <li><?php if (isset($m['href'])): ?><a href="<?= e($m['href']) ?>"><?= e($m['label']) ?></a><?php else: ?><span aria-current="page"><?= e($m['label']) ?></span><?php endif; ?></li>
    <?php endforeach; ?>
  </ol>
</nav>
<?php endif; ?>
