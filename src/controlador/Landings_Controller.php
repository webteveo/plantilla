<?php

require_once 'src/controlador/Local_Controller.php';

/**
 * Landings nacionales / principales de un solo segmento (/cerrajero-montevideo, /apertura-de-puertas, etc.).
 * Cada metodo publico se sirve como /{nombre-del-metodo-con-guiones} (ver App::CONTROLADORES_RAIZ).
 * Comparte la vista y los helpers de Local_Controller (renderLanding, schemas, links).
 */
class Landings_Controller extends Local_Controller
{
    /** Este controlador no genera landings por datos (evita duplicar /local/* en la raiz) */
    protected static function registro(): array { return []; }

    /** Zonas de cobertura que se muestran en las landings nacionales. COMPLETAR. */
    private const AREAS_UY = [
        'Montevideo y área metropolitana',
        'Canelones y Ciudad de la Costa',
        'Resto del país con coordinación previa',
    ];

    /**
     * Links relacionados entre landings nacionales. COMPLETAR.
     * ['href' => 'slug-nacional', 'label' => '...'] o ['slug' => 'servicio-ciudad', 'label' => '...'] para /local/.
     */
    private const LINKS = [];

    private function nacional(array $l): void
    {
        $l['base']       = '';
        $l['zona_label'] = $l['zona_label'] ?? 'Montevideo';
        $l['areas']      = $l['areas'] ?? self::AREAS_UY;
        if (!isset($l['links_block'])) {
            $links = array_values(array_filter(self::LINKS, fn($x) => ($x['href'] ?? '') !== $l['slug']));
            $l['links_block'] = $links ? ['title' => 'Más servicios de ' . EMPRESA_NOMBRE, 'links' => $links] : null;
        }
        $this->renderLanding($l);
    }

    // ── LANDINGS ─────────────────────────────────────────────────────────────
    // Ejemplo (descomentar y completar). URL resultante: /cerrajero-montevideo
    //
    // public function cerrajero_montevideo()
    // {
    //     $this->nacional([
    //         'slug'          => 'cerrajero-montevideo',
    //         'title'         => 'Cerrajería en Montevideo | ' . EMPRESA_NOMBRE,
    //         'description'   => 'Meta description de 140-155 caracteres.',
    //         'keywords'      => 'cerrajería montevideo, empresa de cerrajería montevideo',
    //         'eyebrow'       => 'Cerrajería en Montevideo',
    //         'hero_title'    => 'Cerrajería en Montevideo',
    //         'hero_subtitle' => 'Texto del hero.',
    //         'chips'         => ['Casas', 'Apartamentos', 'Oficinas'],
    //         'hero_img'      => ['file' => 'hero/landing/cerrajería.webp', 'alt' => '...'],   // relativo a public/images/
    //         'highlights'    => ['Punto 1', 'Punto 2', 'Punto 3'],
    //         'benefits'      => [['title' => '...', 'text' => '...'], ['title' => '...', 'text' => '...'], ['title' => '...', 'text' => '...']],
    //         'secciones'     => [['title' => 'H2', 'parrafos' => ['...'], 'lista' => ['...']]],
    //         'faq'           => [['q' => '¿...?', 'a' => '...']],
    //         'cta_label'     => CTA_WHATSAPP_LABEL,   // texto unico de los botones de WhatsApp
    //         'cta_message'   => 'Hola! Quiero cotizar un servicio de cerrajería en Montevideo.',
    //     ]);
    // }
}
