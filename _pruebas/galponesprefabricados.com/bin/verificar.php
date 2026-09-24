<?php
/**
 * Verificación del sitio. Uso: php bin/verificar.php [--base=http://localhost:8000] [--grep=palabra,otra]
 *
 *  1. php -l de todos los archivos PHP.
 *  2. Valida datos (slugs, padres, zonas de cada servicio).
 *  3. Renderiza todas las páginas del sitemap en memoria: title, description y H1 únicos, JSON-LD válido,
 *     canonical = URL, links internos que apuntan a páginas existentes.
 *  4. Con --base: recorre las URLs del sitemap por HTTP (todas 200) y prueba que una URL inventada dé 404.
 *  5. Con --grep: busca palabras del sitio original (marca, dominio, teléfono, ciudad) en todo el código y datos.
 * Sale con código 1 si hay errores.
 */
declare(strict_types=1);

$opts = getopt('', ['base::', 'grep::']);
$base = rtrim((string)($opts['base'] ?? ''), '/');
$grep = array_filter(array_map('trim', explode(',', (string)($opts['grep'] ?? ''))));
$errores = []; $avisos = [];
$err = function (string $m) use (&$errores) { $errores[] = $m; echo "  ERROR: $m\n"; };
$aviso = function (string $m) use (&$avisos) { $avisos[] = $m; echo "  aviso: $m\n"; };

chdir(dirname(__DIR__));

// 1. php -l
echo "1. Sintaxis PHP\n";
$phpFiles = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS)), '/\.php$/');
$n = 0;
foreach ($phpFiles as $f) {
    $p = (string)$f;
    if (str_contains($p, '/vendor/')) continue;
    $n++;
    exec('php -l ' . escapeshellarg($p) . ' 2>&1', $out, $code);
    if ($code !== 0) $err("php -l $p: " . implode(' ', $out));
    $out = [];
}
echo "  $n archivos revisados\n";

// 2. Datos
echo "2. Datos\n";
require __DIR__ . '/../lib/bootstrap.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
try {
    $zs = zonas(); $ss = servicios();
    echo "  " . count($ss) . " servicios, " . count(zonas_con_paginas()) . " zonas con páginas, " . count(paginas_todas()) . " páginas\n";
    foreach ($ss as $s) if (!$s['zonas_lista']) $aviso("servicio '{$s['slug']}' sin zonas");
    foreach (zonas_con_paginas() as $z) if (!zona_servicios($z['slug'])) $aviso("zona '{$z['slug']}' sin servicios (no genera páginas)");
} catch (Throwable $e) {
    $err('datos: ' . $e->getMessage());
    exit(1);
}

// 3. Render en memoria
echo "3. Páginas\n";
$titles = []; $h1s = []; $descs = []; $paths = [];
$paginas = paginas_todas();
foreach ($paginas as $pg) $paths[$pg['path']] = true;
$paths['/contacto/gracias'] = true;
$renderizar = function (array $ruta): string {
    $pagina = pagina_armar($ruta);
    $GLOBALS['pagina'] = $pagina;
    $template = ['servicio-zona' => 'servicio-zona', 'privacidad' => 'legales', 'terminos' => 'legales'][$ruta['tipo']] ?? $ruta['tipo'];
    return render($template, ['pagina' => $pagina]);
};
foreach ($paginas as $pg) {
    $_SERVER['REQUEST_URI'] = $pg['path'];
    try {
        $html = $renderizar($pg);
    } catch (Throwable $e) {
        $err("{$pg['path']}: excepción " . $e->getMessage()); continue;
    }
    preg_match('/<title>(.*?)<\/title>/s', $html, $m); $title = html_entity_decode(trim($m[1] ?? ''));
    preg_match('/<meta name="description" content="(.*?)"/s', $html, $m); $desc = html_entity_decode($m[1] ?? '');
    preg_match_all('/<h1[^>]*>(.*?)<\/h1>/s', $html, $m); $h1 = array_map(fn($x) => trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($x)))), $m[1]);
    preg_match('/<link rel="canonical" href="(.*?)"/', $html, $m); $canon = $m[1] ?? '';
    if ($title === '') $err("{$pg['path']}: sin title");
    if (mb_strlen($title) > 65) $aviso("{$pg['path']}: title de " . mb_strlen($title) . " caracteres");
    if ($desc === '') $err("{$pg['path']}: sin description");
    if (count($h1) !== 1) $err("{$pg['path']}: " . count($h1) . " H1");
    if (isset($titles[$title])) $err("{$pg['path']}: title repetido con {$titles[$title]} → \"$title\"");
    if ($h1 && isset($h1s[$h1[0]])) $err("{$pg['path']}: H1 repetido con {$h1s[$h1[0]]} → \"{$h1[0]}\"");
    if (isset($descs[$desc])) $err("{$pg['path']}: description repetida con {$descs[$desc]}");
    $titles[$title] = $pg['path']; if ($h1) $h1s[$h1[0]] = $pg['path']; $descs[$desc] = $pg['path'];
    if ($canon !== canonical($pg['path'])) $err("{$pg['path']}: canonical '$canon' ≠ " . canonical($pg['path']));
    // JSON-LD
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m);
    foreach ($m[1] as $i => $json) {
        $d = json_decode($json, true);
        if (!is_array($d)) $err("{$pg['path']}: JSON-LD #$i inválido");
        elseif (empty($d['@type'])) $err("{$pg['path']}: JSON-LD #$i sin @type");
    }
    if (!$m[1]) $err("{$pg['path']}: sin JSON-LD");
    // Links internos
    preg_match_all('/href="(\/[^"#?]*)/', $html, $m);
    foreach (array_unique($m[1]) as $href) {
        if (str_starts_with($href, '/assets/')) continue;
        if (!isset($paths[$href])) $err("{$pg['path']}: link interno a '$href' que no existe");
    }
    // WhatsApp con mensaje de la página
    if (in_array($pg['tipo'], ['servicio-zona'], true)) {
        $z = zona($pg['zona']); $s = servicio($pg['servicio']);
        if (!str_contains($html, rawurlencode($z['nombre']))) $err("{$pg['path']}: el mensaje de WhatsApp no incluye la zona");
        if (substr_count($html, 'data-wsp') < 3) $err("{$pg['path']}: menos de 3 botones de WhatsApp con data-wsp");
    }
    if (str_contains($html, '[EJEMPLO]')) $aviso("{$pg['path']}: contiene [EJEMPLO]");
}
echo "  " . count($paginas) . " páginas renderizadas, " . count($titles) . " titles únicos, " . count($h1s) . " H1 únicos\n";
// 404
$_SERVER['REQUEST_URI'] = '/pagina-inventada';
$r = router_resolver('/pagina-inventada');
if ($r['tipo'] !== '404') $err("/pagina-inventada no da 404");
$s0 = array_key_first(servicios());
$r = router_resolver('/' . $s0 . '/zona-inventada');
if ($r['tipo'] !== '404') $err("/$s0/zona-inventada no da 404");

// 4. HTTP
if ($base !== '') {
    echo "4. HTTP en $base\n";
    $xml = @file_get_contents($base . '/sitemap.xml');
    if (!$xml) $err('no se pudo leer /sitemap.xml');
    else {
        $urls = [];
        preg_match_all('/<loc>(.*?)<\/loc>/', $xml, $m);
        foreach ($m[1] as $loc) {
            if (str_contains($loc, '/sitemap-')) { // índice
                $sub = @file_get_contents(str_replace(rtrim((string)cfg('sitio.dominio'), '/'), $base, $loc));
                preg_match_all('/<loc>(.*?)<\/loc>/', (string)$sub, $mm);
                foreach ($mm[1] as $l2) $urls[] = $l2;
            } else $urls[] = $loc;
        }
        $ok = 0;
        foreach ($urls as $loc) {
            $u = str_replace(rtrim((string)cfg('sitio.dominio'), '/'), $base, html_entity_decode($loc));
            $code = http_code($u);
            if ($code !== 200) $err("HTTP $code en $u"); else $ok++;
        }
        echo "  " . count($urls) . " URLs del sitemap, $ok con 200\n";
        foreach (['/esta-url-no-existe', '/' . $s0 . '/zona-que-no-existe', '/' . $s0 . '/' . servicio_zonas($s0)[0] . '/extra'] as $u) {
            $code = http_code($base . $u);
            if ($code !== 404) $err("HTTP $code (esperado 404) en $u");
        }
        foreach (['/' . $s0 . '/' => 301, '/' . strtoupper($s0) => 301, '/index.php' => 301, '/robots.txt' => 200, '/llms.txt' => 200] as $u => $esp) {
            $code = http_code($base . $u);
            if ($code !== $esp) $err("HTTP $code (esperado $esp) en $u");
        }
    }
}

// 5. Rastros del sitio original
if ($grep) {
    echo "5. Rastros de: " . implode(', ', $grep) . "\n";
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        $p = (string)$f;
        if (str_contains($p, '/vendor/') || str_contains($p, '/assets/img/') || preg_match('/\.(png|jpg|webp|ico|svg)$/', $p) || str_ends_with($p, 'INVENTARIO.md') || str_ends_with($p, 'PLANTILLA.md')) continue;
        $c = file_get_contents($p);
        foreach ($grep as $g) {
            if ($g !== '' && stripos($c, $g) !== false) $err("'$g' aparece en $p");
        }
    }
}

echo "\n" . (count($errores) ? count($errores) . " errores" : "SIN ERRORES") . ", " . count($avisos) . " avisos\n";
exit($errores ? 1 : 0);

function http_code(string $url): int
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_NOBODY => false, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => false, CURLOPT_TIMEOUT => 10]);
    curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return $code;
}
