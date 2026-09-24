<?php
$page_title = 'Contactanos | ' . EMPRESA_NOMBRE;
$page_description = 'Tu opinión nos importa. Contanos qué podemos mejorar para vos.';
$page_canonical = SEO_CANONICAL_URL . '/resena/dejar-resena';
$page_extra_css = [$ruta . '/css/resena/resena.css'];
require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main class="form-resena-page">
    <div class="container">
      <div class="form-resena-header">
        <a href="<?= $url ?>resena" class="back-link">
          <i class="ri-arrow-left-line"></i> Volver
        </a>
        <h1>Tu opinión nos importa</h1>
        <p class="subtitle"><?= RESENA_MENSAJE_FORMULARIO ?></p>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <i class="ri-error-warning-line"></i>
          <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

        <form action="<?= $url ?>resena/enviar" method="POST" class="form-resena">
        <input type="hidden" name="calificacion" id="calificacion" value="0">

        <div class="form-group">
          <label for="nombre">
            <i class="ri-user-line"></i> Nombre completo *
          </label>
          <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
        </div>

        <div class="form-group">
          <label for="email">
            <i class="ri-mail-line"></i> Email *
          </label>
          <input type="email" id="email" name="email" required placeholder="tu@email.com">
        </div>

        <div class="form-group">
          <label for="telefono">
            <i class="ri-phone-line"></i> Teléfono
          </label>
          <input type="tel" id="telefono" name="telefono" placeholder="Ej: 098123456">
        </div>

        <div class="form-group">
          <label for="mensaje">
            <i class="ri-chat-3-line"></i> ¿Qué podemos mejorar? *
          </label>
          <textarea id="mensaje" name="mensaje" required rows="5" placeholder="Contanos tu experiencia..."></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary btn-submit">
            <i class="ri-send-plane-fill"></i> Enviar mensaje
          </button>
        </div>

        <p class="form-note">
          <i class="ri-lock-line"></i> Tu información está protegida y solo será usada para mejorar nuestro servicio.
        </p>
      </form>
    </div>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
</body>
</html>
