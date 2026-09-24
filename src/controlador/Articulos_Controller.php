<?php

use benjamin\plantillaweb\libs\App;
use benjamin\plantillaweb\libs\Controlador;

require_once 'src/modelo/Articulos.php';

/**
 * /articulos            -> listado (hub)
 * /articulos/{slug}     -> articulo (ruta dinamica declarada en App::RUTAS_DINAMICAS)
 * /articulos/feed       -> RSS 2.0
 */
class Articulos_Controller extends Controlador
{
    public function index()
    {
        $this->cargarVista('articulos/index', [
            'articulos'  => Articulos::todos(),
            'categorias' => Articulos::categorias(),
        ]);
    }

    public function ver(string $slug = '')
    {
        $a = $slug !== '' ? Articulos::porSlug($slug) : null;
        if (!$a) {
            App::error404();
        }

        $a['canonical']    = SEO_CANONICAL_URL . '/articulos/' . $a['slug'];
        $a['cta_message']  = $a['cta_message'] ?? ('Hola! Leí el artículo "' . $a['titulo'] . '" y quiero hacer una consulta.');
        $a['cta_href']     = wsp_href($a['cta_message']);
        $a['minutos']      = Articulos::minutosLectura($a);
        $a['relacionados'] = Articulos::relacionados($a);

        $this->cargarVista('articulos/articulo', ['a' => $a]);
    }

    public function feed()
    {
        header('Content-Type: application/rss+xml; charset=utf-8');
        date_default_timezone_set(ZONA_HORARIA);
        $items = array_slice(Articulos::todos(), 0, 30);
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"><channel>' . "\n";
        echo '<title>' . htmlspecialchars(EMPRESA_NOMBRE . ' | Artículos y guías') . '</title>' . "\n";
        echo '<link>' . SEO_CANONICAL_URL . '/articulos</link>' . "\n";
        echo '<description>' . htmlspecialchars('Guías y respuestas de ' . EMPRESA_NOMBRE . '. ' . EMPRESA_DESCRIPCION) . '</description>' . "\n";
        echo '<language>es-uy</language>' . "\n";
        echo '<atom:link href="' . SEO_CANONICAL_URL . '/articulos/feed" rel="self" type="application/rss+xml"/>' . "\n";
        foreach ($items as $a) {
            $link = SEO_CANONICAL_URL . '/articulos/' . $a['slug'];
            echo "<item>\n";
            echo '<title>' . htmlspecialchars($a['titulo']) . "</title>\n";
            echo '<link>' . $link . "</link>\n";
            echo '<guid isPermaLink="true">' . $link . "</guid>\n";
            echo '<pubDate>' . date(DATE_RSS, strtotime($a['fecha'] . ' 09:00:00')) . "</pubDate>\n";
            echo '<description>' . htmlspecialchars($a['description'] ?? $a['respuesta'] ?? '') . "</description>\n";
            echo "</item>\n";
        }
        echo "</channel></rss>\n";
    }
}
