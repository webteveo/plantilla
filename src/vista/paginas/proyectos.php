<?php
require_once 'config/variables.php';

$page_title = 'Trabajos realizados | ' . EMPRESA_NOMBRE;
$page_description = 'Fotos de cerrajería y trabajos realizados por ' . EMPRESA_NOMBRE . ' en ' . DIRECCION_COMPLETA . '.';
$page_keywords = SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical = SEO_CANONICAL_URL . '/proyectos';

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">
    <section class="page-intro">
      <div class="container page-intro__inner">
        <p class="page-intro__eyebrow">Trabajos</p>
        <h1 class="page-intro__title">Trabajos realizados</h1>
        <p class="page-intro__desc">Servicios de cerrajería realizados en <?= htmlspecialchars(DIRECCION_COMPLETA) ?>.</p>
      </div>
    </section>

    <?php require 'src/vista/compact/proyectos.php'; ?>
    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
