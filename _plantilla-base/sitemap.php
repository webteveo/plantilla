<?php
/**
 * Sitemap dinámico. /sitemap.xml lista todas las URLs; si pasan de SITEMAP_MAX se convierte en índice
 * y las URLs se reparten en /sitemap-1.xml, /sitemap-2.xml, ...
 * Lo llama el router (no se accede directo). NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

const SITEMAP_MAX = 1000;

function sitemap_emitir(int $n): void
{
    $paginas = array_values(array_filter(paginas_todas(), fn($p) => !in_array($p['tipo'], ['404'], true)));
    $total = count($paginas);
    $partes = (int)ceil($total / SITEMAP_MAX);
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

    if ($n === 0 && $partes > 1) {
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        for ($i = 1; $i <= $partes; $i++) {
            $chunk = array_slice($paginas, ($i - 1) * SITEMAP_MAX, SITEMAP_MAX);
            $last = max(array_column($chunk, 'lastmod'));
            echo "  <sitemap><loc>" . e(canonical("/sitemap-$i.xml")) . "</loc><lastmod>" . date('Y-m-d', $last) . "</lastmod></sitemap>\n";
        }
        echo "</sitemapindex>\n";
        return;
    }
    if ($n > 0) {
        if ($n > $partes) { http_response_code(404); echo "<!-- no existe -->"; return; }
        $paginas = array_slice($paginas, ($n - 1) * SITEMAP_MAX, SITEMAP_MAX);
    }
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($paginas as $p) {
        echo "  <url>\n";
        echo "    <loc>" . e(canonical($p['path'])) . "</loc>\n";
        echo "    <lastmod>" . date('Y-m-d', $p['lastmod']) . "</lastmod>\n";
        echo "    <priority>" . $p['prioridad'] . "</priority>\n";
        echo "  </url>\n";
    }
    echo "</urlset>\n";
}
