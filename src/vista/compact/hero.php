<!-- Hero Section -->
<?php
// ── Contenido del hero de la home. COMPLETAR. ──
// Las landings de zona pasan $hero_override (eyebrow, title_em, title, subtitle, cta_message) y reutilizan este mismo hero.
$hero_override  = $hero_override ?? [];
$hero_eyebrow   = $hero_override['eyebrow']   ?? 'Cerrajería a domicilio en Montevideo';
$hero_title_em  = $hero_override['title_em']  ?? 'Cerrajero 24/7';           // parte en italica
$hero_title     = $hero_override['title']     ?? 'en Montevideo';      // resto del H1
$hero_subtitle  = $hero_override['subtitle']  ?? 'Apertura de puertas y cambio de cerraduras. Consultá presupuesto.';
$hero_cta_label = CTA_WHATSAPP_LABEL;
$hero_cta_message = $hero_override['cta_message'] ?? 'Hola! Necesito un cerrajero con urgencia en Montevideo. Quisiera consultar disponibilidad.';
$hero_cta_2     = ['href' => CONTACTO_TELEFONO !== '' ? 'tel:+' . CONTACTO_TELEFONO : $url . 'contacto', 'label' => 'Llamar Cerrajero'];

// Imagen de fondo (relativa a public/images/). 'desktop' y/o 'mobile'; si falta una, esa vista queda con fondo oscuro.
$hero_img = [
    'desktop'   => null,                          // ej. 'hero/hero-desktop.webp'
    'mobile'    => 'hero/cerrajero-mobile-720.webp', // se muestra hasta 600px de ancho
    'mobile_2x' => 'hero/cerrajero-mobile.webp',     // pantallas de alta densidad
    'alt'       => 'Camioneta de Cerrajero Montevideo circulando por la ruta',
];
// Slideshow de fondo solo mobile (rutas relativas a public/images/). Vacio = sin slideshow.
$hero_slides     = [];
// Numeros de la barra de stats. Vacio = no se muestra. ej. ['n' => '500', 'sup' => '+', 'label' => 'Servicios realizados']
$hero_stats      = [];
// Testimonios (uno al azar por carga). Vacio = no se muestra la tarjeta.
$hero_testimonios = [];

$hero_slides_json = htmlspecialchars(json_encode(array_map(
    static fn($s) => $ruta . '/images/' . $s,
    $hero_slides
), JSON_UNESCAPED_SLASHES), ENT_QUOTES);
$hero_testimonio = $hero_testimonios ? $hero_testimonios[array_rand($hero_testimonios)] : null;
?>
<section class="hero" id="inicio" aria-label="<?= htmlspecialchars(EMPRESA_SLOGAN) ?>">

  <div class="hero__bg" <?= $hero_slides ? "data-slides='{$hero_slides_json}'" : '' ?> style="background:#0a0a0a;">
    <?php if (!empty($hero_img['desktop']) || !empty($hero_img['mobile'])): ?>
    <picture>
      <?php if (!empty($hero_img['mobile'])): ?>
      <source media="(max-width: 600px)"
              srcset="<?= $ruta ?>/images/<?= htmlspecialchars($hero_img['mobile']) ?><?= !empty($hero_img['mobile_2x']) ? ', ' . $ruta . '/images/' . htmlspecialchars($hero_img['mobile_2x']) . ' 2x' : '' ?>">
      <?php endif; ?>
      <?php if (!empty($hero_img['desktop'])): ?>
      <img src="<?= $ruta ?>/images/<?= htmlspecialchars($hero_img['desktop']) ?>"
           alt="<?= htmlspecialchars($hero_img['alt'] ?? '') ?>"
           class="hero__bg-img"
           loading="eager" fetchpriority="high" width="1700" height="956" decoding="async">
      <?php else: ?>
      <img src="<?= $ruta ?>/images/<?= htmlspecialchars($hero_img['mobile']) ?>"
           alt="<?= htmlspecialchars($hero_img['alt'] ?? '') ?>"
           class="hero__bg-img hero__bg-img--solo-mobile"
           loading="eager" fetchpriority="high" width="720" height="1279" decoding="async">
      <?php endif; ?>
    </picture>
    <?php endif; ?>
    <div class="hero__bg-overlay"></div>
  </div>

  <div class="container hero__layout">

    <div class="hero__content">

      <div class="hero__eyebrow">
        <span class="hero__eyebrow-line"></span>
        <?= htmlspecialchars($hero_eyebrow) ?>
      </div>

      <h1 class="hero__title">
        <em><?= htmlspecialchars($hero_title_em) ?></em><br>
        <?= htmlspecialchars($hero_title) ?>
      </h1>

      <p class="hero__subtitle"><?= htmlspecialchars($hero_subtitle) ?></p>

      <div class="hero__actions">
        <a href="<?= htmlspecialchars(wsp_href($hero_cta_message)) ?>"
           class="hero__btn-primary"
           target="_blank" rel="noopener">
          <i class="ri-whatsapp-line"></i>
          <?= htmlspecialchars($hero_cta_label) ?>
        </a>
        <a href="<?= htmlspecialchars($hero_cta_2['href']) ?>" class="hero__btn-secondary">
          <i class="ri-phone-line" aria-hidden="true"></i> <?= htmlspecialchars($hero_cta_2['label']) ?>
        </a>
      </div>

    </div>

  </div>

  <?php if ($hero_stats || $hero_testimonio): ?>
  <div class="hero__stats-bar">
    <?php if ($hero_stats): ?>
    <div class="hero__stats-inner">
      <?php foreach ($hero_stats as $st): ?>
      <div class="hero__stat">
        <strong><?= htmlspecialchars($st['n']) ?><sup><?= htmlspecialchars($st['sup'] ?? '') ?></sup></strong>
        <span><?= htmlspecialchars($st['label']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($hero_testimonio): ?>
    <div class="hero__review">
      <div class="hero__review-card">
        <div class="hero__review-header">
          <div class="hero__review-avatar"><?= htmlspecialchars(mb_substr($hero_testimonio['nombre'], 0, 1)) ?></div>
          <div class="hero__review-meta">
            <div class="hero__review-name"><?= htmlspecialchars($hero_testimonio['nombre']) ?></div>
            <div class="hero__review-stars">
              <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i>
            </div>
          </div>
        </div>
        <p class="hero__review-text"><?= htmlspecialchars($hero_testimonio['texto']) ?></p>
        <div class="hero__review-footer">
          <i class="ri-check-line" aria-hidden="true"></i>
          <?= htmlspecialchars($hero_testimonio['tag'] ?? '') ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</section>
