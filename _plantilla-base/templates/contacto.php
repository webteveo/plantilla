<?php
/** /contacto y /contacto/gracias */
$gracias = $pagina['tipo'] === 'contacto-gracias';
$c = $pagina['contenido'];
$horario = cfg('horario.urgencias_24h') ? '24 horas, todos los días' : (cfg('horario.texto') ?: '');
partial('head');
?>
<body>
  <?php partial('header'); ?>

  <main id="main-content" class="section-page">
    <?php if ($gracias): ?>
    <section class="contacto-gracias" aria-labelledby="gracias-title">
      <div class="container">
        <div class="contacto-gracias__inner">
          <div class="contacto-gracias__icon" aria-hidden="true"><i class="ri-checkbox-circle-fill"></i></div>
          <h1 class="contacto-gracias__title" id="gracias-title"><?= e(t($c['gracias_titulo'] ?? '¡Mensaje recibido!', $pagina['vars'])) ?></h1>
          <p class="contacto-gracias__desc"><?= e(t($c['gracias_texto'] ?? 'Gracias por escribirnos. Te respondemos a la brevedad.', $pagina['vars'])) ?></p>
          <div class="contacto-gracias__actions">
            <a href="<?= e(url('')) ?>" class="btn btn--primary"><i class="ri-home-line" aria-hidden="true"></i> Volver al inicio</a>
            <a <?= wsp_attrs($pagina) ?> class="btn btn--whatsapp"><i class="ri-whatsapp-line" aria-hidden="true"></i> <?= e(cfg('contacto.cta_label')) ?></a>
          </div>
        </div>
      </div>
    </section>
    <?php else: ?>
    <?php partial('page-intro'); ?>
    <?php partial('breadcrumbs'); ?>

    <section class="contacto" aria-labelledby="contacto-datos-title">
      <div class="container">
        <div class="contacto__layout">
          <div class="contacto__form-wrap">
            <h2 class="contacto__form-title" id="contacto-datos-title">Cómo contactarnos</h2>
            <ul class="contacto__hours" role="list">
              <li><span>WhatsApp</span><span><a <?= wsp_attrs($pagina) ?>><?= e(cfg('contacto.whatsapp') ? '+' . cfg('contacto.whatsapp') : cfg('contacto.cta_label')) ?></a></span></li>
              <?php if (tel_attrs()): ?><li><span>Teléfono</span><span><a <?= tel_attrs() ?>><?= e(telefono_visible()) ?></a></span></li><?php endif; ?>
              <?php if (cfg('contacto.email')): ?><li><span>Email</span><span><a href="mailto:<?= e(cfg('contacto.email')) ?>"><?= e(cfg('contacto.email')) ?></a></span></li><?php endif; ?>
              <?php if ($horario): ?><li><span>Horario</span><span><?= e($horario) ?></span></li><?php endif; ?>
              <li><span>Zona</span><span><?= e(cfg('direccion.texto')) ?></span></li>
            </ul>
          </div>
          <aside class="contacto__info" aria-label="Zonas de trabajo">
            <div class="contacto__info-block">
              <h3 class="contacto__info-heading"><i class="ri-map-pin-line" aria-hidden="true"></i> Zonas de trabajo</h3>
              <ul class="contacto__zones" role="list">
                <?php foreach (zonas_raiz() as $z): ?><li><i class="ri-checkbox-circle-line" aria-hidden="true"></i> <a href="<?= e(url($z['slug'])) ?>"><?= e($z['nombre']) ?></a></li><?php endforeach; ?>
              </ul>
            </div>
            <div class="contacto__info-block">
              <h3 class="contacto__info-heading"><i class="ri-tools-line" aria-hidden="true"></i> Servicios</h3>
              <ul class="contacto__zones" role="list">
                <?php foreach (servicios() as $s): ?><li><i class="ri-checkbox-circle-line" aria-hidden="true"></i> <a href="<?= e(url($s['slug'])) ?>"><?= e($s['nombre_h1']) ?></a></li><?php endforeach; ?>
              </ul>
            </div>
          </aside>
        </div>
      </div>
    </section>

    <?php partial('cta-whatsapp'); ?>
    <?php endif; ?>
  </main>

  <?php partial('footer'); ?>
  <?php partial('end'); ?>
