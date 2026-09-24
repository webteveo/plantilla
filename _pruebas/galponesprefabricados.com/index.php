<?php
/**
 * Entrada única del sitio. Con Apache, .htaccess manda acá todo lo que no es archivo.
 * Con el servidor embebido: php -S localhost:8000 index.php (este archivo también sirve los estáticos).
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

// Servidor embebido de PHP: dejar que sirva los archivos estáticos reales
if (PHP_SAPI === 'cli-server') {
    $f = __DIR__ . rawurldecode((string)parse_url((string)$_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (is_file($f) && !str_ends_with($f, '.php')) return false;
}

require_once __DIR__ . '/lib/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) session_start();

router_despachar();
