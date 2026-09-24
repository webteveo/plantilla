<?php
require_once 'config/variables.php';

$page_title = $page_title ?? SEO_TITULO_POR_DEFECTO;
$page_description = $page_description ?? SEO_DESCRIPCION_POR_DEFECTO;
$page_keywords = $page_keywords ?? SEO_PALABRAS_CLAVE_POR_DEFECTO;
$page_author = $page_author ?? EMPRESA_NOMBRE;
$page_canonical = $page_canonical ?? SEO_CANONICAL_URL;
$page_og_image = $page_og_image ?? SEO_OG_IMAGEN;
$page_twitter_image = $page_twitter_image ?? SEO_TWITTER_IMAGEN;
$page_type = $page_type ?? 'website';
?>
<!doctype html>
<html lang="<?= str_replace('_', '-', IDIOMA_DEFAULT . '-' . PAIS_DEFAULT) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
  <meta name="author" content="<?= htmlspecialchars($page_author) ?>">
  <meta name="robots" content="<?= htmlspecialchars($page_robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1') ?>">
  <meta name="theme-color" content="<?= COLOR_ACENTO ?>">
  <meta name="geo.region" content="UY-MO">
  <meta name="geo.placename" content="Montevideo">
  <meta name="geo.position" content="<?= GEO_LAT ?>;<?= GEO_LNG ?>">
  <meta name="ICBM" content="<?= GEO_LAT ?>, <?= GEO_LNG ?>">
  <meta name="format-detection" content="telephone=yes">

  <?php if (GOOGLE_SITE_VERIFICATION): ?>
  <meta name="google-site-verification" content="<?= GOOGLE_SITE_VERIFICATION ?>">
  <?php endif; ?>

  <link rel="icon" href="<?= $ruta ?>/<?= str_replace('public/', '', LOGO_FAVICON) ?>" type="image/x-icon" sizes="48x48">
  <link rel="shortcut icon" href="<?= $url ?>favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?= $ruta ?>/<?= str_replace('public/', '', LOGO_APPLE_TOUCH) ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="https://www.googletagmanager.com">
  <link rel="dns-prefetch" href="https://connect.facebook.net">
  <?php
    // Cache busting: si cambia el archivo, cambia la URL y el navegador lo vuelve a bajar.
    $cssVersion = @filemtime('public/css/style.css') ?: 1;
    $cssHref = $ruta . '/css/style.css?v=' . $cssVersion;
  ?>
  <link rel="preload" as="style" href="<?= $cssHref ?>">

  <link rel="stylesheet" href="<?= $cssHref ?>">
  <?php foreach ((array)($page_extra_css ?? []) as $extraCss): ?>
  <link rel="stylesheet" href="<?= htmlspecialchars($extraCss) ?>">
  <?php endforeach; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Host+Grotesk:ital,wght@0,300..800;1,300..800&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
  <meta property="og:image" content="<?= $ruta ?>/<?= str_replace('public/', '', $page_og_image) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="<?= htmlspecialchars(EMPRESA_NOMBRE . ' - ' . EMPRESA_SLOGAN) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($page_canonical) ?>">
  <meta property="og:type" content="<?= $page_type ?>">
  <meta property="og:locale" content="<?= IDIOMA_DEFAULT ?>_<?= PAIS_DEFAULT ?>">
  <meta property="og:site_name" content="<?= EMPRESA_NOMBRE ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
  <meta name="twitter:image" content="<?= $ruta ?>/<?= str_replace('public/', '', $page_twitter_image) ?>">
  <?php if (META_TWITTER_SITE): ?>
  <meta name="twitter:site" content="<?= META_TWITTER_SITE ?>">
  <?php endif; ?>

  <link rel="canonical" href="<?= htmlspecialchars($page_canonical) ?>">
  <link rel="alternate" hreflang="es-UY" href="<?= htmlspecialchars($page_canonical) ?>">
  <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($page_canonical) ?>">
  <link rel="sitemap" type="application/xml" href="<?= SEO_CANONICAL_URL ?>/sitemap.xml">
  <?php foreach ((array)($page_preload_images ?? []) as $plImg): ?>
  <link rel="preload" as="image" href="<?= htmlspecialchars($plImg['href']) ?>"<?= !empty($plImg['media']) ? ' media="' . htmlspecialchars($plImg['media']) . '"' : '' ?> fetchpriority="high">
  <?php endforeach; ?>

  <?php if (GOOGLE_ANALYTICS_ID): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GOOGLE_ANALYTICS_ID ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= GOOGLE_ANALYTICS_ID ?>');
  </script>
  <?php endif; ?>

  <?php if (GOOGLE_TAG_MANAGER_ID): ?>
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
    var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
    j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?= GOOGLE_TAG_MANAGER_ID ?>');
  </script>
  <?php endif; ?>

  <?php if (FACEBOOK_PIXEL_ID): ?>
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '<?= FACEBOOK_PIXEL_ID ?>');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= FACEBOOK_PIXEL_ID ?>&ev=PageView&noscript=1"></noscript>
  <?php endif; ?>

  <?php
  // Schema LocalBusiness construido como array y serializado con json_encode (siempre JSON valido)
  $lb = [
    '@context'    => 'https://schema.org',
    '@type'       => ['Locksmith', 'LocalBusiness'],
    '@id'         => SEO_CANONICAL_URL . '/#localbusiness',
    'name'        => EMPRESA_NOMBRE,
    'image'       => $ruta . '/' . str_replace('public/', '', LOGO_PRINCIPAL),
    'logo'        => $ruta . '/' . str_replace('public/', '', LOGO_PRINCIPAL),
    'url'         => SEO_CANONICAL_URL,
    'inLanguage'  => 'es-UY',
    'email'       => CONTACTO_EMAIL,
    'priceRange'  => '$$',
    'description' => EMPRESA_DESCRIPCION,
    'slogan'      => EMPRESA_SLOGAN,
    'currenciesAccepted' => 'UYU',
    'paymentAccepted' => 'Efectivo, transferencia, tarjeta',
    'knowsLanguage' => 'es',
    'geo'         => ['@type' => 'GeoCoordinates', 'latitude' => (float)GEO_LAT, 'longitude' => (float)GEO_LNG],
    'areaServed'  => array_map(fn($z) => ['@type' => 'City', 'name' => $z, 'containedInPlace' => ['@type' => 'Country', 'name' => 'Uruguay']], EMPRESA_ZONAS),
    'serviceType' => EMPRESA_SERVICIOS_SCHEMA,
    'address'     => [
      '@type'           => 'PostalAddress',
      'addressLocality' => DIRECCION_CIUDAD,
      'addressRegion'   => DIRECCION_DEPARTAMENTO,
      'addressCountry'  => DIRECCION_PAIS,
    ],
  ];
  if (CONTACTO_TELEFONO) { $lb['telephone'] = '+' . CONTACTO_TELEFONO; }
  if (EMPRESA_SLOGAN) { $lb['alternateName'] = EMPRESA_SLOGAN; }
  require_once 'src/controlador/Local_Controller.php';
  $lbCatalogo = [];
  foreach (Local_Controller::servicios() as $lbK => $lbN) {
    $lbCatalogo[] = ['@type' => 'Offer', 'url' => SEO_CANONICAL_URL . '/local/' . $lbK . '-montevideo', 'itemOffered' => ['@type' => 'Service', 'name' => $lbN . ' en Montevideo', 'url' => SEO_CANONICAL_URL . '/local/' . $lbK . '-montevideo', 'provider' => ['@id' => SEO_CANONICAL_URL . '/#localbusiness'], 'areaServed' => ['@type' => 'City', 'name' => 'Montevideo']]];
  }
  $lb['hasOfferCatalog'] = ['@type' => 'OfferCatalog', 'name' => 'Servicios de cerrajería', 'itemListElement' => $lbCatalogo];
  if (DIRECCION_CALLE) { $lb['address']['streetAddress'] = DIRECCION_CALLE . (DIRECCION_NUMERO ? ' ' . DIRECCION_NUMERO : ''); }
  if (DIRECCION_ENLACE_GOOGLE_MAPS) { $lb['hasMap'] = DIRECCION_ENLACE_GOOGLE_MAPS; }
  $lbHoras = [
    'Monday' => HORARIO_LUNES, 'Tuesday' => HORARIO_MARTES, 'Wednesday' => HORARIO_MIERCOLES,
    'Thursday' => HORARIO_JUEVES, 'Friday' => HORARIO_VIERNES, 'Saturday' => HORARIO_SABADO, 'Sunday' => HORARIO_DOMINGO,
  ];
  $lb['openingHoursSpecification'] = [];
  foreach ($lbHoras as $lbDia => $lbRango) {
    if ($lbRango && $lbRango !== 'Cerrado' && preg_match('/^(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})$/', $lbRango, $lbM)) {
      $lb['openingHoursSpecification'][] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $lbDia, 'opens' => $lbM[1], 'closes' => $lbM[2]];
    }
  }
  if (CONTACTO_WHATSAPP) {
    $lb['contactPoint'] = ['@type' => 'ContactPoint', 'telephone' => '+' . CONTACTO_WHATSAPP, 'contactType' => 'customer service', 'availableLanguage' => IDIOMA_DEFAULT];
  }
  $lbRedes = array_values(array_filter([DIRECCION_ENLACE_GOOGLE_MAPS, REDES_FACEBOOK, REDES_INSTAGRAM, REDES_TWITTER, REDES_LINKEDIN, REDES_YOUTUBE, REDES_TIKTOK, REDES_WHATSAPP]));
  if ($lbRedes) { $lb['sameAs'] = $lbRedes; }
  ?>
  <script type="application/ld+json"><?= json_encode($lb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
  <?php if (!empty($page_schema_blocks) && is_array($page_schema_blocks)): ?>
    <?php foreach ($page_schema_blocks as $schemaBlock): ?>
      <script type="application/ld+json"><?= json_encode($schemaBlock, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endforeach; ?>
  <?php endif; ?>

  <style>
    :root {
      --color-primario: <?= COLOR_PRIMARIO ?>;
      --color-secundario: <?= COLOR_SECUNDARIO ?>;
      --color-acento: <?= COLOR_ACENTO ?>;
      --color-fondo: <?= COLOR_FONDO ?>;
      --color-fondo-secundario: <?= COLOR_FONDO_SECUNDARIO ?>;
      --color-texto: <?= COLOR_TEXTO_PRIMARIO ?>;
      --color-texto-secundario: <?= COLOR_TEXTO_SECUNDARIO ?>;
      --color-borde: <?= COLOR_BORDE ?>;
      --color-error: <?= COLOR_ERROR ?>;
      --color-exito: <?= COLOR_EXITO ?>;
      --color-whatsapp: <?= COLOR_WHATSAPP ?>;
    }
  </style>
</head>
