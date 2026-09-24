# INVENTARIO del sitio original (Cerrajero Montevideo)

Relevamiento hecho antes de armar `_plantilla-base/`. El original no se modificó.

## 1. Árbol de archivos y función

```
index.php                      Entrada única: session_start, autoload, config, App::iniciar()
.htaccess                      Rewrite a index.php?url=..., https/www forzado (dominio hardcodeado), headers, cache, bloqueo de config/src/data/vendor
robots.txt                     Estático. Sitemap con dominio hardcodeado. Disallow /proyectos, /metricas/, /resena/gracias
sitemap.php                    Sitemap dinámico (servido como /sitemap.xml): home, /paginas/*, artículos, landings raíz y /local/* generadas
llms.php                       /llms.txt para crawlers de IA. Textos hardcodeados ("Idioma: español (Uruguay)")
composer.json / composer.lock  Solo PHPMailer
vendor/                        PHPMailer + autoload de composer
config/config.php              Constante URL (con doble barra), credenciales de MySQL (no se usan), session_start
config/variables.php           TODAS las constantes del sitio: empresa, contacto, dirección, horarios, redes, SEO, logos, colores, GA4/GTM/Pixel, métricas, SMTP, moneda, país, URLs legales. Define $ruta (public) y $url (base) y la función wsp_href()
data/articulos/_plantilla.php  Plantilla de artículo de blog (no hay artículos publicados)
data/metrics/*.ndjson          Registros reales de visitas del módulo de métricas propio
data/proyectos.json            Vacío ([])
public/css/style.css           4.800 líneas. Todo el diseño. Usa variables --color-* pero tiene ~45 colores fijos y ~150 rgba() literales
public/css/metrics.css         Panel de métricas
public/css/resena/resena.css   Páginas de reseñas
public/js/main.js              Menú móvil, header al hacer scroll, métricas propias (pageview + click whatsapp/tel), contadores, galería de proyectos, slideshow del hero, carrusel de reseñas
public/images/hero|logo|servicios  Imágenes de marca del cerrajero
scripts/generate-logos.cjs     Genera logos/favicon/og-image desde el SVG con sharp
src/libs/App.php               Router: /controlador/metodo, landings raíz (Landings_Controller), rutas dinámicas (articulos, proyectos), landings generadas por datos (_generada)
src/libs/Controlador.php       Clase base: cargarVista() con extract()
src/libs/Conexion.php, Database.php  PDO/MySQL. No los usa nadie
src/libs/Metrics.php           Módulo de métricas propio (23 KB)
src/controlador/Index_Controller.php      Home
src/controlador/Local_Controller.php      Landings /local/{servicio}-{zona}: CITY_SERVICES, CITY_DATA (4 barrios), contenidoServicio() con 10 servicios escritos a mano (title, description, keywords, hero, FAQ), cityLanding(), schemas Service/Breadcrumb/FAQ, links relacionados
src/controlador/Local_Datos.php           EXTRA_SERVICES (9 servicios), ZONAS_PADRE (montevideo), CITY_DATA_EXTRA (67 barrios con nombre/depto/cerca/areas)
src/controlador/Local_Barrios.php         PERFIL de cada barrio (texto, referencias, tipo de vivienda, tiempo de llegada), WIKI (Wikipedia/Wikidata), plantillas de párrafos por servicio, texto() que combina todo con un seed
src/controlador/Local_Generadas.php       Trait: registro() de todas las combinaciones servicio×zona, _generadas(), _generada()
src/controlador/Landings_Controller.php   Landings nacionales de un segmento (/cerrajero-montevideo). Vacío (ejemplo comentado)
src/controlador/Paginas_Controller.php    /paginas/servicios, /paginas/zonas, /paginas/diferenciales; /paginas/proyectos devuelve 404
src/controlador/Contacto_Controller.php   Formulario + envío por SMTP (PHPMailer)
src/controlador/Resena_Controller.php     Sistema de reseñas (form por email, redirección a Google)
src/controlador/Articulos_Controller.php  Blog: listado, detalle, RSS
src/controlador/Proyectos_Controller.php  Galería de trabajos (data/proyectos.json)
src/controlador/Metricas_Controller.php   Panel privado /metricas y endpoint collect
src/modelo/Articulos.php                  Lee data/articulos/*.php
src/vista/partials/head.php    <head> completo: metas, OG, canonical, hreflang es-UY fijo, geo.region UY-MO fijo, GA4/GTM/Pixel, schema LocalBusiness (tipo Locksmith fijo, hasOfferCatalog con '-montevideo' fijo), :root con colores
src/vista/partials/header.php  Header + menú móvil. Nota "Atención 24 horas en Montevideo" fija
src/vista/partials/footer.php  Footer con 9 links a /local/*-montevideo y 8 barrios escritos a mano + WhatsApp flotante
src/vista/partials/end.php     Config de métricas + main.js
src/vista/index/index.php      Home: hero, testimonios, servicios, stats, pasos, quienes somos, diferenciadores, zonas, FAQ, contacto. Schema WebSite + FAQPage
src/vista/local/landing.php    Landing servicio×zona: mismas secciones que la home + migas + zona-texto + relacionados. Parte el H1 en " en " y le agrega "24/7"
src/vista/compact/*.php        Secciones reutilizables. Cada una tiene su contenido escrito a mano al principio del archivo (hero, servicios, pasos, quienes-somos, diferenciadores, stats-strip, precios, faq-data, cta, zonas, zonas-home, testimonios, relacionados, zona-texto, contacto-home, stats-banner, proyectos, resenas)
src/vista/paginas/*.php        servicios, zonas, diferenciales, proyectos (páginas simples con page-intro + secciones compact)
src/vista/contacto/*.php       Formulario y gracias
src/vista/errores/404.php      404 con buscador de barrios. Textos del cerrajero
src/vista/articulos|proyectos|resena|metricas  Vistas de módulos no centrales
tests/metrics-source.php       Test del módulo de métricas
```

## 2. Cómo se generan hoy las páginas y las URLs

- `.htaccess` manda todo lo que no es archivo a `index.php?url=...`. `App::iniciar()` parte la URL en `seg0/seg1`.
- `/controlador/metodo` → `src/controlador/{Controlador}_Controller.php::{metodo}()` (ej. `/paginas/zonas`, `/contacto/enviar`).
- `/{slug}` de un segmento → método público de `Landings_Controller` (hoy no hay ninguno).
- `/articulos/{slug}` y `/proyectos/{slug}` → rutas dinámicas.
- `/local/{servicio}-{zona}` → no hay método; `Local_Generadas::registro()` arma en memoria todas las combinaciones (10 servicios × 71 zonas = 710 landings) y `_generada()` despacha `cityLanding($servicio, $zona)`.
- `cityLanding()` arma un array `$landing` con un genérico + `contenidoServicio()` (texto por servicio con `{$C}` = ciudad) y renderiza `local/landing.php`, que reutiliza las secciones de la home y suma `zona-texto.php` (párrafos únicos por barrio con `Local_Barrios::texto()`), `relacionados.php` y migas.
- Servicio y zona se separan del slug por "coincidencia más larga" (`cerrajero-a-domicilio` antes que `cerrajero`), porque el guion separa tanto palabras como servicio/zona. Frágil: un barrio que empiece igual que un servicio rompería el parseo.
- Cada sección compact carga sus propios textos al inicio del archivo y decide sola qué mostrar (`$lp_servicio`, `$lp_zona_slug`, `$hero_override`, `$faq_items`, `$zhServicio` viajan como variables globales entre archivos).
- Sitemap y llms.txt reflejan lo mismo con reflexión sobre los controladores.

## 3. Todo lo que está escrito a mano y tiene que pasar a ser variable

| Qué | Dónde está hoy |
|---|---|
| Marca, slogan, descripción, razón social | `config/variables.php` (constantes) |
| Dominio | `variables.php` (SEO_CANONICAL_URL), `.htaccess` (redirección https/www), `robots.txt` (Sitemap) |
| Teléfono y WhatsApp | `variables.php`; formato local "0xx" calculado solo para Uruguay (prefijo 598); "094 633 956" escrito en la meta description de `contacto/index.php` |
| Mensajes de WhatsApp precargados | CONTACTO_WHATSAPP_MENSAJE, hero.php, servicios.php (por card), precios.php (por card), contacto/gracias.php, `contenidoServicio()` por servicio (`"Hola! Necesito ... en {$C}"`). No hay un formato único ni evento de GA4 |
| Texto de los botones (CTA) | CTA_WHATSAPP_LABEL, más textos sueltos en hero ("Llamar Cerrajero"), zona-texto ("Escribinos por WhatsApp"), servicios (btn por card) |
| Colores | 11 constantes COLOR_* → `:root` en head.php. Pero style.css tiene ~45 hex fijos (#fff ×126, #c4131b hover ×9, #033457 ×6, grises, amarillos de estrellas, rojos de error) y ~150 rgba() literales, incluidos `rgba(227,30,36,…)` que es el rojo de OTRO sitio (#e31e24), no el primario actual. Inline: `style="background:#0a0a0a"` en hero.php, `#111` en stats-banner.php, colores de avatares en testimonios.php |
| Textos de secciones | Cada `compact/*.php`: hero (eyebrow, H1, subtítulo), servicios (4 cards), pasos (3), quienes-somos, diferenciadores (4), stats-strip (10+ años, 5000+ puertas: cifras inventadas), precios (3 cards + 2 bloques), faq-data (6 preguntas con links), cta, zonas (mapa, descripciones), testimonios (6 reseñas de ejemplo con nombres inventados que se muestran como reales cuando no hay datos) |
| Títulos, metas, keywords, H1 | Por página en cada vista (`paginas/*.php`, `contacto/*.php`, `index/index.php`, `errores/404.php`) y por servicio en `contenidoServicio()`. Home: SEO_TITULO_POR_DEFECTO. Landing: `title` del servicio con `{$C}` |
| Schema | LocalBusiness en head.php: tipo `Locksmith` fijo, `hasOfferCatalog` con URLs `-montevideo` fijas, `areaServed` con Country "Uruguay" fijo, `currenciesAccepted` UYU fijo, `inLanguage` es-UY fijo. Service en Local_Controller: `category` "Cerrajería" fijo, `containedInPlace` Montevideo/Uruguay fijos, `additionalType` Wikipedia cerrajería. Review/AggregateRating en testimonios.php |
| GA4 / GTM / Pixel | GOOGLE_ANALYTICS_ID (vacío), GTM, FB Pixel en head.php. No existe el evento `click_wsp`: main.js manda `whatsapp_click` al módulo de métricas propio |
| Dirección, geo, horarios | `variables.php`; `geo.region` "UY-MO" y `geo.placename` "Montevideo" fijos en head.php; "24 horas, todos los días" fijo en footer, header y contacto |
| Zonas | `Local_Controller::CITY_DATA` (4), `Local_Datos::CITY_DATA_EXTRA` (67), `ZONAS_PADRE` (montevideo), `Local_Barrios::PERFIL` (71 perfiles) y `WIKI`. "montevideo" aparece como literal en landing.php, relacionados.php, footer.php, 404.php, head.php, zonas-home.php (filtra depto === 'Montevideo'), sitemap.php (prioridad `_montevideo`) |
| Servicios | `CITY_SERVICES` (1) + `EXTRA_SERVICES` (9) + textos en `contenidoServicio()` (10 bloques de ~40 líneas) + plantillas de párrafos en `Local_Barrios::SERVICIO` + cards de la home + 9 links del footer escritos a mano |
| Imágenes | hero (cerrajero-mobile*.webp, con alt "Camioneta de Cerrajero Montevideo"), logo (svg/png/webp/ico/og-image), servicios (apertura, auto, cerraduras, reparacion.webp). Alt fijos "cerrajero en Montevideo" en servicios.php y quienes-somos.php |
| Sitemap | Lista fija de páginas principales en sitemap.php; no segmentado; `lastmod` = hoy en todas |
| robots.txt | Dominio fijo |
| Textos legales | Solo `resena/privacidad.php` (privacidad del sistema de reseñas). URL_TERMINOS y URL_COOKIES apuntan a páginas que no existen |
| llms.txt | "Idioma: español (Uruguay)" y estructura fijos |
| Otros restos de sitios anteriores | main.js: `mudanzasmontevideo_metrics_client_id`; style.css: variables `--isopanel`, `--chapa`, `.lti-*` (landings de otro sitio), `#e31e24` en rgba; config.php: base de datos `destoconadorauy`; settings.local.json (ya excluido) con rutas de `yesosteel` |

## 4. Problemas de SEO técnico detectados

1. **Sin normalización de URL**: `/local/cerrajero-pocitos/`, `/local/cerrajero-pocitos//`, `/index/index`, `/index`, `/?url=local/cerrajero-pocitos` devuelven 200 con el mismo contenido. El canonical apunta a la versión limpia, pero no hay 301.
2. **H1 armado a la fuerza**: `landing.php` corta `hero_title` en el último " en " y agrega "24/7" si no está. "Cerrajero a domicilio en Pocitos" queda como "Cerrajero a domicilio 24/7 / en Pocitos". Cualquier servicio de otro rubro hereda el "24/7".
3. **Title y H1 dependen solo del nombre de zona**: si dos zonas se llaman igual (Centro de dos ciudades) se duplican title, H1 y description.
4. **Schema LocalBusiness con datos fijos**: `hasOfferCatalog` apunta a `/local/{servicio}-montevideo` aunque no exista esa zona; tipo `Locksmith`; `areaServed` solo Montevideo (EMPRESA_ZONAS). `Service.areaServed` mezcla Place con strings de `areas`.
5. **Reseñas de relleno**: con `testimonios-data.php` vacío se muestran 6 reseñas inventadas con nombres de persona (sin schema, pero visibles). Riesgo de contenido engañoso.
6. **Cifras inventadas**: "10+ años", "5000+ puertas abiertas" en stats-strip, "más de 10 años" en quienes-somos.
7. **Enlaces a páginas vacías o inexistentes**: nav y footer enlazan `/articulos` (no hay artículos, página vacía indexable); `URL_TERMINOS`/`URL_COOKIES` no existen; `/resena` está enlazado desde el footer pero sus subpáginas son thin content; `zonas.php` (página /paginas/zonas) muestra iframe de Google Maps con la dirección y "Otras localidades: consultar" genérico.
8. **URLs poco limpias**: `/paginas/servicios`, `/paginas/zonas`, `/paginas/diferenciales`; landings en `/local/{servicio}-{zona}` (guion ambiguo entre servicio y zona) en lugar de `/servicio/zona`.
9. **Páginas huérfanas o casi**: `/paginas/diferenciales` solo aparece en el sitemap; `/resena/dejar-resena`, `/resena/privacidad` solo desde /resena; `/contacto/gracias` indexable con noindex correcto.
10. **Hreflang y geo fijos**: `hreflang="es-UY"`, `geo.region UY-MO` en todas las páginas aunque el sitio sea de Argentina.
11. **Canonical de la home**: `SEO_CANONICAL_URL` sin barra final (`https://cerrajero.uy`), mientras el BreadcrumbList usa `https://cerrajero.uy/`. Inconsistencia menor.
12. **OG image y logo del schema usan `$ruta`** (host de la petición), no el dominio canónico: en staging o localhost el schema declara URLs locales.
13. **JSON-LD duplicado de LocalBusiness**: head.php emite `@id #localbusiness` y testimonios.php vuelve a emitir otro bloque con el mismo `@id` cuando hay reseñas (aceptable, pero mezcla datos).
14. **Sitemap**: `lastmod` = fecha de hoy en todas las URLs (pierde valor), sin segmentación (710 URLs hoy, funciona, pero no escala a 5.000).
15. **CSS**: `preload` de una imagen mobile fija (`cerrajero-mobile-720.webp`) en todas las landings; se cargan Swiper (no se usa en ninguna vista), Material Symbols y Remixicon.
16. **Métricas propias**: cada visita hace un POST a `?url=metricas/collect`; el JS lee `window.siteMetricsConfig`. No hay GA4 configurado ni evento `click_wsp`.
17. **Servicios del formulario**: `Contacto_Controller` recibe `servicio` como texto libre; no valida contra la lista.

## 5. Decisiones tomadas para la plantilla (ver PLANTILLA.md)

- Router propio con URLs `/`, `/{servicio}`, `/{servicio}/{zona}`, `/{zona}`, `/nosotros`, `/contacto`, `/privacidad`, `/terminos`, 404 real para todo lo demás, 301 de barra final, mayúsculas, dobles barras y `index.php`.
- Módulos no centrales que NO se migran: artículos/blog, proyectos, reseñas, panel de métricas propio, base de datos. Quedan anotados como pendientes.
- Colores: se conservan los valores exactos del CSS pero todos pasan a variables definidas en `config.php`. Único cambio deliberado: los `rgba(227,30,36,…)` (rojo de otro sitio) pasan a derivar del color primario.
