<?php /** Reseñas reales (comun.testimonios). Vacío = no se muestra nada. */ $t = array_values((array)($pagina['comun']['testimonios'] ?? [])); ?>
<?php if ($t): ?>
<section class="reviews-strip" id="opiniones" aria-label="Opiniones de clientes" data-reviews>
  <div class="container">
    <div class="reviews-strip__track" id="reviews-track" tabindex="0" role="region" aria-label="Opiniones de clientes, desplazamiento horizontal" aria-roledescription="carrusel">
      <?php foreach ($t as $i => $r): ?>
      <article class="review-card">
        <div class="review-card__author">
          <span class="review-card__avatar" style="background:var(--color-avatar-<?= ($i % 6) + 1 ?>);color:var(--color-blanco)" aria-hidden="true"><?= e(mb_strtoupper(mb_substr($r['nombre'], 0, 1))) ?></span>
          <div><h3><?= e($r['nombre']) ?></h3></div>
          <?php if (($r['fuente'] ?? '') === 'Google'): ?><span class="review-card__source">Google</span><?php endif; ?>
        </div>
        <div class="review-card__stars" aria-label="<?= (int)$r['estrellas'] ?> de 5 estrellas">
          <?php for ($k = 0; $k < 5; $k++): ?><svg class="<?= $k < (int)$r['estrellas'] ? 'is-on' : '' ?>" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg><?php endfor; ?>
        </div>
        <p><?= e($r['texto']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
