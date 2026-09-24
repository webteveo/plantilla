<?php
/**
 * Carga config, helpers y librerías. Lo incluyen index.php, sitemap.php y bin/verificar.php.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

define('BASE_DIR', dirname(__DIR__));

mb_internal_encoding('UTF-8');

/** Config completa (config.php devuelve un array). */
function cfg_all(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require BASE_DIR . '/config.php';
        if (!is_array($cfg)) throw new RuntimeException('config.php debe devolver un array');
    }
    return $cfg;
}

/** Acceso por ruta con puntos: cfg('contacto.whatsapp'), cfg('colores.primario'). */
function cfg(string $path, $default = null)
{
    $v = cfg_all();
    foreach (explode('.', $path) as $k) {
        if (!is_array($v) || !array_key_exists($k, $v)) return $default;
        $v = $v[$k];
    }
    return $v;
}

date_default_timezone_set(cfg('pais.zona_horaria', 'UTC') ?: 'UTC');

/** Escape HTML. */
function e($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Base pública del sitio para links internos. Termina en "/".
 * Funciona en raíz de dominio, en subcarpeta (http://localhost/mi-sitio/) y con php -S.
 */
function base_path(): string
{
    static $b = null;
    if ($b !== null) return $b;
    $script = str_replace(DIRECTORY_SEPARATOR, '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $dir = rtrim(dirname($script), '/');
    return $b = ($dir === '' || $dir === '.' ? '' : $dir) . '/';
}

/** URL interna (relativa a la raíz del sitio). url('plomero/barrio-norte') → /plomero/barrio-norte */
function url(string $path = ''): string
{
    return base_path() . ltrim($path, '/');
}

/** URL absoluta canónica: dominio del config + ruta. canonical('/plomero') → https://dominio/plomero */
function canonical(string $path = '/'): string
{
    $dom = rtrim((string)cfg('sitio.dominio'), '/');
    $path = '/' . ltrim($path, '/');
    return $dom . ($path === '/' ? '/' : $path);
}

/** URL de un asset con cache busting. asset('css/style.css') */
function asset(string $rel): string
{
    $file = BASE_DIR . '/assets/' . $rel;
    $v = is_file($file) ? (string)filemtime($file) : '1';
    return url('assets/' . $rel) . '?v=' . $v;
}

/** URL absoluta de un asset (para schema y OG). */
function asset_abs(string $rel): string
{
    return canonical('/assets/' . ltrim($rel, '/'));
}

/**
 * Reemplaza placeholders en un texto: {marca} {rubro} {Rubro} {servicio} {Servicio} {zona} {zona_en} {padre}
 * {ciudad} {telefono} {dominio} {email} {pais} {razon_social} {sinonimo}.
 */
function t(?string $texto, array $vars = []): string
{
    if ($texto === null || $texto === '') return '';
    $base = [
        '{marca}'        => cfg('marca.nombre'),
        '{marca_corta}'  => cfg('marca.nombre_corto') ?: cfg('marca.nombre'),
        '{rubro}'        => mb_strtolower(mb_substr((string)cfg('marca.rubro'), 0, 1)) . mb_substr((string)cfg('marca.rubro'), 1),
        '{Rubro}'        => ucfirst_utf8((string)cfg('marca.rubro')),
        '{razon_social}' => cfg('marca.razon_social') ?: cfg('marca.nombre'),
        '{ciudad}'       => cfg('direccion.ciudad'),
        '{telefono}'     => telefono_visible(),
        '{dominio}'      => preg_replace('#^https?://#', '', (string)cfg('sitio.dominio')),
        '{email}'        => cfg('contacto.email'),
        '{pais}'         => cfg('pais.nombre'),
    ];
    return strtr($texto, array_merge($base, $vars));
}

/** Teléfono legible. Si no se configuró, lo arma según el país. */
function telefono_visible(): string
{
    if (cfg('contacto.telefono_visible')) return (string)cfg('contacto.telefono_visible');
    $tel = preg_replace('/\D/', '', (string)cfg('contacto.telefono'));
    if ($tel === '') return '';
    $pref = (string)cfg('pais.prefijo_tel');
    if ($pref !== '' && str_starts_with($tel, $pref)) {
        $local = substr($tel, strlen($pref));
        if ($pref === '598') return trim(chunk_split('0' . $local, 3, ' '));          // 091 234 567
        if ($pref === '54')  return preg_replace('/^(\d{2,4})(\d{4})(\d{4})$/', '$1 $2-$3', $local) ?: $local; // 11 1234-5678
        return '0' . $local;
    }
    return '+' . $tel;
}

/** Primera letra en mayúscula respetando UTF-8. */
function ucfirst_utf8(string $s): string
{
    return mb_strtoupper(mb_substr($s, 0, 1)) . mb_substr($s, 1);
}

/** Recorta a N caracteres sin cortar palabras (para meta descriptions). */
function recortar(string $s, int $max = 155): string
{
    $s = trim(preg_replace('/\s+/', ' ', strip_tags($s)));
    if (mb_strlen($s) <= $max) return $s;
    $corte = mb_substr($s, 0, $max);
    $pos = mb_strrpos($corte, ' ');
    return rtrim($pos ? mb_substr($corte, 0, $pos) : $corte, ' ,;:.') . '…';
}

/** 2026-09-24 → "septiembre de 2026". */
function fecha_legible(string $iso): string
{
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $t = strtotime($iso);
    if (!$t) return $iso;
    return $meses[(int)date('n', $t) - 1] . ' de ' . date('Y', $t);
}

/** Une nombres: [A, B, C] → "A, B y C". */
function lista_natural(array $items): string
{
    $items = array_values(array_filter(array_map('strval', $items), fn($x) => $x !== ''));
    $n = count($items);
    if ($n === 0) return '';
    if ($n === 1) return $items[0];
    return implode(', ', array_slice($items, 0, -1)) . ' y ' . $items[$n - 1];
}

/** Elige un elemento de forma determinista según una semilla (variación de textos y anchors). */
function elegir(array $opciones, string $semilla, int $sal = 0)
{
    if (!$opciones) return null;
    $i = (crc32($semilla) + $sal) % count($opciones);
    return array_values($opciones)[$i];
}

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/wsp.php';
require_once __DIR__ . '/seo.php';
require_once __DIR__ . '/texto.php';
require_once __DIR__ . '/schema.php';
require_once __DIR__ . '/links.php';
require_once __DIR__ . '/view.php';
require_once __DIR__ . '/pagina.php';
require_once __DIR__ . '/router.php';
