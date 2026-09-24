<?php
/**
 * Arma el array $pagina que reciben los templates: tipo, servicio, zona, contenido, seo, migas, secciones,
 * faq, links, wsp_mensaje, schema. Es el único punto donde se combinan datos + fórmulas.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

function pagina_armar(array $ruta): array
{
    $tipo = $ruta['tipo'];
    $p = ['tipo' => $tipo, 'path' => $ruta['path'] ?? '/', 'servicio' => null, 'zona' => null, 'contenido' => []];

    switch ($tipo) {
        case 'home':          $p['contenido'] = contenido_pagina('home'); break;
        case 'servicio':      $p['servicio'] = servicio($ruta['servicio']); $p['contenido'] = contenido_servicio($ruta['servicio']); break;
        case 'servicio-zona': $p['servicio'] = servicio($ruta['servicio']); $p['zona'] = zona($ruta['zona']); $p['contenido'] = contenido_servicio_zona($ruta['servicio'], $ruta['zona']); break;
        case 'zona':          $p['zona'] = zona($ruta['zona']); $p['contenido'] = contenido_zona($ruta['zona']); break;
        case 'nosotros':      $p['contenido'] = contenido_pagina('nosotros'); break;
        case 'zonas':         $p['contenido'] = contenido_pagina('zonas'); break;
        case 'contacto':
        case 'contacto-gracias': $p['contenido'] = contenido_pagina('contacto'); break;
        case 'privacidad':
        case 'terminos':      $p['contenido'] = contenido_pagina('legales')[$tipo] ?? []; break;
    }

    $vars = vars_de($p['servicio'], $p['zona']);
    $c = $p['contenido'];

    $p['wsp_mensaje'] = wsp_mensaje($p['servicio'], $p['zona'], (string)($c['wsp_mensaje'] ?? ''));
    $p['seo']   = seo_pagina($p);
    $p['migas'] = migas_pagina($p);

    // Imagen de la página (relativa a assets/img/): del contenido, del servicio o ninguna
    $img = $c['imagen'] ?? (!empty($p['servicio']['imagen']) ? 'servicios/' . $p['servicio']['imagen'] : '');
    $p['imagen']     = $img;
    $p['imagen_abs'] = $img ? asset_abs('img/' . $img) : '';
    $p['imagen_alt'] = t($c['imagen_alt'] ?? ($p['servicio']['imagen_alt'] ?? ''), $vars);

    // Secciones de texto: del contenido, o automáticas
    $secs = secciones_t((array)($c['secciones'] ?? []), $vars);
    if (!$secs) {
        if ($tipo === 'servicio-zona') $secs = texto_automatico($p['servicio'], $p['zona']);
        elseif ($tipo === 'zona')      $secs = texto_automatico_zona($p['zona']);
        elseif ($tipo === 'servicio')  $secs = texto_automatico_servicio($p['servicio']);
    }
    $p['secciones'] = $secs;

    // FAQ: las de la página + las base del servicio (salvo faq_reemplaza)
    $faq = faq_t((array)($c['faq'] ?? []), $vars);
    if ($p['servicio'] && empty($c['faq_reemplaza'])) $faq = array_merge($faq, faq_t($p['servicio']['faq'], $vars));
    // Sin duplicados por pregunta
    $vistas = []; $p['faq'] = [];
    foreach ($faq as $f) { $k = mb_strtolower($f['q']); if (!isset($vistas[$k])) { $vistas[$k] = 1; $p['faq'][] = $f; } }

    // Enlaces internos
    if ($tipo === 'servicio-zona') $p['links'] = links_servicio_zona($p['servicio'], $p['zona']);
    elseif ($tipo === 'zona')      $p['links'] = links_zona($p['zona']);
    elseif ($tipo === 'servicio')  $p['links'] = links_servicio($p['servicio']);
    else $p['links'] = [];

    // Beneficios del servicio (con placeholders)
    $p['beneficios'] = [];
    foreach ((array)($p['servicio']['beneficios'] ?? []) as $b) {
        $p['beneficios'][] = ['titulo' => t($b['titulo'] ?? '', $vars), 'texto' => t($b['texto'] ?? '', $vars)];
    }

    $p['vars']   = $vars;
    $p['comun']  = comun();
    // Reseñas de esta página (contenido.testimonios) pisan a las globales; solo reales, nunca de relleno
    if (!empty($c['testimonios'])) $p['comun']['testimonios'] = array_values((array)$c['testimonios']);
    $p['schema'] = schema_bloques($p);
    return $p;
}

/** Texto común con placeholders de la página: ct('faq_lead') */
function ct(string $clave, string $default = ''): string
{
    $p = $GLOBALS['pagina'] ?? [];
    return t((string)(($p['comun'] ?? [])[$clave] ?? $default), $p['vars'] ?? []);
}
