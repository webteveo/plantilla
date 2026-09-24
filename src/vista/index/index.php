<?php
require_once 'config/variables.php';

$page_title = SEO_TITULO_POR_DEFECTO;
$page_description = SEO_DESCRIPCION_POR_DEFECTO;
$page_keywords = SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical = SEO_CANONICAL_URL;
$page_preload_images = [['href' => $ruta . '/images/hero/cerrajero-mobile-720.webp', 'media' => '(max-width: 600px)']];

// Schema FAQPage a partir de las mismas preguntas que muestra compact/faq.php
$faq_items = require 'src/vista/compact/faq-data.php';
$page_schema_blocks = [];
if ($faq_items) {
  $page_schema_blocks[] = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
      '@type' => 'Question',
      'name' => $f['q'],
      'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])],
    ], $faq_items),
  ];
}
// WebPage + WebSite (la entidad LocalBusiness/Locksmith va en partials/head.php)
$page_schema_blocks[] = [
  '@context' => 'https://schema.org',
  '@type' => 'WebSite',
  '@id' => SEO_CANONICAL_URL . '/#website',
  'url' => SEO_CANONICAL_URL,
  'name' => EMPRESA_NOMBRE,
  'inLanguage' => 'es-UY',
  'publisher' => ['@id' => SEO_CANONICAL_URL . '/#localbusiness'],
];

require 'src/vista/partials/head.php';
?>
<body class="home-page">
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content">
    <?php require 'src/vista/compact/hero.php'; ?>
    <?php require 'src/vista/compact/testimonios.php'; ?>
    <?php require 'src/vista/compact/servicios.php'; ?>
    <?php require 'src/vista/compact/stats-strip.php'; ?>
    <?php require 'src/vista/compact/pasos.php'; ?>
    <?php require 'src/vista/compact/quienes-somos.php'; ?>
    <?php require 'src/vista/compact/diferenciadores.php'; ?>
    <?php require 'src/vista/compact/zonas-home.php'; ?>
    <?php require 'src/vista/compact/faq.php'; ?>
    <?php require 'src/vista/compact/contacto-home.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
