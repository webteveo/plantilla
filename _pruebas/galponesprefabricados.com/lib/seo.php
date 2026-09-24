<?php
/**
 * SEO por página: title, description, H1, eyebrow, subtítulo, canonical, robots.
 * Si el contenido de la página no define un campo, se usa la fórmula por defecto.
 * NO se toca para armar un sitio nuevo. Las fórmulas se pueden ajustar acá si se quiere otro patrón global.
 */
declare(strict_types=1);

/** Agrega el sufijo " | Marca" si el title no lo trae. */
function seo_sufijo(string $title): string
{
    $marca = (string)cfg('marca.nombre');
    $cfgSuf = cfg('sitio.sufijo_title');
    if ($cfgSuf === false) return $title;
    $suf = (string)($cfgSuf ?: ' – ' . $marca);
    if (str_contains($title, $marca) || str_ends_with($title, trim($suf))) return $title;
    return $title . $suf;
}

/**
 * Calcula los campos SEO de una página. Si el contenido trae 'title', se usa tal cual (sin sufijo):
 * quien escribe el title controla el largo. Las fórmulas sí llevan sufijo " – Marca".
 * $pagina: tipo, servicio?, zona?, contenido (array del archivo de contenido), path.
 */
function seo_pagina(array $p): array
{
    $c    = $p['contenido'] ?? [];
    $srv  = $p['servicio'] ?? null;
    $zona = $p['zona'] ?? null;
    $vars = vars_de($srv, $zona);
    $marca = (string)cfg('marca.nombre');
    $S = $vars['{Servicio}']; $s = $vars['{servicio}']; $Z = $vars['{zona}']; $Zseo = $vars['{zona_seo}'];
    $rubro = mb_strtolower(mb_substr((string)cfg('marca.rubro'), 0, 1)) . mb_substr((string)cfg('marca.rubro'), 1);

    switch ($p['tipo']) {
        case 'home':
            $title = cfg('sitio.titulo_home') ?: ($marca . ' | ' . cfg('marca.slogan'));
            $desc  = cfg('sitio.descripcion_home') ?: cfg('marca.descripcion');
            $hero  = $c['hero'] ?? [];
            $h1    = trim(($hero['titulo_em'] ?? $marca) . ' ' . ($hero['titulo'] ?? ''));
            $eyebrow = $hero['eyebrow'] ?? cfg('marca.slogan');
            $sub   = $hero['subtitulo'] ?? cfg('marca.descripcion');
            break;
        case 'servicio':
            $title = "{$S} – {$marca}";
            $desc  = $srv['descripcion'];
            $h1    = $S;
            $eyebrow = "Servicio de {$marca}";
            $sub   = "Elegí tu zona y escribinos por WhatsApp. " . $srv['descripcion'];
            break;
        case 'servicio-zona':
            $title = "{$S} en {$Zseo} – {$marca}";
            $padreZ = zona_padre_con_paginas($zona['slug']);
            $desc  = "{$S} en {$Z}" . ($padreZ ? ", {$padreZ['nombre']}" : '') . ': ' . mb_strtolower(mb_substr($srv['descripcion'], 0, 1)) . mb_substr($srv['descripcion'], 1);
            $h1    = "{$S} en {$Z}";
            $eyebrow = ucfirst_utf8($rubro) . " en {$Z}";
            $vec   = array_map(fn($v) => $v['nombre'], zona_vecinos($zona['slug'], 3));
            $sub   = "{$S} en {$Z}" . ($vec ? ' y zonas cercanas como ' . lista_natural($vec) : '') . '. Contanos qué necesitás y te respondemos por WhatsApp.';
            break;
        case 'zona':
            $title = "Servicios de {$rubro} en {$Zseo} – {$marca}";
            $desc  = "{$marca} en {$Z}: " . lista_natural(array_map(fn($x) => $x['nombre'], zona_servicios($zona['slug']))) . '. Escribinos por WhatsApp y te pasamos precio y horario.';
            $h1    = "{$marca} en {$Z}";
            $eyebrow = ucfirst_utf8($rubro) . " en {$Z}";
            $sub   = "Todos nuestros servicios en {$Z}" . ($zona['notas'] ? '. ' . $zona['notas'] : '.');
            break;
        case 'zonas':
            $title = "Zonas donde trabajamos – {$marca}"; $desc = "Todas las zonas que atiende {$marca}: " . lista_natural(array_map(fn($z) => $z['nombre'], zonas_raiz())) . ". Elegí la tuya y escribinos por WhatsApp."; $h1 = 'Zonas donde trabajamos'; $eyebrow = 'Cobertura'; $sub = 'Elegí tu zona para ver los servicios disponibles y el tiempo de llegada.';
            break;
        case 'nosotros':
            $title = "Quiénes somos – {$marca}"; $desc = "Conocé a {$marca}: " . cfg('marca.descripcion'); $h1 = 'Quiénes somos'; $eyebrow = 'Nosotros'; $sub = cfg('marca.slogan');
            break;
        case 'contacto':
            $title = "Contacto – {$marca}"; $desc = "Escribinos por WhatsApp o llamanos. {$marca}: " . cfg('marca.slogan'); $h1 = 'Contacto'; $eyebrow = 'Contacto'; $sub = 'Respondemos por WhatsApp al momento.';
            break;
        case 'contacto-gracias':
            $title = "Mensaje enviado – {$marca}"; $desc = "Tu mensaje fue recibido. {$marca} te responde a la brevedad."; $h1 = '¡Mensaje recibido!'; $eyebrow = 'Contacto'; $sub = '';
            break;
        case 'privacidad':
        case 'terminos':
            $title = $c['title'] ?? ucfirst($p['tipo']) . " – {$marca}"; $desc = $c['description'] ?? ''; $h1 = $c['h1'] ?? ucfirst($p['tipo']); $eyebrow = 'Legales'; $sub = '';
            break;
        default: // 404
            $title = "Página no encontrada – {$marca}"; $desc = "Esta página no existe. Encontrá el servicio o la zona que buscás en {$marca}."; $h1 = 'Página no encontrada'; $eyebrow = 'Error 404'; $sub = 'La página que buscás no existe o cambió de dirección.';
    }

    // El contenido de la página pisa las fórmulas
    $titleExplicito = !empty($c['title']);
    $title   = t($titleExplicito ? $c['title'] : $title, $vars);
    $desc    = t(!empty($c['description']) ? $c['description'] : $desc, $vars);
    $h1      = t(!empty($c['h1']) ? $c['h1'] : $h1, $vars);
    $eyebrow = t(!empty($c['eyebrow']) ? $c['eyebrow'] : $eyebrow, $vars);
    $sub     = t(!empty($c['subtitulo']) ? $c['subtitulo'] : $sub, $vars);

    $noindex = !empty($c['noindex']) || !cfg('sitio.indexar', true) || in_array($p['tipo'], ['404', 'contacto-gracias'], true);

    return [
        'title'       => $titleExplicito ? $title : seo_sufijo($title),
        'description' => recortar($desc, 158),
        'h1'          => $h1,
        'eyebrow'     => $eyebrow,
        'subtitulo'   => $sub,
        'canonical'   => canonical($p['tipo'] === '404' ? '/' : $p['path']),
        'robots'      => $noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1',
        'og_image'    => !empty($c['imagen']) ? asset_abs('img/' . $c['imagen']) : asset_abs((string)cfg('sitio.og_image')),
        'og_type'     => $p['tipo'] === 'home' ? 'website' : 'article',
        'actualizado' => !empty($c['actualizado']) ? (string)$c['actualizado'] : '',
    ];
}

/** Migas de pan de una página: [[href, label], ..., [label]] (último sin href). */
function migas_pagina(array $p): array
{
    $m = [['href' => url(''), 'label' => 'Inicio']];
    $srv = $p['servicio'] ?? null; $zona = $p['zona'] ?? null;
    switch ($p['tipo']) {
        case 'servicio':
            $m[] = ['label' => $srv['nombre_h1']];
            break;
        case 'servicio-zona':
            $m[] = ['href' => url($srv['slug']), 'label' => $srv['nombre_h1']];
            $padre = zona_padre_con_paginas($zona['slug']);
            if ($padre && combinacion_existe($srv['slug'], $padre['slug'])) {
                $m[] = ['href' => url($srv['slug'] . '/' . $padre['slug']), 'label' => $padre['nombre']];
            }
            $m[] = ['label' => $zona['nombre']];
            break;
        case 'zona':
            $padre = zona_padre_con_paginas($zona['slug']);
            if ($padre) $m[] = ['href' => url($padre['slug']), 'label' => $padre['nombre']];
            $m[] = ['label' => $zona['nombre']];
            break;
        case 'home':
            return [];
        default:
            $m[] = ['label' => $p['seo']['h1'] ?? ucfirst($p['tipo'])];
    }
    return $m;
}
