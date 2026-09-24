<?php
require_once 'config/variables.php';

$page_title = 'Por qué elegirnos | ' . EMPRESA_NOMBRE;
$page_description = 'Atención 24 horas, precio por WhatsApp antes de salir, apertura sin dañar tu puerta y prueba final en cada trabajo. Cerrajero de confianza en Montevideo.';
$page_keywords = 'por que elegir ' . mb_strtolower(EMPRESA_NOMBRE) . ', ' . SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical = SEO_CANONICAL_URL . '/paginas/diferenciales';

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">
    <section class="page-intro">
      <div class="container page-intro__inner">
        <p class="page-intro__eyebrow">Diferenciales</p>
        <h1 class="page-intro__title">Por qué elegir <?= htmlspecialchars(EMPRESA_NOMBRE) ?></h1>
        <p class="page-intro__desc">Presupuesto sin cargo, atención directa y cobertura local real.</p>
      </div>
    </section>

    <?php require 'src/vista/compact/diferenciadores.php'; ?>
    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
