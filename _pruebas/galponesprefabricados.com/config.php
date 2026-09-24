<?php
/**
 * CONFIGURACIÓN DEL SITIO galponesprefabricados.com — rank and rent sin operador todavía.
 * Los datos del negocio que no existen quedan vacíos o neutros. Ver ENTREGA.md → [COMPLETAR].
 */
return [

    'marca' => [
        'nombre'        => 'Galpones Prefabricados',
        'nombre_corto'  => 'Galpones Prefabricados',
        'slogan'        => 'Galpones prefabricados en Montevideo y Canelones',
        'descripcion'   => 'Galpones prefabricados de estructura metálica para depósito, industria y campo en Montevideo y Canelones. Presupuesto a medida por WhatsApp según medidas, uso y terreno.',
        'razon_social'  => '',                        // [COMPLETAR] razón social o nombre del operador cuando exista
        'rubro'         => 'construcción de galpones',
        'anio_inicio'   => '',                        // [COMPLETAR] solo si es real
    ],

    'sitio' => [
        'dominio'        => 'https://galponesprefabricados.com',
        'forzar_https'   => true,
        'idioma'         => 'es',
        'titulo_home'    => 'Galpones prefabricados en Montevideo y Canelones – Presupuesto a medida',
        'descripcion_home' => 'Galpones prefabricados metálicos en Montevideo y Canelones: depósitos, galpones industriales, agrícolas y tinglados. Presupuesto a medida por WhatsApp, sin cargo.',
        'sufijo_title'   => false,                     // los titles se escriben a mano en cada página
        'og_image'       => 'img/logo/og-image.png',
        'robots_extra'   => [],
        'indexar'        => true,
    ],

    'pais' => [
        'codigo'        => 'UY',
        'nombre'        => 'Uruguay',
        'wikidata'      => 'Q77',
        'moneda'        => 'UYU',
        'moneda_simbolo'=> '$U',
        'zona_horaria'  => 'America/Montevideo',
        'prefijo_tel'   => '598',
        'gentilicio'    => 'uruguayo',
    ],

    'contacto' => [
        'whatsapp'       => '',                        // [COMPLETAR] número del operador (598…). Mientras esté vacío, los botones llevan al formulario de /contacto con el mensaje precargado.
        'telefono'       => '',                        // [COMPLETAR]
        'telefono_visible' => '',
        'email'          => 'contacto@galponesprefabricados.com',
        'wsp_mensaje'    => 'Hola, necesito {servicio} en {zona}',
        'wsp_mensaje_generico' => 'Hola, quiero presupuesto para un galpón prefabricado',
        'wsp_mensaje_servicio' => 'Hola, quiero presupuesto para {servicio}',
        'wsp_mensaje_zona'     => 'Hola, quiero presupuesto para un galpón en {zona}',
        'cta_label'      => 'Pedir presupuesto por WhatsApp',
        'cta_label_corto'=> 'WhatsApp',
        'llamar_label'   => 'Llamar',
    ],

    'direccion' => [
        'tiene_local'    => false,
        'calle'          => '',
        'numero'         => '',
        'ciudad'         => 'Montevideo',
        'region'         => 'Montevideo',
        'codigo_postal'  => '',
        'texto'          => 'Montevideo y Canelones',
        'lat'            => '-34.8335',              // punto medio del área de servicio (norte de Montevideo)
        'lng'            => '-56.1600',
        'google_maps'    => '',                       // [COMPLETAR] ficha del operador, si existe
        'zona_principal' => 'montevideo',
    ],

    'horario' => [
        'lunes'     => '08:00-18:00',
        'martes'    => '08:00-18:00',
        'miercoles' => '08:00-18:00',
        'jueves'    => '08:00-18:00',
        'viernes'   => '08:00-18:00',
        'sabado'    => 'Cerrado',
        'domingo'   => 'Cerrado',
        'texto'     => 'Lunes a viernes de 8 a 18 h',   // [COMPLETAR] confirmar con el operador
        'urgencias_24h' => false,
    ],

    'redes' => ['facebook' => '', 'instagram' => '', 'youtube' => '', 'tiktok' => '', 'linkedin' => '', 'x' => ''],

    'analitica' => [
        'ga4_id'            => '',                    // [COMPLETAR] G-XXXXXXXXXX
        'gtm_id'            => '',
        'evento_wsp'        => 'click_wsp',
        'evento_tel'        => 'click_tel',
        'site_verification' => '',
        'indexnow_key'      => 'a3f1c9e2b7d84f0a9c6e5b2d1f8a7c34',
    ],

    'schema' => [
        'tipo'            => 'GeneralContractor',
        'price_range'     => '$$$',
        'pagos'           => 'Efectivo, transferencia, financiación a convenir',
        'servicio_tipo'   => 'Service',
    ],

    'logos' => [
        'header'      => 'img/logo/logo.svg',           // [COMPLETAR] logo real
        'footer'      => 'img/logo/logo.svg',
        'principal'   => 'img/logo/logo.svg',
        'favicon'     => 'img/logo/favicon.svg',
        'apple_touch' => 'img/logo/apple-touch-icon.png',
        'ancho'       => 188, 'alto' => 52,
    ],
    'imagenes' => [
        'hero_desktop'  => '',                        // [COMPLETAR] img/hero/galpon-desktop.webp (1700×956)
        'hero_mobile'   => '',                        // [COMPLETAR] img/hero/galpon-mobile.webp (720×1279)
        'hero_mobile_2x'=> '',
        'hero_alt'      => 'Galpón prefabricado de estructura metálica en Canelones',
        'nosotros'      => '',                        // [COMPLETAR] foto real del equipo o de una obra
    ],

    'colores' => [
        'primario'          => '#e8730c',
        'primario-hover'    => '#c9620a',
        'secundario'        => '#a85207',
        'acento'            => '#1f3a5f',
        'acento-claro'      => '#2f5486',
        'acento-oscuro'     => '#132540',
        'acento-fondo'      => '#eaf0f7',
        'whatsapp'          => '#25d366',
        'whatsapp-hover'    => '#1ebe5d',
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
        'blanco'            => '#ffffff',
        'negro'             => '#000000',
        'negro-suave'       => '#0a0a0a',
        'oscuro'            => '#141414',
        'oscuro-2'          => '#111111',
        'oscuro-claro'      => '#232323',
        'texto'             => '#1a1a1a',
        'titulo'            => '#1a1a1a',
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
        'avatares'          => ['#7b1fa2', '#1565c0', '#c0392b', '#00796b', '#a65b00', '#455a64'],
    ],

    'email' => [
        'formulario'   => true,                       // sin SMTP usa mail() del hosting; [COMPLETAR] SMTP si el hosting no entrega mail()
        'smtp_host'    => '',
        'smtp_usuario' => '',
        'smtp_password'=> '',
        'smtp_puerto'  => 587,
        'smtp_secure'  => 'tls',
        'destino'      => '',
    ],

    'ui' => [
        'menu' => [
            ['href' => 'servicios', 'label' => 'Galpones'],
            ['href' => 'zonas',     'label' => 'Zonas'],
            ['href' => 'nosotros',  'label' => 'Nosotros'],
            ['href' => 'contacto',  'label' => 'Presupuesto'],
        ],
        'footer_nota'  => 'Galpones prefabricados de estructura metálica en Montevideo y Canelones. Presupuesto a medida según uso, medidas y terreno.',
        'legales'      => true,
    ],
];
