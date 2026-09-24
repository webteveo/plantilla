<?php /** Cierre: config para main.js (eventos GA4) y script. */ ?>
<script>
  window.sitioConfig = <?= json_encode([
      'eventoWsp' => cfg('analitica.evento_wsp') ?: 'click_wsp',
      'eventoTel' => cfg('analitica.evento_tel') ?: 'click_tel',
      'pagina'    => $pagina['tipo'],
      'servicio'  => $pagina['servicio']['slug'] ?? '',
      'zona'      => $pagina['zona']['slug'] ?? '',
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= e(asset('js/main.js')) ?>"></script>
</body>
</html>
