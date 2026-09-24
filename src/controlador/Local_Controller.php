<?php

use benjamin\plantillaweb\libs\Controlador;

require_once 'src/controlador/Local_Generadas.php';
require_once 'src/controlador/Local_Barrios.php';

/**
 * Landings locales: /local/{servicio}-{ciudad}
 *
 * - CITY_SERVICES: servicios base (slug => nombre). Cada servicio x cada ciudad/zona genera una landing.
 * - CITY_DATA (+ Local_Datos::CITY_DATA_EXTRA y ZONAS_PADRE): ciudades y barrios con sus zonas cercanas.
 * - contenidoServicio(): textos propios de cada servicio (title, hero, beneficios, FAQ). Lo que no se
 *   defina ahi se completa con un texto generico a partir del nombre del servicio y la ciudad.
 * - Para una landing escrita a mano, declarar un metodo publico con el nombre del slug en snake_case
 *   (ej. public function cerrajero_pocitos()) y llamar a renderLanding([...]).
 */
class Local_Controller extends Controlador
{
    use Local_Generadas;

    /** Servicios base por ciudad: slug => nombre. Cada uno genera /local/{slug}-{ciudad} para todas las ciudades. */
    public const CITY_SERVICES = [
        'cerrajero' => 'Cerrajero',
    ];

    // ── HELPERS ───────────────────────────────────────────────────────────────

    protected function renderLanding(array $landing): void
    {
        $landing['base']          = $landing['base'] ?? 'local/';
        $landing['canonical']     = SEO_CANONICAL_URL . '/' . $landing['base'] . $landing['slug'];
        $landing['cta_label']     = $landing['cta_label'] ?? CTA_WHATSAPP_LABEL;
        $landing['cta_message']   = $landing['cta_message'] ?? CONTACTO_WHATSAPP_MENSAJE;
        $landing['cta_href']      = wsp_href($landing['cta_message']);
        $landing['schema_blocks'] = [
            $this->buildServiceSchema($landing),
            $this->buildFaqSchema($landing['faq'] ?? []),
            $this->buildBreadcrumbSchema($landing),
        ];
        $landing['links_block'] = $landing['links_block'] ?? $this->buildRelatedLinks($landing['slug']);

        // Presentacion del hero (misma en todas las landings salvo que la landing defina la suya)
        $landing['hero_img']  = $landing['hero_img']  ?? self::HERO_IMG;
        $landing['about_img'] = $landing['about_img'] ?? self::HERO_IMG;
        $landing['stats']     = $landing['stats']     ?? self::HERO_STATS;
        if (!array_key_exists('testimonio', $landing)) {
            $resenas = require 'src/vista/compact/testimonios-data.php';
            $landing['testimonio'] = $resenas ? $resenas[array_rand($resenas)] : null;
        }

        $this->cargarVista('local/landing', ['landing' => $landing]);
    }

    /** Imagen de fondo del hero de las landings (relativa a public/images/). Reemplazar por una horizontal cuando haya. */
    public const HERO_IMG = ['file' => 'hero/hero-mobile.webp', 'alt' => 'Imagen de portada'];

    /** Datos de la barra del hero. Sin cifras inventadas: completar con numeros reales cuando existan. */
    public const HERO_STATS = [
        ['n' => '0',   'sup' => '$', 'label' => 'Presupuesto y cotización'],
        ['n' => '100', 'sup' => '%', 'label' => 'Barrios de Montevideo'],
    ];

    /** Separa el slug de una landing en [servicio, zona] (coincidencia mas larga: 'cerrajero-a-domicilio' antes que 'cerrajero'). */
    protected static function servicioYZona(string $slug): array
    {
        $servicios = self::servicios();
        uksort($servicios, fn($a, $b) => strlen($b) <=> strlen($a));
        $srv = 'cerrajero';
        foreach (array_keys($servicios) as $k) { if (str_starts_with($slug, $k . '-')) { $srv = $k; break; } }
        return [$srv, substr($slug, strlen($srv) + 1)];
    }

    /** Lugar como entidad: nombre + sameAs (Wikipedia/Wikidata) cuando el barrio esta identificado, contenido en Montevideo > Uruguay. */
    protected static function placeEntity(string $zonaSlug, string $nombre): array
    {
        $place = ['@type' => $zonaSlug === 'montevideo' ? 'City' : 'Place', 'name' => $nombre];
        if ($wiki = Local_Barrios::WIKI[$zonaSlug] ?? null) {
            $place['sameAs'] = ['https://es.wikipedia.org/wiki/' . str_replace(' ', '_', $wiki[0]), 'https://www.wikidata.org/wiki/' . $wiki[1]];
        }
        if ($zonaSlug !== 'montevideo') {
            $place['containedInPlace'] = self::placeEntity('montevideo', 'Montevideo');
        } else {
            $place['containedInPlace'] = ['@type' => 'Country', 'name' => 'Uruguay', 'sameAs' => 'https://www.wikidata.org/wiki/Q77'];
        }
        return $place;
    }

    protected function buildServiceSchema(array $landing): array
    {
        [$srv, $zonaSlug] = self::servicioYZona($landing['slug']);
        $zona    = self::zonasTodas()[$zonaSlug] ?? null;
        $srvName = self::servicios()[$srv] ?? $landing['hero_title'];

        // Zona principal como entidad + sub-zonas declaradas en la landing
        $areas = [];
        if ($zona) $areas[] = self::placeEntity($zonaSlug, $zona['nombre']);
        foreach ((array)($landing['areas'] ?? []) as $a) {
            if (is_string($a) && (!$zona || $a !== $zona['nombre'])) $areas[] = ['@type' => 'Place', 'name' => $a];
        }

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            '@id'         => $landing['canonical'] . '#service',
            'name'        => $landing['hero_title'],
            'serviceType' => $srvName,
            'category'    => 'Cerrajería',
            'provider'    => [
                '@id'   => SEO_CANONICAL_URL . '/#localbusiness',
                '@type' => ['Locksmith', 'LocalBusiness'],
                'name'  => EMPRESA_NOMBRE,
                'url'   => SEO_CANONICAL_URL,
            ],
            'areaServed'  => $areas ?: ($landing['areas'] ?? []),
            'url'         => $landing['canonical'],
            'description' => $landing['description'],
            'inLanguage'  => 'es-UY',
            'availableChannel' => [
                '@type'       => 'ServiceChannel',
                'name'        => 'WhatsApp',
                'serviceUrl'  => $landing['cta_href'],
                'servicePhone' => ['@type' => 'ContactPoint', 'telephone' => '+' . CONTACTO_WHATSAPP, 'contactType' => 'customer service'],
            ],
        ];
        if ($srv === 'cerrajero-a-domicilio') {
            // El servicio se presta en el domicilio del cliente (no en un local)
            $schema['additionalType'] = 'https://es.wikipedia.org/wiki/Cerrajer%C3%ADa';
            $schema['providerMobility'] = 'dynamic';
            $schema['hoursAvailable'] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'], 'opens' => '00:00', 'closes' => '23:59'];
        }
        return $schema;
    }

    protected function buildBreadcrumbSchema(array $landing): array
    {
        $servicios = self::servicios();
        [$srv, $zona] = self::servicioYZona($landing['slug']);
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => SEO_CANONICAL_URL . '/']];
        if ($zona !== 'montevideo') {
            $items[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $servicios[$srv], 'item' => SEO_CANONICAL_URL . '/local/' . $srv . '-montevideo'];
        }
        $items[] = ['@type' => 'ListItem', 'position' => count($items) + 1, 'name' => $landing['hero_title'], 'item' => $landing['canonical']];
        return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }

    protected function buildFaqSchema(array $faq): array
    {
        $items = [];
        foreach ($faq as $row) {
            $items[] = [
                '@type'          => 'Question',
                'name'           => $row['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $row['a']],
            ];
        }
        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $items,
        ];
    }

    // ── LANDING GENERADA: SERVICIO x CIUDAD ───────────────────────────────────

    /**
     * Contenido especifico por servicio. Devuelve claves de landing que pisan el generico:
     * title, description, keywords, hero_title, hero_subtitle, highlights[], benefits[{title,text}],
     * faq[{q,a}], cta_label, cta_message, chips[], hero_img{file,alt}, secciones[], tabla.
     *
     * $z: datos de la zona ('slug', 'nombre', 'tipo', 'depto', 'cerca', 'areas').
     * Ejemplo:
     *   case 'cerrajero':
     *       return [
     *           'hero_subtitle' => "Cerrajería en {$C} y zonas cercanas ({$cerca}) ...",
     *           'benefits'      => [['title' => '...', 'text' => '...'], ...],
     *           'faq'           => [['q' => "¿Hacen cerrajería en {$C}?", 'a' => '...'], ...],
     *       ];
     */
    protected function contenidoServicio(string $service, array $z): array
    {
        $C = $z['nombre']; $D = $z['depto']; $cerca = $z['cerca'];
        $cLow = mb_strtolower($C);
        $esMvd = ($D === 'Montevideo');
        $balneario = (($z['tipo'] ?? '') === 'balneario');

        switch ($service) {
            case 'cerrajero':
                $cobertura = $esMvd
                    ? "Cerrajería a domicilio en {$C}. Consultá por apertura de puertas, cambios y reparaciones de cerraduras."
                    : "Cerrajería a domicilio en {$C}, Montevideo. Consultá por apertura de puertas, cambios y reparaciones de cerraduras.";
                return [
                    'title' => "Cerrajero en {$C} | Apertura de puertas y cerraduras | " . EMPRESA_NOMBRE,
                    'description' => "Cerrajero en {$C}, {$D}. Consultas por apertura de puertas, cambio de cerraduras y cilindros, reparación e instalación. Confirmá disponibilidad y presupuesto.",
                    'keywords' => "cerrajero {$cLow}, cerrajería {$cLow}, apertura de puertas {$cLow}, cambio de cerraduras {$cLow}",
                    'hero_title' => "Cerrajero en {$C}",
                    'hero_subtitle' => $cobertura,
                    'chips' => ['Apertura de puertas', 'Cambio de cerraduras', 'Reparaciones'],
                    'highlights' => ['Servicios para casas y apartamentos', 'Consulta por cerraduras y cilindros', 'Presupuesto sin cargo'],
                    'benefits' => [
                        ['title' => 'Presupuesto claro', 'text' => 'Te explicamos el trabajo y los repuestos necesarios antes de intervenir.'],
                        ['title' => "Coordinación en {$C}", 'text' => "Indicá tu ubicación en {$C} o cerca de {$cerca} para confirmar cobertura y disponibilidad."],
                        ['title' => 'Revisión del mecanismo', 'text' => 'Evaluamos el estado de la cerradura y de la puerta para definir la solución adecuada.'],
                    ],
                    'secciones' => [
                        [
                            'title' => "Servicios de cerrajería en {$C}",
                            'parrafos' => [
                                'Consultá por puertas cerradas, llaves que no giran o cerraduras que necesitan un cambio. Identificamos el mecanismo y evaluamos si conviene reparar o reemplazar.',
                                'También podés solicitar la instalación de una cerradura de seguridad o el cambio de cilindro o combinación, según la compatibilidad del modelo.',
                            ],
                            'lista' => ['Apertura de puertas', 'Cambio de cerraduras', 'Cambio de cilindros y combinaciones', 'Reparación de mecanismos', 'Instalación de cerraduras de seguridad'],
                        ],
                        [
                            'title' => "Cuánto cuesta un cerrajero en {$C}",
                            'parrafos' => [
                                'El precio depende del tipo de puerta, el estado de la cerradura, los repuestos y el horario solicitado. Confirmamos el alcance antes de comenzar.',
                                "Contanos qué sucede y tu ubicación en {$C}. Si podés, enviá una foto de la cerradura para orientar el presupuesto y coordinar la visita.",
                            ],
                        ],
                    ],
                    'faq' => [
                        ['q' => "¿Cómo solicito un cerrajero en {$C}?", 'a' => "Indicá tu ubicación en {$C} y el problema con tu puerta. Te confirmamos disponibilidad, cobertura y presupuesto antes de coordinar."],
                        ['q' => "¿Cuánto cuesta una apertura de puerta en {$C}?", 'a' => 'Depende de la cerradura, de si está cerrada con llave o de golpe y del estado del mecanismo. Consultá con una descripción del problema para cotizar.'],
                        ['q' => '¿Es necesario cambiar toda la cerradura?', 'a' => 'No siempre. En algunos modelos alcanza con reparar el mecanismo o cambiar el cilindro. Se confirma después de revisar su estado y compatibilidad.'],
                        ['q' => '¿Puedo consultar por una urgencia?', 'a' => 'Sí. Indicá si te quedaste afuera o no podés cerrar. Te confirmamos la disponibilidad y el horario estimado según la zona.'],
                    ],
                    'cta_message' => "Hola! Necesito consultar por un cerrajero en {$C}.",
                ];

            case 'cerrajero-a-domicilio':
                // Entidad central: el servicio "a domicilio" (el cerrajero va hasta la persona). Variantes semanticas
                // repartidas entre H1, H2, texto y FAQ: cerrajeria a domicilio, cerrajero que va a tu casa, visita,
                // servicio en tu domicilio, cerrajero cerca. Entidades relacionadas: barrio, Montevideo, WhatsApp,
                // tipos de puerta y cerradura, apertura no destructiva, urgencia 24 horas.
                $perfil  = Local_Barrios::PERFIL[$z['slug'] ?? ''] ?? null;
                $llegada = $perfil['llegada'] ?? '20 a 40 minutos';
                $refs    = $perfil['refs'] ?? 'las avenidas principales';
                $enMvd   = $esMvd && $C !== 'Montevideo' ? "{$C}, Montevideo" : $C;
                return [
                    'title'         => "Cerrajero a domicilio en {$C} | 24 hs, precio por WhatsApp",
                    'description'   => "Cerrajero a domicilio en {$enMvd}, las 24 hs. Vamos a tu casa: aperturas, cerraduras y llaves en el lugar. Llegamos en {$llegada}. Precio por WhatsApp antes de salir.",
                    'keywords'      => "cerrajero a domicilio {$cLow}, cerrajería a domicilio {$cLow}, cerrajero que va a domicilio {$cLow}, cerrajero cerca {$cLow}, cerrajero urgente a domicilio {$cLow}, servicio de cerrajería a domicilio {$cLow}",
                    'hero_eyebrow'  => "Cerrajería a domicilio en {$enMvd}",
                    'hero_title'    => "Cerrajero a domicilio en {$C}",
                    'hero_subtitle' => "No tenés que ir a ningún lado: el cerrajero va hasta tu puerta en {$C}. Aperturas, cambios de cerradura y copias de llave en el lugar, con precio cerrado antes de salir.",
                    'cta_message'   => "Hola! Necesito un cerrajero a domicilio en {$C}.",
                    'chips'         => ['Vamos a tu casa', 'Precio antes de salir', "Llegamos en {$llegada}"],
                    'secciones' => [
                        [
                            'title'    => "Qué hace un cerrajero a domicilio en {$C}",
                            'parrafos' => [
                                "El servicio a domicilio significa que el cerrajero va con la herramienta y los repuestos hasta donde estás: tu casa, tu apartamento, el comercio o el auto estacionado en {$C}. No tenés que desmontar la cerradura ni llevar la llave a un local.",
                                "En el mismo lugar abrimos la puerta, cambiamos o reparamos la cerradura, hacemos copias de llave y, si hace falta, instalamos una cerradura de seguridad o digital. Casi todo se resuelve en una sola visita.",
                            ],
                            'lista' => [
                                'Apertura de puertas cerradas, con llave adentro o de golpe',
                                'Cambio de cerraduras y cilindros con la cerradura nueva incluida',
                                'Reparación de cerraduras trabadas, forzadas o con llave rota',
                                'Copias de llaves comunes, de seguridad y de auto con chip',
                                'Instalación de cerraduras multipunto, cerrojos y cerraduras digitales',
                                'Apertura de autos, camionetas y motos sin dañar la puerta',
                            ],
                        ],
                        [
                            'title'    => "Cómo funciona la visita a domicilio en {$C}",
                            'parrafos' => [
                                "Escribinos por WhatsApp con tu dirección (una referencia como {$refs} ayuda) y una foto de la cerradura si podés. Con eso te pasamos el precio cerrado y el tiempo estimado de llegada a {$C}, que suele ser de {$llegada}.",
                                'Al llegar verificamos que la vivienda o el vehículo sean tuyos, hacemos el trabajo y probamos que todo abra y cierre bien antes de irnos. El precio es el que te confirmamos por mensaje, sin recargos por la visita.',
                            ],
                        ],
                        [
                            'title'    => $esMvd && $C !== 'Montevideo' ? "Cerrajero cerca de {$C}: zonas que cubrimos" : 'Barrios de Montevideo donde vamos a domicilio',
                            'parrafos' => [
                                $esMvd && $C !== 'Montevideo'
                                    ? "Además de {$C} atendemos a domicilio en {$cerca} y en el resto de Montevideo. Si estás en el límite entre barrios, escribinos igual: cubrimos toda la ciudad, todos los días y a cualquier hora."
                                    : "Atendemos a domicilio en todos los barrios de Montevideo, del Centro y la costa hasta el Cerro, Colón y Punta de Rieles, todos los días y a cualquier hora.",
                            ],
                        ],
                    ],
                    'faq' => [
                        ['q' => "¿Cuánto tarda en llegar un cerrajero a domicilio a {$C}?",   'a' => "Normalmente entre {$llegada}, según la hora y el tránsito. Cuando escribís por WhatsApp con tu dirección en {$C} te confirmamos el tiempo real de llegada antes de salir."],
                        ['q' => '¿Cobran la visita a domicilio aparte?',                     'a' => 'No. Te pasamos un precio cerrado por WhatsApp que ya incluye el traslado y el trabajo. Si al llegar hace falta un repuesto, te lo decimos antes de tocar nada.'],
                        ['q' => "¿Van a domicilio de noche o los domingos en {$C}?",          'a' => "Sí. El servicio a domicilio en {$C} es las 24 horas, todos los días del año, incluidos feriados. Escribinos y te respondemos al momento."],
                        ['q' => '¿Qué trabajos hacen en el domicilio?',                      'a' => 'Aperturas de puertas y autos, cambio y reparación de cerraduras, copias de llaves, extracción de llaves rotas e instalación de cerraduras de seguridad y digitales. Vamos con herramientas y repuestos para resolverlo en la misma visita.'],
                        ['q' => '¿Tengo que llevar la cerradura o la llave a algún lado?',   'a' => 'No. Justamente ese es el servicio a domicilio: el cerrajero va hasta tu casa, apartamento, comercio o hasta donde esté el auto, y hace el trabajo ahí.'],
                        ['q' => "¿Atienden apartamentos y edificios en {$C}?",               'a' => "Sí. Apartamentos, casas, comercios, portones y puertas de edificio en {$C} y zonas cercanas como {$cerca}."],
                        ['q' => '¿Cómo sé que es un cerrajero de confianza?',                'a' => 'Antes de abrir cualquier puerta o vehículo pedimos una identificación o un comprobante de que el lugar es tuyo. Trabajamos con precio confirmado por escrito y probamos todo antes de irnos.'],
                    ],
                ];

            case 'apertura-de-puertas':
                return [
                    'title'         => "Apertura de puertas en {$C} 24 hs | " . EMPRESA_NOMBRE,
                    'description'   => "¿Te quedaste afuera en {$C}? Abrimos tu puerta sin dañar la cerradura, las 24 horas. Cerrajero a domicilio, precio por WhatsApp antes de salir.",
                    'keywords'      => "apertura de puertas {$cLow}, abrir puerta {$cLow}, me quedé afuera {$cLow}, cerrajero urgente {$cLow}, abrir puerta sin llave {$cLow}, cerrajero 24 horas {$cLow}",
                    'hero_eyebrow'  => "Cerrajero de urgencia en {$C}",
                    'hero_title'    => "Apertura de puertas en {$C}",
                    'hero_subtitle' => "¿Te quedaste afuera en {$C}? Llegamos en minutos y abrimos sin romper la cerradura. Precio cerrado antes de salir.",
                    'cta_message'   => "Hola! Me quedé afuera en {$C} y necesito abrir la puerta ahora.",
                    'faq' => [
                        ['q' => "¿Cuánto tardan en llegar a {$C}?",                       'a' => "Atendemos {$C} y zonas cercanas como {$cerca} las 24 horas. Cuando escribís te confirmamos el tiempo estimado de llegada según dónde estés."],
                        ['q' => '¿Pueden abrir la puerta sin romper la cerradura?',        'a' => 'Sí, en la gran mayoría de los casos. Usamos técnicas de apertura no destructiva según el tipo de cerradura. Si hiciera falta cambiar alguna pieza, te lo decimos antes de tocar nada.'],
                        ['q' => "¿Cuánto cuesta abrir una puerta en {$C}?",               'a' => 'Depende del tipo de cerradura y de si está cerrada con llave o de golpe. Te pasamos el precio por WhatsApp antes de salir, sin sorpresas ni cargos ocultos.'],
                        ['q' => 'Se me cerró la puerta de golpe, ¿pueden abrirla?',         'a' => 'Sí. Es la apertura más rápida y habitual. Escribinos con tu dirección y salimos para allá.'],
                        ['q' => 'Dejé las llaves adentro o se me rompió la llave, ¿qué hago?', 'a' => 'No fuerces la cerradura. Escribinos, abrimos la puerta y, si la llave quedó partida adentro, la extraemos sin dañar el cilindro.'],
                        ['q' => '¿Abren puertas blindadas o de seguridad?',                 'a' => 'Sí. Abrimos puertas blindadas, de seguridad y con cerraduras multipunto. Contanos la marca o mandanos una foto para ir preparados.'],
                        ['q' => '¿Trabajan de noche, domingos y feriados?',                 'a' => 'Sí, las 24 horas todos los días del año. El precio se confirma antes de salir en cualquier horario.'],
                        ['q' => '¿Piden alguna identificación para abrir?',                 'a' => 'Sí. Antes de abrir verificamos que la vivienda sea tuya o que tengas autorización, por tu seguridad y la de tus vecinos.'],
                    ],
                ];
            case 'cerrajero-automotriz':
                return [
                    'title'         => "Cerrajero automotriz en {$C} 24 hs | Abrir auto sin llave",
                    'description'   => "¿Te quedaste afuera del auto en {$C}? Abrimos autos, camionetas y motos sin dañar la puerta, las 24 horas. Vamos a donde estés. Precio por WhatsApp.",
                    'keywords'      => "cerrajero automotriz {$cLow}, abrir auto sin llave {$cLow}, me quedé afuera del auto {$cLow}, cerrajero de autos {$cLow}, llaves adentro del auto {$cLow}, cerrajero auto 24 horas {$cLow}",
                    'hero_eyebrow'  => "Cerrajero de autos en {$C}",
                    'hero_title'    => "Cerrajero automotriz en {$C}",
                    'cta_message'   => "Hola! Me quedé afuera del auto en {$C} y necesito abrirlo ahora.",
                    'hero_subtitle' => "¿Te quedaste afuera del auto en {$C}? Vamos hasta donde estés y lo abrimos sin dañar la puerta ni la cerradura. Precio cerrado antes de salir.",
                    'faq' => [
                        ['q' => "¿Cuánto tardan en llegar a {$C}?",                          'a' => "Atendemos {$C} y zonas cercanas como {$cerca} las 24 horas. Escribinos con tu ubicación exacta y te confirmamos el tiempo de llegada al momento."],
                        ['q' => '¿Pueden abrir el auto sin dañarlo?',                        'a' => 'Sí. Usamos herramientas específicas para cada marca y modelo. No rompemos el vidrio ni marcamos la puerta.'],
                        ['q' => "¿Cuánto cuesta abrir un auto en {$C}?",                     'a' => 'Depende del modelo y del tipo de cierre. Te pasamos el precio por WhatsApp antes de salir, sin sorpresas.'],
                        ['q' => 'Dejé las llaves adentro del auto, ¿pueden abrirlo?',         'a' => 'Sí. Es el caso más común. Contanos marca, modelo y dónde estás y salimos para allá.'],
                        ['q' => '¿Abren camionetas, motos y utilitarios?',                   'a' => 'Sí. Abrimos autos, camionetas, utilitarios y motos de todas las marcas.'],
                        ['q' => 'Se me rompió la llave en la puerta o en el arranque, ¿qué hago?', 'a' => 'No la fuerces. Extraemos la llave partida sin dañar el cilindro y te asesoramos si conviene hacer una copia.'],
                        ['q' => '¿Trabajan de noche, domingos y feriados?',                    'a' => 'Sí, las 24 horas todos los días. Mismo precio confirmado antes de salir en cualquier horario.'],
                        ['q' => '¿Piden documentación del auto?',                              'a' => 'Sí. Antes de abrir verificamos que el vehículo sea tuyo, por tu seguridad.'],
                    ],
                ];
            case 'cambio-de-cerraduras':
                return [
                    'title'         => "Cambio de cerraduras en {$C} | Cerrajero a domicilio",
                    'description'   => "Cambio de cerraduras y cilindros en {$C}, en el día. ¿Perdiste las llaves o te forzaron la puerta? Vamos a tu casa con la cerradura nueva. Precio por WhatsApp.",
                    'keywords'      => "cambio de cerraduras {$cLow}, cambiar cerradura {$cLow}, cambio de cilindro {$cLow}, perdí las llaves {$cLow}, cerradura forzada {$cLow}, cerrajero {$cLow}",
                    'hero_eyebrow'  => "Cambio de cerraduras en el día en {$C}",
                    'hero_title'    => "Cambio de cerraduras en {$C}",
                    'hero_subtitle' => "¿Perdiste las llaves o te forzaron la puerta? Cambiamos la cerradura o el cilindro en {$C} en el día, con precio cerrado antes de ir.",
                    'cta_message'   => "Hola! Necesito cambiar una cerradura en {$C}.",
                    'faq' => [
                        ['q' => "¿Cuánto cuesta cambiar una cerradura en {$C}?",       'a' => 'Depende del tipo de cerradura y de si se cambia todo el mecanismo o solo el cilindro. Te pasamos el precio con la cerradura incluida por WhatsApp antes de salir.'],
                        ['q' => 'Perdí las llaves, ¿tengo que cambiar toda la cerradura?', 'a' => 'No siempre. En muchas cerraduras alcanza con cambiar el cilindro o la combinación, y las llaves viejas dejan de funcionar. Lo revisamos en el momento y te decimos qué conviene.'],
                        ['q' => 'Me forzaron la cerradura, ¿pueden venir hoy?',            'a' => "Sí. Atendemos {$C} las 24 horas. Aseguramos la puerta y cambiamos la cerradura en la misma visita."],
                        ['q' => '¿Llevan la cerradura nueva o la tengo que comprar?',      'a' => 'La llevamos nosotros. Trabajamos con marcas conocidas y te ofrecemos opciones según tu puerta y presupuesto. Si ya tenés una, también la instalamos.'],
                        ['q' => '¿Cuánto tarda el cambio?',                                'a' => 'Un cambio de cerradura o cilindro estándar lleva entre 30 y 60 minutos. Antes de irnos probamos que abra y cierre bien con todas las llaves nuevas.'],
                        ['q' => '¿Cambian cerraduras de apartamento y de puerta de calle?', 'a' => "Sí. Casas, apartamentos, puertas de calle, portones y rejas en {$C} y zonas cercanas como {$cerca}."],
                        ['q' => '¿Me quedo con varias copias de llave?',                   'a' => 'Sí. Las cerraduras nuevas vienen con 3 a 5 llaves según el modelo, y podemos hacer copias adicionales.'],
                    ],
                ];

            case 'cerrajero-24-horas':
                return [
                    'title'         => "Cerrajero 24 horas en {$C} | Urgencias todos los días",
                    'description'   => "Cerrajero de urgencia en {$C}, las 24 horas, domingos y feriados. Aperturas, cerraduras y llaves. Llegamos rápido y te decimos el precio antes de salir.",
                    'keywords'      => "cerrajero 24 horas {$cLow}, cerrajero urgente {$cLow}, cerrajero de urgencia {$cLow}, cerrajero nocturno {$cLow}, cerrajero domingo {$cLow}, cerrajero ahora {$cLow}",
                    'hero_eyebrow'  => "Urgencias en {$C}, todos los días",
                    'hero_title'    => "Cerrajero 24 horas en {$C}",
                    'hero_subtitle' => "Sea la hora que sea, salimos para {$C}. Aperturas, cerraduras forzadas y llaves rotas, con precio cerrado antes de ir.",
                    'cta_message'   => "Hola! Necesito un cerrajero urgente en {$C}.",
                    'faq' => [
                        ['q' => '¿Atienden de madrugada?',                                 'a' => 'Sí, las 24 horas. Escribinos por WhatsApp y te respondemos al momento a cualquier hora.'],
                        ['q' => "¿Cuánto tardan en llegar a {$C}?",                        'a' => "Atendemos {$C} y zonas cercanas como {$cerca}. Te confirmamos el tiempo estimado apenas nos escribís con tu ubicación."],
                        ['q' => '¿Cobran más de noche, domingos o feriados?',              'a' => 'Te pasamos el precio por WhatsApp antes de salir, en cualquier horario. Sin sorpresas al llegar.'],
                        ['q' => '¿Qué urgencias resuelven?',                               'a' => 'Puertas cerradas, llaves perdidas o rotas, cerraduras forzadas o trabadas, autos cerrados con las llaves adentro y cambio de cerradura después de un robo.'],
                        ['q' => '¿Piden identificación?',                                  'a' => 'Sí. Antes de abrir verificamos que la vivienda o el vehículo sean tuyos, por tu seguridad.'],
                        ['q' => '¿Abren sin romper la cerradura?',                         'a' => 'En la gran mayoría de los casos sí. Si hiciera falta cambiar alguna pieza te lo decimos antes de tocar nada.'],
                    ],
                ];

            case 'reparacion-de-cerraduras':
                return [
                    'title'         => "Reparación de cerraduras en {$C} | Cerrajero a domicilio",
                    'description'   => "¿La llave no gira o la puerta no cierra? Reparamos cerraduras trabadas, forzadas y con llave rota en {$C}, a domicilio y en el día. Precio por WhatsApp.",
                    'keywords'      => "reparación de cerraduras {$cLow}, arreglar cerradura {$cLow}, cerradura trabada {$cLow}, la llave no gira {$cLow}, puerta no cierra {$cLow}, llave rota en la cerradura {$cLow}",
                    'hero_eyebrow'  => "Arreglo de cerraduras en {$C}",
                    'hero_title'    => "Reparación de cerraduras en {$C}",
                    'hero_subtitle' => "Llave que no gira, cerradura trabada, puerta que no cierra o llave partida adentro. Vamos a {$C} y lo resolvemos en el día.",
                    'cta_message'   => "Hola! Necesito reparar una cerradura en {$C}.",
                    'faq' => [
                        ['q' => 'La llave entra pero no gira, ¿qué puede ser?',            'a' => 'Suele ser desgaste del cilindro, suciedad o una pieza interna vencida. Lo revisamos en el lugar y te decimos si conviene reparar o cambiar el cilindro.'],
                        ['q' => 'La puerta no cierra bien, ¿es la cerradura?',              'a' => 'Puede ser la cerradura, el pestillo o que la puerta se haya descuadrado. Ajustamos lo que haga falta para que cierre y trabe correctamente.'],
                        ['q' => 'Se me rompió la llave adentro de la cerradura',            'a' => 'No la fuerces. Extraemos el trozo sin dañar el cilindro y probamos la cerradura. Si quedó dañada, te ofrecemos el cambio en la misma visita.'],
                        ['q' => "¿Cuánto cuesta reparar una cerradura en {$C}?",            'a' => 'Depende de la falla y de si hay que cambiar piezas. Te pasamos el precio por WhatsApp antes de salir. Si al revisar conviene cambiarla, te lo decimos antes.'],
                        ['q' => '¿Reparan cerraduras forzadas?',                            'a' => 'Sí. Revisamos el daño, aseguramos la puerta y reparamos o reemplazamos la cerradura según el estado.'],
                        ['q' => "¿Van a domicilio en {$C}?",                                'a' => "Sí, a casas, apartamentos y comercios en {$C} y zonas cercanas como {$cerca}, las 24 horas."],
                    ],
                ];

            case 'cerraduras-de-seguridad':
                return [
                    'title'         => "Cerraduras de seguridad en {$C} | Instalación a domicilio",
                    'description'   => "Instalación de cerraduras de seguridad, multipunto y blindadas en {$C}. Te asesoramos según tu puerta y las instalamos en el día. Precio por WhatsApp.",
                    'keywords'      => "cerraduras de seguridad {$cLow}, cerradura multipunto {$cLow}, cerradura antirrobo {$cLow}, instalar cerradura de seguridad {$cLow}, cerradura blindada {$cLow}, cerrojo de seguridad {$cLow}",
                    'hero_eyebrow'  => "Seguridad para tu puerta en {$C}",
                    'hero_title'    => "Cerraduras de seguridad en {$C}",
                    'hero_subtitle' => "Cerraduras multipunto, cerrojos y cilindros antibumping para tu casa en {$C}. Te asesoramos y las instalamos en el día.",
                    'cta_message'   => "Hola! Quiero instalar una cerradura de seguridad en {$C}.",
                    'faq' => [
                        ['q' => '¿Qué cerradura de seguridad me conviene?',                'a' => 'Depende del tipo de puerta y del nivel de seguridad que buscás. Miramos tu puerta y te recomendamos entre cilindro de alta seguridad, cerrojo adicional o cerradura multipunto.'],
                        ['q' => '¿Qué es una cerradura multipunto?',                       'a' => 'Es una cerradura que traba la puerta en varios puntos a la vez, arriba, abajo y al medio. Es la opción más segura para puertas de calle y blindadas.'],
                        ['q' => '¿Se puede poner una cerradura de seguridad en una puerta común?', 'a' => 'Sí. En la mayoría de las puertas de madera o metal se puede instalar un cerrojo o un cilindro de alta seguridad sin cambiar la puerta.'],
                        ['q' => "¿Cuánto cuesta instalar una cerradura de seguridad en {$C}?", 'a' => 'Depende del modelo. Te pasamos opciones con precio por WhatsApp, cerradura e instalación incluidas, antes de ir.'],
                        ['q' => '¿Qué es una cerradura antibumping?',                      'a' => 'Es un cilindro que resiste la técnica de bumping y el taladro, las formas más comunes de forzar una cerradura. Lo instalamos en cerraduras existentes.'],
                        ['q' => "¿Van a domicilio en {$C}?",                               'a' => "Sí. Instalamos en casas, apartamentos y portones en {$C} y zonas cercanas como {$cerca}."],
                    ],
                ];

            case 'copia-de-llaves':
                return [
                    'title'         => "Copia de llaves en {$C} a domicilio | Cerrajero",
                    'description'   => "Copia de llaves a domicilio en {$C}: llaves comunes, de seguridad y de auto con chip. Vamos hasta tu casa y las hacemos en el momento. Precio por WhatsApp.",
                    'keywords'      => "copia de llaves {$cLow}, duplicado de llaves {$cLow}, hacer llaves {$cLow}, copia de llave de auto {$cLow}, llave con chip {$cLow}, cerrajería {$cLow}",
                    'hero_eyebrow'  => "Llaves a domicilio en {$C}",
                    'hero_title'    => "Copia de llaves en {$C}",
                    'hero_subtitle' => "Duplicados de llaves comunes, de seguridad y de auto con chip. Vamos a tu casa en {$C} y las hacemos en el momento.",
                    'cta_message'   => "Hola! Necesito copias de llaves en {$C}.",
                    'faq' => [
                        ['q' => '¿Hacen copias de llaves a domicilio?',                    'a' => "Sí. Vamos a tu casa o trabajo en {$C} con el equipo y hacemos las copias en el momento."],
                        ['q' => '¿Qué tipos de llaves copian?',                             'a' => 'Llaves comunes, de doble paleta, de seguridad con tarjeta de propiedad, de candado y de auto, incluidas las que tienen chip.'],
                        ['q' => '¿Hacen copias de llaves de auto con chip?',               'a' => 'Sí. Duplicamos y programamos llaves con transponder para la mayoría de las marcas. Contanos marca y modelo para confirmarte.'],
                        ['q' => '¿Pueden hacer una llave sin tener el original?',          'a' => 'En muchos casos sí, a partir de la cerradura o del cilindro. Lo revisamos y te confirmamos antes de ir.'],
                        ['q' => "¿Cuánto cuesta una copia de llave en {$C}?",               'a' => 'Depende del tipo de llave. Te pasamos el precio por WhatsApp antes de salir.'],
                        ['q' => '¿Copian llaves de seguridad?',                            'a' => 'Sí, presentando la tarjeta de propiedad de la cerradura, como exigen los fabricantes.'],
                    ],
                ];

            case 'cerraduras-digitales':
                return [
                    'title'         => "Cerraduras digitales en {$C} | Instalación a domicilio",
                    'description'   => "Instalación de cerraduras digitales y electrónicas en {$C}: con código, huella, tarjeta o celular. Te asesoramos y las instalamos en el día. Precio por WhatsApp.",
                    'keywords'      => "cerradura digital {$cLow}, cerradura electrónica {$cLow}, cerradura inteligente {$cLow}, cerradura con huella {$cLow}, cerradura con código {$cLow}, instalar cerradura digital {$cLow}",
                    'hero_eyebrow'  => "Cerraduras inteligentes en {$C}",
                    'hero_title'    => "Cerraduras digitales en {$C}",
                    'hero_subtitle' => "Abrí con código, huella, tarjeta o desde el celular. Te asesoramos e instalamos cerraduras digitales en {$C} en el día.",
                    'cta_message'   => "Hola! Quiero instalar una cerradura digital en {$C}.",
                    'faq' => [
                        ['q' => '¿Qué es una cerradura digital?',                          'a' => 'Es una cerradura que se abre sin llave: con código, huella, tarjeta o desde el celular. Ideal para casas, apartamentos y alquileres temporarios.'],
                        ['q' => '¿Se puede instalar en mi puerta?',                        'a' => 'En la mayoría de las puertas de madera, metal o blindadas sí. Miramos tu puerta y te recomendamos el modelo compatible.'],
                        ['q' => '¿Qué pasa si se queda sin batería?',                      'a' => 'Avisan con anticipación cuando la batería está baja y casi todas tienen apertura de emergencia con llave o con batería externa.'],
                        ['q' => "¿Cuánto cuesta una cerradura digital instalada en {$C}?", 'a' => 'Depende del modelo y sus funciones. Te pasamos opciones con precio por WhatsApp, cerradura e instalación incluidas.'],
                        ['q' => '¿Sirven para alquileres temporarios?',                    'a' => 'Sí. Podés crear códigos temporales para cada huésped y borrarlos cuando se van, sin cambiar llaves.'],
                        ['q' => "¿Van a domicilio en {$C}?",                               'a' => "Sí. Instalamos en {$C} y zonas cercanas como {$cerca}. Dejamos la cerradura configurada y te explicamos cómo usarla."],
                    ],
                ];
            default:
                return [];
        }
    }

    protected function cityLanding(string $service, string $cityKey): void
    {
        $z     = self::zonasTodas()[$cityKey];
        $z['slug'] = $cityKey;
        $C     = $z['nombre'];
        $D     = $z['depto'] ?? '';
        $cerca = $z['cerca'];
        $S     = self::servicios()[$service] ?? ucfirst(str_replace('-', ' ', $service));
        $sLow  = mb_strtolower($S);
        $cLow  = mb_strtolower($C);
        $dLow  = mb_strtolower($D);

        $generico = [
            'title'         => "{$S} en {$C} | " . EMPRESA_NOMBRE,
            'description'   => "{$S} en {$C}, {$D}. " . EMPRESA_DESCRIPCION,
            'keywords'      => "{$sLow} {$cLow}, {$sLow} {$dLow}, empresa de {$sLow} {$cLow}",
            'hero_title'    => "{$S} en {$C}",
            'hero_subtitle' => "Servicio de {$sLow} en {$C} y zonas cercanas ({$cerca}). Presupuesto sin cargo y atención directa por correo.",
            'highlights'    => ["{$S} en {$C}", 'Presupuesto sin cargo', 'Atención directa por correo'],
            'benefits'      => [
                ['title' => 'Presupuesto claro',   'text' => 'Te pasamos una propuesta por escrito con alcance y precio antes de empezar.'],
                ['title' => "Conocemos {$C}",      'text' => "Trabajamos habitualmente en {$C} y en {$cerca}."],
                ['title' => 'Atención directa',    'text' => 'Coordinás todo por correo con la misma persona, sin intermediarios.'],
            ],
            'faq' => [
                ['q' => "¿Hacen {$sLow} en {$C}?", 'a' => "Sí. Atendemos {$C} y zonas cercanas como {$cerca}. Escribinos por correo y te pasamos presupuesto sin cargo."],
                ['q' => '¿El presupuesto tiene costo?', 'a' => 'No. El presupuesto es sin cargo y sin compromiso.'],
            ],
            'cta_label'   => CTA_WHATSAPP_LABEL,
            'cta_message' => "Hola! Quiero cotizar {$sLow} en {$C}.",
        ];

        $landing = array_replace($generico, $this->contenidoServicio($service, $z));

        $landing['slug']       = $service . '-' . $cityKey;
        $landing['eyebrow']    = $landing['eyebrow'] ?? ($S . " en {$C}" . ($D ? " — {$D}" : ''));
        $landing['areas']      = $landing['areas'] ?? $z['areas'];
        $landing['zona_label'] = $C;

        $this->renderLanding($landing);
    }

    /** Links relacionados: otros servicios en la misma zona, misma zona padre y landing nacional */
    protected function buildRelatedLinks(string $slug): ?array
    {
        $servicios = self::servicios();
        $zonas     = self::zonasTodas();
        foreach ($servicios as $s => $sname) {
            if (!str_starts_with($slug, $s . '-')) continue;
            $zk = substr($slug, strlen($s) + 1);
            if (!isset($zonas[$zk])) continue;
            $z = $zonas[$zk]; $Z = $z['nombre'];
            $nacional = Local_Datos::NACIONAL_DE[$s] ?? null;

            // Zona padre: el mismo servicio en cada ciudad del departamento + otros servicios en la zona
            if (isset(Local_Datos::ZONAS_PADRE[$zk])) {
                $links = [];
                foreach (self::ciudades() as $ck => $c) {
                    if ($c['depto'] === $z['depto']) $links[] = ['slug' => $s . '-' . $ck, 'label' => $c['nombre']];
                }
                foreach ($servicios as $ok => $on) {
                    if ($ok !== $s) $links[] = ['slug' => $ok . '-' . $zk, 'label' => $on . ' en ' . $Z];
                }
                if ($nacional) $links[] = ['href' => $nacional, 'label' => $sname . ' en Uruguay'];
                return ['title' => $sname . ' por zona en ' . $Z, 'links' => $links];
            }

            // Ciudad: los otros servicios en esa ciudad + el mismo servicio en la zona padre + nacional
            $links = [];
            foreach ($servicios as $ok => $on) {
                if ($ok !== $s) $links[] = ['slug' => $ok . '-' . $zk, 'label' => $on . ' en ' . $Z];
            }
            $padre = self::padreDe($z);
            if ($padre) $links[] = ['slug' => $s . '-' . $padre, 'label' => $sname . ' en ' . Local_Datos::ZONAS_PADRE[$padre]['nombre']];
            if ($nacional) $links[] = ['href' => $nacional, 'label' => $sname . ' en Uruguay'];
            return $links ? ['title' => 'Más servicios en ' . $Z, 'links' => $links] : null;
        }
        return null;
    }

    // ── CIUDADES BASE (ver tambien Local_Datos::CITY_DATA_EXTRA y ZONAS_PADRE) ──

    public const CITY_DATA = [
        'carrasco' => [
            'nombre' => 'Carrasco', 'tipo' => 'ciudad', 'depto' => 'Montevideo',
            'cerca'  => 'Punta Gorda, Carrasco Norte y Malvín',
            'areas'  => ['Carrasco y Carrasco Norte', 'Punta Gorda y Malvín', 'Bañados de Carrasco y Barra de Carrasco'],
        ],
        'pocitos' => [
            'nombre' => 'Pocitos', 'tipo' => 'ciudad', 'depto' => 'Montevideo',
            'cerca'  => 'Punta Carretas, Buceo y Parque Batlle',
            'areas'  => ['Pocitos y Pocitos Nuevo', 'Punta Carretas y Parque Rodó', 'Buceo, Parque Batlle y Cordón'],
        ],
        'malvin' => [
            'nombre' => 'Malvín', 'tipo' => 'ciudad', 'depto' => 'Montevideo',
            'cerca'  => 'Buceo, Punta Gorda y Malvín Norte',
            'areas'  => ['Malvín y Malvín Norte', 'Buceo y Punta Gorda', 'Unión, Carrasco y barrios cercanos'],
        ],
        'prado' => [
            'nombre' => 'Prado', 'tipo' => 'ciudad', 'depto' => 'Montevideo',
            'cerca'  => 'Sayago, Atahualpa y Capurro',
            'areas'  => ['Prado y Atahualpa', 'Sayago, Peñarol y Colón', 'Capurro, Bella Vista y Aguada'],
        ],
    ];
}
