<!-- Resenas Section -->
<section class="resenas-home" id="resenas" aria-labelledby="resenas-titulo">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title" id="resenas-titulo">Resenas y <span>Referencias</span></h2>
      <p class="section-subtitle">Si queres validar como trabajamos antes de avanzar, podes ver nuestro circuito de resenas o pedir referencias por correo.</p>
    </div>

    <div class="resenas-home__grid">
      <article class="resenas-home__card">
        <div class="resenas-home__icon"><i class="ri-star-smile-line" aria-hidden="true"></i></div>
        <h3>Ver y dejar tu opinion</h3>
        <p>Tenemos una pagina dedicada para organizar resenas y comentarios de clientes que ya trabajaron con <?= htmlspecialchars(EMPRESA_NOMBRE) ?>.</p>
        <a href="<?= $url ?>resena" class="resenas-home__link">Ir a resenas <i class="ri-arrow-right-line" aria-hidden="true"></i></a>
      </article>

      <article class="resenas-home__card">
        <div class="resenas-home__icon"><i class="ri-message-2-line" aria-hidden="true"></i></div>
        <h3>Pedir referencias</h3>
        <p>Escribinos y te contamos que tipo de trabajos hacemos en tu zona.</p>
        <a href="<?= htmlspecialchars(wsp_href(CONTACTO_WHATSAPP_MENSAJE)) ?>" class="resenas-home__link" target="_blank" rel="noopener"><?= htmlspecialchars(CTA_WHATSAPP_LABEL) ?> <i class="ri-arrow-right-line" aria-hidden="true"></i></a>
      </article>

      <article class="resenas-home__card">
        <div class="resenas-home__icon"><i class="ri-map-2-line" aria-hidden="true"></i></div>
        <h3>Trabajos en <?= htmlspecialchars(DIRECCION_CIUDAD) ?></h3>
        <p>La mejor forma de evaluar una empresa local es ver si realmente trabaja donde vos estas. Por eso mostramos zonas y trabajos realizados.</p>
        <a href="<?= $url ?>proyectos" class="resenas-home__link">Ver trabajos <i class="ri-arrow-right-line" aria-hidden="true"></i></a>
      </article>
    </div>
  </div>
</section>
