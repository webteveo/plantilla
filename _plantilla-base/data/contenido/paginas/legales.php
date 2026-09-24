<?php
/**
 * Textos legales genéricos. Placeholders: {marca} {razon_social} {dominio} {email} {pais} {telefono}.
 * Ajustar a la normativa de cada país (Uruguay: Ley 18.331; Argentina: Ley 25.326).
 */
return [
    'privacidad' => [
        'title'       => 'Política de privacidad – {marca}',
        'description' => 'Política de privacidad de {dominio}: qué datos recopilamos, para qué y cómo ejercer tus derechos.',
        'h1'          => 'Política de privacidad',
        'secciones' => [
            ['h2' => 'Responsable', 'parrafos' => ['{razon_social} ("{marca}") es responsable del tratamiento de los datos personales recogidos a través de {dominio}. Contacto: {email}.']],
            ['h2' => 'Qué datos recopilamos', 'parrafos' => ['Los datos que nos envías voluntariamente por WhatsApp, teléfono, correo o formulario (nombre, teléfono, correo, dirección y descripción del servicio) y datos de navegación anónimos a través de Google Analytics (páginas vistas, dispositivo, origen de la visita).']],
            ['h2' => 'Para qué los usamos', 'parrafos' => ['Para responder consultas, coordinar y prestar el servicio solicitado, emitir presupuestos y mejorar el sitio. No vendemos ni cedemos datos a terceros, salvo obligación legal.']],
            ['h2' => 'Cookies y analítica', 'parrafos' => ['El sitio usa Google Analytics para medir visitas y clics en los botones de contacto. Podés bloquear las cookies desde tu navegador sin que el sitio deje de funcionar.']],
            ['h2' => 'Conservación y derechos', 'parrafos' => ['Conservamos los datos el tiempo necesario para prestar el servicio y cumplir obligaciones legales. Podés pedir acceso, rectificación o eliminación escribiendo a {email}.']],
        ],
    ],
    'terminos' => [
        'title'       => 'Términos y condiciones – {marca}',
        'description' => 'Condiciones de uso del sitio {dominio} y de los servicios de {marca}.',
        'h1'          => 'Términos y condiciones',
        'secciones' => [
            ['h2' => 'Uso del sitio', 'parrafos' => ['La información de {dominio} es orientativa. Los presupuestos se confirman por WhatsApp o correo antes de realizar cualquier trabajo.']],
            ['h2' => 'Presupuestos y pagos', 'parrafos' => ['El precio informado antes de la visita se basa en la descripción del cliente. Si al llegar el trabajo difiere, se informa el nuevo precio antes de empezar. Formas de pago: según lo acordado.']],
            ['h2' => 'Garantía', 'parrafos' => ['Los trabajos realizados por {marca} tienen la garantía informada en el presupuesto. No cubre daños por mal uso ni intervenciones de terceros.']],
            ['h2' => 'Propiedad intelectual', 'parrafos' => ['Textos, imágenes y marca de este sitio pertenecen a {marca}. No se permite su reproducción sin autorización.']],
            ['h2' => 'Legislación aplicable', 'parrafos' => ['Estos términos se rigen por las leyes de {pais}.']],
        ],
    ],
];
