<?php
$page_title = 'Dejá tu reseña | ' . EMPRESA_NOMBRE;
$page_description = 'Comparte tu experiencia con ' . EMPRESA_NOMBRE . '. Tu opinión nos ayuda a mejorar.';
$page_canonical = SEO_CANONICAL_URL . '/resena';
$page_extra_css = [$ruta . '/css/resena/resena.css'];
require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main class="resena-page">
    <div class="container">
      <div class="resena-header">
        <h1>¿Cómo fue tu experiencia?</h1>
        <p>Tu opinión es muy importante para nosotros. Seleccioná la cantidad de estrellas que refleja tu experiencia.</p>
      </div>

      <div class="star-selector">
        <p class="star-question">¿Cuántas estrellas nos das?</p>
        <div class="stars-container">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <button type="button" class="star-btn" data-value="<?= $i ?>" aria-label="<?= $i ?> estrellas">
              <i class="ri-star-fill"></i>
            </button>
          <?php endfor; ?>
        </div>
        <p class="star-feedback" id="starFeedback"></p>
      </div>

      <div class="resena-actions">
        <?php if (RESENA_GOOGLE_PROFILE_URL): ?>
        <a href="<?= RESENA_GOOGLE_PROFILE_URL ?>" class="btn btn-primary btn-resena btn-hidden" id="btnGoogle" target="_blank">
          <i class="ri-star-fill"></i> Dejar reseña en Google
        </a>
        <?php endif; ?>
        <a href="<?= $url ?>resena/dejar-resena" class="btn btn-secondary btn-resena btn-hidden" id="btnFormulario">
          <i class="ri-edit-line"></i> Dejar reseña
        </a>
      </div>

      <p class="resena-privacy">
        <a href="<?= $url . ltrim(URL_PRIVACIDAD, "/") ?>">Política de privacidad</a>
      </p>
    </div>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <script src="<?= $ruta ?>/js/resena/resena.js"></script>
</body>
</html>
