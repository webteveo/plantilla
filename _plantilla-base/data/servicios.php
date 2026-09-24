<?php
/**
 * SERVICIOS. Clave = slug (va en la URL: /{slug} y /{slug}/{zona}).
 *
 * Campos obligatorios:
 *   nombre        Cómo se nombra el servicio en frases: "{nombre} en {zona}". Minúscula inicial salvo nombre propio.
 *   descripcion   1 o 2 frases. Se usa en la card de la home, meta description por defecto y schema.
 *   zonas         '*' = todas las zonas con páginas, o lista de slugs de zonas. SOLO existen las combinaciones declaradas acá.
 *
 * Campos opcionales:
 *   nombre_h1     Forma para títulos, si difiere de nombre (ej. nombre 'plomero', nombre_h1 'Plomero').
 *   sinonimos     Variantes de búsqueda. Se usan en textos automáticos, anchors variados y meta keywords.
 *   entidades     Conceptos del servicio (herramientas, problemas, piezas). Enriquecen el texto automático y el schema.
 *   faq           Preguntas base. Placeholders: {servicio} {zona} {marca} {padre}. Se combinan con las FAQ de cada página.
 *   imagen        Relativa a assets/img/servicios/. Vacío = card con fondo degradado.
 *   imagen_alt    Alt de la imagen. Placeholders permitidos.
 *   beneficios    3 items [titulo, texto] para la sección "por qué elegirnos" de la página troncal y de las servicio×zona.
 *   wsp_mensaje   Mensaje de WhatsApp propio. Vacío = contacto.wsp_mensaje del config.
 *   schema_extra  Claves extra para el schema Service (ej. 'additionalType' => 'https://es.wikipedia.org/wiki/...').
 *   excluir_zonas Lista de slugs a excluir cuando zonas = '*'.
 *   orden         Orden en menús y listados (menor primero). Por defecto el orden del archivo.
 */
return [

    'plomero' => [
        'nombre'      => 'plomero',
        'nombre_h1'   => 'Plomero',
        'descripcion' => 'Plomero a domicilio para destapaciones, pérdidas de agua, grifería y termotanques. Presupuesto por WhatsApp antes de ir. [EJEMPLO]',
        'zonas'       => '*',
        'sinonimos'   => ['plomería', 'sanitario', 'plomero a domicilio', 'servicio de plomería'],
        'entidades'   => ['destapación de cañerías', 'pérdidas de agua', 'grifería', 'termotanque', 'inodoro', 'cisterna', 'caño roto'],
        'imagen'      => '',
        'imagen_alt'  => 'Plomero de {marca} trabajando en {zona} [EJEMPLO]',
        'beneficios'  => [
            ['titulo' => 'Precio antes de ir',        'texto' => 'Te decimos cuánto cuesta por WhatsApp. Sin sorpresas. [EJEMPLO]'],
            ['titulo' => 'Conocemos {zona}',          'texto' => 'Trabajamos todas las semanas en {zona} y alrededores. [EJEMPLO]'],
            ['titulo' => 'Trabajo con garantía',      'texto' => 'Probamos que todo funcione antes de irnos y dejamos garantía escrita. [EJEMPLO]'],
        ],
        'faq' => [
            ['q' => '¿Cuánto cuesta un {servicio} en {zona}?',   'a' => 'Depende del trabajo. Contanos qué pasa y te pasamos el precio por WhatsApp antes de ir. [EJEMPLO]'],
            ['q' => '¿Cuánto tardan en llegar a {zona}?',        'a' => 'Cuando escribís te confirmamos el horario. En {zona} solemos llegar el mismo día. [EJEMPLO]'],
            ['q' => '¿Trabajan con garantía?',                   'a' => 'Sí. Todos los trabajos de {marca} tienen garantía por escrito. [EJEMPLO]'],
        ],
        'wsp_mensaje' => '',
        'schema_extra'=> ['additionalType' => 'https://es.wikipedia.org/wiki/Fontanería'],
    ],

];
