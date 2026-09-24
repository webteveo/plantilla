<?php
/** Hub de zona (/{zona}): todos los servicios en la zona, zonas hijas, texto, FAQ, cercanas. */
$L = $pagina['links'];
partial('head');
?>
<body>
  <?php partial('header'); ?>

  <main id="main-content" class="section-page">
    <?php partial('page-intro'); ?>
    <?php partial('breadcrumbs'); ?>
    <?php partial('servicios', ['titulo' => 'Servicios en <em>' . e($pagina['zona']['nombre']) . '</em>', 'lead' => ct('servicios_lead')]); ?>
    <?php partial('texto'); ?>
    <?php if ($L['hijas']) partial('zonas', ['items' => $L['hijas'], 'titulo' => 'Zonas de ' . $pagina['zona']['nombre'], 'lead' => ct('zonas_lead')]); ?>
    <?php partial('diferenciadores'); ?>
    <?php partial('faq'); ?>
    <?php partial('enlaces-relacionados'); ?>
    <?php partial('cta-whatsapp'); ?>
  </main>

  <?php partial('footer'); ?>
  <?php partial('end'); ?>
