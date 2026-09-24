<?php
$page_title = 'Política de Privacidad - Reseñas | ' . EMPRESA_NOMBRE;
$page_description = 'Política de privacidad del sistema de reseñas de ' . EMPRESA_NOMBRE . '.';
$page_canonical = SEO_CANONICAL_URL . '/resena/privacidad';
$page_extra_css = [$ruta . '/css/resena/resena.css'];
require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main class="privacidad-page">
    <div class="container">
      <div class="privacidad-header">
        <a href="<?= $url ?>resena" class="back-link">
          <i class="ri-arrow-left-line"></i> Volver
        </a>
        <h1>Política de Privacidad</h1>
        <p class="subtitle">Sistema de Reseñas</p>
      </div>

      <div class="privacidad-content">
        <section class="privacidad-section">
          <h2><i class="ri-star-fill"></i> Reseñas de Google (4-5 estrellas)</h2>
          <p>Cuando seleccionas 4 o 5 estrellas, te redirigimos a Google para que puedas dejarnos tu reseña públicamente. Esta reseña:</p>
          <ul>
            <li>Se publica directamente en nuestro perfil de Google Business</li>
            <li>Es visible para todos los usuarios de Google</li>
            <li>Ayuda a otros clientes a conocer nuestra calidad de servicio</li>
            <li>No recopilamos ni almacenamos tus datos personales en este proceso</li>
          </ul>
        </section>

        <section class="privacidad-section">
          <h2><i class="ri-mail-line"></i> Feedback Privado (1-3 estrellas)</h2>
          <p>Cuando seleccionas 1, 2 o 3 estrellas, te ofrecemos un formulario privado para que nos compartas tu experiencia. En este caso:</p>
          <ul>
            <li>Tus datos (nombre, email, teléfono) se usan únicamente para responderte</li>
            <li>El mensaje llega directamente a nuestro equipo por correo electrónico</li>
            <li>No se publica en ningún lugar público</li>
            <li>No compartimos tu información con terceros</li>
            <li>Usamos tus datos solo para mejorar nuestro servicio</li>
          </ul>
        </section>

        <section class="privacidad-section">
          <h2><i class="ri-shield-check-line"></i> Protección de tus Datos</h2>
          <p>Nos comprometemos a:</p>
          <ul>
            <li>No vender ni compartir tus datos personales con empresas externas</li>
            <li>Usar el email solo para responder a tu mensaje</li>
            <li>Eliminar o anonimizar los datos cuando ya no sean necesarios</li>
            <li>No enviarte correos promocionales sin tu consentimiento</li>
          </ul>
        </section>

        <section class="privacidad-section">
          <h2><i class="ri-user-line"></i> Tus Derechos</h2>
          <p>Tienes derecho a:</p>
          <ul>
            <li>Solicitar acceso a tus datos personales</li>
            <li>Rectificar información incorrecta</li>
            <li>Solicitar la eliminación de tus datos</li>
            <li>Retirar tu consentimiento en cualquier momento</li>
          </ul>
          <p>Para ejercer cualquiera de estos derechos, contáctanos a: <a href="mailto:<?= CONTACTO_EMAIL ?>"><?= CONTACTO_EMAIL ?></a></p>
        </section>

        <section class="privacidad-section">
          <h2><i class="ri-file-list-2-line"></i> Información General</h2>
          <p>Esta política es específica para nuestro sistema de reseñas. Para información más completa sobre cómo manejamos tus datos, consulta nuestra política de privacidad general.</p>
          <p><strong>Última actualización:</strong> <?= date('d/m/Y') ?></p>
        </section>
      </div>

      <div class="privacidad-footer">
        <a href="<?= $url ?>resena" class="btn btn-primary">
          <i class="ri-arrow-left-line"></i> Volver al sistema de reseñas
        </a>
      </div>
    </div>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
</body>
</html>
