<?php
/**
 * ZONAS. Clave = slug (va en la URL: /{servicio}/{slug} y /{slug}). Jerarquía por 'padre'.
 *
 * Campos obligatorios:
 *   nombre   Nombre visible.
 *   tipo     'pais' | 'departamento' | 'provincia' | 'ciudad' | 'localidad' | 'barrio'
 *   padre    Slug de la zona que la contiene. null solo para el país.
 *
 * Campos opcionales:
 *   paginas        false = la zona es solo jerarquía (no genera /{zona} ni /{servicio}/{zona}). Por defecto false para 'pais', true para el resto.
 *   nombre_en      Cómo va en frases "en ...": 'en el Centro', 'en Ciudad de la Costa'. Vacío = nombre.
 *   vecinos        Slugs de zonas cercanas (enlazado interno). Vacío = hermanas del mismo padre.
 *   referencias    Lugares, calles o puntos conocidos. Se usan en el texto automático y en las FAQ.
 *   tiempo_llegada Texto: '20 a 30 minutos'. Se usa en textos y FAQ.
 *   notas          Descripción corta de la zona (1 o 2 frases). Se usa en el texto automático y en el hub de zona.
 *   vivienda       'casas' | 'apartamentos' | 'mixto' | 'rural' | 'comercial'. Orienta el texto automático.
 *   lat, lng       Coordenadas (schema GeoCoordinates del hub de zona).
 *   wikidata       ID de Wikidata (Q...). Se emite como sameAs en el schema Place.
 *   wikipedia      Título del artículo en es.wikipedia.org.
 *   codigo         Código ISO de subdivisión para departamentos/provincias (UY-MO, AR-C, AR-B).
 *   orden          Orden en listados.
 */
return [

    'argentina' => [
        'nombre' => 'Argentina', 'tipo' => 'pais', 'padre' => null, 'paginas' => false,
        'wikidata' => 'Q414',   // Uruguay: Q77
    ],

    'provincia-ejemplo' => [
        'nombre' => 'Provincia Ejemplo [EJEMPLO]', 'tipo' => 'provincia', 'padre' => 'argentina', 'paginas' => false,
        'codigo' => 'AR-X',     // ISO 3166-2 (UY-MO, AR-C, AR-B...)
    ],

    // Zona padre con páginas: /ciudad-ejemplo (hub) y /plomero/ciudad-ejemplo
    'ciudad-ejemplo' => [
        'nombre'         => 'Ciudad Ejemplo [EJEMPLO]',
        'tipo'           => 'ciudad',
        'padre'          => 'provincia-ejemplo',
        'nombre_en'      => '',
        'vecinos'        => [],
        'referencias'    => ['la plaza principal', 'la avenida central', 'la terminal'],
        'tiempo_llegada' => '30 a 45 minutos según el barrio',
        'notas'          => 'Ciudad Ejemplo concentra barrios de casas con jardín hacia el norte y edificios de apartamentos en el centro y la costa. [EJEMPLO]',
        'vivienda'       => 'mixto',
        'lat' => '-33.0000', 'lng' => '-60.0000',
        'wikidata' => '', 'wikipedia' => '',
    ],

    // Barrio con contenido propio en data/contenido/plomero/barrio-norte.php
    'barrio-norte' => [
        'nombre'         => 'Barrio Norte [EJEMPLO]',
        'tipo'           => 'barrio',
        'padre'          => 'ciudad-ejemplo',
        'vecinos'        => ['barrio-sur'],
        'referencias'    => ['el parque del norte', 'la avenida de los Plátanos'],
        'tiempo_llegada' => '20 a 30 minutos',
        'notas'          => 'Barrio Norte es una zona residencial de casas de una y dos plantas con jardín, calles arboladas y comercio de barrio. [EJEMPLO]',
        'vivienda'       => 'casas',
    ],

    // Barrio SIN archivo de contenido: todo sale de las fórmulas por defecto
    'barrio-sur' => [
        'nombre'         => 'Barrio Sur [EJEMPLO]',
        'tipo'           => 'barrio',
        'padre'          => 'ciudad-ejemplo',
        'vecinos'        => ['barrio-norte'],
        'referencias'    => ['la rambla', 'el mercado viejo'],
        'tiempo_llegada' => '15 a 25 minutos',
        'notas'          => 'Barrio Sur es la zona de edificios de apartamentos junto a la rambla, con mucha rotación de inquilinos. [EJEMPLO]',
        'vivienda'       => 'apartamentos',
    ],

];
