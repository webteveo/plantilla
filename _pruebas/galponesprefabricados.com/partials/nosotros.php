<?php /** Bloque "Quiénes somos" (comun.nosotros_*). La foto sale de config imagenes.nosotros. */
$img = cfg('imagenes.nosotros'); ?>
<section class="quienes-somos" id="quienes-somos" aria-labelledby="quienes-somos-titulo">
  <div class="container quienes-somos__inner">

    <?php if ($img): ?>
    <div class="quienes-somos__media">
      <img src="<?= e(url('assets/img/' . $img)) ?>" alt="<?= e(ct('nosotros_imagen_alt', cfg('marca.nombre'))) ?>" class="quienes-somos__img" width="900" height="1100" loading="lazy" decoding="async">
    </div>
    <?php endif; ?>

    <div class="quienes-somos__content">
      <h2 class="quienes-somos__title" id="quienes-somos-titulo"><?= e(ct('nosotros_titulo', 'Quiénes somos')) ?></h2>
      <p class="quienes-somos__lead"><?= strip_tags(ct('nosotros_lead'), '<strong><a>') ?></p>
      <p class="quienes-somos__text"><?= strip_tags(ct('nosotros_texto'), '<strong><a>') ?> <a href="<?= e(url('nosotros')) ?>">Conocé al equipo</a>.</p>
      <a <?= wsp_attrs($pagina) ?> class="quienes-somos__btn">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= e(cfg('contacto.cta_label')) ?>
      </a>
    </div>

  </div>
</section>
