<?php
/** Bloque final de contacto (diseño contacto-home): WhatsApp + datos + formulario opcional (config email.formulario). */
$form = (bool)cfg('email.formulario');
$ctServicios = servicios();
?>
<section class="contacto-home" id="contacto" aria-labelledby="contacto-home-titulo">
  <div class="container">

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="contacto-home__alert" role="alert"><i class="ri-error-warning-line" aria-hidden="true"></i> <?= e($_SESSION['error']) ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="contacto-home__layout">

      <div class="contacto-home__main">
        <h2 class="contacto-home__title" id="contacto-home-titulo"><?= e(ct('contacto_titulo', 'Contactanos')) ?></h2>
        <p class="contacto-home__lead"><?= e(ct('contacto_lead')) ?></p>

        <a <?= wsp_attrs($pagina) ?> class="contacto-home__wa">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          <?= e(cfg('contacto.cta_label')) ?>
        </a>

        <ul class="contacto-home__datos" role="list">
          <?php if (tel_attrs()): ?><li><a <?= tel_attrs() ?>><i class="ri-phone-line" aria-hidden="true"></i><?= e(telefono_visible()) ?></a></li><?php endif; ?>
          <?php if (cfg('contacto.email')): ?><li><a href="mailto:<?= e(cfg('contacto.email')) ?>"><i class="ri-mail-line" aria-hidden="true"></i><?= e(cfg('contacto.email')) ?></a></li><?php endif; ?>
          <li><span><i class="ri-map-pin-line" aria-hidden="true"></i><?= e(cfg('direccion.texto')) ?></span></li>
        </ul>
      </div>

      <?php if ($form): ?>
      <form class="contacto-home__form" action="<?= e(url('contacto/enviar')) ?>" method="POST" novalidate>
        <p class="contacto-home__form-title" id="contacto-form-title"><?= e(t($pagina['contenido']['form_titulo'] ?? 'O dejanos tu consulta', $pagina['vars'])) ?></p>
        <div class="contacto-home__row">
          <input class="contacto-home__input" type="text" name="nombre" placeholder="Nombre" required autocomplete="name" aria-label="Nombre">
          <input class="contacto-home__input" type="tel" name="telefono" placeholder="Teléfono" autocomplete="tel" aria-label="Teléfono">
        </div>
        <div class="contacto-home__row">
          <input class="contacto-home__input" type="email" name="email" placeholder="Email" autocomplete="email" aria-label="Email">
          <select class="contacto-home__input" name="servicio" aria-label="Servicio">
            <option value="">Servicio</option>
            <?php foreach ($ctServicios as $s): ?><option value="<?= e($s['slug']) ?>" <?= ($pagina['servicio']['slug'] ?? '') === $s['slug'] ? 'selected' : '' ?>><?= e($s['nombre_h1']) ?></option><?php endforeach; ?>
            <option value="otro">Otro</option>
          </select>
        </div>
        <textarea class="contacto-home__input" name="mensaje" rows="4" placeholder="Contanos qué necesitás y en qué zona" required aria-label="Mensaje"><?= e($_GET['msg'] ?? '') ?></textarea>
        <input type="text" name="sitio_web" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
        <button type="submit" class="contacto-home__submit"><i class="ri-send-plane-line" aria-hidden="true"></i> Enviar</button>
      </form>
      <?php endif; ?>

    </div>
  </div>
</section>
