<!-- Proyectos Section -->
<?php
$proyectos_json = file_get_contents('data/proyectos.json');
$todos_proyectos = json_decode($proyectos_json, true) ?? [];

$destacado_idx = null;
foreach ($todos_proyectos as $i => $p) {
    if (!empty($p['destacado'])) {
        $destacado_idx = $i;
        break;
    }
}

if ($destacado_idx !== null) {
    $destacado = $todos_proyectos[$destacado_idx];
    array_splice($todos_proyectos, $destacado_idx, 1);
    array_unshift($todos_proyectos, $destacado);
}

// $proyectos_limite = 'todos' muestra el catalogo completo (listado);
// sin definir, la home muestra las 6 primeras.
$limite = $proyectos_limite ?? 6;
$mostrar_todos = ($limite === 'todos');
$proyectos_a_mostrar = $mostrar_todos ? $todos_proyectos : array_slice($todos_proyectos, 0, (int) $limite);
$ocultar_header = !empty($proyectos_ocultar_header);
$ocultar_footer = $mostrar_todos;
?>
<?php if ($proyectos_a_mostrar || $mostrar_todos): ?>
<section class="proyectos" id="proyectos" aria-labelledby="proyectos-titulo">
  <div class="container">

    <?php if (!$ocultar_header): ?>
    <div class="proyectos__header">
      <span class="proyectos__eyebrow">Trabajos de cerrajería</span>
      <h2 class="proyectos__title" id="proyectos-titulo">
        Así <em>trabajamos</em>
      </h2>
      <p class="proyectos__lead">Fotos de nuestros trabajos de cerrajería en <?= htmlspecialchars(DIRECCION_CIUDAD) ?>.</p>
    </div>
    <?php endif; ?>

    <?php if (!$proyectos_a_mostrar): ?>
    <p class="proyectos__lead" style="text-align:center;">Todavía no hay trabajos publicados. Los vas a ver acá apenas los carguemos en <code>data/proyectos.json</code>.</p>
    <?php endif; ?>

    <div class="proyectos__grid">
      <?php foreach ($proyectos_a_mostrar as $p): ?>
      <?php
        $card_link = $url . 'proyectos/' . ($p['slug'] ?? '');
        $card_fotos = count($p['imagenes'] ?? []);
      ?>
      <a href="<?= htmlspecialchars($card_link) ?>"
         class="proyecto-card"
         aria-label="Ver proyecto <?= htmlspecialchars($p['titulo']) ?>">

        <div class="proyecto-card__media">
          <?php if (!empty($p['imagen'])): ?>
          <img src="<?= $ruta ?>/images/proyectos/<?= htmlspecialchars($p['imagen']) ?>"
               alt="<?= htmlspecialchars($p['titulo']) ?> - <?= htmlspecialchars($p['zona']) ?>"
               class="proyecto-card__img"
               width="400" height="300"
               loading="lazy"
               decoding="async">
          <?php endif; ?>
          <span class="proyecto-card__cat"><?= htmlspecialchars($p['categoria']) ?></span>
          <?php if ($card_fotos > 1): ?>
          <span class="proyecto-card__count">
            <i class="ri-layout-grid-line" aria-hidden="true"></i><?= $card_fotos ?>
          </span>
          <?php endif; ?>
        </div>

        <div class="proyecto-card__body">
          <h3 class="proyecto-card__title"><?= htmlspecialchars($p['titulo']) ?></h3>
          <div class="proyecto-card__meta">
            <span class="proyecto-card__link">
              Ver proyecto <i class="ri-arrow-right-line" aria-hidden="true"></i>
            </span>
          </div>
        </div>

      </a>
      <?php endforeach; ?>
    </div>

    <?php if (!$ocultar_footer && $proyectos_a_mostrar): ?>
    <div class="proyectos__footer">
      <a href="<?= $url ?>proyectos" class="proyectos__cta">
        Ver todos los proyectos
        <i class="ri-arrow-right-up-line" aria-hidden="true"></i>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?>
<?php unset($proyectos_limite, $proyectos_ocultar_header); ?>
