<!-- Zonas Section -->
<?php
require_once 'src/controlador/Local_Controller.php';
$zonasPadre     = Local_Datos::ZONAS_PADRE;
$zonasServicios = Local_Controller::servicios();
$zonasPrimerSrv = array_key_first($zonasServicios);

/** Bloques descriptivos por zona padre. COMPLETAR. Clave = clave de ZONAS_PADRE. */
$zonas_desc = [
    'montevideo'   => ['icono' => 'ri-building-line', 'texto' => 'Cerrajería a domicilio en los barrios de Montevideo, con horario y visita coordinados.'],
];

// Directorio automatico de todas las localidades con landing (ver Local_Datos::CITY_DATA_EXTRA)
$lpDeptos = [];
foreach (Local_Controller::ciudades() as $lpCk => $lpC) { $lpDeptos[$lpC['depto']][$lpCk] = $lpC['nombre']; }
?>
<section class="zonas" id="zonas" aria-labelledby="zonas-titulo">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title" id="zonas-titulo">Zonas de <span>Cobertura</span></h2>
      <p class="section-subtitle">Trabajamos en <?= htmlspecialchars(DIRECCION_COMPLETA) ?> y alrededores con presupuesto sin cargo.</p>
    </div>

    <div class="zonas__inner">
      <div class="zonas__panel zonas__panel--map">
        <div class="zonas__map-card">
          <div class="zonas__map-header">
            <div>
              <strong>Mapa de cobertura</strong>
              <span><?= htmlspecialchars(DIRECCION_COMPLETA) ?></span>
            </div>
            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode(DIRECCION_COMPLETA) ?>"
               target="_blank" rel="noopener" class="zonas__map-link">
              Abrir en Google Maps
            </a>
          </div>

          <div class="zonas__map-frame">
            <iframe
              title="Mapa de cobertura de <?= htmlspecialchars(EMPRESA_NOMBRE) ?>"
              src="https://www.google.com/maps?q=<?= urlencode(DIRECCION_COMPLETA) ?>&z=10&output=embed"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              allowfullscreen></iframe>
          </div>

          <div class="zonas__map-footer" aria-label="Referencias del mapa">
            <span><i class="ri-checkbox-circle-line" aria-hidden="true"></i> Base operativa en <?= htmlspecialchars(DIRECCION_CIUDAD) ?></span>
            <span><i class="ri-route-line" aria-hidden="true"></i> Otras localidades: consultar disponibilidad</span>
          </div>
        </div>
      </div>

      <div class="zonas__panel zonas__panel--content">
        <div class="zonas__eyebrow">
          <span class="zonas__eyebrow-line"></span>
          Zonas de consulta
        </div>

        <p class="zonas__desc">
          Coordinamos servicios de cerrajería en Montevideo. Para las demás zonas, confirmá cobertura y disponibilidad antes de agendar.
        </p>

        <div class="zonas__chips" aria-label="Cobertura principal">
          <?php foreach ($zonasPadre as $zp): ?>
          <span class="zonas__chip"><?= htmlspecialchars($zp['nombre']) ?></span>
          <?php endforeach; ?>
        </div>

        <div class="zonas__list" aria-label="Zonas de trabajo">
          <?php foreach ($zonasPadre as $zk => $zp): ?>
          <article class="zonas__item">
            <div class="zonas__item-icon">
              <i class="<?= htmlspecialchars($zonas_desc[$zk]['icono'] ?? 'ri-map-pin-2-line') ?>" aria-hidden="true"></i>
            </div>
            <div class="zonas__item-text">
              <strong><?= htmlspecialchars($zp['nombre']) ?></strong>
              <span><?= htmlspecialchars($zonas_desc[$zk]['texto'] ?? $zp['cerca']) ?></span>
            </div>
          </article>
          <?php endforeach; ?>
        </div>

        <div class="zonas__actions">
          <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
             class="zonas__cta" target="_blank" rel="noopener">
            <i class="ri-whatsapp-line" aria-hidden="true"></i>
            <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($zonasServicios): ?>
<section class="local-links" aria-labelledby="local-links-title">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title" id="local-links-title">Servicios por <span>zona</span></h2>
      <p class="section-subtitle">Páginas con información detallada de cada servicio según la zona.</p>
    </div>
    <div class="local-links__grid">
      <?php foreach ($zonasPadre as $zk => $zp): ?>
      <div class="local-links__col">
        <h3 class="local-links__zone">
          <i class="ri-map-pin-2-fill" aria-hidden="true"></i> <?= htmlspecialchars($zp['nombre']) ?>
        </h3>
        <ul role="list">
          <?php foreach ($zonasServicios as $s => $sName): ?>
          <li><a href="<?= $url ?>local/<?= $s . '-' . $zk ?>"><?= htmlspecialchars($sName) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="local-links" aria-labelledby="todas-zonas-title">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title" id="todas-zonas-title">Todas las <span>localidades</span></h2>
      <p class="section-subtitle"><?= htmlspecialchars($zonasServicios[$zonasPrimerSrv]) ?> localidad por localidad.</p>
    </div>
    <div class="local-links__grid">
      <?php foreach ($lpDeptos as $lpDepto => $lpCiudades): ?>
      <div class="local-links__col">
        <h3 class="local-links__zone"><i class="ri-map-pin-2-fill" aria-hidden="true"></i> <?= htmlspecialchars($lpDepto) ?></h3>
        <ul role="list">
          <?php foreach ($lpCiudades as $lpCk => $lpNombre): ?>
          <li><a href="<?= $url ?>local/<?= $zonasPrimerSrv ?>-<?= $lpCk ?>"><?= htmlspecialchars($lpNombre) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
