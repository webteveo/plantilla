<?php
/**
 * WhatsApp: mensaje precargado por página, href y atributos para el evento GA4 (config analitica.evento_wsp).
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

/** Variables de placeholders para un servicio/zona dados. */
function vars_de(?array $servicio, ?array $zona): array
{
    $v = [];
    $zonaPrincipal = zona((string)cfg('direccion.zona_principal')) ?: null;
    $z = $zona ?: $zonaPrincipal;
    $v['{servicio}'] = $servicio ? $servicio['nombre'] : mb_strtolower((string)cfg('marca.rubro'));
    $v['{Servicio}'] = $servicio ? $servicio['nombre_h1'] : ucfirst_utf8((string)cfg('marca.rubro'));
    $v['{sinonimo}'] = $servicio && $servicio['sinonimos'] ? $servicio['sinonimos'][0] : $v['{servicio}'];
    $v['{zona}']     = $z ? $z['nombre'] : (string)cfg('direccion.ciudad');
    $v['{zona_en}']  = $z ? $z['nombre_en'] : (string)cfg('direccion.ciudad');
    $v['{zona_seo}'] = $z ? zona_nombre_seo($z['slug']) : (string)cfg('direccion.ciudad');
    $padre = $z ? zona_padre_con_paginas($z['slug']) : null;
    $v['{padre}']    = $padre ? $padre['nombre'] : $v['{zona}'];
    $v['{llegada}']  = $z ? ($z['tiempo_llegada'] ?: '') : '';
    return $v;
}

/** Mensaje precargado según la página. $override viene del contenido (wsp_mensaje) o del servicio. */
function wsp_mensaje(?array $servicio = null, ?array $zona = null, string $override = ''): string
{
    if ($override !== '') $tpl = $override;
    elseif ($servicio && $zona) $tpl = $servicio['wsp_mensaje'] ?: (string)cfg('contacto.wsp_mensaje');
    elseif ($servicio) $tpl = (string)cfg('contacto.wsp_mensaje_servicio');
    elseif ($zona) $tpl = (string)cfg('contacto.wsp_mensaje_zona');
    else $tpl = (string)cfg('contacto.wsp_mensaje_generico');
    return t($tpl, vars_de($servicio, $zona));
}

/** Href del botón: wa.me con el mensaje, o /contacto si no hay número (etapa de posicionamiento). */
function wsp_href(string $mensaje): string
{
    $num = preg_replace('/\D/', '', (string)cfg('contacto.whatsapp'));
    if ($num !== '') return 'https://wa.me/' . $num . '?text=' . rawurlencode($mensaje);
    return url('contacto') . '?origen=whatsapp&msg=' . rawurlencode($mensaje) . '#contacto-form-title';
}

/**
 * Atributos completos de un <a> de WhatsApp: href + data-* para GA4 (main.js dispara el evento con
 * servicio, zona y página) + target/rel. Uso: <a <?= wsp_attrs($pagina) ?> class="...">
 * $pagina: array con 'servicio', 'zona', 'wsp_mensaje' (ver lib/pagina.php). $mensaje fuerza otro texto.
 */
function wsp_attrs(array $pagina, string $mensaje = ''): string
{
    $msg = $mensaje !== '' ? $mensaje : ($pagina['wsp_mensaje'] ?? wsp_mensaje());
    $srv = $pagina['servicio']['slug'] ?? '';
    $zon = $pagina['zona']['slug'] ?? '';
    $ext = preg_replace('/\D/', '', (string)cfg('contacto.whatsapp')) !== '';
    return 'href="' . e(wsp_href($msg)) . '" data-wsp data-servicio="' . e($srv) . '" data-zona="' . e($zon) . '" data-pagina="' . e($pagina['tipo'] ?? '') . '"'
        . ($ext ? ' target="_blank" rel="noopener"' : '');
}

/** Atributos de un link tel: con evento GA4. Vacío si no hay teléfono. */
function tel_attrs(): string
{
    $tel = preg_replace('/\D/', '', (string)cfg('contacto.telefono'));
    return $tel === '' ? '' : 'href="tel:+' . e($tel) . '" data-tel';
}
