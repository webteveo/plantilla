<?php
/**
 * Bloques compartidos por todas las páginas (home, servicio, servicio×zona, zona, nosotros). [EJEMPLO]
 * Placeholders: {marca} {rubro} {ciudad} {zona} (zona de la página o zona principal) {servicio} (servicio de la página o rubro).
 */
return [

    // "Cómo trabajamos": 3 pasos.
    'pasos_titulo' => 'Cómo trabajamos',
    'pasos_lead'   => 'Tres pasos, sin vueltas. [EJEMPLO]',
    'pasos' => [
        ['icono' => 'ri-whatsapp-line',            'titulo' => 'Escribinos',              'texto' => 'Contanos qué necesitás y en qué zona estás. Una foto ayuda. [EJEMPLO]'],
        ['icono' => 'ri-money-dollar-circle-line', 'titulo' => 'Precio antes de ir',      'texto' => 'Te pasamos el precio y el horario por WhatsApp. Sin sorpresas. [EJEMPLO]'],
        ['icono' => 'ri-tools-line',               'titulo' => 'Resolvemos y probamos',   'texto' => 'Hacemos el trabajo y comprobamos que todo funcione antes de irnos. [EJEMPLO]'],
    ],

    // "Quiénes somos" (bloque corto de la home y las landings; la página /nosotros tiene su propio archivo).
    'nosotros_titulo' => 'Quiénes somos',
    'nosotros_lead'   => 'Somos <strong>{marca}</strong>, un equipo de {rubro} que atiende {zona}. [EJEMPLO]',
    'nosotros_texto'  => 'Trabajamos con presupuesto cerrado, llegamos en el horario acordado y dejamos todo probado y limpio. [EJEMPLO]',
    'nosotros_imagen_alt' => 'Equipo de {marca} [EJEMPLO]',

    // "Por qué elegirnos": 4 items. 'href' opcional (ruta relativa) para enlazar el título.
    'diferenciadores_titulo' => 'Por qué elegirnos',
    'diferenciadores_lead'   => 'Esto es lo que te garantizamos en cada servicio. [EJEMPLO]',
    'diferenciadores' => [
        ['icono' => 'ri-price-tag-3-line',  'titulo' => 'Precio antes de ir',     'texto' => 'Te decimos cuánto cuesta por WhatsApp, antes de salir. [EJEMPLO]'],
        ['icono' => 'ri-time-line',         'titulo' => 'Llegamos a horario',     'texto' => 'Coordinamos un horario y lo cumplimos. [EJEMPLO]'],
        ['icono' => 'ri-shield-check-line', 'titulo' => 'Garantía escrita',       'texto' => 'Todos los trabajos con garantía por escrito. [EJEMPLO]'],
        ['icono' => 'ri-map-pin-line',      'titulo' => 'Cobertura real',         'texto' => 'Trabajamos todas las semanas en {zona}. [EJEMPLO]'],
    ],

    // Cifras. Vacío = la sección no se muestra. No inventar números.
    'stats' => [
        // ['n' => 10, 'sup' => '+', 'label' => 'Años de experiencia'],
    ],

    // Reseñas reales. Vacío = la sección no se muestra (nunca se muestran reseñas de relleno).
    // Cada item: nombre, texto, estrellas (1-5), fecha (YYYY-MM-DD, opcional), fuente ('Google', opcional).
    'testimonios' => [
        // ['nombre' => 'María G.', 'texto' => 'Vinieron el mismo día y dejaron todo funcionando.', 'estrellas' => 5, 'fecha' => '2026-08-15', 'fuente' => 'Google'],
    ],

    // Textos de secciones automáticas
    'servicios_titulo'  => 'Nuestros <em>servicios</em>',           // admite <em> para el resaltado del diseño
    'servicios_lead'    => 'Elegí el servicio y escribinos. Te respondemos por WhatsApp. [EJEMPLO]',
    'servicios_btn'     => 'Pedir presupuesto',                      // Botón de cada card
    'zonas_titulo'      => '{Servicio} en todas las zonas de {zona}', // Chips de zonas en landings
    'zonas_lead'        => 'Elegí tu zona para ver tiempos de llegada y consultar. [EJEMPLO]',
    'faq_titulo'        => 'Preguntas frecuentes',
    'faq_lead'          => 'Lo que más nos consultan sobre {servicio}. [EJEMPLO]',
    'contacto_titulo'   => 'Contactanos',
    'contacto_lead'     => '¿Necesitás {servicio} en {zona}? Escribinos por WhatsApp y te respondemos al momento. [EJEMPLO]',
    'relacionados_otros'   => 'Más servicios en {zona}',
    'relacionados_cerca'   => 'Cerca de {zona}',
    'texto_cta'         => 'Escribinos por WhatsApp',
];
