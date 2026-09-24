<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/variables.php';
require_once __DIR__ . '/src/libs/Controlador.php';
require_once __DIR__ . '/src/controlador/Local_Controller.php';

use benjamin\plantillaweb\libs\App;

header('Content-Type: application/xml; charset=utf-8');

$hoy = date('Y-m-d');
$urls = [];
$add = function (string $path, string $prio = '0.8', string $freq = 'weekly') use (&$urls, $hoy) {
    $urls[] = ['loc' => SEO_CANONICAL_URL . $path, 'lastmod' => $hoy, 'changefreq' => $freq, 'priority' => $prio];
};

// Home y paginas principales
$add('/', '1.0');
foreach (['/paginas/servicios', '/paginas/zonas'] as $p) $add($p, '0.8');
foreach (['/contacto', '/paginas/diferenciales'] as $p) $add($p, '0.6', 'monthly');

// Articulos
require_once __DIR__ . '/src/modelo/Articulos.php';
$articulos = Articulos::todos();
if ($articulos) {
    $urls[] = ['loc' => SEO_CANONICAL_URL . '/articulos', 'lastmod' => reset($articulos)['actualizado'], 'changefreq' => 'daily', 'priority' => '0.8'];
    foreach ($articulos as $ar) {
        $urls[] = ['loc' => SEO_CANONICAL_URL . '/articulos/' . $ar['slug'], 'lastmod' => $ar['actualizado'], 'changefreq' => 'monthly', 'priority' => '0.8'];
    }
}


// Landings de un segmento: todos los metodos publicos de los controladores raiz (ver App::CONTROLADORES_RAIZ)
foreach (App::CONTROLADORES_RAIZ as $c) {
    $cls = $c . '_Controller';
    require_once __DIR__ . '/src/controlador/' . $cls . '.php';
    $ref = new ReflectionClass($cls);
    foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
        if ($m->getDeclaringClass()->getName() !== $cls || $m->isStatic() || str_starts_with($m->getName(), '_')) continue;
        $add('/' . str_replace('_', '-', $m->getName()), '0.9');
    }
    if (method_exists($cls, '_generadas')) {
        foreach ($cls::_generadas() as $m => $_) {
            $add('/' . str_replace('_', '-', $m), '0.7');
        }
    }
}

// Landings locales escritas a mano: metodos publicos de Local_Controller
$ref = new ReflectionClass('Local_Controller');
foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
    if ($m->getDeclaringClass()->getName() !== 'Local_Controller' || $m->isStatic() || str_starts_with($m->getName(), '_')) continue;
    $add('/local/' . str_replace('_', '-', $m->getName()), '0.8');
}

// Landings locales generadas por datos (servicios x ciudades): ver Local_Generadas
foreach (Local_Controller::_generadas() as $m => $_) {
    $add('/local/' . str_replace('_', '-', $m), str_ends_with($m, '_montevideo') ? '0.8' : '0.6');
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($u['loc']) . "</loc>\n";
    echo '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
    echo '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
    echo '    <priority>' . $u['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
