<?php
/**
 * Sitemap dinámico segmentado por tipo (para seguir la indexación por segmento en Search Console):
 *   /sitemap.xml              índice
 *   /sitemap-paginas.xml      home, zonas, nosotros, contacto, legales
 *   /sitemap-servicios.xml    troncales de servicio
 *   /sitemap-hubs.xml         hubs de zona
 *   /sitemap-{servicio}.xml   páginas servicio × zona de ese servicio (si pasa de SITEMAP_MAX, /sitemap-{servicio}-2.xml, ...)
 * Lo llama el router (no se accede directo). NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

const SITEMAP_MAX = 1000;

/** Segmentos: nombre => páginas. */
function sitemap_segmentos(): array
{
    $seg = ['paginas' => [], 'servicios' => [], 'hubs' => []];
    foreach (servicios() as $s) $seg[$s['slug']] = [];
    foreach (paginas_todas() as $p) {
        if ($p['tipo'] === 'servicio') $seg['servicios'][] = $p;
        elseif ($p['tipo'] === 'zona') $seg['hubs'][] = $p;
        elseif ($p['tipo'] === 'servicio-zona') $seg[$p['servicio']][] = $p;
        else $seg['paginas'][] = $p;
    }
    // Dividir los que pasan del máximo
    $out = [];
    foreach ($seg as $k => $pags) {
        if (!$pags) continue;
        if (count($pags) <= SITEMAP_MAX) { $out[$k] = $pags; continue; }
        foreach (array_chunk($pags, SITEMAP_MAX) as $i => $chunk) $out[$k . '-' . ($i + 1)] = $chunk;
    }
    return $out;
}

function sitemap_emitir(string $n): void
{
    $segs = sitemap_segmentos();
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    if ($n === '') {
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($segs as $k => $pags) {
            echo "  <sitemap><loc>" . e(canonical("/sitemap-$k.xml")) . "</loc><lastmod>" . date('Y-m-d', max(array_column($pags, 'lastmod'))) . "</lastmod></sitemap>\n";
        }
        echo "</sitemapindex>\n";
        return;
    }
    if (!isset($segs[$n])) { http_response_code(404); echo "<!-- no existe -->"; return; }
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($segs[$n] as $p) {
        echo "  <url>\n    <loc>" . e(canonical($p['path'])) . "</loc>\n    <lastmod>" . date('Y-m-d', $p['lastmod']) . "</lastmod>\n    <priority>" . $p['prioridad'] . "</priority>\n  </url>\n";
    }
    echo "</urlset>\n";
}
