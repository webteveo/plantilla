<?php
$page_title = '¡Gracias! | ' . EMPRESA_NOMBRE;
$page_description = 'Gracias por compartir tu experiencia con nosotros.';
$page_canonical = SEO_CANONICAL_URL . '/resena/gracias';
$page_extra_css = [$ruta . '/css/resena/resena.css'];
require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main class="gracias-page">
    <div class="container">
      <div class="gracias-content">
        <div class="gracias-icon">
          <i class="ri-checkbox-circle-line"></i>
        </div>
        <h1>¡Gracias por tu mensaje!</h1>
        <p class="gracias-mensaje"><?= $_SESSION['exito'] ?? 'Hemos recibido tu mensaje y trabajaremos para mejorar.' ?></p>
        
        <div class="gracias-actions">
          <a href="<?= $url ?>" class="btn btn-primary">
            <i class="ri-home-4-line"></i> Volver al inicio
          </a>
          <a href="<?= $url ?>resena" class="btn btn-secondary">
            <i class="ri-star-fill"></i> Volver a calificar
          </a>
        </div>
      </div>
    </div>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
</body>
</html>
<?php 
if (isset($_SESSION['exito'])) unset($_SESSION['exito']);
?>
