<?php
/** Página servicio × zona (/{servicio}/{zona}): la landing principal del sitio. */
$L = $pagina['links'];
// Chips: zonas hijas con el mismo servicio (si es zona padre) o vecinas
$chips = $L['hijas'] ?: $L['vecinos'];
$chipsTitulo = $L['hijas'] ? ct('zonas_titulo', '{Servicio} en todas las zonas de {zona}') : $pagina['servicio']['nombre_h1'] . ' cerca de ' . $pagina['zona']['nombre'];
$padre = zona_padre_con_paginas($pagina['zona']['slug']);
$mas = $padre && combinacion_existe($pagina['servicio']['slug'], $padre['slug']) ? ['href' => url($pagina['servicio']['slug'] . '/' . $padre['slug']), 'label' => 'Ver ' . $pagina['servicio']['nombre'] . ' en todo ' . $padre['nombre_en']] : ['href' => url($pagina['servicio']['slug']), 'label' => 'Todas las zonas'];
partial('head');
?>
<body class="home-page">
  <?php partial('header'); ?>

  <main id="main-content">
    <?php partial('hero'); ?>
    <?php partial('breadcrumbs'); ?>
    <?php partial('testimonios'); ?>
    <?php partial('servicios'); ?>
    <?php partial('stats'); ?>
    <?php partial('texto'); ?>
    <?php partial('pasos'); ?>
    <?php partial('nosotros'); ?>
    <?php partial('diferenciadores'); ?>
    <?php partial('zonas', ['items' => $chips, 'titulo' => $chipsTitulo, 'lead' => ct('zonas_lead'), 'mas' => $mas]); ?>
    <?php partial('faq'); ?>
    <?php partial('enlaces-relacionados'); ?>
    <?php partial('cta-whatsapp'); ?>
  </main>

  <?php partial('footer'); ?>
  <?php partial('end'); ?>
