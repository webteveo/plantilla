<?php /** Bloques JSON-LD de la página (ver lib/schema.php). */ ?>
<?php foreach ($pagina['schema'] as $bloque): ?>
  <script type="application/ld+json"><?= schema_json($bloque) ?></script>
<?php endforeach; ?>
