<!-- CTA Bottom Section -->
<?php
/** Tres motivos para consultar (panel derecho). COMPLETAR. */
$cta_panel = [
    ['t' => 'Presupuesto sin cargo', 'd' => 'Te decimos cuánto sale antes de empezar.'],
    ['t' => 'Atención directa',      'd' => 'Coordinás todo por correo, sin intermediarios.'],
    ['t' => 'Cobertura local',       'd' => htmlspecialchars(DIRECCION_COMPLETA) . ' y alrededores.'],
];
?>
<section class="cta-bottom" id="contacto" aria-labelledby="cta-titulo">
  <div class="container">
    <div class="cta-bottom__inner">
      <div class="cta-bottom__content">
        <p class="cta-bottom__eyebrow">Presupuesto</p>
        <h2 class="cta-bottom__title" id="cta-titulo">Pedí tu presupuesto en <?= htmlspecialchars(DIRECCION_CIUDAD) ?></h2>
        <p class="cta-bottom__desc">Contanos qué necesitás y te respondemos con una propuesta clara, sin cargo y sin compromiso.</p>

        <div class="cta-bottom__trust">
          <span><i class="ri-checkbox-circle-line" aria-hidden="true"></i> Presupuesto sin cargo</span>
          <span><i class="ri-map-pin-line" aria-hidden="true"></i> Cobertura local real</span>
          <span><i class="ri-whatsapp-line" aria-hidden="true"></i> Respuesta directa por correo</span>
        </div>

        <div class="cta-bottom__actions">
          <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
             class="btn btn--whatsapp cta-bottom__btn"
             target="_blank" rel="noopener"
             aria-label="<?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?> por correo">
            <i class="ri-whatsapp-line" aria-hidden="true"></i>
            <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
          </a>

          <?php if (CONTACTO_TELEFONO): ?>
          <a href="tel:+<?= CONTACTO_TELEFONO ?>" class="cta-bottom__phone" aria-label="Llamar a <?= EMPRESA_NOMBRE ?>">
            <i class="ri-phone-line" aria-hidden="true"></i>
            <?= htmlspecialchars(CONTACTO_TELEFONO_VISIBLE) ?>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <aside class="cta-bottom__panel" aria-label="Motivos para consultar">
        <?php foreach ($cta_panel as $cp): ?>
        <div class="cta-bottom__panel-item">
          <strong><?= htmlspecialchars($cp['t']) ?></strong>
          <span><?= $cp['d'] ?></span>
        </div>
        <?php endforeach; ?>
      </aside>
    </div>
  </div>
</section>
