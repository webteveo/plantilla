<?php
require_once 'config/variables.php';
require_once 'src/controlador/Local_Controller.php';
$page_title       = 'Página no encontrada | ' . EMPRESA_NOMBRE;
$page_description = 'Esta página no existe, pero el cerrajero sí: atendemos las 24 horas en todos los barrios de Montevideo. Escribinos por WhatsApp.';
$page_canonical   = SEO_CANONICAL_URL;
$page_robots      = 'noindex, follow';

$e404Servicios = Local_Controller::servicios();
$e404Barrios   = [];
foreach (Local_Controller::ciudades() as $e404K => $e404C) $e404Barrios[$e404K] = $e404C['nombre'];
asort($e404Barrios);

require 'src/vista/partials/head.php';
?>
<body class="home-page">
  <?php require 'src/vista/partials/header.php'; ?>
  <main id="main-content">

    <section class="e404">
      <div class="container e404__inner">
        <span class="e404__code" aria-hidden="true">404</span>
        <h1 class="e404__title">Esta puerta no abre</h1>
        <p class="e404__lead">La página que buscás no existe o cambió de dirección. Si necesitás un cerrajero, escribinos y salimos ahora.</p>
        <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="e404__btn" target="_blank" rel="noopener">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
        </a>
      </div>
    </section>

    <section class="e404-links" aria-labelledby="e404-servicios">
      <div class="container">
        <h2 class="e404-links__title" id="e404-servicios">Servicios en Montevideo</h2>
        <ul class="zonas-home__chips" role="list">
          <?php foreach ($e404Servicios as $e404K => $e404N): ?>
          <li><a href="<?= $url ?>local/<?= $e404K ?>-montevideo" class="zonas-home__chip"><i class="ri-key-2-line" aria-hidden="true"></i><?= htmlspecialchars($e404N) ?></a></li>
          <?php endforeach; ?>
        </ul>

        <h2 class="e404-links__title" id="e404-barrios">Buscá tu barrio</h2>
        <input type="search" class="e404__search" id="e404-buscar" placeholder="Escribí tu barrio, por ejemplo Pocitos" aria-label="Buscar barrio" autocomplete="off">
        <ul class="zonas-home__chips" id="e404-lista" role="list" aria-labelledby="e404-barrios">
          <?php foreach ($e404Barrios as $e404K => $e404N): ?>
          <li data-nombre="<?= htmlspecialchars(mb_strtolower($e404N)) ?>"><a href="<?= $url ?>local/cerrajero-<?= $e404K ?>" class="zonas-home__chip"><i class="ri-map-pin-2-line" aria-hidden="true"></i><?= htmlspecialchars($e404N) ?></a></li>
          <?php endforeach; ?>
        </ul>
        <p class="e404-links__otros">
          <a href="<?= $url ?>">Inicio</a> · <a href="<?= $url ?>paginas/servicios">Servicios</a> · <a href="<?= $url ?>paginas/zonas">Zonas</a> · <a href="<?= $url ?>articulos">Guías</a> · <a href="<?= $url ?>contacto">Contacto</a>
        </p>
      </div>
    </section>

  </main>
  <script>
    (function () {
      var q = document.getElementById('e404-buscar'), items = document.querySelectorAll('#e404-lista li');
      if (!q) return;
      var norm = function (s) { return s.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase(); };
      q.addEventListener('input', function () {
        var v = norm(q.value.trim());
        items.forEach(function (li) { li.hidden = v !== '' && norm(li.dataset.nombre).indexOf(v) === -1; });
      });
    })();
  </script>
  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
