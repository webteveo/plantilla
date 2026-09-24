<!-- Servicios Section -->
<?php
/**
 * Tarjetas de servicios de la home.
 * Cada item: titulo, img (relativa a public/images/servicios/), btn (texto del boton), wa_text (mensaje de WhatsApp).
 * Si falta la imagen se usa un fondo degradado.
 */
$servicios_home = [
    ['titulo' => 'Me quedé afuera de casa',        'img' => 'apertura.webp',   'srv' => 'apertura-de-puertas',      'btn' => 'Necesito entrar ahora',         'wa_text' => 'Hola! Me quedé afuera y necesito abrir la puerta ahora.'],
    ['titulo' => 'Me quedé afuera del auto',       'img' => 'auto.webp',       'srv' => 'cerrajero-automotriz',     'btn' => 'Necesito abrir el auto',        'wa_text' => 'Hola! Me quedé afuera del auto y necesito abrirlo ahora.'],
    ['titulo' => 'Cerradura rota, trabada o forzada', 'img' => 'reparacion.webp', 'srv' => 'reparacion-de-cerraduras', 'btn' => 'Necesito arreglarla hoy',       'wa_text' => 'Hola! Tengo una cerradura rota, trabada o forzada y necesito un cerrajero hoy.'],
    ['titulo' => 'Perdí las llaves',               'img' => 'cerraduras.webp', 'srv' => 'cambio-de-cerraduras',     'btn' => 'Necesito cambiar la cerradura', 'wa_text' => 'Hola! Perdí las llaves y quiero cambiar la cerradura o el cilindro.'],
];
?>
<?php if ($servicios_home): ?>
<section class="servicios" id="servicios" aria-labelledby="servicios-titulo">
  <div class="container">

    <div class="servicios__header">
      <h2 class="servicios__title" id="servicios-titulo">
        Servicios de <em>cerrajería en Montevideo</em>
      </h2>
      <?php $svDomicilio = ($lp_servicio ?? '') !== 'cerrajero-a-domicilio' ? $url . 'local/cerrajero-a-domicilio-' . ($lp_zona_slug ?? 'montevideo') : ''; ?>
      <p class="servicios__lead">Cerrajero de urgencia en Montevideo, las 24 horas. <?php if ($svDomicilio): ?><a href="<?= htmlspecialchars($svDomicilio) ?>">Vamos a tu casa o apartamento</a><?php else: ?>Vamos a tu casa o apartamento<?php endif; ?> y resolvemos en el momento.</p>
    </div>

    <div class="servicios__grid">
      <?php foreach ($servicios_home as $sv): ?>
      <?php $svImg = !empty($sv['img']) && is_file(__DIR__ . '/../../../public/images/servicios/' . $sv['img']) ? $ruta . '/images/servicios/' . $sv['img'] : ''; ?>
      <article class="servicio-card" aria-label="<?= htmlspecialchars($sv['titulo']) ?>">
        <?php if ($svImg): ?>
        <img src="<?= htmlspecialchars($svImg) ?>" alt="<?= htmlspecialchars($sv['titulo']) ?> - cerrajero en Montevideo" class="servicio-card__bg" width="1200" height="675" loading="lazy" decoding="async">
        <?php endif; ?>
        <div class="servicio-card__body">
          <h3 class="servicio-card__title"><?php if (!empty($sv['srv'])): ?><a href="<?= $url ?>local/<?= $sv['srv'] . '-' . ($lp_zona_slug ?? 'montevideo') ?>"><?= htmlspecialchars($sv['titulo']) ?></a><?php else: ?><?= htmlspecialchars($sv['titulo']) ?><?php endif; ?></h3>
          <a href="<?= htmlspecialchars(wsp_href($sv['wa_text'] ?? CONTACTO_WHATSAPP_MENSAJE)) ?>"
             class="servicio-card__btn" target="_blank" rel="noopener">
            <i class="ri-whatsapp-line" aria-hidden="true"></i>
            <?= htmlspecialchars($sv['btn']) ?>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="servicios__cta">
      <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="servicios__cta-btn" target="_blank" rel="noopener">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>
