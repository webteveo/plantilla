<!-- Testimonios Section -->
<?php
/** Las reseñas se cargan en compact/testimonios-data.php. Vacio = la seccion no se muestra. Con datos, se emite schema Review/AggregateRating. */
$testimonios = require 'src/vista/compact/testimonios-data.php';
$tsPreview = !$testimonios;
if ($tsPreview) {
    $tsEjemplos = [
        'Muy buena atención. Me explicaron las opciones y el costo antes de cambiar la cerradura.',
        'Coordinamos la visita y revisaron la puerta que se trababa. Quedó funcionando bien.',
        'Necesitaba cambiar el cilindro del apartamento. Trato amable y trabajo prolijo.',
        'Consulté por una cerradura para el local y me orientaron con claridad. Buena experiencia.',
        'La llave giraba con dificultad. Revisaron el mecanismo y comprobaron el cierre al terminar.',
        'Destaco la comunicación y el cuidado de la puerta durante el trabajo. Todo quedó en orden.',
    ];
    $testimonios = array_map(fn($texto, $nombre) => ['nombre' => $nombre, 'texto' => $texto, 'estrellas' => 5], $tsEjemplos, ['Lucía A.', 'Martín R.', 'Valentina S.', 'Diego M.', 'Carolina P.', 'Andrés G.']);
}
$tsColores = ['#7b1fa2', '#1565c0', '#c0392b', '#00796b', '#a65b00', '#455a64'];
?>
<?php if ($testimonios): ?>
<?php
$tsTotal = count($testimonios);
$tsProm  = round(array_sum(array_column($testimonios, 'estrellas')) / $tsTotal, 1);
$tsSchema = [
    '@context' => 'https://schema.org',
    '@type'    => ['Locksmith', 'LocalBusiness'],
    '@id'      => SEO_CANONICAL_URL . '/#localbusiness',
    'name'     => EMPRESA_NOMBRE,
    'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => $tsProm, 'reviewCount' => $tsTotal, 'bestRating' => 5],
    'review' => array_map(fn($t) => [
        '@type'         => 'Review',
        'author'        => ['@type' => 'Person', 'name' => $t['nombre']],
        'reviewRating'  => ['@type' => 'Rating', 'ratingValue' => (int)$t['estrellas'], 'bestRating' => 5],
        'reviewBody'    => $t['texto'],
        'datePublished' => $t['fecha'] ?? null,
    ], $testimonios),
];
?>
<?php if (!$tsPreview): ?><script type="application/ld+json"><?= json_encode($tsSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script><?php endif; ?>
<section class="reviews-strip" id="opiniones" aria-label="Opiniones de clientes" data-reviews>
  <div class="container">

    <div class="reviews-strip__track" id="reviews-track" tabindex="0" role="region" aria-label="Opiniones de clientes, desplazamiento horizontal" aria-roledescription="carrusel">
      <?php foreach (array_values($testimonios) as $tsIndex => $t): ?>
      <article class="review-card">
        <div class="review-card__author">
          <span class="review-card__avatar" style="background:<?= $tsColores[$tsIndex % count($tsColores)] ?>;color:#fff" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr($t['nombre'], 0, 1))) ?></span>
          <div><h3><?= htmlspecialchars($t['nombre']) ?></h3></div>
          <?php if (($t['fuente'] ?? '') === 'Google'): ?><span class="review-card__source">Google</span><?php endif; ?>
        </div>
        <div class="review-card__stars" aria-label="<?= (int)$t['estrellas'] ?> de 5 estrellas">
          <?php for ($i = 0; $i < 5; $i++): ?><svg class="<?= $i < (int)$t['estrellas'] ? 'is-on' : '' ?>" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg><?php endfor; ?>
        </div>
        <p><?= htmlspecialchars($t['texto']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>
