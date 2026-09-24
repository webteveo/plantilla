<?php
/** Contenido de la home (/). [EJEMPLO] */
return [
    'title'       => '',   // Vacío = sitio.titulo_home o "{marca} | {slogan}"
    'description' => '',   // Vacío = marca.descripcion
    'hero' => [
        'eyebrow'    => 'Plomería a domicilio en Ciudad Ejemplo [EJEMPLO]',
        'titulo_em'  => 'Plomero a domicilio',        // Parte del H1 en itálica (diseño original)
        'titulo'     => 'en Ciudad Ejemplo [EJEMPLO]', // Resto del H1
        'subtitulo'  => 'Destapaciones, pérdidas y grifería con precio por WhatsApp antes de ir. [EJEMPLO]',
    ],
    'faq' => [
        ['q' => '¿Cuánto cuesta un plomero en Ciudad Ejemplo? [EJEMPLO]', 'a' => 'Depende del trabajo. Contanos qué pasa y te pasamos el <strong>precio por WhatsApp antes de ir</strong>. [EJEMPLO]'],
        ['q' => '¿En qué zonas trabajan? [EJEMPLO]',                       'a' => 'En toda Ciudad Ejemplo y sus barrios. Mirá la lista de zonas o preguntanos por la tuya. [EJEMPLO]'],
        ['q' => '¿Trabajan con garantía? [EJEMPLO]',                       'a' => 'Sí, todos los trabajos tienen garantía por escrito. [EJEMPLO]'],
    ],
];
