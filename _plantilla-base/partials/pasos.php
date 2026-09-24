<?php /** "Cómo trabajamos": pasos de data/contenido/paginas/comun.php */ $pasos = (array)($pagina['comun']['pasos'] ?? []); ?>
<?php if ($pasos): ?>
<section class="pasos" aria-label="<?= e(ct('pasos_titulo', 'Cómo trabajamos')) ?>">
  <div class="container">

    <div class="pasos__header">
      <h2 class="pasos__title"><?= e(ct('pasos_titulo', 'Cómo trabajamos')) ?></h2>
      <?php if (ct('pasos_lead')): ?><p class="pasos__lead"><?= e(ct('pasos_lead')) ?></p><?php endif; ?>
    </div>

    <ol class="pasos__list" role="list">
      <?php foreach ($pasos as $paso): ?>
      <li class="pasos__item">
        <div class="pasos__icon"><i class="<?= e($paso['icono'] ?? 'ri-check-line') ?>" aria-hidden="true"></i></div>
        <div class="pasos__content">
          <h3 class="pasos__step-title"><?= e(t($paso['titulo'], $pagina['vars'])) ?></h3>
          <p class="pasos__desc"><?= e(t($paso['texto'], $pagina['vars'])) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>

    <div class="pasos__cta">
      <a <?= wsp_attrs($pagina) ?> class="pasos__cta-btn">
        <i class="ri-whatsapp-line" aria-hidden="true"></i>
        <?= e(cfg('contacto.cta_label')) ?>
      </a>
    </div>

  </div>
</section>
<?php endif; ?>
