<?php
/**
 * Textos legales genéricos. Placeholders: {marca} {razon_social} {dominio} {email} {pais} {telefono}.
 * Uruguay: Ley 18.331 de protección de datos personales y Ley 17.250 de relaciones de consumo.
 */
return [
    'privacidad' => [
        'title'       => 'Política de privacidad – {marca}',
        'description' => 'Política de privacidad de {dominio}: qué datos recopilamos al pedir presupuesto de un galpón, para qué los usamos y cómo ejercer tus derechos.',
        'h1'          => 'Política de privacidad',
        'secciones' => [
            ['h2' => 'Responsable', 'parrafos' => ['{razon_social} ("{marca}") es responsable del tratamiento de los datos personales recogidos a través de {dominio}. Contacto: {email}.']],
            ['h2' => 'Qué datos recopilamos', 'parrafos' => ['Los datos que nos envías voluntariamente por WhatsApp, teléfono, correo o formulario (nombre, teléfono, correo, dirección y descripción del servicio) y datos de navegación anónimos a través de Google Analytics (páginas vistas, dispositivo, origen de la visita).']],
            ['h2' => 'Para qué los usamos', 'parrafos' => ['Para responder consultas, coordinar y prestar el servicio solicitado, emitir presupuestos y mejorar el sitio. No vendemos ni cedemos datos a terceros, salvo obligación legal.']],
            ['h2' => 'Cookies y analítica', 'parrafos' => ['El sitio usa Google Analytics para medir visitas y clics en los botones de contacto. Podés bloquear las cookies desde tu navegador sin que el sitio deje de funcionar.']],
            ['h2' => 'Conservación y derechos', 'parrafos' => ['Conservamos los datos el tiempo necesario para prestar el servicio y cumplir obligaciones legales. Podés pedir acceso, rectificación o eliminación escribiendo a {email}, según la Ley 18.331 de protección de datos personales de Uruguay.']],
        ],
    ],
    'terminos' => [
        'title'       => 'Términos y condiciones – {marca}',
        'description' => 'Condiciones de uso de {dominio}: cómo se arman los presupuestos de galpones, responsabilidad del constructor, garantía y legislación aplicable en Uruguay.',
        'h1'          => 'Términos y condiciones',
        'secciones' => [
            ['h2' => 'Uso del sitio', 'parrafos' => ['La información de {dominio} es orientativa. Los presupuestos se confirman por WhatsApp o correo antes de realizar cualquier trabajo.']],
            ['h2' => 'Presupuestos y pagos', 'parrafos' => ['Los presupuestos de galpones se arman a partir de las medidas, el uso y el terreno informados por el cliente y se confirman después del relevamiento. El precio, los plazos y las formas de pago quedan por escrito en la propuesta del constructor antes de empezar la obra.']],
            ['h2' => 'Garantía y responsabilidad', 'parrafos' => ['La garantía de cada obra es la que figura por escrito en la propuesta del constructor. {marca} es un sitio de contacto: la ejecución, el permiso de construcción y la garantía son responsabilidad del constructor que presenta la propuesta.']],
            ['h2' => 'Propiedad intelectual', 'parrafos' => ['Textos, imágenes y marca de este sitio pertenecen a {marca}. No se permite su reproducción sin autorización.']],
            ['h2' => 'Legislación aplicable', 'parrafos' => ['Estos términos se rigen por las leyes de {pais}, incluida la Ley 17.250 de relaciones de consumo.']],
        ],
    ],
];
