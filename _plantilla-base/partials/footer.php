<?php
/** Footer: servicios (troncales), zonas de primer nivel, contacto. Todo sale de los datos. */
$fServicios = servicios();
$fZonas = array_filter(zonas_raiz(), fn($z) => zona_servicios($z['slug']));
// Si hay una sola zona raíz, mostrar sus hijas
if (count($fZonas) === 1) { $unica = reset($fZonas); $fZonas = zonas_hijas($unica['slug']) ?: $fZonas; $fZonaPadre = $unica; } else { $fZonaPadre = null; }
$fZonas = array_slice($fZonas, 0, 10, true);
$fServicioPrincipal = array_key_first($fServicios);
$fHorario = cfg('horario.urgencias_24h') ? '24 horas, todos los días' : (cfg('horario.texto') ?: '');
$redes = array_filter((array)cfg('redes', []));
?>
<footer class="footer" role="contentinfo" aria-label="Pie de página">
  <div class="container">

    <div class="footer__top">

      <div class="footer__brand">
        <a href="<?= e(url('')) ?>" class="footer__logo" aria-label="<?= e(cfg('marca.nombre')) ?> - Inicio">
          <img src="<?= e(url('assets/' . cfg('logos.footer'))) ?>" alt="<?= e(cfg('marca.nombre')) ?>" width="152" height="42" loading="lazy">
        </a>
        <p class="footer__tagline"><?= e(cfg('ui.footer_nota') ?: cfg('marca.descripcion')) ?></p>
        <a <?= wsp_attrs($pagina) ?> class="footer__wa">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          <?= e(cfg('contacto.cta_label')) ?>
        </a>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Servicios</h3>
        <ul class="footer__list" role="list">
          <?php foreach ($fServicios as $s): ?>
          <li><a href="<?= e(url($s['slug'])) ?>"><?= e($s['nombre_h1']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="<?= e(url('nosotros')) ?>">Nosotros</a></li>
          <li><a href="<?= e(url('contacto')) ?>">Contacto</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Zonas</h3>
        <ul class="footer__list" role="list">
          <?php foreach ($fZonas as $z): ?>
          <?php if ($fServicioPrincipal && combinacion_existe($fServicioPrincipal, $z['slug'])): ?>
          <li><a href="<?= e(url($fServicioPrincipal . '/' . $z['slug'])) ?>"><?= e($fServicios[$fServicioPrincipal]['nombre_h1']) ?> en <?= e($z['nombre']) ?></a></li>
          <?php else: ?>
          <li><a href="<?= e(url($z['slug'])) ?>">Servicios en <?= e($z['nombre']) ?></a></li>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php if ($fZonaPadre): ?>
          <li><a href="<?= e(url($fZonaPadre['slug'])) ?>">Todas las zonas de <?= e($fZonaPadre['nombre']) ?></a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Contacto</h3>
        <ul class="footer__contact" role="list">
          <?php if (tel_attrs()): ?>
          <li><i class="ri-phone-line" aria-hidden="true"></i><a <?= tel_attrs() ?>><?= e(telefono_visible()) ?></a></li>
          <?php endif; ?>
          <?php if (cfg('contacto.email')): ?>
          <li><i class="ri-mail-line" aria-hidden="true"></i><a href="mailto:<?= e(cfg('contacto.email')) ?>"><?= e(cfg('contacto.email')) ?></a></li>
          <?php endif; ?>
          <li><i class="ri-map-pin-line" aria-hidden="true"></i><span><?= e(cfg('direccion.texto')) ?></span></li>
          <?php if ($fHorario): ?><li><i class="ri-time-line" aria-hidden="true"></i><span><?= e($fHorario) ?></span></li><?php endif; ?>
        </ul>
        <?php if ($redes): ?>
        <div class="footer__social" aria-label="Redes sociales">
          <?php $iconos = ['instagram' => 'ri-instagram-line', 'facebook' => 'ri-facebook-circle-line', 'youtube' => 'ri-youtube-line', 'tiktok' => 'ri-tiktok-line', 'linkedin' => 'ri-linkedin-box-line', 'x' => 'ri-twitter-x-line']; ?>
          <?php foreach ($redes as $k => $href): ?>
          <a href="<?= e($href) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($k)) ?>"><i class="<?= e($iconos[$k] ?? 'ri-link') ?>" aria-hidden="true"></i></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

    </div>

    <div class="footer__bottom">
      <p class="footer__copy">&copy; <?= date('Y') ?> <?= e(cfg('marca.nombre_corto') ?: cfg('marca.nombre')) ?>. Todos los derechos reservados.
        <?php if (cfg('ui.legales', true)): ?> · <a href="<?= e(url('privacidad')) ?>">Privacidad</a> · <a href="<?= e(url('terminos')) ?>">Términos</a><?php endif; ?>
      </p>
    </div>

  </div>
</footer>

<!-- WhatsApp flotante -->
<a <?= wsp_attrs($pagina) ?> class="whatsapp-float" aria-label="Escribinos por WhatsApp">
  <i class="ri-whatsapp-line" aria-hidden="true"></i>
</a>
