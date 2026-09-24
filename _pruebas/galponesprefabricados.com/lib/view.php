<?php
/**
 * Render de templates y partials. Las variables se pasan como array y quedan disponibles como $variables.
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

/** Renderiza templates/{nombre}.php y devuelve el HTML. */
function render(string $template, array $vars = []): string
{
    $file = BASE_DIR . '/templates/' . $template . '.php';
    if (!is_file($file)) throw new RuntimeException("Template no encontrado: $template");
    extract($vars, EXTR_SKIP);
    ob_start();
    require $file;
    return (string)ob_get_clean();
}

/** Incluye partials/{nombre}.php. $pagina siempre está disponible. */
function partial(string $nombre, array $vars = []): void
{
    $file = BASE_DIR . '/partials/' . $nombre . '.php';
    if (!is_file($file)) throw new RuntimeException("Partial no encontrado: $nombre");
    if (isset($GLOBALS['pagina']) && !isset($vars['pagina'])) $vars['pagina'] = $GLOBALS['pagina'];
    extract($vars, EXTR_SKIP);
    require $file;
}

/** Variables CSS de colores (config colores) para el :root. */
function css_colores(): string
{
    $out = '';
    foreach ((array)cfg('colores', []) as $k => $v) {
        if ($k === 'avatares') {
            foreach ((array)$v as $i => $c) $out .= "--color-avatar-" . ($i + 1) . ":" . $c . ";";
            continue;
        }
        $out .= "--color-{$k}:{$v};";
        // Versión RGB para transparencias: rgb(var(--color-x-rgb) / .5)
        if (preg_match('/^#([0-9a-f]{6})$/i', (string)$v, $m)) {
            $out .= "--color-{$k}-rgb:" . hexdec(substr($m[1], 0, 2)) . ' ' . hexdec(substr($m[1], 2, 2)) . ' ' . hexdec(substr($m[1], 4, 2)) . ";";
        } elseif (preg_match('/^#([0-9a-f]{3})$/i', (string)$v, $m)) {
            $out .= "--color-{$k}-rgb:" . hexdec($m[1][0] . $m[1][0]) . ' ' . hexdec($m[1][1] . $m[1][1]) . ' ' . hexdec($m[1][2] . $m[1][2]) . ";";
        }
    }
    return $out;
}
