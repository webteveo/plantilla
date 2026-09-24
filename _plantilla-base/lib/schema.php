<?php
/**
 * JSON-LD generado desde config.php y los datos. @id consistentes en todo el sitio:
 *   {dominio}/#localbusiness  {dominio}/#website  {canonical}#webpage  {canonical}#service  {canonical}#breadcrumb  {canonical}#faq
 * NO se toca para armar un sitio nuevo.
 */
declare(strict_types=1);

function schema_id_negocio(): string { return canonical('/') . '#localbusiness'; }
function schema_id_website(): string { return canonical('/') . '#website'; }

/** Entidad Place de una zona con containedInPlace hasta el país. */
function schema_place(array $zona): array
{
    $tipos = ['pais' => 'Country', 'departamento' => 'AdministrativeArea', 'provincia' => 'AdministrativeArea', 'ciudad' => 'City', 'localidad' => 'City', 'barrio' => 'Place'];
    $pl = ['@type' => $tipos[$zona['tipo']] ?? 'Place', 'name' => $zona['nombre']];
    $same = [];
    if (!empty($zona['wikidata']))  $same[] = 'https://www.wikidata.org/wiki/' . $zona['wikidata'];
    if (!empty($zona['wikipedia'])) $same[] = 'https://es.wikipedia.org/wiki/' . str_replace(' ', '_', $zona['wikipedia']);
    if ($same) $pl['sameAs'] = count($same) === 1 ? $same[0] : $same;
    if (!empty($zona['lat']) && !empty($zona['lng'])) $pl['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (float)$zona['lat'], 'longitude' => (float)$zona['lng']];
    if ($zona['padre'] && ($p = zona($zona['padre']))) $pl['containedInPlace'] = schema_place($p);
    return $pl;
}

/** Tipo del negocio: [subtipo, 'LocalBusiness'] o solo 'LocalBusiness'. */
function schema_tipo_negocio()
{
    $t = (string)(cfg('schema.tipo') ?: 'LocalBusiness');
    return $t === 'LocalBusiness' ? 'LocalBusiness' : [$t, 'LocalBusiness'];
}

function schema_localbusiness(): array
{
    $marca = (string)cfg('marca.nombre');
    $lb = [
        '@context'    => 'https://schema.org',
        '@type'       => schema_tipo_negocio(),
        '@id'         => schema_id_negocio(),
        'name'        => $marca,
        'url'         => canonical('/'),
        'logo'        => asset_abs((string)cfg('logos.principal')),
        'image'       => asset_abs((string)cfg('sitio.og_image')),
        'description' => (string)cfg('marca.descripcion'),
        'slogan'      => (string)cfg('marca.slogan'),
        'inLanguage'  => cfg('sitio.idioma') . '-' . cfg('pais.codigo'),
        'priceRange'  => (string)cfg('schema.price_range'),
        'currenciesAccepted' => (string)cfg('pais.moneda'),
        'paymentAccepted'    => (string)cfg('schema.pagos'),
        'address'     => array_filter([
            '@type'           => 'PostalAddress',
            'streetAddress'   => cfg('direccion.tiene_local') ? trim(cfg('direccion.calle') . ' ' . cfg('direccion.numero')) : null,
            'addressLocality' => (string)cfg('direccion.ciudad'),
            'addressRegion'   => (string)cfg('direccion.region'),
            'postalCode'      => cfg('direccion.codigo_postal') ?: null,
            'addressCountry'  => (string)cfg('pais.codigo'),
        ]),
    ];
    if (cfg('contacto.email')) $lb['email'] = cfg('contacto.email');
    if (cfg('contacto.telefono')) $lb['telephone'] = '+' . preg_replace('/\D/', '', (string)cfg('contacto.telefono'));
    if (cfg('marca.anio_inicio')) $lb['foundingDate'] = (string)cfg('marca.anio_inicio');
    if (cfg('direccion.lat') && cfg('direccion.lng')) $lb['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (float)cfg('direccion.lat'), 'longitude' => (float)cfg('direccion.lng')];
    if (cfg('direccion.google_maps')) $lb['hasMap'] = cfg('direccion.google_maps');
    // Área de servicio: zonas de primer nivel
    $lb['areaServed'] = array_values(array_map('schema_place', zonas_raiz()));
    // Horario
    $dias = ['lunes' => 'Monday', 'martes' => 'Tuesday', 'miercoles' => 'Wednesday', 'jueves' => 'Thursday', 'viernes' => 'Friday', 'sabado' => 'Saturday', 'domingo' => 'Sunday'];
    $oh = [];
    foreach ($dias as $k => $dia) {
        $r = (string)cfg("horario.$k");
        if (preg_match('/^(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})$/', $r, $m)) $oh[] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $dia, 'opens' => $m[1], 'closes' => $m[2]];
    }
    if ($oh) $lb['openingHoursSpecification'] = $oh;
    // Contacto por WhatsApp
    if (cfg('contacto.whatsapp')) {
        $lb['contactPoint'] = ['@type' => 'ContactPoint', 'telephone' => '+' . preg_replace('/\D/', '', (string)cfg('contacto.whatsapp')), 'contactType' => 'customer service', 'availableLanguage' => cfg('sitio.idioma')];
    }
    // Redes
    $same = array_values(array_filter(array_merge([cfg('direccion.google_maps')], array_values((array)cfg('redes', [])))));
    if (cfg('contacto.whatsapp')) $same[] = 'https://wa.me/' . preg_replace('/\D/', '', (string)cfg('contacto.whatsapp'));
    if ($same) $lb['sameAs'] = $same;
    // Catálogo de servicios (páginas troncales)
    $items = [];
    foreach (servicios() as $s) {
        $items[] = ['@type' => 'Offer', 'url' => canonical('/' . $s['slug']), 'itemOffered' => ['@type' => 'Service', '@id' => canonical('/' . $s['slug']) . '#service', 'name' => $s['nombre_h1'], 'url' => canonical('/' . $s['slug'])]];
    }
    if ($items) $lb['hasOfferCatalog'] = ['@type' => 'OfferCatalog', 'name' => 'Servicios de ' . $marca, 'itemListElement' => $items];
    $lb['serviceType'] = array_values(array_map(fn($s) => $s['nombre_h1'], servicios()));
    return $lb;
}

function schema_website(): array
{
    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'WebSite',
        '@id'        => schema_id_website(),
        'url'        => canonical('/'),
        'name'       => (string)cfg('marca.nombre'),
        'inLanguage' => cfg('sitio.idioma') . '-' . cfg('pais.codigo'),
        'publisher'  => ['@id' => schema_id_negocio()],
    ];
}

function schema_webpage(array $p): array
{
    $wp = [
        '@context'   => 'https://schema.org',
        '@type'      => 'WebPage',
        '@id'        => $p['seo']['canonical'] . '#webpage',
        'url'        => $p['seo']['canonical'],
        'name'       => $p['seo']['title'],
        'description'=> $p['seo']['description'],
        'inLanguage' => cfg('sitio.idioma') . '-' . cfg('pais.codigo'),
        'isPartOf'   => ['@id' => schema_id_website()],
        'about'      => ['@id' => schema_id_negocio()],
    ];
    if (!empty($p['seo']['actualizado'])) $wp['dateModified'] = $p['seo']['actualizado'];
    if (!empty($p['migas']) && count($p['migas']) > 1) $wp['breadcrumb'] = ['@id' => $p['seo']['canonical'] . '#breadcrumb'];
    if (in_array($p['tipo'], ['servicio', 'servicio-zona'], true)) $wp['mainEntity'] = ['@id' => $p['seo']['canonical'] . '#service'];
    return $wp;
}

/** Service de una página servicio o servicio×zona. areaServed = la zona de la página (o todas las raíz en la troncal). */
function schema_service(array $p): array
{
    $srv = $p['servicio']; $zona = $p['zona'] ?? null;
    $areas = $zona ? [schema_place($zona)] : array_values(array_map('schema_place', zonas_raiz()));
    $sch = [
        '@context'    => 'https://schema.org',
        '@type'       => (string)(cfg('schema.servicio_tipo') ?: 'Service'),
        '@id'         => $p['seo']['canonical'] . '#service',
        'name'        => $p['seo']['h1'],
        'serviceType' => $srv['nombre_h1'],
        'category'    => (string)cfg('marca.rubro'),
        'description' => $p['seo']['description'],
        'url'         => $p['seo']['canonical'],
        'inLanguage'  => cfg('sitio.idioma') . '-' . cfg('pais.codigo'),
        'provider'    => ['@id' => schema_id_negocio(), '@type' => schema_tipo_negocio(), 'name' => cfg('marca.nombre'), 'url' => canonical('/')],
        'areaServed'  => count($areas) === 1 ? $areas[0] : $areas,
        'availableChannel' => array_filter([
            '@type'      => 'ServiceChannel',
            'name'       => 'WhatsApp',
            'serviceUrl' => wsp_href($p['wsp_mensaje']),
            'servicePhone' => cfg('contacto.whatsapp') ? ['@type' => 'ContactPoint', 'telephone' => '+' . preg_replace('/\D/', '', (string)cfg('contacto.whatsapp')), 'contactType' => 'customer service'] : null,
        ]),
    ];
    if ($srv['sinonimos']) $sch['alternateName'] = $srv['sinonimos'];
    if (!empty($p['imagen_abs'])) $sch['image'] = $p['imagen_abs'];
    foreach ($srv['schema_extra'] as $k => $v) $sch[$k] = $v;
    return $sch;
}

function schema_breadcrumb(array $p): ?array
{
    $migas = $p['migas'] ?? [];
    if (count($migas) < 2) return null;
    $items = [];
    foreach ($migas as $i => $m) {
        $it = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $m['label']];
        if (isset($m['href'])) $it['item'] = canonical(substr($m['href'], strlen(base_path()) - 1) ?: '/');
        else $it['item'] = $p['seo']['canonical'];
        $items[] = $it;
    }
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', '@id' => $p['seo']['canonical'] . '#breadcrumb', 'itemListElement' => $items];
}

function schema_faq(array $p): ?array
{
    $faq = $p['faq'] ?? [];
    if (!$faq) return null;
    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => $p['seo']['canonical'] . '#faq',
        'mainEntity' => array_map(fn($f) => ['@type' => 'Question', 'name' => strip_tags($f['q']), 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])]], $faq),
    ];
}

/** Review + AggregateRating a partir de reseñas reales (comun.testimonios). Nada si no hay. */
function schema_resenas(): ?array
{
    $t = (array)(comun()['testimonios'] ?? []);
    if (!$t) return null;
    $n = count($t);
    return [
        '@context' => 'https://schema.org',
        '@type'    => schema_tipo_negocio(),
        '@id'      => schema_id_negocio(),
        'name'     => cfg('marca.nombre'),
        'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => round(array_sum(array_column($t, 'estrellas')) / $n, 1), 'reviewCount' => $n, 'bestRating' => 5],
        'review'   => array_map(fn($r) => array_filter([
            '@type'        => 'Review',
            'author'       => ['@type' => 'Person', 'name' => $r['nombre']],
            'reviewRating' => ['@type' => 'Rating', 'ratingValue' => (int)$r['estrellas'], 'bestRating' => 5],
            'reviewBody'   => $r['texto'],
            'datePublished'=> $r['fecha'] ?? null,
        ]), $t),
    ];
}

/** Todos los bloques JSON-LD de una página. */
function schema_bloques(array $p): array
{
    $b = [schema_localbusiness(), schema_website(), schema_webpage($p)];
    if (in_array($p['tipo'], ['servicio', 'servicio-zona'], true)) $b[] = schema_service($p);
    if ($bc = schema_breadcrumb($p)) $b[] = $bc;
    if ($fq = schema_faq($p)) $b[] = $fq;
    if ($p['tipo'] === 'home' && ($rs = schema_resenas())) $b[] = $rs;
    return $b;
}

function schema_json(array $bloque): string
{
    return json_encode($bloque, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
