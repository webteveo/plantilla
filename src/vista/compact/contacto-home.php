<!-- Contacto Home Section -->
<section class="contacto-home" id="contacto" aria-labelledby="contacto-home-titulo">
  <div class="container">

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="contacto-home__alert" role="alert">
        <i class="ri-error-warning-line" aria-hidden="true"></i>
        <?= htmlspecialchars($_SESSION['error']) ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="contacto-home__layout">

      <div class="contacto-home__main">
        <h2 class="contacto-home__title" id="contacto-home-titulo">Contactanos</h2>
        <p class="contacto-home__lead">¿Es urgente? Escribinos por WhatsApp y te respondemos al momento, las 24 horas.</p>

        <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="contacto-home__wa" target="_blank" rel="noopener">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
        </a>

        <ul class="contacto-home__datos" role="list">
          <?php if (CONTACTO_TELEFONO): ?>
          <li><a href="tel:+<?= CONTACTO_TELEFONO ?>"><i class="ri-phone-line" aria-hidden="true"></i><?= htmlspecialchars(CONTACTO_TELEFONO_VISIBLE) ?></a></li>
          <?php endif; ?>
          <li><a href="mailto:<?= CONTACTO_EMAIL ?>"><i class="ri-mail-line" aria-hidden="true"></i><?= CONTACTO_EMAIL ?></a></li>
          <li><span><i class="ri-map-pin-line" aria-hidden="true"></i><?= htmlspecialchars(DIRECCION_COMPLETA) ?></span></li>
        </ul>
      </div>

      <form class="contacto-home__form" action="<?= $url ?>contacto/enviar" method="POST" novalidate>
        <p class="contacto-home__form-title">O dejanos tu consulta</p>

        <div class="contacto-home__row">
          <input class="contacto-home__input" type="text" name="nombre" placeholder="Nombre" required autocomplete="name" aria-label="Nombre"
                 value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
          <input class="contacto-home__input" type="tel" name="telefono" placeholder="Teléfono" autocomplete="tel" aria-label="Teléfono"
                 value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
        </div>
        <input class="contacto-home__input" type="email" name="email" placeholder="Email" required autocomplete="email" aria-label="Email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <textarea class="contacto-home__textarea" name="mensaje" rows="4" required aria-label="Mensaje"
                  placeholder="Contanos qué pasó y en qué barrio estás"><?= htmlspecialchars($_POST['mensaje'] ?? $_GET['msg'] ?? '') ?></textarea>

        <button type="submit" class="contacto-home__submit">Enviar consulta</button>
      </form>

    </div>
  </div>
</section>
