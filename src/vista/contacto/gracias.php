<?php
require_once 'config/variables.php';

$page_title       = 'Mensaje enviado | ' . EMPRESA_NOMBRE;
$page_description = 'Tu mensaje fue recibido. ' . EMPRESA_NOMBRE . ' te responde a la brevedad.';
$page_robots      = 'noindex, follow';
$page_keywords    = '';
$page_canonical   = SEO_CANONICAL_URL . '/contacto/gracias';

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">
    <section class="contacto-gracias" aria-labelledby="gracias-title">
      <div class="container">
        <div class="contacto-gracias__inner">
          <div class="contacto-gracias__icon" aria-hidden="true">
            <i class="ri-checkbox-circle-fill"></i>
          </div>
          <h1 class="contacto-gracias__title" id="gracias-title">¡Mensaje recibido!</h1>
          <p class="contacto-gracias__desc">
            Gracias por escribirnos. Te respondemos en menos de 24 horas con presupuesto sin cargo.
          </p>
          <div class="contacto-gracias__actions">
            <a href="<?= $url ?>" class="btn btn--primary">
              <i class="ri-home-line" aria-hidden="true"></i>
              Volver al inicio
            </a>
            <a href="<?= htmlspecialchars(wsp_href('Hola! Recién llené el formulario de contacto y quería consultar por un servicio de cerrajería.')) ?>"
               class="btn btn--whatsapp" target="_blank" rel="noopener">
              <i class="ri-whatsapp-line" aria-hidden="true"></i>
              <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
            </a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
