<?php
/**
 * Contenido único de la página /plomero/ciudad-ejemplo (servicio × zona padre). [EJEMPLO]
 * Todos los campos son opcionales: lo que falte se completa con las fórmulas por defecto (ver lib/seo.php y lib/texto.php).
 * Placeholders válidos en cualquier texto: {servicio} {Servicio} {zona} {zona_en} {padre} {marca} {rubro} {telefono} {ciudad}.
 */
return [
    'title'       => 'Plomero en Ciudad Ejemplo – Destapaciones en el día [EJEMPLO]',
    'description' => 'Plomero a domicilio en toda Ciudad Ejemplo. Destapaciones, pérdidas de agua, grifería y termotanques con precio por WhatsApp antes de ir. [EJEMPLO]',
    'h1'          => 'Plomero en Ciudad Ejemplo [EJEMPLO]',
    'eyebrow'     => 'Plomería a domicilio en toda la ciudad [EJEMPLO]',
    'subtitulo'   => 'Cubrimos todos los barrios de Ciudad Ejemplo. Contanos qué pasa y te decimos precio y horario por WhatsApp. [EJEMPLO]',

    // Secciones de texto (H2 + párrafos + lista opcional). Reemplazan al texto automático.
    'secciones' => [
        [
            'h2'       => 'Plomería en Ciudad Ejemplo: así trabajamos [EJEMPLO]',
            'parrafos' => [
                'Atendemos toda Ciudad Ejemplo, desde los edificios del centro hasta las casas con jardín del norte. Llegamos con el equipo para resolver en la primera visita destapaciones, pérdidas y cambios de grifería. [EJEMPLO]',
                'Antes de ir te pasamos el precio por WhatsApp. Si al llegar el trabajo es distinto a lo que describiste, te lo decimos antes de empezar. [EJEMPLO]',
            ],
        ],
        [
            'h2'       => 'Precios de referencia en Ciudad Ejemplo [EJEMPLO]',
            'parrafos' => ['Valores orientativos; el precio final se confirma por WhatsApp según el trabajo. [EJEMPLO]'],
            'tabla'    => [
                'cabecera' => ['Trabajo', 'Desde', 'Incluye'],
                'filas'    => [
                    ['Destapación de pileta o inodoro', '$ 2.500 [EJEMPLO]', 'Visita, máquina y prueba'],
                    ['Cambio de grifería', '$ 1.800 [EJEMPLO]', 'Mano de obra (sin la grifería)'],
                ],
                'nota'     => 'Precios vigentes a septiembre de 2026, en pesos. [EJEMPLO]',
            ],
        ],
        [
            'h2'       => 'Qué resolvemos en Ciudad Ejemplo [EJEMPLO]',
            'parrafos' => ['Estos son los pedidos más frecuentes que recibimos en la ciudad. [EJEMPLO]'],
            'lista'    => [
                'Destapación de cañerías, piletas e inodoros [EJEMPLO]',
                'Pérdidas de agua en paredes, pisos y cisternas [EJEMPLO]',
                'Instalación y cambio de grifería y termotanques [EJEMPLO]',
            ],
        ],
    ],

    // Tabla (opcional en cualquier sección): trabajos recientes, precios con fecha, tiempos de llegada.
    // 'parrafos_despues' va después de la tabla o la lista.
    'actualizado' => '2026-09-01',   // Fecha visible "Actualizado: ..." + dateModified en el schema. [EJEMPLO]

    // FAQ propias de esta página. Se muestran antes de las FAQ base del servicio (data/servicios.php).
    'faq' => [
        ['q' => '¿Atienden en todos los barrios de Ciudad Ejemplo? [EJEMPLO]', 'a' => 'Sí. Cubrimos toda la ciudad. Elegí tu barrio en la lista de zonas para ver tiempos de llegada. [EJEMPLO]'],
    ],
    'faq_reemplaza' => false,   // true = solo se muestran las FAQ de este archivo

    'wsp_mensaje' => '',        // Vacío = "Hola, necesito plomero en Ciudad Ejemplo" (config contacto.wsp_mensaje)
    'imagen'      => '',        // Relativa a assets/img/. Vacío = imagen del servicio o del hero.
    'imagen_alt'  => '',
];
