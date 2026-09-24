<?php

/**
 * Datos de las landings generadas: servicios extra, zona padre y barrios de Montevideo.
 * Va en una clase aparte porque las constantes no pueden vivir en un trait en PHP < 8.2.
 *
 * Solo Montevideo: cada clave de barrio genera una landing por servicio en /local/{servicio}-{barrio}.
 */
final class Local_Datos
{
    /**
     * Servicios "extra" ademas de los base de Local_Controller::CITY_SERVICES. slug => nombre.
     * Cada uno necesita su contenido en Local_Controller::contenidoServicio().
     */
    public const EXTRA_SERVICES = [
        'cerrajero-a-domicilio' => 'Cerrajero a domicilio',
        'apertura-de-puertas' => 'Apertura de puertas',
        'cerrajero-automotriz' => 'Cerrajero automotriz',
        'cambio-de-cerraduras' => 'Cambio de cerraduras',
        'cerrajero-24-horas' => 'Cerrajero 24 horas',
        'reparacion-de-cerraduras' => 'Reparación de cerraduras',
        'cerraduras-de-seguridad' => 'Cerraduras de seguridad',
        'copia-de-llaves' => 'Copia de llaves',
        'cerraduras-digitales' => 'Cerraduras digitales',
    ];

    /** Landing nacional por servicio (bloque de links relacionados). */
    public const NACIONAL_DE = [];

    /** Zona "padre": la landing general de la ciudad. */
    public const ZONAS_PADRE = [
        'montevideo' => ['nombre' => 'Montevideo', 'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Pocitos, Centro, Carrasco y Prado', 'areas' => ['Centro, Ciudad Vieja y Cordón', 'Pocitos, Malvín, Carrasco y Punta Gorda', 'Prado, Sayago, Colón, Cerro y toda la ciudad']],
    ];

    /** Barrios de Montevideo (se suman a Local_Controller::CITY_DATA). Claves = slug. */
    public const CITY_DATA_EXTRA = [
        // ── Costa este ──
        'punta-carretas'       => ['nombre' => 'Punta Carretas',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Pocitos, Parque Rodó y Villa Biarritz',                'areas' => ['Punta Carretas y Villa Biarritz', 'Pocitos y Parque Rodó', 'Cordón y Centro']],
        'villa-biarritz'       => ['nombre' => 'Villa Biarritz',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Punta Carretas, Pocitos y Parque Rodó',                'areas' => ['Villa Biarritz y Punta Carretas', 'Pocitos y Pocitos Nuevo', 'Parque Rodó y Palermo']],
        'pocitos-nuevo'        => ['nombre' => 'Pocitos Nuevo',        'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Pocitos, Buceo y Parque Batlle',                      'areas' => ['Pocitos Nuevo y Pocitos', 'Buceo y Puerto del Buceo', 'Parque Batlle y Villa Dolores']],
        'buceo'                => ['nombre' => 'Buceo',                'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Pocitos, Malvín y Parque Batlle',                      'areas' => ['Buceo y Puerto del Buceo', 'Pocitos y Malvín', 'Parque Batlle y La Blanqueada']],
        'malvin-norte'         => ['nombre' => 'Malvín Norte',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Malvín, Unión y Punta Gorda',                         'areas' => ['Malvín Norte y Boix y Merino', 'Malvín y Buceo', 'Unión y Punta Gorda']],
        'punta-gorda'          => ['nombre' => 'Punta Gorda',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Carrasco, Malvín y Malvín Norte',                     'areas' => ['Punta Gorda', 'Carrasco y Malvín', 'Malvín Norte y Unión']],
        'carrasco-norte'       => ['nombre' => 'Carrasco Norte',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Carrasco, Bañados de Carrasco y Punta Gorda',         'areas' => ['Carrasco Norte', 'Carrasco y Punta Gorda', 'Bañados de Carrasco y Paso Carrasco']],
        'banados-de-carrasco'  => ['nombre' => 'Bañados de Carrasco',  'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Carrasco Norte, Punta de Rieles y Villa García',      'areas' => ['Bañados de Carrasco', 'Carrasco Norte y Carrasco', 'Punta de Rieles y Villa García']],
        // ── Centro y Ciudad Vieja ──
        'centro'               => ['nombre' => 'Centro',               'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cordón, Ciudad Vieja y Aguada',                       'areas' => ['Centro y 18 de Julio', 'Cordón y Barrio Sur', 'Ciudad Vieja y Aguada']],
        'ciudad-vieja'         => ['nombre' => 'Ciudad Vieja',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Centro, Aguada y Barrio Sur',                         'areas' => ['Ciudad Vieja y Puerto', 'Centro y Barrio Sur', 'Aguada y La Comercial']],
        'barrio-sur'           => ['nombre' => 'Barrio Sur',           'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Palermo, Centro y Ciudad Vieja',                      'areas' => ['Barrio Sur y Rambla Sur', 'Palermo y Parque Rodó', 'Centro y Ciudad Vieja']],
        'palermo'              => ['nombre' => 'Palermo',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Barrio Sur, Parque Rodó y Cordón',                    'areas' => ['Palermo', 'Barrio Sur y Parque Rodó', 'Cordón y Centro']],
        'cordon'               => ['nombre' => 'Cordón',               'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Centro, Tres Cruces y Parque Rodó',                   'areas' => ['Cordón y Cordón Norte', 'Centro y Tres Cruces', 'Parque Rodó y Palermo']],
        'parque-rodo'          => ['nombre' => 'Parque Rodó',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Punta Carretas, Barrio Sur y Cordón',                 'areas' => ['Parque Rodó y Palermo', 'Punta Carretas y Pocitos', 'Barrio Sur y Cordón']],
        'tres-cruces'          => ['nombre' => 'Tres Cruces',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cordón, Parque Batlle y La Blanqueada',               'areas' => ['Tres Cruces', 'Cordón y Centro', 'Parque Batlle y La Blanqueada']],
        'parque-batlle'        => ['nombre' => 'Parque Batlle',        'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Buceo, La Blanqueada y Tres Cruces',                  'areas' => ['Parque Batlle y Villa Dolores', 'Buceo y Pocitos', 'La Blanqueada y Tres Cruces']],
        'la-blanqueada'        => ['nombre' => 'La Blanqueada',        'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Parque Batlle, Unión y Larrañaga',                    'areas' => ['La Blanqueada y Larrañaga', 'Parque Batlle y Tres Cruces', 'Unión y Villa Española']],
        'larranaga'            => ['nombre' => 'Larrañaga',            'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'La Blanqueada, Jacinto Vera y Brazo Oriental',        'areas' => ['Larrañaga', 'La Blanqueada y Tres Cruces', 'Jacinto Vera y Brazo Oriental']],
        // ── Norte del Centro ──
        'aguada'               => ['nombre' => 'Aguada',               'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Centro, Bella Vista y Reducto',                       'areas' => ['Aguada y La Comercial', 'Centro y Ciudad Vieja', 'Bella Vista, Reducto y Arroyo Seco']],
        'la-comercial'         => ['nombre' => 'La Comercial',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Aguada, Villa Muñoz y Cordón',                        'areas' => ['La Comercial', 'Aguada y Villa Muñoz', 'Cordón Norte y Tres Cruces']],
        'villa-munoz'          => ['nombre' => 'Villa Muñoz',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'La Comercial, Reducto y Jacinto Vera',                'areas' => ['Villa Muñoz y Retiro', 'La Comercial y Aguada', 'Reducto y Jacinto Vera']],
        'reducto'              => ['nombre' => 'Reducto',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Aguada, Bella Vista y Jacinto Vera',                  'areas' => ['Reducto', 'Aguada y Arroyo Seco', 'Bella Vista y Jacinto Vera']],
        'bella-vista'          => ['nombre' => 'Bella Vista',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Capurro, Aguada y Prado',                             'areas' => ['Bella Vista y Capurro', 'Aguada y Arroyo Seco', 'Prado y Reducto']],
        'capurro'              => ['nombre' => 'Capurro',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Bella Vista, Prado y Paso Molino',                    'areas' => ['Capurro y Bella Vista', 'Prado y Arroyo Seco', 'Paso Molino y Belvedere']],
        'jacinto-vera'         => ['nombre' => 'Jacinto Vera',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'La Figurita, Brazo Oriental y Reducto',               'areas' => ['Jacinto Vera y La Figurita', 'Brazo Oriental y Larrañaga', 'Reducto y Villa Muñoz']],
        'la-figurita'          => ['nombre' => 'La Figurita',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Jacinto Vera, Brazo Oriental y Larrañaga',            'areas' => ['La Figurita', 'Jacinto Vera y Reducto', 'Brazo Oriental y Larrañaga']],
        'brazo-oriental'       => ['nombre' => 'Brazo Oriental',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerrito, Jacinto Vera y Aires Puros',                 'areas' => ['Brazo Oriental y Aires Puros', 'Jacinto Vera y La Figurita', 'Cerrito y Atahualpa']],
        'atahualpa'            => ['nombre' => 'Atahualpa',            'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Prado, Brazo Oriental y Aires Puros',                 'areas' => ['Atahualpa', 'Prado y Sayago', 'Brazo Oriental y Aires Puros']],
        'aires-puros'          => ['nombre' => 'Aires Puros',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerrito, Brazo Oriental y Atahualpa',                 'areas' => ['Aires Puros', 'Cerrito y Las Acacias', 'Brazo Oriental y Atahualpa']],
        // ── Este y noreste ──
        'union'                => ['nombre' => 'Unión',                'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'La Blanqueada, Malvín Norte y Villa Española',         'areas' => ['Unión y Villa Española', 'La Blanqueada y Larrañaga', 'Malvín Norte y Maroñas']],
        'villa-espanola'       => ['nombre' => 'Villa Española',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Unión, Cerrito y Maroñas',                            'areas' => ['Villa Española', 'Unión y La Blanqueada', 'Cerrito y Maroñas']],
        'mercado-modelo'       => ['nombre' => 'Mercado Modelo',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Bolívar, Villa Española y Cerrito',                   'areas' => ['Mercado Modelo y Bolívar', 'Villa Española y Unión', 'Cerrito y Las Acacias']],
        'castro-castellanos'   => ['nombre' => 'Castro y Castellanos', 'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerrito, Las Acacias y Mercado Modelo',               'areas' => ['Castro y Castellanos', 'Cerrito y Las Acacias', 'Mercado Modelo y Piedras Blancas']],
        'cerrito'              => ['nombre' => 'Cerrito de la Victoria','tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Villa Española, Las Acacias y Brazo Oriental',        'areas' => ['Cerrito y Las Acacias', 'Villa Española y Unión', 'Brazo Oriental y Aires Puros']],
        'las-acacias'          => ['nombre' => 'Las Acacias',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerrito, Casavalle y Piedras Blancas',                'areas' => ['Las Acacias', 'Cerrito y Aires Puros', 'Casavalle y Piedras Blancas']],
        'maronas'              => ['nombre' => 'Maroñas',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Flor de Maroñas, Curva de Maroñas y Jardines del Hipódromo', 'areas' => ['Maroñas y Curva de Maroñas', 'Flor de Maroñas e Ituzaingó', 'Jardines del Hipódromo y Villa Española']],
        'flor-de-maronas'      => ['nombre' => 'Flor de Maroñas',      'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Maroñas, Ituzaingó y Punta de Rieles',                'areas' => ['Flor de Maroñas', 'Maroñas y Curva de Maroñas', 'Ituzaingó y Punta de Rieles']],
        'ituzaingo'            => ['nombre' => 'Ituzaingó',            'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Flor de Maroñas, Jardines del Hipódromo y Las Canteras', 'areas' => ['Ituzaingó', 'Flor de Maroñas y Maroñas', 'Jardines del Hipódromo y Las Canteras']],
        'jardines-del-hipodromo' => ['nombre' => 'Jardines del Hipódromo', 'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Maroñas, Ituzaingó y Piedras Blancas',           'areas' => ['Jardines del Hipódromo', 'Maroñas e Ituzaingó', 'Piedras Blancas y Las Acacias']],
        'las-canteras'         => ['nombre' => 'Las Canteras',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Malvín Norte, Ituzaingó y Punta de Rieles',           'areas' => ['Las Canteras', 'Malvín Norte y Unión', 'Ituzaingó y Punta de Rieles']],
        'punta-de-rieles'      => ['nombre' => 'Punta de Rieles',      'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Piedras Blancas, Villa García y Bañados de Carrasco', 'areas' => ['Punta de Rieles y Bella Italia', 'Villa García y Piedras Blancas', 'Bañados de Carrasco y ruta 8']],
        'bella-italia'         => ['nombre' => 'Bella Italia',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Punta de Rieles, Flor de Maroñas y Piedras Blancas',  'areas' => ['Bella Italia', 'Punta de Rieles y Villa García', 'Flor de Maroñas y Piedras Blancas']],
        'villa-garcia'         => ['nombre' => 'Villa García',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Punta de Rieles, Manga y Bañados de Carrasco',        'areas' => ['Villa García y Manga Rural', 'Punta de Rieles y Bella Italia', 'Bañados de Carrasco y ruta 8']],
        // ── Norte ──
        'piedras-blancas'      => ['nombre' => 'Piedras Blancas',      'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Manga, Punta de Rieles y Villa García',               'areas' => ['Piedras Blancas y Manga', 'Punta de Rieles y Villa García', 'Las Acacias y Casavalle']],
        'manga'                => ['nombre' => 'Manga',                'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Piedras Blancas, Toledo Chico y Casavalle',           'areas' => ['Manga y Toledo Chico', 'Piedras Blancas y Villa García', 'Casavalle y Las Acacias']],
        'casavalle'            => ['nombre' => 'Casavalle',            'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Las Acacias, Manga y Piedras Blancas',                'areas' => ['Casavalle y Barrio Borro', 'Las Acacias y Cerrito', 'Manga y Piedras Blancas']],
        'paso-de-las-duranas'  => ['nombre' => 'Paso de las Duranas',  'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Sayago, Peñarol y Casavalle',                        'areas' => ['Paso de las Duranas', 'Sayago y Conciliación', 'Peñarol y Casavalle']],
        // ── Oeste y noroeste ──
        'sayago'               => ['nombre' => 'Sayago',               'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Prado, Peñarol y Colón',                             'areas' => ['Sayago y Sayago Norte', 'Prado y Atahualpa', 'Peñarol, Lavalleja y Colón']],
        'conciliacion'         => ['nombre' => 'Conciliación',         'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Sayago, Belvedere y Nuevo París',                     'areas' => ['Conciliación', 'Sayago y Peñarol', 'Belvedere y Nuevo París']],
        'penarol'              => ['nombre' => 'Peñarol',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Sayago, Colón y Lavalleja',                           'areas' => ['Peñarol y Lavalleja', 'Sayago y Conciliación', 'Colón y Cerrito']],
        'colon'                => ['nombre' => 'Colón',                'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Lezica, Sayago y Melilla',                            'areas' => ['Colón centro y Colón Sudeste', 'Lezica y Melilla', 'Sayago, Peñarol y Abayubá']],
        'abayuba'              => ['nombre' => 'Abayubá',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Colón, Lezica y Melilla',                             'areas' => ['Abayubá y Colón Sudeste', 'Colón centro y Lezica', 'Melilla y ruta 5']],
        'lezica'               => ['nombre' => 'Lezica',               'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Colón, Melilla y Abayubá',                            'areas' => ['Lezica y Melilla', 'Colón y Abayubá', 'Peñarol y Sayago']],
        'melilla'              => ['nombre' => 'Melilla',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Lezica, Colón y Santiago Vázquez',                    'areas' => ['Melilla y Lezica', 'Colón y Abayubá', 'Santiago Vázquez y zonas rurales']],
        'belvedere'            => ['nombre' => 'Belvedere',            'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Paso Molino, La Teja y Nuevo París',                  'areas' => ['Belvedere', 'Paso Molino y Prado', 'La Teja y Nuevo París']],
        'paso-molino'          => ['nombre' => 'Paso Molino',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Belvedere, Prado y La Teja',                          'areas' => ['Paso Molino y Belvedere', 'Prado y Capurro', 'La Teja y Nuevo París']],
        'nuevo-paris'          => ['nombre' => 'Nuevo París',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Belvedere, La Teja y Paso de la Arena',               'areas' => ['Nuevo París', 'Belvedere y Conciliación', 'La Teja y Paso de la Arena']],
        'la-teja'              => ['nombre' => 'La Teja',              'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerro, Belvedere y Paso Molino',                      'areas' => ['La Teja y Pueblo Victoria', 'Belvedere y Paso Molino', 'Cerro y Nuevo París']],
        'tres-ombues'          => ['nombre' => 'Tres Ombúes',          'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'La Teja, Pueblo Victoria y Nuevo París',              'areas' => ['Tres Ombúes y Pueblo Victoria', 'La Teja y Belvedere', 'Nuevo París y Paso de la Arena']],
        'cerro'                => ['nombre' => 'Cerro',                'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'La Teja, Casabó y Paso de la Arena',                  'areas' => ['Cerro y Casabó', 'La Teja y Belvedere', 'Paso de la Arena y Santa Catalina']],
        'casabo'               => ['nombre' => 'Casabó',               'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerro, Pajas Blancas y Santa Catalina',               'areas' => ['Casabó y Cerro Norte', 'Cerro y La Paloma', 'Pajas Blancas y Santa Catalina']],
        'la-paloma'            => ['nombre' => 'La Paloma',            'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerro, Tomkinson y Casabó',                           'areas' => ['La Paloma y Tomkinson', 'Cerro y Casabó', 'Paso de la Arena y Los Bulevares']],
        'santa-catalina'       => ['nombre' => 'Santa Catalina',       'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Casabó, Cerro y Pajas Blancas',                       'areas' => ['Santa Catalina', 'Casabó y Cerro', 'Pajas Blancas y Punta Espinillo']],
        'pajas-blancas'        => ['nombre' => 'Pajas Blancas',        'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Santa Catalina, Casabó y Punta Espinillo',            'areas' => ['Pajas Blancas', 'Santa Catalina y Casabó', 'Punta Espinillo y zonas rurales']],
        'paso-de-la-arena'     => ['nombre' => 'Paso de la Arena',     'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Cerro, Santiago Vázquez y Nuevo París',               'areas' => ['Paso de la Arena y Los Bulevares', 'Nuevo París y La Teja', 'Santiago Vázquez y Cerro']],
        'santiago-vazquez'     => ['nombre' => 'Santiago Vázquez',     'tipo' => 'ciudad', 'depto' => 'Montevideo', 'cerca' => 'Paso de la Arena, Melilla y Los Bulevares',           'areas' => ['Santiago Vázquez', 'Paso de la Arena y Los Bulevares', 'Melilla y zonas rurales']],
    ];

}
