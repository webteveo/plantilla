<?php
/**
 * Router. Solo existen las URLs declaradas en los datos; el resto es 404 real.
 *   /                     home
 *   /{servicio}           troncal del servicio
 *   /{servicio}/{zona}    servicio × zona (solo combinaciones declaradas)
 *   /{zona}               hub de zona
 *   /nosotros /contacto /privacidad /terminos
 *   /contacto/enviar      POST del formulario
 *   /sitemap.xml /sitemap-N.xml /robots.txt /llms.txt
 * Normalización con 301: barra final, mayúsculas, dobles barras, index.php, ?url= legado.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

/** Ruta pedida, sin la carpeta base ni query string. Siempre empieza con "/". */
function router_path(): string
{
    $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
    $path = (string)(parse_url($uri, PHP_URL_PATH) ?? '/');
    $path = rawurldecode($path);
    $base = rtrim(base_path(), '/');
    if ($base !== '' && str_starts_with($path, $base)) $path = substr($path, strlen($base));
    return '/' . ltrim($path, '/');
}

/** Devuelve la ruta normalizada; si difiere de la pedida hay que redirigir 301. */
function router_normalizar(string $path): string
{
    $n = preg_replace('#/+#', '/', $path);             // dobles barras
    $n = preg_replace('#^/index\.php(/|$)#', '/', $n); // index.php
    $n = mb_strtolower($n);                            // mayúsculas
    if ($n !== '/' ) $n = rtrim($n, '/');              // barra final
    // Archivos especiales: se aceptan tal cual
    return $n === '' ? '/' : $n;
}

/** Resuelve una ruta normalizada a un tipo de página. */
function router_resolver(string $path, string $method = 'GET'): array
{
    if ($path === '/') return ['tipo' => 'home', 'path' => '/'];
    if ($path === '/sitemap.xml') return ['tipo' => 'sitemap', 'n' => 0];
    if (preg_match('#^/sitemap-(\d+)\.xml$#', $path, $m)) return ['tipo' => 'sitemap', 'n' => (int)$m[1]];
    if ($path === '/robots.txt') return ['tipo' => 'robots'];
    if ($path === '/llms.txt') return ['tipo' => 'llms'];

    $seg = explode('/', trim($path, '/'));
    if (count($seg) > 2) return ['tipo' => '404', 'path' => $path];
    [$a, $b] = [$seg[0], $seg[1] ?? null];
    foreach ($seg as $s) if (!slug_valido($s)) return ['tipo' => '404', 'path' => $path];

    if ($b === null) {
        if (in_array($a, ['nosotros', 'contacto'], true)) return ['tipo' => $a, 'path' => $path];
        if (in_array($a, ['privacidad', 'terminos'], true) && cfg('ui.legales', true)) return ['tipo' => $a, 'path' => $path];
        if (servicio($a)) return ['tipo' => 'servicio', 'servicio' => $a, 'path' => $path];
        if (zona($a) && zona($a)['paginas'] && zona_servicios($a)) return ['tipo' => 'zona', 'zona' => $a, 'path' => $path];
        return ['tipo' => '404', 'path' => $path];
    }
    if ($a === 'contacto' && $b === 'enviar') return ['tipo' => 'contacto-enviar', 'path' => $path];
    if ($a === 'contacto' && $b === 'gracias') return ['tipo' => 'contacto-gracias', 'path' => $path];
    if (servicio($a) && combinacion_existe($a, $b)) return ['tipo' => 'servicio-zona', 'servicio' => $a, 'zona' => $b, 'path' => $path];
    return ['tipo' => '404', 'path' => $path];
}

function router_redirigir(string $to, int $code = 301): void
{
    $qs = (string)($_SERVER['QUERY_STRING'] ?? '');
    header('Location: ' . url(ltrim($to, '/')) . ($qs !== '' ? '?' . $qs : ''), true, $code);
    exit;
}

/** Punto de entrada: normaliza, resuelve y emite la respuesta. */
function router_despachar(): void
{
    $path = router_path();

    // ?url=... del sitio viejo → 301 a la ruta limpia
    if (isset($_GET['url']) && $path === '/') {
        $legacy = '/' . trim((string)$_GET['url'], '/');
        unset($_GET['url']);
        $_SERVER['QUERY_STRING'] = http_build_query($_GET);
        router_redirigir(router_normalizar($legacy));
    }

    $norm = router_normalizar($path);
    if ($norm !== $path) router_redirigir($norm);

    $ruta = router_resolver($norm, $_SERVER['REQUEST_METHOD'] ?? 'GET');

    switch ($ruta['tipo']) {
        case 'sitemap':
            require BASE_DIR . '/sitemap.php';
            sitemap_emitir($ruta['n']);
            return;
        case 'robots':
            header('Content-Type: text/plain; charset=utf-8');
            echo robots_txt();
            return;
        case 'llms':
            header('Content-Type: text/plain; charset=utf-8');
            echo llms_txt();
            return;
        case 'contacto-enviar':
            require BASE_DIR . '/lib/contacto.php';
            contacto_enviar();
            return;
        case '404':
            http_response_code(404);
            $pagina = pagina_armar(['tipo' => '404', 'path' => $norm]);
            $GLOBALS['pagina'] = $pagina;
            echo render('404', ['pagina' => $pagina]);
            return;
    }

    $pagina = pagina_armar($ruta);
    $GLOBALS['pagina'] = $pagina;
    $template = ['servicio-zona' => 'servicio-zona', 'privacidad' => 'legales', 'terminos' => 'legales', 'contacto-gracias' => 'contacto'][$ruta['tipo']] ?? $ruta['tipo'];
    echo render($template, ['pagina' => $pagina]);
}

/** robots.txt generado desde el config. */
function robots_txt(): string
{
    $l = ["User-agent: *"];
    if (!cfg('sitio.indexar', true)) {
        $l[] = "Disallow: /";
    } else {
        $l[] = "Allow: /";
        $l[] = "Disallow: /contacto/gracias";
        $l[] = "Disallow: /*?origen=";
        foreach ((array)cfg('sitio.robots_extra', []) as $r) $l[] = "Disallow: " . $r;
    }
    $l[] = "";
    $l[] = "Sitemap: " . canonical('/sitemap.xml');
    return implode("\n", $l) . "\n";
}

/** /llms.txt (índice para crawlers de IA, formato llmstxt.org). */
function llms_txt(): string
{
    $o = "# " . cfg('marca.nombre') . " — " . cfg('marca.slogan') . "\n\n";
    $o .= "> " . cfg('marca.descripcion') . (cfg('contacto.whatsapp') ? " WhatsApp +" . cfg('contacto.whatsapp') . "." : '') . "\n\n";
    $o .= "Idioma: español (" . cfg('pais.nombre') . "). Cobertura: " . lista_natural(array_map(fn($z) => $z['nombre'], zonas_raiz())) . ".\n\n";
    $o .= "## Servicios\n\n";
    foreach (servicios() as $s) $o .= "- [" . $s['nombre_h1'] . "](" . canonical('/' . $s['slug']) . "): " . $s['descripcion'] . "\n";
    $o .= "\n## Zonas\n\n";
    foreach (zonas_raiz() as $z) {
        if (zona_servicios($z['slug'])) $o .= "- [Servicios en " . $z['nombre'] . "](" . canonical('/' . $z['slug']) . ")\n";
    }
    $o .= "\n## Páginas\n\n";
    $o .= "- [Quiénes somos](" . canonical('/nosotros') . ")\n- [Contacto](" . canonical('/contacto') . ")\n";
    $o .= "\n## Optional\n\n- [Sitemap XML](" . canonical('/sitemap.xml') . ")\n";
    return $o;
}
