<!-- Footer -->
<?php
require_once 'src/controlador/Local_Controller.php';
$footerServicio = array_key_first(Local_Controller::CITY_SERVICES) ?: 'cerrajero';
$footerZonas    = ['pocitos' => 'Pocitos', 'centro' => 'Centro', 'carrasco' => 'Carrasco', 'malvin' => 'Malvín', 'prado' => 'Prado', 'cordon' => 'Cordón', 'union' => 'Unión', 'cerro' => 'Cerro'];
?>
<footer class="footer" role="contentinfo" aria-label="Pie de pagina">
  <div class="container">

    <div class="footer__top">

      <div class="footer__brand">
        <a href="<?= $url ?>" class="footer__logo" aria-label="<?= EMPRESA_NOMBRE ?> - Inicio">
          <img src="<?= $ruta ?>/<?= str_replace('public/', '', LOGO_HEADER_CLARO) ?>"
               alt="<?= EMPRESA_NOMBRE ?>"
               width="152" height="42" loading="lazy">
        </a>
        <p class="footer__tagline">Cerrajero de urgencia en Montevideo, las 24 horas. Aperturas, cambios y reparación de cerraduras.</p>
        <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="footer__wa" target="_blank" rel="noopener">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
        </a>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Sitio</h3>
        <ul class="footer__list" role="list">
          <li><a href="<?= $url ?>paginas/servicios">Servicios</a></li>
          <li><a href="<?= $url ?>local/cerrajero-a-domicilio-montevideo">Cerrajero a domicilio</a></li>
          <li><a href="<?= $url ?>local/apertura-de-puertas-montevideo">Apertura de puertas</a></li>
          <li><a href="<?= $url ?>local/cerrajero-automotriz-montevideo">Cerrajero automotriz</a></li>
          <li><a href="<?= $url ?>local/cambio-de-cerraduras-montevideo">Cambio de cerraduras</a></li>
          <li><a href="<?= $url ?>local/reparacion-de-cerraduras-montevideo">Reparación de cerraduras</a></li>
          <li><a href="<?= $url ?>local/cerraduras-de-seguridad-montevideo">Cerraduras de seguridad</a></li>
          <li><a href="<?= $url ?>local/cerraduras-digitales-montevideo">Cerraduras digitales</a></li>
          <li><a href="<?= $url ?>local/copia-de-llaves-montevideo">Copia de llaves</a></li>
          <li><a href="<?= $url ?>local/cerrajero-24-horas-montevideo">Cerrajero 24 horas</a></li>
          <li><a href="<?= $url ?>paginas/zonas">Zonas</a></li>
          <li><a href="<?= $url ?>articulos">Guías</a></li>
          <li><a href="<?= $url ?>resena">Reseñas</a></li>
          <li><a href="<?= $url ?>contacto">Contacto</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Zonas</h3>
        <ul class="footer__list" role="list">
          <?php foreach ($footerZonas as $fz => $fzNombre): ?>
          <li><a href="<?= $url ?>local/<?= $footerServicio . '-' . $fz ?>">Cerrajero en <?= htmlspecialchars($fzNombre) ?></a></li>
          <?php endforeach; ?>
          <li><a href="<?= $url ?>paginas/zonas">Todos los barrios</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h3 class="footer__heading">Contacto</h3>
        <ul class="footer__contact" role="list">
          <?php if (CONTACTO_TELEFONO): ?>
          <li><i class="ri-phone-line" aria-hidden="true"></i><a href="tel:+<?= CONTACTO_TELEFONO ?>"><?= htmlspecialchars(CONTACTO_TELEFONO_VISIBLE) ?></a></li>
          <?php endif; ?>
          <li><i class="ri-mail-line" aria-hidden="true"></i><a href="mailto:<?= CONTACTO_EMAIL ?>"><?= CONTACTO_EMAIL ?></a></li>
          <li><i class="ri-map-pin-line" aria-hidden="true"></i><span><?= htmlspecialchars(DIRECCION_COMPLETA) ?></span></li>
          <li><i class="ri-time-line" aria-hidden="true"></i><span>24 horas, todos los días</span></li>
        </ul>
        <?php if (REDES_INSTAGRAM || REDES_FACEBOOK): ?>
        <div class="footer__social" aria-label="Redes sociales">
          <?php if (REDES_INSTAGRAM): ?><a href="<?= REDES_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="ri-instagram-line" aria-hidden="true"></i></a><?php endif; ?>
          <?php if (REDES_FACEBOOK): ?><a href="<?= REDES_FACEBOOK ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="ri-facebook-circle-line" aria-hidden="true"></i></a><?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

    </div>

    <div class="footer__bottom">
      <p class="footer__copy">&copy; <?= date('Y') ?> <?= EMPRESA_NOMBRE ?>. Todos los derechos reservados.</p>
    </div>

  </div>
</footer>

<!-- WhatsApp flotante -->
<a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
   class="whatsapp-float"
   target="_blank" rel="noopener"
   aria-label="Escribinos por WhatsApp">
  <i class="ri-whatsapp-line" aria-hidden="true"></i>
</a>
