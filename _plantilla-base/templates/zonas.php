<?php
/** /zonas: índice HTML de todas las zonas (sitemap navegable). Zonas raíz → hijas → nietas, con los servicios de cada una. */
$raiz = array_filter(zonas_raiz(), fn($z) => zona_servicios($z['slug']));
$principal = array_key_first(servicios());
partial('head');
?>
<body>
  <?php partial('header'); ?>

  <main id="main-content" class="section-page">
    <?php partial('page-intro'); ?>
    <?php partial('breadcrumbs'); ?>
    <?php partial('texto'); ?>

    <section class="local-links" aria-labelledby="zonas-indice">
      <div class="container">
        <h2 class="section-title" id="zonas-indice">Todas las zonas</h2>
        <div class="local-links__grid">
          <?php foreach ($raiz as $z): ?>
          <div class="local-links__col">
            <h3 class="local-links__zone"><i class="ri-map-pin-2-fill" aria-hidden="true"></i> <a href="<?= e(url($z['slug'])) ?>"><?= e($z['nombre']) ?></a></h3>
            <ul role="list">
              <?php foreach (zonas_hijas($z['slug']) as $h): if (!zona_servicios($h['slug'])) continue; ?>
              <li><a href="<?= e(url($h['slug'])) ?>"><?= e($h['nombre']) ?></a>
                <?php $nietas = array_filter(zonas_hijas($h['slug']), fn($n) => zona_servicios($n['slug'])); if ($nietas): ?>
                <ul role="list">
                  <?php foreach ($nietas as $n): ?><li><a href="<?= e(url($n['slug'])) ?>"><?= e($n['nombre']) ?></a></li><?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <?php partial('cta-whatsapp'); ?>
  </main>

  <?php partial('footer'); ?>
  <?php partial('end'); ?>
