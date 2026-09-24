<?php
/**
 * Texto automático para páginas servicio×zona que no tienen archivo de contenido.
 * Combina datos del servicio (descripcion, entidades, sinonimos) y de la zona (notas, referencias, vivienda,
 * tiempo_llegada, vecinos) con variantes elegidas por semilla, para que dos páginas no repitan el mismo texto.
 * Es un texto de respaldo: el contenido único escrito a mano (data/contenido/) siempre es mejor.
 * NO se toca para armar un sitio nuevo. Si se quiere otro tono global, se editan las plantillas de acá.
 */
declare(strict_types=1);

function texto_automatico(array $srv, array $zona): array
{
    $vars  = vars_de($srv, $zona);
    $seed  = $srv['slug'] . '|' . $zona['slug'];
    $S = $vars['{Servicio}']; $s = $vars['{servicio}']; $Z = $vars['{zona}']; $Zen = $vars['{zona_en}'];
    $marca = (string)cfg('marca.nombre');
    $refs  = lista_natural(array_slice($zona['referencias'], 0, 3));
    $vec   = lista_natural(array_map(fn($v) => $v['nombre'], array_slice(zona_vecinos($zona['slug'], 3), 0, 3)));
    $ent   = lista_natural(array_slice($srv['entidades'], 0, 4));
    $sin   = $srv['sinonimos'][0] ?? $s;
    $lleg  = $zona['tiempo_llegada'];

    $titulos = [
        "{$S} en {$Z}: cómo trabajamos",
        "Así es el servicio de {$s} en {$Z}",
        "{$marca} en {$Z}",
    ];
    $apertura = [
        "{$marca} atiende {$Zen}" . ($vec ? " y zonas cercanas como {$vec}" : '') . ". " . $srv['descripcion'],
        "Si necesitás {$s} en {$Z}, escribinos por WhatsApp. " . $srv['descripcion'],
        "Trabajamos como {$sin} en {$Z}" . ($refs ? ", cerca de {$refs}" : '') . ". " . $srv['descripcion'],
    ];
    $vivienda = [
        'casas'         => "En {$Z} predominan las casas, así que la mayoría de los pedidos son de viviendas particulares y sus exteriores.",
        'apartamentos'  => "En {$Z} predominan los edificios de apartamentos, con trabajos coordinados con porteros y administraciones cuando hace falta.",
        'mixto'         => "En {$Z} conviven casas y edificios, y adaptamos el trabajo a cada tipo de vivienda.",
        'rural'         => "En {$Z} muchos trabajos son en casas de campo y predios amplios, y coordinamos el traslado con anticipación.",
        'comercial'     => "En {$Z} atendemos sobre todo comercios y oficinas, con horarios acordados para no interrumpir la actividad.",
    ];
    $medio = trim(($zona['notas'] ? $zona['notas'] . ' ' : '') . ($vivienda[$zona['vivienda']] ?? $vivienda['mixto']));
    $cierre = [
        ($lleg ? "Llegamos a {$Z} en {$lleg}. " : '') . ($ent ? "Resolvemos {$ent}. " : '') . "Te pasamos el precio por WhatsApp antes de ir.",
        ($ent ? "Los pedidos más comunes de {$s} en {$Z} son {$ent}. " : '') . ($lleg ? "El tiempo de llegada habitual es de {$lleg}. " : '') . "Contanos qué pasa y te respondemos al momento.",
        "Antes de ir te confirmamos precio y horario por WhatsApp" . ($lleg ? " (llegada estimada: {$lleg})" : '') . ". " . ($ent ? "Trabajamos con {$ent}. " : '') . "Si el trabajo es distinto a lo descripto, te lo decimos antes de empezar.",
    ];
    return [[
        'h2'       => elegir($titulos, $seed, 1),
        'parrafos' => array_values(array_filter([elegir($apertura, $seed, 2), $medio, elegir($cierre, $seed, 3)])),
    ]];
}

/** Texto automático para el hub de zona sin archivo de contenido. */
function texto_automatico_zona(array $zona): array
{
    $marca = (string)cfg('marca.nombre');
    $Z = $zona['nombre'];
    $servs = lista_natural(array_map(fn($x) => $x['nombre'], zona_servicios($zona['slug'])));
    $hijas = zonas_hijas($zona['slug']);
    $p = [];
    $p[] = "{$marca} trabaja en {$Z} con los siguientes servicios: {$servs}. Elegí el que necesitás y escribinos por WhatsApp con tu consulta.";
    if ($zona['notas']) $p[] = $zona['notas'];
    if ($zona['tiempo_llegada']) $p[] = "Tiempo de llegada habitual a {$Z}: {$zona['tiempo_llegada']}.";
    if ($hijas) $p[] = "También tenemos páginas por zona para " . lista_natural(array_map(fn($h) => $h['nombre'], array_slice($hijas, 0, 6))) . (count($hijas) > 6 ? ' y más' : '') . '.';
    return [['h2' => "Servicios de {$marca} en {$Z}", 'parrafos' => $p]];
}

/** Texto automático para la página troncal de un servicio sin archivo de contenido. */
function texto_automatico_servicio(array $srv): array
{
    $marca = (string)cfg('marca.nombre');
    $S = $srv['nombre_h1']; $s = $srv['nombre'];
    $ent = lista_natural($srv['entidades']);
    $raiz = lista_natural(array_map(fn($z) => $z['nombre'], zonas_raiz()));
    $p = [$srv['descripcion']];
    if ($ent) $p[] = "Dentro de {$s} resolvemos {$ent}.";
    if ($raiz) $p[] = "Atendemos {$raiz}. Elegí tu zona para ver la página con tiempos de llegada y preguntas frecuentes.";
    return [['h2' => "{$S}: qué incluye el servicio de {$marca}", 'parrafos' => $p]];
}

/** Aplica placeholders a una lista de secciones [{h2, parrafos[], lista[]}]. */
function secciones_t(array $secciones, array $vars): array
{
    $out = [];
    foreach ($secciones as $sec) {
        if (empty($sec['h2'])) continue;
        $tabla = null;
        if (!empty($sec['tabla']['filas'])) {
            $tabla = [
                'cabecera' => array_map(fn($x) => t((string)$x, $vars), array_values((array)($sec['tabla']['cabecera'] ?? []))),
                'filas'    => array_map(fn($f) => array_map(fn($x) => t((string)$x, $vars), array_values((array)$f)), array_values((array)$sec['tabla']['filas'])),
                'nota'     => t((string)($sec['tabla']['nota'] ?? ''), $vars),
            ];
        }
        $out[] = [
            'h2'       => t($sec['h2'], $vars),
            'parrafos' => array_map(fn($x) => t($x, $vars), array_values((array)($sec['parrafos'] ?? []))),
            'lista'    => array_map(fn($x) => t($x, $vars), array_values((array)($sec['lista'] ?? []))),
            'tabla'    => $tabla,
            'parrafos_despues' => array_map(fn($x) => t($x, $vars), array_values((array)($sec['parrafos_despues'] ?? []))),
        ];
    }
    return $out;
}

/** Aplica placeholders a una FAQ [{q, a}]. */
function faq_t(array $faq, array $vars): array
{
    $out = [];
    foreach ($faq as $f) {
        if (empty($f['q']) || empty($f['a'])) continue;
        $out[] = ['q' => t($f['q'], $vars), 'a' => t($f['a'], $vars)];
    }
    return $out;
}
