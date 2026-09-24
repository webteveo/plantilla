<?php
require_once 'config/variables.php';

$imagenes = array_values(array_filter($proyecto['imagenes'] ?? []));
$imagenPrincipal = $proyecto['imagen'] ?? ($imagenes[0] ?? '');
$titulo = $proyecto['titulo'] ?? 'Proyecto';
$categoria = $proyecto['categoria'] ?? 'Proyecto';
$zona = $proyecto['zona'] ?? 'Uruguay';
$descripcion = $proyecto['descripcion'] ?? '';
$slug = $proyecto['slug'] ?? '';

// La imagen de portada abre la galeria; si no esta en el array, se antepone.
if ($imagenPrincipal && !in_array($imagenPrincipal, $imagenes, true)) {
    array_unshift($imagenes, $imagenPrincipal);
} elseif ($imagenPrincipal) {
    $imagenes = array_merge([$imagenPrincipal], array_values(array_diff($imagenes, [$imagenPrincipal])));
}
$totalImagenes = count($imagenes);

// Ficha tecnica: usa "specs" del JSON; si no hay, arma una por defecto.
$specs = [];
foreach ($proyecto['specs'] ?? [] as $spec) {
    if (!empty($spec['valor'])) {
        $specs[] = [
            'icono' => $spec['icono'] ?? 'ri-checkbox-circle-line',
            'valor' => $spec['valor'],
            'clave' => $spec['clave'] ?? '',
        ];
    }
}
if (!$specs) {
    $specs[] = ['icono' => 'ri-price-tag-3-line', 'valor' => $categoria, 'clave' => 'Tipo'];
    $specs[] = ['icono' => 'ri-map-pin-line', 'valor' => $zona, 'clave' => 'Zona'];
    if ($totalImagenes > 1) {
        $specs[] = ['icono' => 'ri-layout-grid-line', 'valor' => $totalImagenes, 'clave' => 'Fotos de obra'];
    }
}

// Otros proyectos: misma categoria primero, completando con el resto.
$todos = json_decode(@file_get_contents('data/proyectos.json'), true) ?: [];
$otros = array_values(array_filter($todos, static fn($p) => ($p['slug'] ?? '') !== $slug));
usort($otros, static function ($a, $b) use ($categoria) {
    return (int) (($b['categoria'] ?? '') === $categoria) <=> (int) (($a['categoria'] ?? '') === $categoria);
});
$relacionados = array_slice($otros, 0, 3);
$mismaCategoria = !empty($relacionados) && ($relacionados[0]['categoria'] ?? '') === $categoria;

// Telefono legible a partir del numero internacional.
$telefonoCrudo = preg_replace('/\D/', '', CONTACTO_TELEFONO);
$telefonoLocal = str_starts_with($telefonoCrudo, '598') ? '0' . substr($telefonoCrudo, 3) : $telefonoCrudo;
$telefonoVisible = trim(chunk_split($telefonoLocal, 3, ' '));

$waTexto = 'Hola! Me interesa el proyecto "' . $titulo . '". Queria consultar por algo similar.';
$waLink = wsp_href($waTexto);

$page_title = $titulo . ' | Trabajos ' . EMPRESA_NOMBRE;
$page_description = $descripcion ?: 'Trabajo realizado por ' . EMPRESA_NOMBRE . ' en ' . $zona . '.';
$page_keywords = mb_strtolower($categoria) . ', ' . mb_strtolower(EMPRESA_NOMBRE) . ', ' . SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_canonical = SEO_CANONICAL_URL . '/proyectos/' . $slug;
$page_og_image = $imagenPrincipal ? 'public/images/proyectos/' . $imagenPrincipal : SEO_OG_IMAGEN;
$page_type = 'article';
$page_twitter_image = $page_og_image;

require 'src/vista/partials/head.php';
?>
<body>
  <?php require 'src/vista/partials/header.php'; ?>

  <script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => SEO_CANONICAL_URL . '/'],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Proyectos', 'item' => SEO_CANONICAL_URL . '/proyectos'],
      ['@type' => 'ListItem', 'position' => 3, 'name' => $titulo, 'item' => $page_canonical],
    ],
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>

  <main id="main-content" class="pj">

    <nav class="pj-crumb" aria-label="Ruta de navegacion">
      <div class="container">
        <ol>
          <li><a href="<?= $url ?>">Inicio</a></li>
          <li><a href="<?= $url ?>proyectos">Proyectos</a></li>
          <li><span aria-current="page"><?= htmlspecialchars($titulo) ?></span></li>
        </ol>
      </div>
    </nav>

    <section class="pj-main">
      <div class="container pj-layout">

        <?php if ($totalImagenes): ?>
        <div class="pj-gallery"
             id="pj-gallery"
             data-imagenes='<?= htmlspecialchars(json_encode(array_map(
                 static fn($img) => $ruta . '/images/proyectos/' . $img,
                 $imagenes
             ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>'>

          <div class="pj-stage">
            <img src="<?= $ruta ?>/images/proyectos/<?= htmlspecialchars($imagenes[0]) ?>"
                 alt="<?= htmlspecialchars($titulo . ' - ' . $zona) ?>"
                 class="pj-stage__img"
                 id="pj-stage-img"
                 width="900" height="620"
                 loading="eager" decoding="async" fetchpriority="high">

            <span class="pj-stage__count">
              <i class="ri-layout-grid-line" aria-hidden="true"></i>
              <span id="pj-stage-count">1 / <?= $totalImagenes ?></span>
            </span>

            <?php if ($totalImagenes > 1): ?>
              <button type="button" class="pj-stage__nav pj-stage__nav--prev" data-pj-step="-1" aria-label="Imagen anterior">
                <i class="ri-arrow-left-line" aria-hidden="true"></i>
              </button>
              <button type="button" class="pj-stage__nav pj-stage__nav--next" data-pj-step="1" aria-label="Imagen siguiente">
                <i class="ri-arrow-right-line" aria-hidden="true"></i>
              </button>
            <?php endif; ?>

            <button type="button" class="pj-stage__zoom" id="pj-zoom" aria-label="Ampliar imagen">
              <i class="ri-add-line" aria-hidden="true"></i>
            </button>
          </div>

          <?php if ($totalImagenes > 1): ?>
          <div class="pj-thumbs" role="tablist" aria-label="Imagenes del proyecto">
            <?php foreach ($imagenes as $i => $imagen): ?>
              <button type="button"
                      class="pj-thumb"
                      role="tab"
                      data-pj-index="<?= $i ?>"
                      aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
                      aria-label="Ver imagen <?= $i + 1 ?> de <?= $totalImagenes ?>">
                <img src="<?= $ruta ?>/images/proyectos/<?= htmlspecialchars($imagen) ?>"
                     alt=""
                     width="104" height="78"
                     loading="<?= $i < 5 ? 'eager' : 'lazy' ?>" decoding="async">
              </button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

        </div>
        <?php endif; ?>

        <aside class="pj-panel">
          <span class="pj-panel__cat">
            <i class="ri-price-tag-3-line" aria-hidden="true"></i>
            <?= htmlspecialchars($categoria) ?>
          </span>

          <h1 class="pj-panel__title"><?= htmlspecialchars($titulo) ?></h1>

          <?php if ($specs): ?>
          <div class="pj-specs">
            <?php foreach ($specs as $spec): ?>
              <div class="pj-spec">
                <i class="<?= htmlspecialchars($spec['icono']) ?>" aria-hidden="true"></i>
                <span>
                  <span class="pj-spec__val"><?= htmlspecialchars((string) $spec['valor']) ?></span>
                  <?php if ($spec['clave']): ?>
                    <span class="pj-spec__key"><?= htmlspecialchars($spec['clave']) ?></span>
                  <?php endif; ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <a href="<?= htmlspecialchars($waLink) ?>" class="pj-btn pj-btn--wa" target="_blank" rel="noopener">
            <i class="ri-whatsapp-line" aria-hidden="true"></i>
            <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
          </a>

          <a href="tel:+<?= htmlspecialchars($telefonoCrudo) ?>" class="pj-btn pj-btn--ghost">
            <i class="ri-phone-line" aria-hidden="true"></i>
            <?= htmlspecialchars($telefonoVisible) ?>
          </a>

          <a href="<?= $url ?>proyectos" class="pj-panel__back">
            <i class="ri-arrow-left-line" aria-hidden="true"></i>
            Ver todos los trabajos
          </a>
        </aside>

      </div>
    </section>

    <?php if ($descripcion): ?>
    <section class="pj-about">
      <div class="container pj-about__inner">
        <p class="pj-eyebrow">Sobre este trabajo</p>
        <h2 class="pj-about__title">Detalles del trabajo</h2>
        <div class="pj-about__text"><?= htmlspecialchars($descripcion) ?></div>
      </div>
    </section>
    <?php endif; ?>

    <section class="pj-invite-wrap">
      <div class="container">
        <div class="pj-invite">
          <p class="pj-invite__eyebrow">A medida</p>
          <h2>&iquest;Necesitas algo similar?</h2>
          <p>Cada trabajo se coordina segun tus necesidades. Contanos que necesitas y te asesoramos sin compromiso.</p>
          <div class="pj-invite__actions">
            <a href="<?= htmlspecialchars($waLink) ?>" class="pj-btn pj-btn--wa" style="width:auto" target="_blank" rel="noopener">
              <i class="ri-whatsapp-line" aria-hidden="true"></i>
              <?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?>
            </a>
            <a href="<?= $url ?>proyectos" class="pj-invite__link">
              Ver otros proyectos
              <i class="ri-arrow-right-line" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <?php if ($relacionados): ?>
    <section class="pj-more" aria-labelledby="pj-more-title">
      <div class="container">
        <p class="pj-eyebrow">Mas proyectos</p>
        <h2 class="pj-more__title" id="pj-more-title">
          <?php if ($mismaCategoria): ?>
            Otros en <em><?= htmlspecialchars($categoria) ?></em>
          <?php else: ?>
            Otras <em>obras realizadas</em>
          <?php endif; ?>
        </h2>

        <div class="pj-more__grid">
          <?php foreach ($relacionados as $r): ?>
            <?php $rImgs = count($r['imagenes'] ?? []); ?>
            <a href="<?= $url ?>proyectos/<?= htmlspecialchars($r['slug'] ?? '') ?>"
               class="proyecto-card"
               aria-label="Ver proyecto <?= htmlspecialchars($r['titulo'] ?? '') ?>">
              <div class="proyecto-card__media">
                <?php if (!empty($r['imagen'])): ?>
                  <img src="<?= $ruta ?>/images/proyectos/<?= htmlspecialchars($r['imagen']) ?>"
                       alt="<?= htmlspecialchars(($r['titulo'] ?? '') . ' - ' . ($r['zona'] ?? '')) ?>"
                       class="proyecto-card__img"
                       width="400" height="300"
                       loading="lazy" decoding="async">
                <?php endif; ?>
                <span class="proyecto-card__cat"><?= htmlspecialchars($r['categoria'] ?? '') ?></span>
                <?php if ($rImgs > 1): ?>
                  <span class="proyecto-card__count">
                    <i class="ri-layout-grid-line" aria-hidden="true"></i><?= $rImgs ?>
                  </span>
                <?php endif; ?>
              </div>
              <div class="proyecto-card__body">
                <h3 class="proyecto-card__title"><?= htmlspecialchars($r['titulo'] ?? '') ?></h3>
                <div class="proyecto-card__meta">
                  <span class="proyecto-card__link">
                    Ver proyecto <i class="ri-arrow-right-line" aria-hidden="true"></i>
                  </span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php require 'src/vista/compact/cta.php'; ?>
  </main>

  <?php if ($totalImagenes): ?>
  <div class="pj-lightbox" id="pj-lightbox" role="dialog" aria-modal="true" aria-label="Galeria del proyecto">
    <img src="" alt="<?= htmlspecialchars($titulo) ?>" id="pj-lightbox-img">
    <button type="button" class="pj-lightbox__btn pj-lightbox__btn--close" id="pj-lightbox-close" aria-label="Cerrar galeria">
      <i class="ri-close-circle-line" aria-hidden="true"></i>
    </button>
    <?php if ($totalImagenes > 1): ?>
      <button type="button" class="pj-lightbox__btn pj-lightbox__btn--prev" data-pj-step="-1" aria-label="Imagen anterior">
        <i class="ri-arrow-left-line" aria-hidden="true"></i>
      </button>
      <button type="button" class="pj-lightbox__btn pj-lightbox__btn--next" data-pj-step="1" aria-label="Imagen siguiente">
        <i class="ri-arrow-right-line" aria-hidden="true"></i>
      </button>
      <span class="pj-lightbox__count" id="pj-lightbox-count"></span>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <?php require 'src/vista/partials/footer.php'; ?>
  <?php require 'src/vista/partials/end.php'; ?>
