<?php
/** Página troncal de un servicio (/{servicio}): hero, migas, texto, pasos, beneficios, zonas donde se ofrece, FAQ, otros servicios, contacto. */
$L = $pagina['links'];
// Chips de zonas donde existe el servicio (agrupadas por zona padre)
$chips = [];
foreach ($L['zonas'] as $g) {
    if ($g['padre'] && combinacion_existe($pagina['servicio']['slug'], $g['padre']['slug'])) $chips[] = ['href' => url($pagina['servicio']['slug'] . '/' . $g['padre']['slug']), 'label' => $g['padre']['nombre']];
    foreach ($g['items'] as $it) if (!$g['padre'] || $it['href'] !== url($pagina['servicio']['slug'] . '/' . $g['padre']['slug'])) $chips[] = $it;
}
partial('head');
?>
<body class="home-page">
  <?php partial('header'); ?>

  <main id="main-content">
    <?php partial('hero'); ?>
    <?php partial('breadcrumbs'); ?>
    <?php partial('testimonios'); ?>
    <?php partial('texto'); ?>
    <?php partial('pasos'); ?>
    <?php partial('diferenciadores'); ?>
    <?php partial('zonas', ['items' => $chips, 'titulo' => $pagina['servicio']['nombre_h1'] . ' por zona', 'lead' => ct('zonas_lead')]); ?>
    <?php partial('faq'); ?>
    <?php partial('enlaces-relacionados'); ?>
    <?php partial('cta-whatsapp'); ?>
  </main>

  <?php partial('footer'); ?>
  <?php partial('end'); ?>
