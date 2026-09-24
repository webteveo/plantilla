<?php
require_once 'config/variables.php';

$page_title = 'Servicios de cerrajería en Montevideo | ' . EMPRESA_NOMBRE;
$page_description = 'Apertura de puertas y autos, cambio y reparación de cerraduras, cerraduras de seguridad y digitales, copia de llaves. A domicilio en Montevideo, las 24 horas.';
$page_keywords = SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical = SEO_CANONICAL_URL . '/paginas/servicios';

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">
    <section class="page-intro">
      <div class="container page-intro__inner">
        <p class="page-intro__eyebrow">Servicios</p>
        <h1 class="page-intro__title">Nuestros servicios</h1>
        <p class="page-intro__desc"><?= htmlspecialchars(EMPRESA_DESCRIPCION) ?></p>
      </div>
    </section>

    <?php require 'src/vista/compact/servicios.php'; ?>
    <?php require 'src/vista/compact/precios.php'; ?>
    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
