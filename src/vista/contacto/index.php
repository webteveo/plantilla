<?php
require_once 'config/variables.php';
require_once 'src/controlador/Local_Controller.php';

$page_title       = 'Contacto | Cerrajero 24 horas en Montevideo';
$page_description = 'Escribinos por WhatsApp al 094 633 956 o dejanos tu consulta. Cerrajero a domicilio en Montevideo, las 24 horas, todos los días.';
$page_keywords    = 'contacto ' . mb_strtolower(EMPRESA_NOMBRE) . ', presupuesto cerrajero montevideo';
$page_canonical   = SEO_CANONICAL_URL . '/contacto';

$ctServicios = array_values(Local_Controller::servicios()) ?: ['Consulta general'];
$ctServicios[] = 'Otro';
$ctZonas = array_column(Local_Datos::ZONAS_PADRE, 'nombre');

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">

    <section class="page-intro">
      <div class="container page-intro__inner">
        <p class="page-intro__eyebrow">Contacto</p>
        <h1 class="page-intro__title">Contanos qué necesitás resolver</h1>
        <p class="page-intro__desc">Completá el formulario o escribinos por correo. Respondemos a la brevedad con presupuesto sin cargo.</p>
      </div>
    </section>

    <section class="contacto" aria-labelledby="contacto-form-title">
      <div class="container">

        <?php if (!empty($_SESSION['error'])): ?>
          <div class="contacto__alert contacto__alert--error" role="alert">
            <i class="ri-error-warning-line" aria-hidden="true"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
          </div>
          <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="contacto__layout">

          <!-- Formulario -->
          <div class="contacto__form-wrap">
            <h2 class="contacto__form-title" id="contacto-form-title">Envianos un mensaje</h2>
            <form class="contacto__form" action="<?= $url ?>contacto/enviar" method="POST" novalidate>

              <div class="contacto__row contacto__row--2">
                <div class="contacto__group">
                  <label class="contacto__label" for="nombre">Nombre <span aria-hidden="true">*</span></label>
                  <input class="contacto__input" type="text" id="nombre" name="nombre"
                         placeholder="Tu nombre" required autocomplete="name"
                         value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="contacto__group">
                  <label class="contacto__label" for="email">Email <span aria-hidden="true">*</span></label>
                  <input class="contacto__input" type="email" id="email" name="email"
                         placeholder="tu@email.com" required autocomplete="email"
                         value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
              </div>

              <div class="contacto__row contacto__row--2">
                <div class="contacto__group">
                  <label class="contacto__label" for="telefono">Teléfono</label>
                  <input class="contacto__input" type="tel" id="telefono" name="telefono"
                         placeholder="09X XXX XXX" autocomplete="tel"
                         value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                </div>
                <div class="contacto__group">
                  <label class="contacto__label" for="servicio">Servicio de interés</label>
                  <select class="contacto__select" id="servicio" name="servicio">
                    <option value="">Seleccioná un servicio</option>
                    <?php foreach ($ctServicios as $ctS): ?>
                    <option value="<?= htmlspecialchars($ctS) ?>" <?= ($_POST['servicio'] ?? '') === $ctS ? 'selected' : '' ?>><?= htmlspecialchars($ctS) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="contacto__group">
                <label class="contacto__label" for="mensaje">Mensaje <span aria-hidden="true">*</span></label>
                <textarea class="contacto__textarea" id="mensaje" name="mensaje"
                          placeholder="Contanos qué pasa con tu puerta o cerradura, tu barrio y cuándo necesitás el servicio..."
                          rows="5" required><?= htmlspecialchars($_POST['mensaje'] ?? $_GET['msg'] ?? '') ?></textarea>
              </div>

              <button type="submit" class="btn btn--primary btn--lg contacto__submit">
                <i class="ri-send-plane-line" aria-hidden="true"></i>
                Enviar mensaje
              </button>
            </form>
          </div>

          <!-- Info lateral -->
          <aside class="contacto__info" aria-label="Información de contacto">

            <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
               class="contacto__wa-card" target="_blank" rel="noopener">
              <i class="ri-whatsapp-line" aria-hidden="true"></i>
              <div>
                <strong><?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?></strong>
                <span><?= htmlspecialchars(CONTACTO_TELEFONO_VISIBLE ?: 'Respondemos a la brevedad') ?></span>
              </div>
              <i class="ri-arrow-right-line contacto__wa-arrow" aria-hidden="true"></i>
            </a>

            <div class="contacto__info-block">
              <h3 class="contacto__info-heading">
                <i class="ri-time-line" aria-hidden="true"></i> Horarios
              </h3>
              <ul class="contacto__hours" role="list">
                <li><span>Todos los días</span><span>24 horas</span></li>
                <li><span>Feriados</span><span>24 horas</span></li>
              </ul>
            </div>

            <div class="contacto__info-block">
              <h3 class="contacto__info-heading">
                <i class="ri-map-pin-line" aria-hidden="true"></i> Zonas de trabajo
              </h3>
              <ul class="contacto__zones" role="list">
                <?php foreach ($ctZonas as $ctZ): ?>
                <li><i class="ri-checkbox-circle-line" aria-hidden="true"></i> <?= htmlspecialchars($ctZ) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>

            <div class="contacto__info-block">
              <h3 class="contacto__info-heading">
                <i class="ri-mail-line" aria-hidden="true"></i> Email
              </h3>
              <a href="mailto:<?= CONTACTO_EMAIL ?>" class="contacto__email-link">
                <?= CONTACTO_EMAIL ?>
              </a>
            </div>

          </aside>
        </div>
      </div>
    </section>

  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
