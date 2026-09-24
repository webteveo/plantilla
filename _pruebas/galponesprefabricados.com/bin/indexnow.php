<?php
/**
 * Avisa a IndexNow (Bing, Copilot, Yandex...) las URLs del sitio. Uso:
 *   php bin/indexnow.php                 todas las páginas del sitemap
 *   php bin/indexnow.php /plomero/pocitos /plomero/centro   solo esas rutas
 * Requiere config analitica.indexnow_key (32 caracteres hex; el router sirve /{clave}.txt).
 * Google no usa IndexNow: para Google alcanza con sitemap + enlazado interno.
 */
declare(strict_types=1);
require __DIR__ . '/../lib/bootstrap.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

$key = (string)cfg('analitica.indexnow_key');
if (!preg_match('/^[a-f0-9]{32}$/i', $key)) { fwrite(STDERR, "Falta analitica.indexnow_key en config.php (32 hex). Generar: php -r 'echo bin2hex(random_bytes(16));'\n"); exit(1); }
$host = parse_url((string)cfg('sitio.dominio'), PHP_URL_HOST);
$rutas = array_slice($argv, 1);
$urls = $rutas ? array_map(fn($r) => canonical($r), $rutas) : array_map(fn($p) => canonical($p['path']), paginas_todas());
$ok = 0;
foreach (array_chunk($urls, 10000) as $lote) {
    $body = json_encode(['host' => $host, 'key' => $key, 'keyLocation' => canonical("/$key.txt"), 'urlList' => array_values($lote)], JSON_UNESCAPED_SLASHES);
    $ch = curl_init('https://api.indexnow.org/indexnow');
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $body, CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf-8'], CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30]);
    curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    echo "Lote de " . count($lote) . " URLs → HTTP $code\n";
    if (in_array($code, [200, 202], true)) $ok += count($lote);
}
echo "$ok/" . count($urls) . " URLs enviadas\n";
exit($ok === count($urls) ? 0 : 1);
