<!-- Texto unico por servicio y barrio (solo landings) + secciones propias del servicio (landing['secciones']) -->
<?php
require_once 'src/controlador/Local_Barrios.php';
$ztZona = Local_Controller::zonasTodas()[$lp_zona_slug] ?? null;
$zt = $ztZona ? Local_Barrios::texto($lp_servicio, $lp_zona_slug, $ztZona, $lp_servicio_nombre) : [];
$ztSecciones = array_values(array_filter((array)($landing['secciones'] ?? []), fn($s) => !empty($s['title'])));
?>
<?php if ($zt || $ztSecciones): ?>
<section class="zona-texto" aria-labelledby="zona-texto-titulo">
  <div class="container zona-texto__inner">
    <?php if ($zt): ?>
    <h2 class="zona-texto__title" id="zona-texto-titulo"><?= htmlspecialchars($zt['titulo']) ?></h2>
    <?php foreach ($zt['parrafos'] as $ztP): ?>
    <p><?= htmlspecialchars($ztP) ?></p>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php foreach ($ztSecciones as $ztI => $ztS): ?>
    <h2 class="zona-texto__title zona-texto__title--sub"<?= !$zt && $ztI === 0 ? ' id="zona-texto-titulo"' : '' ?>><?= htmlspecialchars($ztS['title']) ?></h2>
    <?php foreach ((array)($ztS['parrafos'] ?? []) as $ztP): ?>
    <p><?= htmlspecialchars($ztP) ?></p>
    <?php endforeach; ?>
    <?php if (!empty($ztS['lista'])): ?>
    <ul class="zona-texto__lista">
      <?php foreach ($ztS['lista'] as $ztL): ?>
      <li><?= htmlspecialchars($ztL) ?></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php endforeach; ?>
    <a href="<?= htmlspecialchars(wsp_href($landing['cta_message'])) ?>" class="zona-texto__link" target="_blank" rel="noopener">
      <i class="ri-whatsapp-line" aria-hidden="true"></i> Escribinos por WhatsApp
    </a>
  </div>
</section>
<?php endif; ?>
