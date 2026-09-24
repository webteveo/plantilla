<?php

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');

$ruta = $protocol . $host . $basePath . '/public';
$url = $protocol . $host . $basePath . '/';

// ── Empresa ──────────────────────────────────────────────────────────────────
define('EMPRESA_NOMBRE', 'Cerrajero Montevideo');
define('EMPRESA_SLOGAN', 'Cerrajería a domicilio en Montevideo');
define('EMPRESA_DESCRIPCION', 'Cerrajero en Montevideo, Uruguay. Apertura de puertas, cambio de cerraduras y cilindros, reparación e instalación de cerraduras para casas y apartamentos. Consultá por tu servicio y presupuesto.');
define('EMPRESA_NIT', '');
define('EMPRESA_RAZON_SOCIAL', 'Cerrajero Montevideo');

/** Zonas y servicios que se declaran en el schema LocalBusiness (head.php). Completar. */
define('EMPRESA_ZONAS', ['Montevideo']);
define('EMPRESA_SERVICIOS_SCHEMA', [
    'Apertura de puertas',
    'Cambio de cerraduras',
    'Cambio de cilindros y combinaciones',
    'Instalación de cerraduras de seguridad',
    'Reparación de cerraduras',
]);

// ── Contacto (COMPLETAR: telefono en formato internacional sin "+", ej. 59899123456) ──
define('CONTACTO_TELEFONO', '59894633956');
define('CONTACTO_TELEFONO_2', '');
define('CONTACTO_WHATSAPP', '59894633956');
define('CONTACTO_WHATSAPP_MENSAJE', 'Hola! Necesito un cerrajero en Montevideo.');
/** Texto unico de todos los botones de WhatsApp del sitio */
define('CTA_WHATSAPP_LABEL', 'Necesito un cerrajero');
define('CONTACTO_EMAIL', 'contacto@cerrajero.uy');
define('CONTACTO_EMAIL_CONTACTO', 'contacto@cerrajero.uy');
define('CONTACTO_EMAIL_NOREPLY', 'contacto@cerrajero.uy');

// Telefono legible (099 123 456) derivado de CONTACTO_TELEFONO. Vacio si no hay telefono.
$telefonoLocal = preg_replace('/\D/', '', CONTACTO_TELEFONO);
if (str_starts_with($telefonoLocal, '598')) {
    $telefonoLocal = '0' . substr($telefonoLocal, 3);
}
define('CONTACTO_TELEFONO_VISIBLE', $telefonoLocal !== '' ? trim(chunk_split($telefonoLocal, 3, ' ')) : '');
unset($telefonoLocal);

/**
 * Destino de todos los botones de WhatsApp del sitio.
 * - Con CONTACTO_WHATSAPP cargado: abre el chat con el mensaje prellenado.
 * - Sin numero (etapa de posicionamiento): lleva al formulario de contacto con el mensaje precargado.
 *   La URL incluye "origen=whatsapp" para que el modulo de metricas siga contando estos clicks como clicks a WhatsApp.
 */
function wsp_href(string $mensaje = CONTACTO_WHATSAPP_MENSAJE): string
{
    if (CONTACTO_WHATSAPP !== '') {
        return 'https://wa.me/' . CONTACTO_WHATSAPP . '?text=' . urlencode($mensaje);
    }
    return $GLOBALS['url'] . 'contacto?origen=whatsapp&msg=' . urlencode($mensaje) . '#contacto-form-title';
}

define('DIRECCION_CALLE', '');
define('DIRECCION_NUMERO', '');
define('DIRECCION_CIUDAD', 'Montevideo');
define('DIRECCION_DEPARTAMENTO', 'Montevideo');
define('DIRECCION_PAIS', 'UY');
define('DIRECCION_COMPLETA', 'Montevideo, Uruguay');
define('DIRECCION_ENLACE_GOOGLE_MAPS', '');
define('GEO_LAT', '-34.9011');
define('GEO_LNG', '-56.1645');

define('HORARIO_LUNES', '00:00-23:59');
define('HORARIO_MARTES', '00:00-23:59');
define('HORARIO_MIERCOLES', '00:00-23:59');
define('HORARIO_JUEVES', '00:00-23:59');
define('HORARIO_VIERNES', '00:00-23:59');
define('HORARIO_SABADO', '00:00-23:59');
define('HORARIO_DOMINGO', '00:00-23:59');
define('HORARIO_FESTIVOS', '00:00-23:59');

define('REDES_FACEBOOK', '');
define('REDES_INSTAGRAM', '');
define('REDES_INSTAGRAM_USUARIO', '');
define('REDES_TWITTER', '');
define('REDES_LINKEDIN', '');
define('REDES_YOUTUBE', '');
define('REDES_TIKTOK', '');
define('REDES_WHATSAPP', CONTACTO_WHATSAPP ? 'https://wa.me/' . CONTACTO_WHATSAPP : '');

// ── SEO (COMPLETAR: dominio real en SEO_CANONICAL_URL, sin barra final) ──────
define('SEO_TITULO_POR_DEFECTO', 'Cerrajero 24 horas en Montevideo | Aperturas y cerraduras');
define('SEO_DESCRIPCION_POR_DEFECTO', 'Cerrajero a domicilio en Montevideo, las 24 horas. Apertura de puertas y autos, cambio y reparación de cerraduras. Precio por WhatsApp antes de salir.');
define('SEO_PALABRAS_CLAVE_POR_DEFECTO', 'cerrajero montevideo, cerrajería montevideo, apertura de puertas montevideo, cambio de cerraduras montevideo, reparación de cerraduras, cerrajero a domicilio');
define('SEO_AUTOR', 'Cerrajero Montevideo');
define('SEO_CANONICAL_URL', 'https://cerrajero.uy');
define('SEO_OG_IMAGEN', 'public/images/logo/og-image.png');
define('SEO_TWITTER_IMAGEN', 'public/images/logo/og-image.png');

// ── Logos (generados desde cerrajero-montevideo.svg; ver scripts/generate-logos.cjs) ──
define('LOGO_PRINCIPAL', 'public/images/logo/logo.png');               // color, fondo transparente (schema, PNG de respaldo)
define('LOGO_HEADER_OSCURO', 'public/images/logo/logo-blanco.webp');   // todo blanco: para usar sobre fondos oscuros
define('LOGO_HEADER_CLARO', 'public/images/logo/logo.webp');           // color: header (siempre fondo blanco) y footer
define('LOGO_ICONO', 'public/images/logo/icono.webp');                 // símbolo de llave y casa, cuadrado
define('LOGO_FAVICON', 'public/images/logo/favicon.ico');
define('LOGO_FAVICON_16', 'public/images/logo/favicon.ico');
define('LOGO_FAVICON_32', 'public/images/logo/favicon.ico');
define('LOGO_APPLE_TOUCH', 'public/images/logo/apple-touch-icon.png');

// Colores tomados del logo: rojo #da1b35 y azul marino #033457
define('COLOR_PRIMARIO', '#da1b35');
define('COLOR_SECUNDARIO', '#b3152b');
define('COLOR_ACENTO', '#033457');
define('COLOR_FONDO', '#ffffff');
define('COLOR_FONDO_SECUNDARIO', '#f4f4f4');
define('COLOR_TEXTO_PRIMARIO', '#1a1a1a');
define('COLOR_TEXTO_SECUNDARIO', '#555555');
define('COLOR_BORDE', '#e0e0e0');
define('COLOR_ERROR', '#ef4444');
define('COLOR_EXITO', '#22c55e');
define('COLOR_WHATSAPP', '#25d366');

define('GOOGLE_ANALYTICS_ID', '');
define('GOOGLE_TAG_MANAGER_ID', '');
define('GOOGLE_SITE_VERIFICATION', '');
define('GOOGLE_MAPS_API_KEY', '');
define('GOOGLE_RECAPTCHA_SITE_KEY', '');
define('GOOGLE_RECAPTCHA_SECRET_KEY', '');

define('FACEBOOK_PIXEL_ID', '');
define('FACEBOOK_APP_ID', '');
define('META_TWITTER_SITE', '');

define('CHAT_WIDGET_HABILITADO', false);
define('CHAT_WIDGET_TIPO', 'whatsapp');
define('CHAT_Tidio_HABILITADO', false);
define('CHAT_TIDIO_KEY', '');
define('CHAT_MESSENGER_HABILITADO', false);
define('CHAT_MESSENGER_PAGE_ID', '');

// ── Metricas (panel privado en /metricas). Generar hash nuevo: php -r "echo password_hash('clave', PASSWORD_BCRYPT);" ──
define('METRICAS_HABILITADAS', true);
define('METRICAS_PASSWORD_HASH', '$2y$10$zKGy3JUZA5OrmVMhVCgPTOc242csJsBoOr1ptSriQzCXLaj5htQ4q');
define('METRICAS_SESSION_KEY', 'cerrajeromontevideo_metricas_auth');
define('METRICAS_IGNORAR_LOCALHOST', false); // true para no registrar visitas hechas desde localhost/XAMPP
define('METRICAS_DATA_DIR', __DIR__ . '/../data/metrics');

// ── Email saliente (SMTP) ─────────────────────────────────────────────────────
define('EMAIL_SMTP_HOST', 'smtp.gmail.com');
define('EMAIL_SMTP_USUARIO', 'correosrolb@gmail.com');
define('EMAIL_SMTP_PASSWORD', 'COMPLETAR');
define('EMAIL_SMTP_PUERTO', 587);
define('EMAIL_SMTP_SECURE', 'tls');
define('EMAIL_FROM_NOMBRE', 'Cerrajero Montevideo');
define('EMAIL_FROM_EMAIL', 'contacto@cerrajero.uy');

define('WHATSAPP_BOT_HABILITADO', false);
define('WHATSAPP_BOT_TELEFONO', '');
define('WHATSAPP_BOT_API_URL', '');
define('WHATSAPP_BOT_API_KEY', '');

define('PAYPAL_CLIENT_ID', '');
define('PAYPAL_MODO', 'sandbox');
define('MERCADOPAGO_ACCESS_TOKEN', '');
define('MERCADOPAGO_PUBLIC_KEY', '');

define('MONEDA_SIMBOLO', '$U');
define('MONEDA_CODIGO', 'UYU');
define('MONEDA_DECIMALES', 0);

define('PAIS_DEFAULT', 'UY');
define('IDIOMA_DEFAULT', 'es');
define('ZONA_HORARIA', 'America/Montevideo');

define('CACHE_HABILITADO', false);
define('CACHE_DURACION', 3600);

define('MANTENIMIENTO_HABILITADO', false);
define('MANTENIMIENTO_MENSAJE', 'Estamos en mantenimiento. Volveremos pronto.');

define('URL_PRIVACIDAD', '/resena/privacidad');
define('URL_TERMINOS', '/terminos');
define('URL_COOKIES', '/cookies');
define('URL_CONTACTO', '/contacto');

define('RESENA_GOOGLE_PROFILE_URL', '');
define('RESENA_MENSAJE_POSITIVO', 'Gracias! Nos alegra saberlo. Puedes dejarnos tu resena en Google.');
define('RESENA_MENSAJE_NEGATIVO', 'Lamentamos que no estes satisfecho. Por favor cuentanos que podemos mejorar.');
define('RESENA_MENSAJE_FORMULARIO', 'Tu opinion es muy importante para nosotros. Contanos tu experiencia y nos pondremos en contacto.');
