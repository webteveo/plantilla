<?php
/**
 * /llms.txt — indice en texto plano para crawlers de IA (ChatGPT, Perplexity, Claude, Gemini).
 * Formato: https://llmstxt.org/
 * Se arma solo a partir de las constantes, las landings declaradas y los articulos publicados.
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/variables.php';
require_once __DIR__ . '/src/libs/Controlador.php';
require_once __DIR__ . '/src/controlador/Local_Controller.php';
require_once __DIR__ . '/src/modelo/Articulos.php';

use benjamin\plantillaweb\libs\App;

header('Content-Type: text/plain; charset=utf-8');
$base = SEO_CANONICAL_URL;

echo "# " . EMPRESA_NOMBRE . " — " . EMPRESA_SLOGAN . "\n\n";
echo "> " . EMPRESA_DESCRIPCION . (CONTACTO_TELEFONO ? " WhatsApp +" . CONTACTO_TELEFONO . "." : '') . "\n\n";
echo "Idioma: español (Uruguay). Los precios se cotizan por trabajo.\n\n";

echo "## Páginas principales\n\n";
$principales = [
    ['/', 'Inicio — ' . SEO_TITULO_POR_DEFECTO],
    ['/paginas/servicios', 'Servicios'],
    ['/paginas/zonas', 'Zonas de trabajo'],
    ['/contacto', 'Contacto'],
];
foreach ($principales as [$p, $t]) echo "- [{$t}]({$base}{$p})\n";

// Landings nacionales (metodos publicos de los controladores raiz)
$nacionales = [];
foreach (App::CONTROLADORES_RAIZ as $c) {
    $cls = $c . '_Controller';
    require_once __DIR__ . '/src/controlador/' . $cls . '.php';
    foreach ((new ReflectionClass($cls))->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
        if ($m->getDeclaringClass()->getName() !== $cls || $m->isStatic() || str_starts_with($m->getName(), '_')) continue;
        $slug = str_replace('_', '-', $m->getName());
        $nacionales[] = ['/' . $slug, ucfirst(str_replace('-', ' ', $slug))];
    }
}
if ($nacionales) {
    echo "\n## Servicios\n\n";
    foreach ($nacionales as [$p, $t]) echo "- [{$t}]({$base}{$p})\n";
}

// Zonas: servicios base en las zonas padre
$zonas = [];
foreach (Local_Datos::ZONAS_PADRE as $zk => $z) {
    foreach (Local_Controller::servicios() as $s => $sname) {
        $zonas[] = ['/local/' . $s . '-' . $zk, $sname . ' en ' . $z['nombre']];
    }
}
if ($zonas) {
    echo "\n## Zonas\n\n";
    foreach ($zonas as [$p, $t]) echo "- [{$t}]({$base}{$p})\n";
}

$articulos = Articulos::todos();
if ($articulos) {
    echo "\n## Artículos y guías\n\n";
    foreach ($articulos as $a) {
        echo "- [" . $a['titulo'] . "]({$base}/articulos/" . $a['slug'] . "): " . ($a['description'] ?? '') . "\n";
    }
}

echo "\n## Optional\n\n";
echo "- [Sitemap XML]({$base}/sitemap.xml)\n";
echo "- [RSS de artículos]({$base}/articulos/feed)\n";
