<?php
require_once 'config/variables.php';

$page_title       = 'Guías de cerrajería | ' . EMPRESA_NOMBRE;
$page_description = 'Guías y respuestas claras sobre cerrajería en Montevideo, escritas por el equipo de ' . EMPRESA_NOMBRE . '.';
$page_keywords    = 'guias cerrajería, consejos cerrajería montevideo, ' . SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical   = SEO_CANONICAL_URL . '/articulos';
$page_schema_blocks = [[
    '@context' => 'https://schema.org',
    '@type'    => 'CollectionPage',
    '@id'      => $page_canonical,
    'name'     => 'Artículos y guías de ' . EMPRESA_NOMBRE,
    'description' => $page_description,
    'inLanguage' => 'es-UY',
    'isPartOf' => ['@type' => 'WebSite', 'name' => EMPRESA_NOMBRE, 'url' => SEO_CANONICAL_URL],
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => array_values(array_map(fn($a, $i) => [
            '@type' => 'ListItem', 'position' => $i + 1, 'url' => SEO_CANONICAL_URL . '/articulos/' . $a['slug'], 'name' => $a['titulo'],
        ], array_values($articulos), array_keys(array_values($articulos)))),
    ],
]];

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="section-page">
    <section class="page-intro">
      <div class="container page-intro__inner">
        <p class="page-intro__eyebrow">Artículos</p>
        <h1 class="page-intro__title">Guías y respuestas sobre cerrajería</h1>
        <p class="page-intro__desc">Respuestas concretas escritas por el equipo que hace el trabajo: apertura de puertas, mantenimiento, cambios de cerraduras y presupuestos.</p>
      </div>
    </section>

    <section class="ar-list">
      <div class="container">
        <?php if (!empty($categorias) && count($categorias) > 1): ?>
        <div class="ar-list__cats" role="list" aria-label="Categorías">
          <?php foreach ($categorias as $c => $n): ?>
          <span role="listitem" class="ar-list__cat"><?= htmlspecialchars($c) ?> <small><?= $n ?></small></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($articulos): ?>
        <div class="ar-grid">
          <?php foreach ($articulos as $r): ?>
            <?php require 'src/vista/articulos/card.php'; ?>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p>Todavía no hay artículos publicados.</p>
        <?php endif; ?>

        <p class="ar-list__feed"><a href="<?= $url ?>articulos/feed"><i class="ri-rss-line" aria-hidden="true"></i> Suscribirse por RSS</a></p>
      </div>
    </section>

    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
