<?php
/**
 * PLANTILLA DE ARTICULO. Copiar como data/articulos/YYYY-MM-DD-slug.php y completar.
 * Los archivos que empiezan con "_" no se publican. 'borrador' => true tampoco. 'fecha' futura = programado.
 * Con solo crear el archivo queda publicado en /articulos/{slug}, en /articulos, en sitemap.xml, en /articulos/feed y en /llms.txt.
 *
 * Reglas editoriales:
 *  - NADA DE PRECIOS. Toda pregunta de precio deriva a WhatsApp.
 *  - 'respuesta': 2-3 oraciones que responden la pregunta del titulo de forma directa (lo que citan Google AI Overviews y ChatGPT).
 *  - 'puntos_clave': 3-5 bullets con datos concretos, no adjetivos.
 *  - Cada H2 arranca con una oracion que responde el subtitulo; despues explica.
 *  - 1200-2000 palabras. Minimo 4 secciones H2, 4-6 FAQ, 2-4 fuentes externas reales, 3-6 links internos.
 *  - HTML permitido dentro de parrafos/lista/html: <strong>, <em>, <a href="...">. Los links internos van con href relativo: 'contacto'.
 */
return [
    'slug'        => 'slug-del-articulo',                         // URL: /articulos/slug-del-articulo
    'titulo'      => 'Título H1 en forma de pregunta o promesa',  // 50-65 caracteres ideal
    'title'       => 'Title SEO | ' . EMPRESA_NOMBRE,             // opcional, 55-60 caracteres
    'description' => 'Meta description de 140-155 caracteres con la keyword y la respuesta resumida.',
    'keywords'    => 'keyword principal, variante 1, variante 2',
    'categoria'   => 'Cerrajería',           // categoria libre; agrupa en /articulos
    'tema'        => 'Tema principal (entidad) del artículo',
    'fecha'       => '2026-01-01',
    'actualizado' => '2026-01-01',
    'autor'       => 'Equipo ' . EMPRESA_NOMBRE,
    'imagen'      => '',                   // relativa a public/images/. Opcional.
    'imagen_alt'  => 'Texto alternativo descriptivo con la keyword',
    'imagen_pie'  => '',
    'bajada'      => 'Una o dos oraciones bajo el H1 que dicen qué va a aprender el lector.',

    'respuesta'   => 'Respuesta directa a la pregunta del título en 2-3 oraciones, con el dato principal.',

    'puntos_clave' => [
        'Dato concreto 1.',
        'Dato concreto 2.',
        'Dato concreto 3.',
    ],

    'secciones' => [
        [
            'h2'       => 'Primer subtítulo',
            'parrafos' => [
                'Párrafo 1.',
                'Párrafo 2 con <a href="contacto">link interno</a>.',
            ],
            'lista'    => ['Item 1', 'Item 2'],
            'nota'     => 'Aviso o aclaración opcional en recuadro.',
            'h3s'      => [
                ['h3' => 'Sub-subtítulo', 'parrafos' => ['Texto.']],
            ],
        ],
        [
            'h2'    => 'Sección con tabla',
            'tabla' => [
                'cabecera' => ['Columna 1', 'Columna 2'],
                'filas'    => [['a', 'b'], ['c', 'd']],
            ],
        ],
    ],

    'faq' => [
        ['q' => '¿Pregunta 1?', 'a' => 'Respuesta breve y directa.'],
        ['q' => '¿Pregunta 2?', 'a' => 'Respuesta breve y directa.'],
    ],

    'fuentes' => [
        ['label' => 'Nombre de la fuente', 'url' => 'https://...', 'nota' => 'qué aporta'],
    ],

    'links' => [
        ['href' => 'paginas/servicios', 'label' => 'Servicios'],
        ['href' => 'contacto',          'label' => 'Pedir presupuesto'],
    ],

    'cta_titulo'  => 'Título del CTA final (opcional)',
    'cta_texto'   => 'Texto del CTA final (opcional).',
    'cta_message' => 'Hola! Leí el artículo sobre X y quiero pedir presupuesto.',
];
