<?php /** FAQ visible (el schema FAQPage usa las mismas preguntas, ver lib/schema.php). */ $faq = $pagina['faq'] ?? []; ?>
<?php if ($faq): ?>
<section class="faq" id="preguntas-frecuentes" aria-labelledby="faq-titulo">
  <div class="container">

    <div class="faq__header">
      <h2 class="faq__title" id="faq-titulo"><?= e(ct('faq_titulo', 'Preguntas frecuentes')) ?></h2>
      <?php if (ct('faq_lead')): ?><p class="faq__lead"><?= e(ct('faq_lead')) ?></p><?php endif; ?>
    </div>

    <div class="faq__list">
      <?php foreach ($faq as $f): ?>
      <details class="faq__item">
        <summary>
          <span class="faq__q"><?= e($f['q']) ?></span>
          <span class="faq__icon" aria-hidden="true"><i class="ri-add-line"></i></span>
        </summary>
        <div class="faq__a"><p><?= strip_tags($f['a'], '<a><strong><em>') ?></p></div>
      </details>
      <?php endforeach; ?>
    </div>

    <div class="faq__cta">
      <a <?= wsp_attrs($pagina) ?> class="faq__cta-btn">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= e(cfg('contacto.cta_label')) ?>
      </a>
    </div>

  </div>
</section>
<?php endif; ?>
