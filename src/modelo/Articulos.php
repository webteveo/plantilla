<?php
/**
 * Modelo de articulos. Cada articulo es un archivo PHP en data/articulos/ que devuelve un array.
 * Nombre de archivo: YYYY-MM-DD-slug.php (la fecha del nombre solo ordena; la fecha real es la clave 'fecha').
 * Los archivos que empiezan con "_" se ignoran (plantilla, borradores).
 *
 * Campos del array (ver data/articulos/_plantilla.php):
 *   slug, titulo, title, description, keywords, categoria, fecha, actualizado, autor, imagen, imagen_alt,
 *   respuesta (parrafo directo), puntos_clave[], secciones[{h2, html?, parrafos[]?, lista[]?, h3s[{h3, html?, parrafos[]?, lista[]?}]?}],
 *   faq[{q,a}], fuentes[{label,url}], links[{href,label}], cta_message
 */
class Articulos
{
    private const DIR = 'data/articulos';

    private static ?array $cache = null;

    /** Todos los articulos publicados, del mas nuevo al mas viejo. */
    public static function todos(): array
    {
        if (self::$cache !== null) return self::$cache;

        $items = [];
        foreach (glob(self::DIR . '/*.php') ?: [] as $file) {
            $base = basename($file);
            if (str_starts_with($base, '_')) continue;
            $a = include $file;
            if (!is_array($a) || empty($a['slug']) || empty($a['titulo'])) continue;
            if (!empty($a['borrador'])) continue;
            $a['fecha']       = $a['fecha'] ?? substr($base, 0, 10);
            $a['actualizado'] = $a['actualizado'] ?? $a['fecha'];
            if ($a['fecha'] > date('Y-m-d')) continue; // programado a futuro
            $a['_archivo']    = $base;
            $items[$a['slug']] = $a;
        }
        uasort($items, fn($x, $y) => strcmp($y['fecha'], $x['fecha']) ?: strcmp($y['_archivo'], $x['_archivo']));
        return self::$cache = $items;
    }

    public static function porSlug(string $slug): ?array
    {
        return self::todos()[$slug] ?? null;
    }

    /** Otros articulos, misma categoria primero. */
    public static function relacionados(array $a, int $n = 3): array
    {
        $otros = array_values(array_filter(self::todos(), fn($x) => $x['slug'] !== $a['slug']));
        usort($otros, fn($x, $y) => (int)(($y['categoria'] ?? '') === ($a['categoria'] ?? '')) <=> (int)(($x['categoria'] ?? '') === ($a['categoria'] ?? '')));
        return array_slice($otros, 0, $n);
    }

    public static function categorias(): array
    {
        $cats = [];
        foreach (self::todos() as $a) {
            $c = $a['categoria'] ?? 'General';
            $cats[$c] = ($cats[$c] ?? 0) + 1;
        }
        ksort($cats);
        return $cats;
    }

    /** Texto plano de todo el articulo (para tiempo de lectura y feeds). */
    public static function textoPlano(array $a): string
    {
        $t = [$a['respuesta'] ?? ''];
        foreach ($a['puntos_clave'] ?? [] as $p) $t[] = $p;
        foreach ($a['secciones'] ?? [] as $s) {
            $t[] = $s['h2'] ?? '';
            $t[] = self::bloqueTexto($s);
            foreach ($s['h3s'] ?? [] as $h) { $t[] = $h['h3'] ?? ''; $t[] = self::bloqueTexto($h); }
        }
        foreach ($a['faq'] ?? [] as $f) { $t[] = $f['q']; $t[] = $f['a']; }
        return trim(preg_replace('/\s+/', ' ', strip_tags(implode(' ', $t))));
    }

    private static function bloqueTexto(array $b): string
    {
        $t = [$b['html'] ?? ''];
        foreach ($b['parrafos'] ?? [] as $p) $t[] = $p;
        foreach ($b['lista'] ?? [] as $l) $t[] = $l;
        return implode(' ', $t);
    }

    public static function minutosLectura(array $a): int
    {
        $palabras = count(preg_split('/\s+/u', self::textoPlano($a), -1, PREG_SPLIT_NO_EMPTY));
        return max(1, (int) round($palabras / 200));
    }

    public static function fechaLegible(string $ymd): string
    {
        $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        [$y, $m, $d] = array_map('intval', explode('-', $ymd));
        return $d . ' de ' . ($meses[$m - 1] ?? '') . ' de ' . $y;
    }
}
