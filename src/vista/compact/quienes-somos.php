<!-- Quiénes Somos Section -->
<?php
$qs_img = 'hero/cerrajero-mobile.webp'; // relativa a public/images/. Reemplazar por foto del equipo, ej. 'equipo/equipo.webp'.
?>
<section class="quienes-somos" id="quienes-somos" aria-labelledby="quienes-somos-titulo">
  <div class="container quienes-somos__inner">

    <div class="quienes-somos__media">
      <img src="<?= $ruta ?>/images/<?= htmlspecialchars($qs_img) ?>"
           alt="Cerrajero de <?= htmlspecialchars(EMPRESA_NOMBRE) ?> trabajando en una puerta en Montevideo"
           class="quienes-somos__img"
           width="900" height="1100" loading="lazy" decoding="async">
    </div>

    <div class="quienes-somos__content">
      <h2 class="quienes-somos__title" id="quienes-somos-titulo">Quiénes somos</h2>

      <p class="quienes-somos__lead">
        Somos <strong><?= htmlspecialchars(EMPRESA_NOMBRE) ?></strong>, <a href="<?= $url ?>local/cerrajero-24-horas-montevideo">cerrajeros de urgencia en Montevideo</a> con más de 10 años abriendo puertas.
      </p>

      <p class="quienes-somos__text">
        Vamos a tu casa, apartamento o auto en <a href="<?= $url ?>paginas/zonas">todos los barrios de Montevideo</a>, las 24 horas.
        Te decimos el precio antes de salir y probamos que todo funcione antes de irnos.
      </p>

      <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="quienes-somos__btn" target="_blank" rel="noopener">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
      </a>
    </div>

  </div>
</section>
