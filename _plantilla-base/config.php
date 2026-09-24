<?php
/**
 * CONFIGURACIÓN DEL SITIO. Es el único archivo de configuración: una skill arma un sitio nuevo
 * completando este archivo + data/servicios.php + data/zonas.php + data/contenido/.
 *
 * Todo lo marcado [EJEMPLO] es de demostración y debe reemplazarse.
 * Los valores vacíos ('') desactivan la función correspondiente (teléfono, redes, GA4, SMTP...).
 */
return [

    // ── Marca ────────────────────────────────────────────────────────────────
    'marca' => [
        'nombre'        => 'Plomero Ejemplo [EJEMPLO]',           // Nombre comercial. Aparece en title, schema, header, footer.
        'nombre_corto'  => 'Plomero Ejemplo',                     // Para textos cortos (menú móvil, copyright).
        'slogan'        => 'Plomería a domicilio en Ciudad Ejemplo [EJEMPLO]',
        'descripcion'   => 'Plomero a domicilio en Ciudad Ejemplo. Destapaciones, pérdidas de agua, instalación de grifería y termotanques. Presupuesto por WhatsApp antes de ir. [EJEMPLO]',
        'razon_social'  => '',                                    // Opcional. Para los textos legales.
        'rubro'         => 'Plomería [EJEMPLO]',                  // Categoría genérica del rubro (schema Service.category, textos).
        'anio_inicio'   => '',                                    // Opcional. Año de fundación (schema foundingDate).
    ],

    // ── Dominio y SEO global ─────────────────────────────────────────────────
    'sitio' => [
        'dominio'        => 'https://plomero-ejemplo.com.ar',          // Sin barra final. Base de canonicals, sitemap, schema @id.
        'forzar_https'   => true,                                  // .htaccess redirige http → https y www → sin www (solo en el dominio de producción).
        'idioma'         => 'es',
        'titulo_home'    => '',                                    // Vacío = fórmula: "{marca} | {slogan}"
        'descripcion_home' => '',                                  // Vacío = marca.descripcion
        'sufijo_title'   => '',                                    // Vacío = " – {marca}"; false = sin sufijo. Solo se agrega a los title de fórmula (los escritos a mano van tal cual).
        'og_image'       => 'img/logo/og-image.png',               // Relativa a assets/. 1200×630.
        'robots_extra'   => [],                                    // Rutas extra a bloquear en robots.txt, ej. ['/privado/']
        'indexar'        => true,                                  // false = noindex en todo el sitio (staging).
    ],

    // ── País, moneda, zona horaria ───────────────────────────────────────────
    'pais' => [
        'codigo'        => 'AR',                                   // ISO 3166-1: UY, AR  [EJEMPLO: Argentina]
        'nombre'        => 'Argentina',
        'wikidata'      => 'Q414',                                 // Uruguay Q77, Argentina Q414
        'moneda'        => 'ARS',                                  // UYU, ARS
        'moneda_simbolo'=> '$',
        'zona_horaria'  => 'America/Argentina/Buenos_Aires',       // Uruguay: America/Montevideo
        'prefijo_tel'   => '54',                                   // Prefijo internacional sin "+": 598 Uruguay, 54 Argentina
        'gentilicio'    => 'argentino',
    ],

    // ── Contacto ─────────────────────────────────────────────────────────────
    'contacto' => [
        'whatsapp'       => '5491112345678',                        // Internacional sin "+" ni espacios (UY: 59891234567). Vacío = los botones llevan a /contacto.
        'telefono'       => '5491112345678',                        // Para tel: y schema. Vacío = no se muestra "Llamar".
        'telefono_visible' => '',                                   // Cómo se ve el teléfono. Vacío = se arma solo (091 234 567 / 11 1234-5678).
        'email'          => 'contacto@plomero-ejemplo.com.ar',
        // Mensaje precargado de WhatsApp. Variables: {servicio} {zona} {marca}. Se usa en cada página servicio×zona.
        'wsp_mensaje'    => 'Hola, necesito {servicio} en {zona}',
        // Mensaje cuando la página no tiene servicio ni zona (home, contacto, legales).
        'wsp_mensaje_generico' => 'Hola, necesito un plomero. [EJEMPLO]',
        'wsp_mensaje_servicio' => 'Hola, necesito {servicio}',     // Página troncal de servicio (sin zona).
        'wsp_mensaje_zona'     => 'Hola, necesito un servicio en {zona}',  // Hub de zona (sin servicio).
        'cta_label'      => 'Pedir presupuesto por WhatsApp',       // Texto de todos los botones de WhatsApp.
        'cta_label_corto'=> 'WhatsApp',
        'llamar_label'   => 'Llamar ahora',
    ],

    // ── Dirección o área de servicio ─────────────────────────────────────────
    'direccion' => [
        'tiene_local'    => false,          // false = negocio a domicilio (schema sin streetAddress, con areaServed).
        'calle'          => '',
        'numero'         => '',
        'ciudad'         => 'Ciudad Ejemplo [EJEMPLO]',            // addressLocality
        'region'         => 'Provincia Ejemplo [EJEMPLO]',         // addressRegion (departamento / provincia)
        'codigo_postal'  => '',
        'texto'          => 'Ciudad Ejemplo y alrededores [EJEMPLO]', // Cómo se muestra en footer y contacto.
        'lat'            => '-33.0000',     // Centro del área de servicio (schema geo). Vacío = no se emite.
        'lng'            => '-60.0000',
        'google_maps'    => '',             // URL de la ficha de Google Business Profile (schema hasMap + sameAs).
        'zona_principal' => 'ciudad-ejemplo', // Slug de data/zonas.php que representa "toda la cobertura" (H1 de la home, textos genéricos).
    ],

    // ── Horario ──────────────────────────────────────────────────────────────
    // Formato 'HH:MM-HH:MM' o 'Cerrado'. '00:00-23:59' = 24 horas.
    'horario' => [
        'lunes'     => '08:00-20:00',
        'martes'    => '08:00-20:00',
        'miercoles' => '08:00-20:00',
        'jueves'    => '08:00-20:00',
        'viernes'   => '08:00-20:00',
        'sabado'    => '09:00-13:00',
        'domingo'   => 'Cerrado',
        'texto'     => 'Lunes a viernes de 8 a 20, sábados de 9 a 13 [EJEMPLO]',  // Cómo se muestra. Vacío = se arma solo.
        'urgencias_24h' => false,           // true = textos de "24 horas" en header/footer.
    ],

    // ── Redes ────────────────────────────────────────────────────────────────
    'redes' => [
        'facebook'  => '',
        'instagram' => '',
        'youtube'   => '',
        'tiktok'    => '',
        'linkedin'  => '',
        'x'         => '',
    ],

    // ── Analítica ────────────────────────────────────────────────────────────
    'analitica' => [
        'ga4_id'            => '',          // G-XXXXXXXXXX. Vacío = no se carga gtag.
        'gtm_id'            => '',          // GTM-XXXXXXX. Opcional.
        'evento_wsp'        => 'click_wsp', // Nombre del evento GA4 que disparan todos los links de WhatsApp.
        'evento_tel'        => 'click_tel',
        'site_verification' => '',          // google-site-verification
        'indexnow_key'      => '',          // Clave IndexNow (32 hex). El router sirve /{clave}.txt y bin/indexnow.php avisa a Bing/Copilot.
    ],

    // ── Schema.org ───────────────────────────────────────────────────────────
    'schema' => [
        // Subtipo de LocalBusiness. Ver https://schema.org/LocalBusiness (Locksmith, MovingCompany, Plumber, Electrician,
        // RoofingContractor, HousePainter, GeneralContractor, HomeAndConstructionBusiness, ProfessionalService...).
        'tipo'            => 'Plumber',
        'price_range'     => '$$',
        'pagos'           => 'Efectivo, transferencia, tarjeta',
        'servicio_tipo'   => 'Service',     // Tipo del schema de cada página servicio×zona.
    ],

    // ── Logos e imágenes de marca (relativas a assets/) ──────────────────────
    'logos' => [
        'header'      => 'img/logo/logo.svg',          // Logo del header (fondo blanco).
        'footer'      => 'img/logo/logo.svg',
        'principal'   => 'img/logo/logo.svg',          // Para el schema (mejor PNG cuadrado o apaisado).
        'favicon'     => 'img/logo/favicon.svg',
        'apple_touch' => 'img/logo/apple-touch-icon.png',
        'ancho'       => 188, 'alto' => 52,            // Dimensiones del logo del header (evita saltos de layout).
    ],
    'imagenes' => [
        'hero_desktop'  => '',                         // Relativa a assets/img/. Vacío = fondo oscuro sin imagen.
        'hero_mobile'   => '',                         // Se muestra hasta 600px.
        'hero_mobile_2x'=> '',
        'hero_alt'      => 'Equipo de {marca} trabajando',
        'nosotros'      => '',                         // Foto del equipo en "Quiénes somos". Vacío = no se muestra la imagen.
    ],

    // ── Colores. TODOS los colores del CSS salen de acá (se imprimen como variables en :root) ──
    // Los nombres son fijos: el CSS los referencia como var(--color-xxx). Cambiar solo los valores.
    'colores' => [
        // Marca
        'primario'          => '#e0521f',   // Botones, links y acentos de acción. [EJEMPLO naranja]
        'primario-hover'    => '#c4441a',   // Hover de botones primarios.
        'secundario'        => '#b23b14',
        'acento'            => '#0f2f4f',   // Segundo color de marca: títulos de sección, iconos, fondo del footer. [EJEMPLO azul marino]
        'acento-claro'      => '#1b4a75',
        'acento-oscuro'     => '#08203a',
        'acento-fondo'      => '#eaf0f5',   // Fondo suave derivado del acento.
        'whatsapp'          => '#25d366',
        'whatsapp-hover'    => '#1ebe5d',
        // Estados
        'exito'             => '#22c55e',
        'exito-oscuro'      => '#1e8e3e',
        'error'             => '#ef4444',
        'error-oscuro'      => '#b91c1c',
        'error-fondo'       => '#fef2f2',
        'error-borde'       => '#fecaca',
        'estrella'          => '#fbbc04',
        'estrella-oscura'   => '#b8860b',
        'estrella-fondo'    => '#fffbe6',
        'estrella-clara'    => '#f5e6a3',
        // Neutros (normalmente no se tocan)
        'blanco'            => '#ffffff',
        'negro'             => '#000000',
        'negro-suave'       => '#0a0a0a',   // Fondo del hero sin imagen.
        'oscuro'            => '#141414',   // Fondos oscuros (page-intro, stats).
        'oscuro-2'          => '#111111',
        'oscuro-claro'      => '#232323',
        'texto'             => '#1a1a1a',
        'titulo'            => '#1a1a1a',   // Títulos de formularios y páginas internas.
        'texto-secundario'  => '#555555',
        'gris-oscuro'       => '#444444',
        'gris-medio'        => '#666666',
        'gris'              => '#70757a',
        'borde'             => '#e0e0e0',
        'borde-oscuro'      => '#b0b4b9',
        'fondo'             => '#ffffff',
        'fondo-secundario'  => '#f4f4f4',
        'fondo-suave'       => '#f8f8f8',
        'gris-claro'        => '#ededed',
        // Avatares de reseñas (iniciales). Se usan en orden.
        'avatares'          => ['#7b1fa2', '#1565c0', '#c0392b', '#00796b', '#a65b00', '#455a64'],
    ],

    // ── Formulario de contacto (opcional). Vacío = solo WhatsApp/teléfono/email en /contacto ──
    'email' => [
        'formulario'   => false,            // true = muestra el formulario y envía por SMTP (PHPMailer).
        'smtp_host'    => '',               // ej. smtp.gmail.com
        'smtp_usuario' => '',
        'smtp_password'=> '',               // NUNCA subir al repo con valor real.
        'smtp_puerto'  => 587,
        'smtp_secure'  => 'tls',
        'destino'      => '',               // A dónde llegan los mensajes. Vacío = contacto.email
    ],

    // ── Textos fijos de la interfaz (se pueden dejar tal cual) ───────────────
    'ui' => [
        'menu' => [                          // Menú principal. href relativo a la raíz.
            ['href' => 'servicios', 'label' => 'Servicios'],   // 'servicios' = ancla #servicios de la home
            ['href' => 'zonas',     'label' => 'Zonas'],       // página /zonas (índice de todas las zonas)
            ['href' => 'nosotros',  'label' => 'Nosotros'],
            ['href' => 'contacto',  'label' => 'Contacto'],
        ],
        'footer_nota'  => '',                // Texto corto bajo el logo del footer. Vacío = marca.descripcion
        'legales'      => true,              // Genera /privacidad y /terminos y los enlaza en el footer.
    ],
];
