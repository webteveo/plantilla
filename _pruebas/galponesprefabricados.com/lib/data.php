<?php
/**
 * Acceso a los datos (data/servicios.php, data/zonas.php, data/contenido/). Valida y normaliza.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

/** Slugs que no pueden usarse como servicio ni zona. */
const SLUGS_RESERVADOS = ['nosotros', 'contacto', 'privacidad', 'terminos', 'assets', 'sitemap', 'robots', 'llms', 'index', 'servicios', 'zonas', 'vendor', 'lib', 'data', 'templates', 'partials', 'bin'];

function slug_valido(string $s): bool
{
    return (bool)preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $s);
}

/** Zonas normalizadas. */
function zonas(): array
{
    static $z = null;
    if ($z !== null) return $z;
    $raw = require BASE_DIR . '/data/zonas.php';
    $z = [];
    foreach ($raw as $slug => $d) {
        $slug = (string)$slug;
        if (!slug_valido($slug)) throw new RuntimeException("Zona con slug inválido: '$slug'");
        if (in_array($slug, SLUGS_RESERVADOS, true)) throw new RuntimeException("Zona con slug reservado: '$slug'");
        if (empty($d['nombre']) || empty($d['tipo'])) throw new RuntimeException("Zona '$slug' sin nombre o tipo");
        $d['slug']      = $slug;
        $d['padre']     = $d['padre'] ?? null;
        $d['paginas']   = array_key_exists('paginas', $d) ? (bool)$d['paginas'] : ($d['tipo'] !== 'pais');
        $d['nombre_en'] = !empty($d['nombre_en']) ? $d['nombre_en'] : $d['nombre'];
        $d['vecinos']   = array_values((array)($d['vecinos'] ?? []));
        $d['referencias'] = array_values((array)($d['referencias'] ?? []));
        $d['tiempo_llegada'] = $d['tiempo_llegada'] ?? '';
        $d['notas']     = $d['notas'] ?? '';
        $d['vivienda']  = $d['vivienda'] ?? 'mixto';
        $d['orden']     = $d['orden'] ?? 0;
        $z[$slug] = $d;
    }
    foreach ($z as $slug => $d) {
        if ($d['padre'] !== null && !isset($z[$d['padre']])) throw new RuntimeException("Zona '$slug': padre '{$d['padre']}' no existe");
        foreach ($d['vecinos'] as $v) if (!isset($z[$v])) throw new RuntimeException("Zona '$slug': vecino '$v' no existe");
    }
    return $z;
}

function zona(string $slug): ?array
{
    return zonas()[$slug] ?? null;
}

/** Zonas que generan páginas. */
function zonas_con_paginas(): array
{
    return array_filter(zonas(), fn($z) => $z['paginas']);
}

/** Padre directo. */
function zona_padre(string $slug): ?array
{
    $z = zona($slug);
    return $z && $z['padre'] ? zona($z['padre']) : null;
}

/** Ancestros de arriba hacia abajo (país primero), sin la zona misma. */
function zona_ancestros(string $slug): array
{
    $out = [];
    $z = zona($slug);
    while ($z && $z['padre']) {
        $z = zona($z['padre']);
        if ($z) array_unshift($out, $z);
    }
    return $out;
}

/** Ancestro más cercano que tenga páginas (para migas y enlace "zona padre"). */
function zona_padre_con_paginas(string $slug): ?array
{
    foreach (array_reverse(zona_ancestros($slug)) as $a) if ($a['paginas']) return $a;
    return null;
}

/** Hijas directas con páginas, ordenadas. */
function zonas_hijas(string $slug): array
{
    $h = array_filter(zonas_con_paginas(), fn($z) => $z['padre'] === $slug);
    uasort($h, fn($a, $b) => [$a['orden'], $a['nombre']] <=> [$b['orden'], $b['nombre']]);
    return $h;
}

/** Todas las descendientes con páginas (recursivo). */
function zonas_descendientes(string $slug): array
{
    $out = [];
    foreach (zonas_hijas($slug) as $h) {
        $out[$h['slug']] = $h;
        $out += zonas_descendientes($h['slug']);
    }
    return $out;
}

/** Zonas de primer nivel con páginas (su padre no tiene páginas). Son las que lista la home. */
function zonas_raiz(): array
{
    $r = array_filter(zonas_con_paginas(), fn($z) => !$z['padre'] || !zona($z['padre'])['paginas']);
    uasort($r, fn($a, $b) => [$a['orden'], $a['nombre']] <=> [$b['orden'], $b['nombre']]);
    return $r;
}

/** Vecinas: las declaradas, o las hermanas del mismo padre. Solo con páginas. */
function zona_vecinos(string $slug, int $max = 8): array
{
    $z = zona($slug);
    if (!$z) return [];
    $out = [];
    foreach ($z['vecinos'] as $v) if (zona($v) && zona($v)['paginas']) $out[$v] = zona($v);
    if (!$out && $z['padre']) {
        foreach (zonas_hijas($z['padre']) as $h) if ($h['slug'] !== $slug) $out[$h['slug']] = $h;
    }
    return array_slice($out, 0, $max, true);
}

/**
 * Nombre para title/meta: si otra zona con páginas tiene el mismo nombre, se agrega el padre
 * ("Centro, Ciudad A") para que title y H1 no se repitan.
 */
function zona_nombre_seo(string $slug): string
{
    static $dup = null;
    if ($dup === null) {
        $c = [];
        foreach (zonas_con_paginas() as $z) $c[mb_strtolower($z['nombre'])] = ($c[mb_strtolower($z['nombre'])] ?? 0) + 1;
        $dup = array_filter($c, fn($n) => $n > 1);
    }
    $z = zona($slug);
    if (!$z) return '';
    if (isset($dup[mb_strtolower($z['nombre'])])) {
        $p = zona_padre($slug);
        if ($p) return $z['nombre'] . ', ' . $p['nombre'];
    }
    return $z['nombre'];
}

/** Servicios normalizados. */
function servicios(): array
{
    static $s = null;
    if ($s !== null) return $s;
    $raw = require BASE_DIR . '/data/servicios.php';
    $s = [];
    $orden = 0;
    foreach ($raw as $slug => $d) {
        $slug = (string)$slug;
        if (!slug_valido($slug)) throw new RuntimeException("Servicio con slug inválido: '$slug'");
        if (in_array($slug, SLUGS_RESERVADOS, true)) throw new RuntimeException("Servicio con slug reservado: '$slug'");
        if (isset(zonas()[$slug])) throw new RuntimeException("El slug '$slug' está usado como servicio y como zona");
        if (empty($d['nombre']) || empty($d['descripcion'])) throw new RuntimeException("Servicio '$slug' sin nombre o descripcion");
        if (!isset($d['zonas'])) throw new RuntimeException("Servicio '$slug' sin 'zonas' ('*' o lista)");
        $d['slug']       = $slug;
        $d['nombre_h1']  = !empty($d['nombre_h1']) ? $d['nombre_h1'] : ucfirst_utf8($d['nombre']);
        $d['sinonimos']  = array_values((array)($d['sinonimos'] ?? []));
        $d['entidades']  = array_values((array)($d['entidades'] ?? []));
        $d['faq']        = array_values((array)($d['faq'] ?? []));
        $d['beneficios'] = array_values((array)($d['beneficios'] ?? []));
        $d['imagen']     = $d['imagen'] ?? '';
        $d['imagen_alt'] = $d['imagen_alt'] ?? '';
        $d['wsp_mensaje']= $d['wsp_mensaje'] ?? '';
        $d['schema_extra'] = (array)($d['schema_extra'] ?? []);
        $d['orden']      = $d['orden'] ?? $orden++;
        // Zonas declaradas para este servicio
        if ($d['zonas'] === '*') {
            $lista = array_keys(zonas_con_paginas());
        } else {
            $lista = [];
            foreach ((array)$d['zonas'] as $zs) {
                if (!zona($zs)) throw new RuntimeException("Servicio '$slug': zona '$zs' no existe");
                if (!zona($zs)['paginas']) throw new RuntimeException("Servicio '$slug': zona '$zs' no genera páginas (paginas => false)");
                $lista[] = $zs;
            }
        }
        $lista = array_diff($lista, (array)($d['excluir_zonas'] ?? []));
        $d['zonas_lista'] = array_values($lista);
        $s[$slug] = $d;
    }
    uasort($s, fn($a, $b) => $a['orden'] <=> $b['orden']);
    return $s;
}

function servicio(string $slug): ?array
{
    return servicios()[$slug] ?? null;
}

/** Slugs de zonas donde existe el servicio. */
function servicio_zonas(string $srv): array
{
    return servicio($srv)['zonas_lista'] ?? [];
}

function combinacion_existe(string $srv, string $zona): bool
{
    return in_array($zona, servicio_zonas($srv), true);
}

/** Servicios disponibles en una zona. */
function zona_servicios(string $zona): array
{
    return array_filter(servicios(), fn($s) => in_array($zona, $s['zonas_lista'], true));
}

/** Zonas de un servicio agrupadas por su padre con páginas (para chips y hubs). */
function servicio_zonas_agrupadas(string $srv): array
{
    $g = [];
    foreach (servicio_zonas($srv) as $zs) {
        $z = zona($zs);
        $p = zona_padre_con_paginas($zs);
        $g[$p ? $p['slug'] : '_raiz'][$zs] = $z;
    }
    return $g;
}

// ── Contenido ────────────────────────────────────────────────────────────────

function contenido_archivo(string $rel): array
{
    $file = BASE_DIR . '/data/contenido/' . $rel . '.php';
    if (!is_file($file)) return [];
    $c = require $file;
    if (!is_array($c)) throw new RuntimeException("data/contenido/$rel.php debe devolver un array");
    return $c;
}

function contenido_archivo_mtime(string $rel): ?int
{
    $file = BASE_DIR . '/data/contenido/' . $rel . '.php';
    return is_file($file) ? filemtime($file) : null;
}

function contenido_servicio_zona(string $srv, string $zona): array { return contenido_archivo($srv . '/' . $zona); }
function contenido_servicio(string $srv): array { return contenido_archivo('servicios/' . $srv); }
function contenido_zona(string $zona): array { return contenido_archivo('zonas/' . $zona); }
function contenido_pagina(string $nombre): array { return contenido_archivo('paginas/' . $nombre); }

/** Bloques comunes (pasos, diferenciadores, nosotros, textos de secciones). */
function comun(): array
{
    static $c = null;
    return $c ??= contenido_pagina('comun');
}

/** Fecha de última modificación de los datos globales (para lastmod del sitemap). */
function datos_mtime(): int
{
    $m = 0;
    foreach (['config.php', 'data/servicios.php', 'data/zonas.php'] as $f) {
        $m = max($m, (int)@filemtime(BASE_DIR . '/' . $f));
    }
    return $m ?: time();
}

/**
 * Todas las páginas indexables del sitio: [tipo, path, servicio?, zona?, lastmod, prioridad].
 * Lo usan el sitemap, llms.txt y bin/verificar.php.
 */
function paginas_todas(): array
{
    $p = [];
    $base = datos_mtime();
    $p[] = ['tipo' => 'home', 'path' => '/', 'lastmod' => max($base, contenido_archivo_mtime('paginas/home') ?? 0), 'prioridad' => '1.0'];
    foreach (servicios() as $s) {
        $p[] = ['tipo' => 'servicio', 'path' => '/' . $s['slug'], 'servicio' => $s['slug'], 'lastmod' => max($base, contenido_archivo_mtime('servicios/' . $s['slug']) ?? 0), 'prioridad' => '0.9'];
    }
    foreach (zonas_con_paginas() as $z) {
        if (!zona_servicios($z['slug'])) continue; // zona sin ningún servicio: no tiene hub
        $p[] = ['tipo' => 'zona', 'path' => '/' . $z['slug'], 'zona' => $z['slug'], 'lastmod' => max($base, contenido_archivo_mtime('zonas/' . $z['slug']) ?? 0), 'prioridad' => '0.7'];
    }
    foreach (servicios() as $s) {
        foreach ($s['zonas_lista'] as $zs) {
            $p[] = ['tipo' => 'servicio-zona', 'path' => '/' . $s['slug'] . '/' . $zs, 'servicio' => $s['slug'], 'zona' => $zs,
                    'lastmod' => max($base, contenido_archivo_mtime($s['slug'] . '/' . $zs) ?? 0),
                    'prioridad' => zona($zs)['tipo'] === 'barrio' ? '0.6' : '0.8'];
        }
    }
    $p[] = ['tipo' => 'zonas', 'path' => '/zonas', 'lastmod' => $base, 'prioridad' => '0.6'];
    $p[] = ['tipo' => 'nosotros', 'path' => '/nosotros', 'lastmod' => max($base, contenido_archivo_mtime('paginas/nosotros') ?? 0), 'prioridad' => '0.5'];
    $p[] = ['tipo' => 'contacto', 'path' => '/contacto', 'lastmod' => max($base, contenido_archivo_mtime('paginas/contacto') ?? 0), 'prioridad' => '0.6'];
    if (cfg('ui.legales', true)) {
        $p[] = ['tipo' => 'privacidad', 'path' => '/privacidad', 'lastmod' => $base, 'prioridad' => '0.2'];
        $p[] = ['tipo' => 'terminos', 'path' => '/terminos', 'lastmod' => $base, 'prioridad' => '0.2'];
    }
    return $p;
}
