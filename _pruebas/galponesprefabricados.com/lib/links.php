<?php
/**
 * Enlazado interno automático con anchors variados.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

/** Anchor variado para "servicio en zona". La variante depende de la semilla (página origen + destino). */
function anchor_servicio_zona(array $srv, array $zona, string $semilla): string
{
    $S = $srv['nombre_h1']; $s = $srv['nombre']; $Z = $zona['nombre']; $Zen = $zona['nombre_en'];
    $sin = $srv['sinonimos'] ? ucfirst_utf8(elegir($srv['sinonimos'], $semilla, 7)) : $S;
    $ops = [
        "{$S} en {$Z}",
        "{$s} en {$Zen}",
        "{$sin} en {$Z}",
        "{$Z}: {$s}",
        "{$S} {$Zen}",
    ];
    return elegir($ops, $semilla, 0);
}

/** Enlaces de una página servicio×zona: vecinas con el mismo servicio, otros servicios en la zona, zona padre y hub. */
function links_servicio_zona(array $srv, array $zona): array
{
    $out = ['vecinos' => [], 'otros' => [], 'padre' => [], 'hijas' => []];
    $origen = $srv['slug'] . '/' . $zona['slug'];

    // Mismo servicio en zonas vecinas (solo combinaciones declaradas)
    foreach (zona_vecinos($zona['slug']) as $v) {
        if (!combinacion_existe($srv['slug'], $v['slug'])) continue;
        $out['vecinos'][] = ['href' => url($srv['slug'] . '/' . $v['slug']), 'label' => anchor_servicio_zona($srv, $v, $origen . '>' . $v['slug'])];
    }
    // Mismo servicio en zonas hijas (cuando la página es una zona padre)
    foreach (zonas_hijas($zona['slug']) as $h) {
        if (!combinacion_existe($srv['slug'], $h['slug'])) continue;
        $out['hijas'][] = ['href' => url($srv['slug'] . '/' . $h['slug']), 'label' => $h['nombre']];
    }
    // Otros servicios en la misma zona
    foreach (zona_servicios($zona['slug']) as $o) {
        if ($o['slug'] === $srv['slug']) continue;
        $out['otros'][] = ['href' => url($o['slug'] . '/' . $zona['slug']), 'label' => anchor_servicio_zona($o, $zona, $origen . '>' . $o['slug'])];
    }
    // Zona padre: mismo servicio en el padre + hub del padre; y hub de la zona actual
    $padre = zona_padre_con_paginas($zona['slug']);
    if ($padre && combinacion_existe($srv['slug'], $padre['slug'])) {
        $out['padre'][] = ['href' => url($srv['slug'] . '/' . $padre['slug']), 'label' => elegir(["{$srv['nombre_h1']} en todo {$padre['nombre_en']}", "{$srv['nombre_h1']} en {$padre['nombre']}", "Ver {$srv['nombre']} en {$padre['nombre']}"], $origen, 5)];
    }
    if ($padre) $out['padre'][] = ['href' => url($padre['slug']), 'label' => "Todos los servicios en {$padre['nombre']}"];
    $out['padre'][] = ['href' => url($zona['slug']), 'label' => elegir(["Todos los servicios en {$zona['nombre']}", "Qué hacemos en {$zona['nombre']}", "{$zona['nombre']}: todos los servicios"], $origen, 9)];
    $out['padre'][] = ['href' => url($srv['slug']), 'label' => elegir(["{$srv['nombre_h1']}: página del servicio", "Más sobre {$srv['nombre']}", "Servicio de {$srv['nombre']}"], $origen, 11)];
    return $out;
}

/** Enlaces del hub de zona: cada servicio en la zona, hijas, hermanas/vecinas y padre. */
function links_zona(array $zona): array
{
    $out = ['servicios' => [], 'hijas' => [], 'vecinos' => [], 'padre' => []];
    foreach (zona_servicios($zona['slug']) as $s) {
        $out['servicios'][] = ['href' => url($s['slug'] . '/' . $zona['slug']), 'label' => anchor_servicio_zona($s, $zona, 'hub:' . $zona['slug'] . '>' . $s['slug']), 'servicio' => $s];
    }
    foreach (zonas_hijas($zona['slug']) as $h) if (zona_servicios($h['slug'])) $out['hijas'][] = ['href' => url($h['slug']), 'label' => $h['nombre']];
    foreach (zona_vecinos($zona['slug']) as $v) if (zona_servicios($v['slug'])) $out['vecinos'][] = ['href' => url($v['slug']), 'label' => "Servicios en {$v['nombre']}"];
    $padre = zona_padre_con_paginas($zona['slug']);
    if ($padre) $out['padre'][] = ['href' => url($padre['slug']), 'label' => "Volver a {$padre['nombre']}"];
    return $out;
}

/** Enlaces de la página troncal de un servicio: zonas agrupadas y otros servicios. */
function links_servicio(array $srv): array
{
    $out = ['zonas' => [], 'otros' => []];
    foreach (servicio_zonas_agrupadas($srv['slug']) as $padreSlug => $zs) {
        $grupo = ['padre' => $padreSlug !== '_raiz' ? zona($padreSlug) : null, 'items' => []];
        foreach ($zs as $z) $grupo['items'][] = ['href' => url($srv['slug'] . '/' . $z['slug']), 'label' => $z['nombre']];
        $out['zonas'][] = $grupo;
    }
    foreach (servicios() as $o) {
        if ($o['slug'] !== $srv['slug']) $out['otros'][] = ['href' => url($o['slug']), 'label' => $o['nombre_h1']];
    }
    return $out;
}
