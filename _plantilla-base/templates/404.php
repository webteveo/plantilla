<?php
/** 404 real (http 404): CTA + lista de servicios + buscador de zonas. */
$zonasBuscables = array_filter(zonas_con_paginas(), fn($z) => zona_servicios($z['slug']));
uasort($zonasBuscables, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));
partial('head');
?>
<body class="home-page">
  <?php partial('header'); ?>
  <main id="main-content">

    <section class="e404">
      <div class="container e404__inner">
        <span class="e404__code" aria-hidden="true">404</span>
        <h1 class="e404__title"><?= e($pagina['seo']['h1']) ?></h1>
        <p class="e404__lead"><?= e($pagina['seo']['subtitulo']) ?> Si necesitás <?= e(mb_strtolower(cfg('marca.rubro'))) ?>, escribinos.</p>
        <a <?= wsp_attrs($pagina) ?> class="e404__btn">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          <?= e(cfg('contacto.cta_label')) ?>
        </a>
      </div>
    </section>

    <section class="e404-links" aria-labelledby="e404-servicios">
      <div class="container">
        <h2 class="e404-links__title" id="e404-servicios">Servicios</h2>
        <ul class="zonas-home__chips" role="list">
          <?php foreach (servicios() as $s): ?>
          <li><a href="<?= e(url($s['slug'])) ?>" class="zonas-home__chip"><i class="ri-tools-line" aria-hidden="true"></i><?= e($s['nombre_h1']) ?></a></li>
          <?php endforeach; ?>
        </ul>

        <h2 class="e404-links__title" id="e404-zonas">Buscá tu zona</h2>
        <input type="search" class="e404__search" id="e404-buscar" placeholder="Escribí tu barrio o ciudad" aria-label="Buscar zona" autocomplete="off">
        <ul class="zonas-home__chips" id="e404-lista" role="list" aria-labelledby="e404-zonas">
          <?php foreach ($zonasBuscables as $z): ?>
          <li data-nombre="<?= e(mb_strtolower($z['nombre'])) ?>"><a href="<?= e(url($z['slug'])) ?>" class="zonas-home__chip"><i class="ri-map-pin-2-line" aria-hidden="true"></i><?= e($z['nombre']) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <p class="e404-links__otros">
          <a href="<?= e(url('')) ?>">Inicio</a> · <a href="<?= e(url('nosotros')) ?>">Nosotros</a> · <a href="<?= e(url('contacto')) ?>">Contacto</a>
        </p>
      </div>
    </section>

  </main>
  <?php partial('footer'); ?>
  <?php partial('end'); ?>
