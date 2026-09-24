<a class="skip-link" href="#main-content">Ir al contenido</a>

<div class="site-top" id="site-top">

<header class="header" id="header" role="banner" aria-label="Encabezado de <?= EMPRESA_NOMBRE ?>">
  <div class="container header__inner">
    <a href="<?= $url ?>" class="header__logo" aria-label="<?= EMPRESA_NOMBRE ?> - Inicio">
      <img src="<?= $ruta ?>/<?= str_replace('public/', '', LOGO_HEADER_CLARO) ?>" alt="<?= EMPRESA_NOMBRE ?>" width="188" height="52" loading="eager" fetchpriority="high" class="header__logo-img">
    </a>

    <nav class="header__nav" aria-label="Navegacion principal">
      <ul class="header__nav-list" role="list">
        <li><a href="<?= $url ?>paginas/servicios">Servicios</a></li>
        <li><a href="<?= $url ?>paginas/zonas">Zonas</a></li>
        <li><a href="<?= $url ?>articulos">Guías</a></li>
        <li><a href="<?= $url ?>contacto">Contacto</a></li>
      </ul>
    </nav>

    <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
       class="header__cta" target="_blank" rel="noopener">
      <i class="ri-whatsapp-line" aria-hidden="true"></i>
      <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
    </a>

    <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
       class="header__cta-mobile" target="_blank" rel="noopener" aria-label="<?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>">
      <i class="ri-whatsapp-line" aria-hidden="true"></i>
    </a>

    <button class="header__hamburger" id="hamburger" aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="header__mobile-menu" id="mobile-menu" aria-hidden="true" role="navigation" aria-label="Menu movil">
    <div class="mobile-menu__inner">

      <ul class="mobile-menu__nav" role="list">
        <li><a href="<?= $url ?>paginas/servicios" class="mobile-link">Servicios</a></li>
        <li><a href="<?= $url ?>paginas/zonas" class="mobile-link">Zonas</a></li>
        <li><a href="<?= $url ?>articulos" class="mobile-link">Guías</a></li>
        <li><a href="<?= $url ?>contacto" class="mobile-link">Contacto</a></li>
      </ul>

      <div class="mobile-menu__bottom">
        <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
           class="mobile-menu__wsp" target="_blank" rel="noopener">
          <i class="ri-whatsapp-line" aria-hidden="true"></i> <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
        </a>
        <?php if (CONTACTO_TELEFONO): ?>
        <a href="tel:+<?= CONTACTO_TELEFONO ?>" class="mobile-menu__tel"><i class="ri-phone-line" aria-hidden="true"></i> <?= htmlspecialchars(CONTACTO_TELEFONO_VISIBLE) ?></a>
        <?php endif; ?>
        <p class="mobile-menu__note">Atención 24 horas en Montevideo</p>
      </div>

    </div>
  </div>
</header>

</div>
