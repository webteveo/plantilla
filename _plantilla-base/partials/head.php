<?php /** <head> completo. Recibe $pagina (ver lib/pagina.php). */
$seo = $pagina['seo'];
$lang = cfg('sitio.idioma') . '-' . cfg('pais.codigo');
$geoRegion = null;
if (!empty($pagina['zona'])) { foreach (array_merge(zona_ancestros($pagina['zona']['slug']), [$pagina['zona']]) as $a) if (!empty($a['codigo'])) $geoRegion = $a['codigo']; }
?>
<!doctype html>
<html lang="<?= e($lang) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= e($seo['title']) ?></title>
  <meta name="description" content="<?= e($seo['description']) ?>">
  <meta name="robots" content="<?= e($seo['robots']) ?>">
  <meta name="author" content="<?= e(cfg('marca.nombre')) ?>">
  <meta name="theme-color" content="<?= e(cfg('colores.acento')) ?>">
  <?php if ($geoRegion): ?><meta name="geo.region" content="<?= e($geoRegion) ?>">
  <?php endif; ?>
  <meta name="geo.placename" content="<?= e($pagina['zona']['nombre'] ?? cfg('direccion.ciudad')) ?>">
  <?php if (cfg('direccion.lat') && cfg('direccion.lng')): ?>
  <meta name="geo.position" content="<?= e(cfg('direccion.lat')) ?>;<?= e(cfg('direccion.lng')) ?>">
  <?php endif; ?>
  <meta name="format-detection" content="telephone=yes">
  <?php if (cfg('analitica.site_verification')): ?>
  <meta name="google-site-verification" content="<?= e(cfg('analitica.site_verification')) ?>">
  <?php endif; ?>

  <link rel="icon" href="<?= e(url('assets/' . cfg('logos.favicon'))) ?>">
  <link rel="apple-touch-icon" href="<?= e(url('assets/' . cfg('logos.apple_touch'))) ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <?php $css = asset('css/style.css'); ?>
  <link rel="preload" as="style" href="<?= e($css) ?>">
  <link rel="stylesheet" href="<?= e($css) ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Host+Grotesk:ital,wght@0,300..800;1,300..800&display=swap">

  <meta property="og:title" content="<?= e($seo['title']) ?>">
  <meta property="og:description" content="<?= e($seo['description']) ?>">
  <meta property="og:image" content="<?= e($seo['og_image']) ?>">
  <meta property="og:url" content="<?= e($seo['canonical']) ?>">
  <meta property="og:type" content="<?= e($seo['og_type']) ?>">
  <meta property="og:locale" content="<?= e(cfg('sitio.idioma') . '_' . cfg('pais.codigo')) ?>">
  <meta property="og:site_name" content="<?= e(cfg('marca.nombre')) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($seo['title']) ?>">
  <meta name="twitter:description" content="<?= e($seo['description']) ?>">
  <meta name="twitter:image" content="<?= e($seo['og_image']) ?>">

  <link rel="canonical" href="<?= e($seo['canonical']) ?>">
  <link rel="alternate" hreflang="<?= e($lang) ?>" href="<?= e($seo['canonical']) ?>">
  <link rel="alternate" hreflang="x-default" href="<?= e($seo['canonical']) ?>">
  <link rel="sitemap" type="application/xml" href="<?= e(canonical('/sitemap.xml')) ?>">
  <?php if (cfg('imagenes.hero_mobile')): ?>
  <link rel="preload" as="image" href="<?= e(url('assets/img/' . cfg('imagenes.hero_mobile'))) ?>" media="(max-width: 600px)" fetchpriority="high">
  <?php endif; ?>

  <?php if (cfg('analitica.ga4_id')): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(cfg('analitica.ga4_id')) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= e(cfg('analitica.ga4_id')) ?>');
  </script>
  <?php endif; ?>
  <?php if (cfg('analitica.gtm_id')): ?>
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
    var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
    j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?= e(cfg('analitica.gtm_id')) ?>');
  </script>
  <?php endif; ?>

  <?php partial('schema'); ?>

  <style>:root{<?= css_colores() ?>}</style>
</head>
