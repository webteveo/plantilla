<?php
/**
 * Hero (diseño original: eyebrow, H1 con parte en itálica, subtítulo, botón WhatsApp + botón llamar).
 * Recibe $pagina. Para la home usa contenido.hero; para el resto parte el H1 en el último " en ".
 */
$seo = $pagina['seo'];
if ($pagina['tipo'] === 'home') {
    $h = $pagina['contenido']['hero'] ?? [];
    $titulo_em = t($h['titulo_em'] ?? cfg('marca.nombre'), $pagina['vars']);
    $titulo    = t($h['titulo'] ?? '', $pagina['vars']);
} else {
    $pos = mb_strrpos($seo['h1'], ' en ');
    $titulo_em = $pos !== false ? mb_substr($seo['h1'], 0, $pos) : $seo['h1'];
    $titulo    = $pos !== false ? mb_substr($seo['h1'], $pos + 1) : '';
}
$img = ['desktop' => cfg('imagenes.hero_desktop'), 'mobile' => cfg('imagenes.hero_mobile'), 'mobile_2x' => cfg('imagenes.hero_mobile_2x'), 'alt' => t((string)cfg('imagenes.hero_alt'), $pagina['vars'])];
if (!empty($pagina['imagen']) && $pagina['tipo'] !== 'home') { $img['desktop'] = $pagina['imagen']; $img['alt'] = $pagina['imagen_alt'] ?: $img['alt']; }
$llamar = tel_attrs() ? ['attrs' => tel_attrs(), 'label' => cfg('contacto.llamar_label')] : ['attrs' => 'href="' . e(url('contacto')) . '"', 'label' => 'Contacto'];
?>
<section class="hero" id="inicio" aria-label="<?= e(cfg('marca.slogan')) ?>">

  <div class="hero__bg">
    <?php if (!empty($img['desktop']) || !empty($img['mobile'])): ?>
    <picture>
      <?php if (!empty($img['mobile'])): ?>
      <source media="(max-width: 600px)" srcset="<?= e(url('assets/img/' . $img['mobile'])) ?><?= !empty($img['mobile_2x']) ? ', ' . e(url('assets/img/' . $img['mobile_2x'])) . ' 2x' : '' ?>">
      <?php endif; ?>
      <?php if (!empty($img['desktop'])): ?>
      <img src="<?= e(url('assets/img/' . $img['desktop'])) ?>" alt="<?= e($img['alt']) ?>" class="hero__bg-img" loading="eager" fetchpriority="high" width="1700" height="956" decoding="async">
      <?php else: ?>
      <img src="<?= e(url('assets/img/' . $img['mobile'])) ?>" alt="<?= e($img['alt']) ?>" class="hero__bg-img hero__bg-img--solo-mobile" loading="eager" fetchpriority="high" width="720" height="1279" decoding="async">
      <?php endif; ?>
    </picture>
    <?php endif; ?>
    <div class="hero__bg-overlay"></div>
  </div>

  <div class="container hero__layout">
    <div class="hero__content">

      <div class="hero__eyebrow">
        <span class="hero__eyebrow-line"></span>
        <?= e($seo['eyebrow']) ?>
      </div>

      <h1 class="hero__title">
        <em><?= e($titulo_em) ?></em><?php if ($titulo !== ''): ?><br>
        <?= e($titulo) ?><?php endif; ?>
      </h1>

      <?php if ($seo['subtitulo']): ?><p class="hero__subtitle"><?= e($seo['subtitulo']) ?></p><?php endif; ?>

      <div class="hero__actions">
        <a <?= wsp_attrs($pagina) ?> class="hero__btn-primary">
          <i class="ri-whatsapp-line"></i>
          <?= e(cfg('contacto.cta_label')) ?>
        </a>
        <a <?= $llamar['attrs'] ?> class="hero__btn-secondary">
          <i class="ri-phone-line" aria-hidden="true"></i> <?= e($llamar['label']) ?>
        </a>
      </div>

    </div>
  </div>

  <?php $stats = (array)($pagina['comun']['stats'] ?? []); if ($stats): ?>
  <div class="hero__stats-bar">
    <div class="hero__stats-inner">
      <?php foreach ($stats as $st): ?>
      <div class="hero__stat">
        <strong><?= e($st['n']) ?><sup><?= e($st['sup'] ?? '') ?></sup></strong>
        <span><?= e($st['label']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</section>
