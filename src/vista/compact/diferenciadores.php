<!-- Diferenciadores Section -->
<?php
$dif_items = [
    ['icono' => 'ri-time-line',         'href' => 'local/cerrajero-24-horas-montevideo', 't' => 'Atención 24 horas',        'd' => 'Todos los días, feriados incluidos. Llegamos rápido a cualquier barrio de Montevideo.'],
    ['icono' => 'ri-price-tag-3-line',  't' => 'Precio antes de salir',    'd' => 'Te decimos cuánto cuesta por WhatsApp. Sin sorpresas ni cargos ocultos.'],
    ['icono' => 'ri-shield-check-line', 'href' => 'local/apertura-de-puertas-montevideo', 't' => 'Sin dañar tu puerta',      'd' => 'Abrimos y reparamos con las herramientas correctas para cada cerradura.'],
    ['icono' => 'ri-tools-line',        't' => 'Probamos antes de irnos',  'd' => 'Verificamos que la cerradura abra y cierre bien con tus llaves.'],
];
?>
<section class="diferenciadores" id="por-que-nosotros" aria-labelledby="dif-titulo">
  <div class="container">
    <div class="diferenciadores__header">
      <h2 class="diferenciadores__title" id="dif-titulo">Por qué elegirnos</h2>
      <p class="diferenciadores__lead">Cerrajero de confianza en Montevideo. Esto es lo que te garantizamos en cada servicio.</p>
    </div>

    <div class="diferenciadores__grid">
      <?php foreach ($dif_items as $d): ?>
      <article class="dif-item">
        <div class="dif-item__icon" aria-hidden="true"><i class="<?= htmlspecialchars($d['icono']) ?>"></i></div>
        <h3 class="dif-item__title"><?php if (!empty($d['href'])): ?><a href="<?= $url . $d['href'] ?>"><?= htmlspecialchars($d['t']) ?></a><?php else: ?><?= htmlspecialchars($d['t']) ?><?php endif; ?></h3>
        <p class="dif-item__desc"><?= htmlspecialchars($d['d']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
