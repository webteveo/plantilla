<?php
/**
 * Plantilla de landing de zona: misma estructura y secciones que la home.
 * Solo cambian el hero (H1, bajada, mensaje de WhatsApp) y las preguntas frecuentes, que vienen del array $landing.
 *   Usa: slug, title, description, keywords, canonical, schema_blocks, hero_title, hero_subtitle, cta_message, faq[], zona_label
 */
$page_title         = $landing['title'];
$page_description   = $landing['description'];
$page_keywords      = $landing['keywords'];
$page_canonical     = $landing['canonical'];
$page_schema_blocks = $landing['schema_blocks'];
$page_preload_images = [['href' => $ruta . '/images/hero/cerrajero-mobile-720.webp', 'media' => '(max-width: 600px)']];

$lp_zona = $landing['zona_label'] ?? 'Montevideo';

// H1: "Cerrajero 24/7" en italica + "en {Zona}" debajo (mismo formato que la home)
$lp_en_pos  = strrpos($landing['hero_title'], ' en ');
$lp_title_em = $lp_en_pos !== false ? substr($landing['hero_title'], 0, $lp_en_pos) : $landing['hero_title'];
$lp_title    = $lp_en_pos !== false ? substr($landing['hero_title'], $lp_en_pos + 1) : '';
if (stripos($lp_title_em, '24/7') === false && stripos($lp_title_em, '24 horas') === false) $lp_title_em .= ' 24/7';

$hero_override = [
    'eyebrow'     => $landing['hero_eyebrow'] ?? ('Cerrajería a domicilio en ' . $lp_zona),
    'title_em'    => $lp_title_em,
    'title'       => $lp_title,
    'subtitle'    => $landing['hero_subtitle'],
    'cta_message' => $landing['cta_message'],
];

// Servicio y zona actuales (coincidencia mas larga: 'cerrajero-24-horas' antes que 'cerrajero')
$lp_servicios = Local_Controller::servicios();
uksort($lp_servicios, fn($a, $b) => strlen($b) <=> strlen($a));
$lp_servicio = 'cerrajero';
foreach (array_keys($lp_servicios) as $lp_srv) {
    if (str_starts_with($landing['slug'], $lp_srv . '-')) { $lp_servicio = $lp_srv; break; }
}
$lp_servicio_nombre = $lp_servicios[$lp_servicio];
$lp_zona_slug = substr($landing['slug'], strlen($lp_servicio) + 1);
$lp_cerca     = Local_Controller::zonasTodas()[$lp_zona_slug]['cerca'] ?? '';

// Chips de barrios (compact/zonas-home.php) enlazan a este mismo servicio
$zhServicio = $lp_servicio;
$zhTitulo   = $lp_servicio_nombre . ' en todos los barrios de Montevideo';

// Migas
$lp_migas = [['href' => $url, 'label' => 'Inicio']];
if ($lp_zona_slug !== 'montevideo') $lp_migas[] = ['href' => $url . 'local/' . $lp_servicio . '-montevideo', 'label' => $lp_servicio_nombre];
$lp_migas[] = ['label' => $lp_zona_slug === 'montevideo' ? $lp_servicio_nombre . ' en Montevideo' : $lp_zona];

// FAQ de la zona (compact/faq.php usa $faq_items si ya esta definido)
$faq_items = !empty($landing['faq']) ? $landing['faq'] : null;

require 'src/vista/partials/head.php';
?>
<body class="home-page">
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content">
    <?php require 'src/vista/compact/hero.php'; ?>
    <nav class="migas" aria-label="Ubicación en el sitio">
      <ol class="container migas__list">
        <?php foreach ($lp_migas as $lp_i => $lp_m): ?>
        <li><?php if (isset($lp_m['href'])): ?><a href="<?= htmlspecialchars($lp_m['href']) ?>"><?= htmlspecialchars($lp_m['label']) ?></a><?php else: ?><span aria-current="page"><?= htmlspecialchars($lp_m['label']) ?></span><?php endif; ?></li>
        <?php endforeach; ?>
      </ol>
    </nav>
    <?php require 'src/vista/compact/testimonios.php'; ?>
    <?php require 'src/vista/compact/servicios.php'; ?>
    <?php require 'src/vista/compact/stats-strip.php'; ?>
    <?php require 'src/vista/compact/zona-texto.php'; ?>
    <?php require 'src/vista/compact/pasos.php'; ?>
    <?php require 'src/vista/compact/quienes-somos.php'; ?>
    <?php require 'src/vista/compact/diferenciadores.php'; ?>
    <?php require 'src/vista/compact/zonas-home.php'; ?>
    <?php require 'src/vista/compact/faq.php'; ?>
    <?php require 'src/vista/compact/relacionados.php'; ?>
    <?php require 'src/vista/compact/contacto-home.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
