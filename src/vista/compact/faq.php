<!-- FAQ Section -->
<?php $faq_items = $faq_items ?? require 'src/vista/compact/faq-data.php'; ?>
<?php if ($faq_items): ?>
<section class="faq" id="preguntas-frecuentes" aria-labelledby="faq-titulo">
  <div class="container">

    <div class="faq__header">
      <h2 class="faq__title" id="faq-titulo">Preguntas frecuentes</h2>
      <p class="faq__lead">Lo que más nos consultan sobre cerrajería en Montevideo.</p>
    </div>

    <div class="faq__list">
      <?php foreach ($faq_items as $f): ?>
      <details class="faq__item">
        <summary>
          <span class="faq__q"><?= htmlspecialchars($f['q']) ?></span>
          <span class="faq__icon" aria-hidden="true"><i class="ri-add-line"></i></span>
        </summary>
        <div class="faq__a">
          <p><?= $f['a'] ?></p>
        </div>
      </details>
      <?php endforeach; ?>
    </div>

    <div class="faq__cta">
      <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="faq__cta-btn" target="_blank" rel="noopener">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
      </a>
    </div>

  </div>
</section>
<?php endif; ?>
