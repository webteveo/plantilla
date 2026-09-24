<!-- Precios Section (SEO) -->
<?php
/**
 * Seccion "de que depende el precio". COMPLETAR. Regla del sitio: no se publican precios.
 * Cada card: icono, titulo, desc, factores[], wa_text. Si 'cards' queda vacio, solo se muestra el texto general.
 */
$precios = [
    'titulo_em' => 'un cerrajero en Montevideo',
    'lead' => 'El precio depende del tipo de puerta, la cerradura, su estado y el trabajo necesario. Contanos qué sucede y en qué barrio estás para recibir un <strong>presupuesto sin cargo</strong>.',
    'cards' => [
        [
            'icono' => 'ri-home-4-line',
            'titulo' => 'Apertura de puertas',
            'desc' => 'El costo de una <strong>apertura de puerta</strong> depende del mecanismo y de si está cerrada de golpe, con llave o trabada.',
            'factores' => ['Tipo de puerta', 'Modelo de cerradura', 'Estado del mecanismo', 'Puerta cerrada con llave o de golpe', 'Barrio y horario solicitado'],
            'wa_text' => 'Hola! Quiero consultar el precio de una apertura de puerta.',
        ],
        [
            'icono' => 'ri-building-2-line',
            'titulo' => 'Cambio de cerradura o cilindro',
            'desc' => 'El <strong>cambio de cerradura o cilindro</strong> se cotiza según el repuesto compatible y la instalación necesaria.',
            'factores' => ['Marca y modelo existentes', 'Medidas del cilindro o cerradura', 'Tipo de repuesto elegido', 'Adaptaciones de la puerta', 'Cantidad de cerraduras a cambiar'],
            'wa_text' => 'Hola! Quiero cotizar un cambio de cerradura o cilindro.',
        ],
        [
            'icono' => 'ri-truck-line',
            'titulo' => 'Reparación o instalación',
            'desc' => 'Una <strong>reparación o instalación</strong> requiere evaluar el estado de la puerta y las piezas que necesita.',
            'factores' => ['Falla que presenta la cerradura', 'Estado de la puerta y el marco', 'Repuestos necesarios', 'Complejidad de la instalación', 'Barrio y horario solicitado'],
            'wa_text' => 'Hola! Quiero cotizar una reparación o instalación de cerradura.',
        ],
    ],
];
?>
<section class="precios" id="precios" aria-labelledby="precios-titulo">
  <div class="container">

    <div class="precios__header">
      <span class="precios__eyebrow">Presupuestos</span>
      <h2 class="precios__title" id="precios-titulo">
        ¿Cuánto cuesta <em><?= htmlspecialchars($precios['titulo_em']) ?></em>?
      </h2>
      <p class="precios__lead"><?= $precios['lead'] ?></p>
    </div>

    <?php if ($precios['cards']): ?>
    <div class="precios__grid">
      <?php foreach ($precios['cards'] as $i => $pc): ?>
      <article class="precio-card" aria-labelledby="precio-card-<?= $i ?>">
        <header class="precio-card__head">
          <span class="precio-card__icon" aria-hidden="true"><i class="<?= htmlspecialchars($pc['icono']) ?>"></i></span>
          <h3 class="precio-card__title" id="precio-card-<?= $i ?>"><?= htmlspecialchars($pc['titulo']) ?></h3>
        </header>
        <p class="precio-card__desc"><?= $pc['desc'] ?></p>
        <?php if (!empty($pc['factores'])): ?>
        <p class="precio-card__factors-label">Factores que definen el precio</p>
        <ul class="precio-card__factors" role="list">
          <?php foreach ($pc['factores'] as $f): ?><li><?= htmlspecialchars($f) ?></li><?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <a href="<?= htmlspecialchars(wsp_href($pc['wa_text'] ?? CONTACTO_WHATSAPP_MENSAJE)) ?>"
           class="precio-card__cta" target="_blank" rel="noopener">
          <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?> <i class="ri-arrow-right-line" aria-hidden="true"></i>
        </a>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="precios__info">
      <div class="precios__info-block">
        <h3 class="precios__info-title">¿Por qué no publicamos una lista de precios?</h3>
        <p>
          Porque abrir una puerta cerrada de golpe no requiere el mismo trabajo que reemplazar una
          cerradura dañada. Primero evaluamos tu caso y los repuestos necesarios para
          explicarte el alcance y el precio antes de intervenir.
        </p>
      </div>
      <div class="precios__info-block">
        <h3 class="precios__info-title">Presupuesto sin cargo y sin compromiso</h3>
        <p>
          Contanos qué pasa con tu puerta, en qué barrio estás y, si podés, enviá una foto de la cerradura.
          Te indicamos las opciones y qué incluye el presupuesto. No cobramos por cotizar.
        </p>
      </div>
    </div>

    <div class="precios__cta">
      <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>"
         class="btn btn--whatsapp btn--lg" target="_blank" rel="noopener">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
      </a>
      <p class="precios__cta-note">Respondemos por correo y coordinamos según tu zona.</p>
    </div>

  </div>
</section>
