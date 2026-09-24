<?php
/** Header + menú móvil. Los links del menú salen de config ui.menu ('servicios' es ancla de la home; 'zonas' es la página /zonas). */
$menu = [];
foreach ((array)cfg('ui.menu', []) as $m) {
    $href = $m['href'] === 'servicios' ? url('') . '#servicios' : url($m['href']);
    $menu[] = ['href' => $href, 'label' => $m['label']];
}
$nota = cfg('horario.urgencias_24h') ? 'Atención 24 horas en ' . ($pagina['zona']['nombre'] ?? cfg('direccion.ciudad')) : (cfg('horario.texto') ?: '');
?>
<a class="skip-link" href="#main-content">Ir al contenido</a>

<div class="site-top" id="site-top">

<header class="header" id="header" role="banner" aria-label="Encabezado de <?= e(cfg('marca.nombre')) ?>">
  <div class="container header__inner">
    <a href="<?= e(url('')) ?>" class="header__logo" aria-label="<?= e(cfg('marca.nombre')) ?> - Inicio">
      <img src="<?= e(url('assets/' . cfg('logos.header'))) ?>" alt="<?= e(cfg('marca.nombre')) ?>" width="<?= (int)cfg('logos.ancho', 188) ?>" height="<?= (int)cfg('logos.alto', 52) ?>" loading="eager" fetchpriority="high" class="header__logo-img">
    </a>

    <nav class="header__nav" aria-label="Navegación principal">
      <ul class="header__nav-list" role="list">
        <?php foreach ($menu as $m): ?>
        <li><a href="<?= e($m['href']) ?>"><?= e($m['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <a <?= wsp_attrs($pagina) ?> class="header__cta">
      <i class="ri-whatsapp-line" aria-hidden="true"></i>
      <?= e(cfg('contacto.cta_label')) ?>
    </a>

    <a <?= wsp_attrs($pagina) ?> class="header__cta-mobile" aria-label="<?= e(cfg('contacto.cta_label')) ?>">
      <i class="ri-whatsapp-line" aria-hidden="true"></i>
    </a>

    <button class="header__hamburger" id="hamburger" aria-label="Abrir menú" aria-expanded="false" aria-controls="mobile-menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="header__mobile-menu" id="mobile-menu" aria-hidden="true" role="navigation" aria-label="Menú móvil">
    <div class="mobile-menu__inner">

      <ul class="mobile-menu__nav" role="list">
        <?php foreach ($menu as $m): ?>
        <li><a href="<?= e($m['href']) ?>" class="mobile-link"><?= e($m['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>

      <div class="mobile-menu__bottom">
        <a <?= wsp_attrs($pagina) ?> class="mobile-menu__wsp">
          <i class="ri-whatsapp-line" aria-hidden="true"></i> <?= e(cfg('contacto.cta_label')) ?>
        </a>
        <?php if (tel_attrs()): ?>
        <a <?= tel_attrs() ?> class="mobile-menu__tel"><i class="ri-phone-line" aria-hidden="true"></i> <?= e(telefono_visible()) ?></a>
        <?php endif; ?>
        <?php if ($nota): ?><p class="mobile-menu__note"><?= e($nota) ?></p><?php endif; ?>
      </div>

    </div>
  </div>
</header>

</div>
