<?php
require_once 'config/variables.php';

// ── Metas ────────────────────────────────────────────────────────────────────
$page_title       = $a['title'] ?? ($a['titulo'] . ' | ' . EMPRESA_NOMBRE);
$page_description = $a['description'];
$page_keywords    = $a['keywords'] ?? SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical   = $a['canonical'];
$page_type        = 'article';
$imagenRel        = !empty($a['imagen']) ? 'public/images/' . $a['imagen'] : SEO_OG_IMAGEN;
$page_og_image    = $imagenRel;
$page_twitter_image = $imagenRel;
$imagenAbs        = SEO_CANONICAL_URL . '/public/' . str_replace('public/', '', $imagenRel);
$autor            = $a['autor'] ?? 'Equipo ' . EMPRESA_NOMBRE;

// ── Schema: Article + FAQ + Breadcrumb ───────────────────────────────────────
$faqItems = [];
foreach ($a['faq'] ?? [] as $f) {
    $faqItems[] = ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])]];
}
$page_schema_blocks = [[
    '@context'         => 'https://schema.org',
    '@type'            => 'Article',
    '@id'              => $a['canonical'] . '#article',
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $a['canonical']],
    'headline'         => $a['titulo'],
    'description'      => $a['description'],
    'image'            => [$imagenAbs],
    'datePublished'    => $a['fecha'] . 'T09:00:00-03:00',
    'dateModified'     => $a['actualizado'] . 'T09:00:00-03:00',
    'inLanguage'       => 'es-UY',
    'articleSection'   => $a['categoria'] ?? 'Artículos',
    'keywords'         => $a['keywords'] ?? '',
    'wordCount'        => count(preg_split('/\s+/u', Articulos::textoPlano($a), -1, PREG_SPLIT_NO_EMPTY)),
    'author'           => ['@type' => 'Organization', 'name' => $autor, 'url' => SEO_CANONICAL_URL],
    'publisher'        => [
        '@type' => 'Organization',
        '@id'   => SEO_CANONICAL_URL . '/#localbusiness',
        'name'  => EMPRESA_NOMBRE,
        'logo'  => ['@type' => 'ImageObject', 'url' => SEO_CANONICAL_URL . '/' . LOGO_PRINCIPAL],
    ],
    'about'            => ['@type' => 'Thing', 'name' => $a['tema'] ?? EMPRESA_SLOGAN],
    'speakable'        => ['@type' => 'SpeakableSpecification', 'cssSelector' => ['.ar-answer', '.ar-keypoints']],
], [
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => SEO_CANONICAL_URL . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Artículos', 'item' => SEO_CANONICAL_URL . '/articulos'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $a['titulo'], 'item' => $a['canonical']],
    ],
]];
if ($faqItems) {
    $page_schema_blocks[] = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faqItems];
}

// ── Helpers de render ────────────────────────────────────────────────────────
$ar_id = function (string $texto): string {
    $texto = strtr($texto, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n','ü'=>'u','Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N','¿'=>'','?'=>'','¡'=>'','!'=>'']);
    $s = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $texto), '-'));
    return $s !== '' ? $s : 'seccion';
};
$ar_bloque = function (array $b) {
    if (!empty($b['html'])) echo $b['html'] . "\n";
    foreach ($b['parrafos'] ?? [] as $p) echo '<p>' . $p . "</p>\n";
    if (!empty($b['lista'])) {
        $tag = !empty($b['lista_ordenada']) ? 'ol' : 'ul';
        echo "<$tag>\n";
        foreach ($b['lista'] as $li) echo '<li>' . $li . "</li>\n";
        echo "</$tag>\n";
    }
    if (!empty($b['tabla']) && is_array($b['tabla'])) {
        echo '<div class="ar-table-wrap"><table class="ar-table">';
        if (!empty($b['tabla']['cabecera'])) {
            echo '<thead><tr>';
            foreach ($b['tabla']['cabecera'] as $c) echo '<th>' . htmlspecialchars($c) . '</th>';
            echo '</tr></thead>';
        }
        echo '<tbody>';
        $cab = $b['tabla']['cabecera'] ?? [];
        foreach ($b['tabla']['filas'] ?? [] as $fila) {
            echo '<tr>';
            foreach (array_values($fila) as $i => $c) {
                // data-label permite apilar la tabla en celular (cada celda muestra su columna)
                echo '<td data-label="' . htmlspecialchars($cab[$i] ?? '') . '">' . $c . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div>' . "\n";
    }
    if (!empty($b['nota'])) echo '<aside class="ar-note"><i class="ri-information-line" aria-hidden="true"></i><div>' . $b['nota'] . "</div></aside>\n";
};

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <main id="main-content" class="ar">

    <nav class="pj-crumb" aria-label="Ruta de navegación">
      <div class="container">
        <ol>
          <li><a href="<?= $url ?>">Inicio</a></li>
          <li><a href="<?= $url ?>articulos">Artículos</a></li>
          <li><span aria-current="page"><?= htmlspecialchars($a['titulo']) ?></span></li>
        </ol>
      </div>
    </nav>

    <?php ob_start(); // los href relativos del contenido (ej. 'contacto') se prefijan con $url al final ?>
    <article class="ar-article" itemscope itemtype="https://schema.org/Article">
      <header class="ar-head">
        <div class="container ar-head__inner">
          <p class="ar-head__cat"><a href="<?= $url ?>articulos"><?= htmlspecialchars($a['categoria'] ?? 'Artículos') ?></a></p>
          <h1 class="ar-head__title" itemprop="headline"><?= htmlspecialchars($a['titulo']) ?></h1>
          <?php if (!empty($a['bajada'])): ?>
          <p class="ar-head__lead"><?= $a['bajada'] ?></p>
          <?php endif; ?>
          <div class="ar-head__meta">
            <span><i class="ri-user-3-line" aria-hidden="true"></i> <span itemprop="author"><?= htmlspecialchars($autor) ?></span></span>
            <span><i class="ri-calendar-line" aria-hidden="true"></i> <time datetime="<?= $a['fecha'] ?>" itemprop="datePublished"><?= Articulos::fechaLegible($a['fecha']) ?></time></span>
            <?php if ($a['actualizado'] !== $a['fecha']): ?>
            <span><i class="ri-refresh-line" aria-hidden="true"></i> Actualizado <time datetime="<?= $a['actualizado'] ?>" itemprop="dateModified"><?= Articulos::fechaLegible($a['actualizado']) ?></time></span>
            <?php endif; ?>
            <span><i class="ri-time-line" aria-hidden="true"></i> <?= $a['minutos'] ?> min de lectura</span>
          </div>
        </div>
      </header>

      <div class="container ar-layout">
        <div class="ar-body">

          <?php if (!empty($a['imagen'])): ?>
          <figure class="ar-figure">
            <img src="<?= $ruta ?>/images/<?= htmlspecialchars($a['imagen']) ?>"
                 alt="<?= htmlspecialchars($a['imagen_alt'] ?? $a['titulo']) ?>"
                 width="900" height="560" loading="eager" decoding="async" fetchpriority="high">
            <?php if (!empty($a['imagen_pie'])): ?><figcaption><?= $a['imagen_pie'] ?></figcaption><?php endif; ?>
          </figure>
          <?php endif; ?>

          <?php if (!empty($a['respuesta'])): ?>
          <div class="ar-answer">
            <p class="ar-answer__label"><i class="ri-flashlight-line" aria-hidden="true"></i> Respuesta corta</p>
            <p><?= $a['respuesta'] ?></p>
          </div>
          <?php endif; ?>

          <?php if (!empty($a['puntos_clave'])): ?>
          <section class="ar-keypoints" aria-labelledby="ar-kp-title">
            <h2 id="ar-kp-title" class="ar-keypoints__title">Puntos clave</h2>
            <ul>
              <?php foreach ($a['puntos_clave'] as $p): ?><li><?= $p ?></li><?php endforeach; ?>
            </ul>
          </section>
          <?php endif; ?>

          <?php $secciones = $a['secciones'] ?? []; ?>
          <?php if (count($secciones) >= 3): ?>
          <nav class="ar-toc" aria-label="Contenido del artículo">
            <details class="ar-toc__details" open>
            <summary class="ar-toc__title">En este artículo <i class="ri-arrow-down-s-line" aria-hidden="true"></i></summary>
            <ol>
              <?php foreach ($secciones as $s): ?>
              <li><a href="#<?= $ar_id($s['h2']) ?>"><?= htmlspecialchars($s['h2']) ?></a></li>
              <?php endforeach; ?>
              <?php if (!empty($a['faq'])): ?><li><a href="#preguntas-frecuentes">Preguntas frecuentes</a></li><?php endif; ?>
            </ol>
            </details>
          </nav>
          <script>if (window.matchMedia('(max-width: 767px)').matches) { document.querySelector('.ar-toc__details').removeAttribute('open'); }</script>
          <?php endif; ?>

          <div class="ar-content" itemprop="articleBody">
            <?php foreach ($secciones as $s): ?>
              <section id="<?= $ar_id($s['h2']) ?>">
                <h2><?= htmlspecialchars($s['h2']) ?></h2>
                <?php $ar_bloque($s); ?>
                <?php foreach ($s['h3s'] ?? [] as $h): ?>
                  <h3><?= htmlspecialchars($h['h3']) ?></h3>
                  <?php $ar_bloque($h); ?>
                <?php endforeach; ?>
              </section>
            <?php endforeach; ?>
          </div>

          <?php if (!empty($a['faq'])): ?>
          <section class="ar-faq" id="preguntas-frecuentes" aria-labelledby="ar-faq-title">
            <h2 id="ar-faq-title">Preguntas frecuentes</h2>
            <?php foreach ($a['faq'] as $i => $f): ?>
            <details class="ar-faq__item"<?= $i === 0 ? ' open' : '' ?>>
              <summary><h3><?= htmlspecialchars($f['q']) ?></h3><i class="ri-arrow-down-s-line" aria-hidden="true"></i></summary>
              <div class="ar-faq__a"><p><?= $f['a'] ?></p></div>
            </details>
            <?php endforeach; ?>
          </section>
          <?php endif; ?>

          <div class="ar-cta">
            <p class="ar-cta__eyebrow">¿Necesitás un cerrajero?</p>
            <h2><?= htmlspecialchars($a['cta_titulo'] ?? 'Te pasamos presupuesto sin cargo') ?></h2>
            <p><?= $a['cta_texto'] ?? 'Contanos qué problema tiene tu cerradura y en qué barrio estás. Te respondemos con una propuesta para tu caso.' ?></p>
            <a href="<?= htmlspecialchars($a['cta_href']) ?>" class="btn btn--whatsapp" target="_blank" rel="noopener">
              <i class="ri-whatsapp-line" aria-hidden="true"></i> <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
            </a>
          </div>

          <?php if (!empty($a['fuentes'])): ?>
          <section class="ar-sources" aria-labelledby="ar-src-title">
            <h2 id="ar-src-title">Fuentes y referencias</h2>
            <ol>
              <?php foreach ($a['fuentes'] as $f): ?>
              <li><a href="<?= htmlspecialchars($f['url']) ?>" target="_blank" rel="noopener nofollow"><?= htmlspecialchars($f['label']) ?></a><?php if (!empty($f['nota'])): ?> — <?= htmlspecialchars($f['nota']) ?><?php endif; ?></li>
              <?php endforeach; ?>
            </ol>
          </section>
          <?php endif; ?>

          <p class="ar-author">
            <strong>Sobre <?= EMPRESA_NOMBRE ?>.</strong>
            <?= $a['autor_bio'] ?? htmlspecialchars(EMPRESA_DESCRIPCION) . ' Lo que escribimos sale de lo que hacemos todos los días.' ?>
            <a href="<?= $url ?>proyectos">Ver trabajos realizados</a>.
          </p>
        </div>

        <aside class="ar-side">
          <?php if (!empty($a['links'])): ?>
          <div class="ar-side__box">
            <p class="ar-side__title">Servicios relacionados</p>
            <ul>
              <?php foreach ($a['links'] as $l): ?>
              <li><a href="<?= $url . ltrim($l['href'], '/') ?>"><i class="ri-arrow-right-s-line" aria-hidden="true"></i><?= htmlspecialchars($l['label']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
          <div class="ar-side__box ar-side__box--dark">
            <p class="ar-side__title">Presupuesto sin cargo</p>
            <p>Propuesta por escrito. Respondemos a la brevedad.</p>
            <a href="<?= htmlspecialchars($a['cta_href']) ?>" class="btn btn--whatsapp" target="_blank" rel="noopener"><i class="ri-whatsapp-line" aria-hidden="true"></i> <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?></a>
          </div>
        </aside>
      </div>
    </article>
    <?php echo preg_replace('~href="(?![a-z][a-z0-9+.-]*:|/|#|\?)~i', 'href="' . $url, ob_get_clean()); ?>

    <?php if (!empty($a['relacionados'])): ?>
    <section class="ar-more" aria-labelledby="ar-more-title">
      <div class="container">
        <p class="pj-eyebrow">Seguir leyendo</p>
        <h2 class="ar-more__title" id="ar-more-title">Más <em>artículos</em></h2>
        <div class="ar-grid">
          <?php foreach ($a['relacionados'] as $r): ?>
            <?php require 'src/vista/articulos/card.php'; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
