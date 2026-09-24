<?php
$metricsConfig = [
    'enabled' => defined('METRICAS_HABILITADAS') && METRICAS_HABILITADAS,
    'endpoint' => $url . '?url=metricas/collect',
    'isMetricsPage' => str_starts_with($_GET['url'] ?? '', 'metricas/'),
];
?>
<script>
  window.siteMetricsConfig = <?= json_encode($metricsConfig, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= $ruta ?>/js/main.js?v=<?= @filemtime('public/js/main.js') ?: 1 ?>"></script>
</body>
</html>
