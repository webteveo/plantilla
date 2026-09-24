<?php
require_once 'config/variables.php';

$page_title = 'Cerrajero por barrio en Montevideo | ' . EMPRESA_NOMBRE;
$page_description = 'Cerrajero a domicilio en todos los barrios de Montevideo: Pocitos, Centro, Carrasco, Prado, Cerro y más. Elegí tu barrio y escribinos por WhatsApp, las 24 horas.';
$page_keywords = 'zonas de cobertura ' . mb_strtolower(EMPRESA_NOMBRE) . ', ' . SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical = SEO_CANONICAL_URL . '/paginas/zonas';

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">
    <section class="page-intro">
      <div class="container page-intro__inner">
        <p class="page-intro__eyebrow">Cobertura</p>
        <h1 class="page-intro__title">Zonas donde trabajamos</h1>
        <p class="page-intro__desc">Atendemos <?= htmlspecialchars(DIRECCION_COMPLETA) ?> y alrededores con coordinación previa.</p>
      </div>
    </section>

    <?php require 'src/vista/compact/zonas.php'; ?>
    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
