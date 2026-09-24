# PLANTILLA BASE — sitios de servicios locales (servicio × zona)

Plantilla en PHP puro (sin framework) para producir en serie sitios de rank and rent y de clientes en Uruguay y Argentina.
Un sitio nuevo se arma **cambiando solo datos**: `config.php`, `data/servicios.php`, `data/zonas.php`, `data/contenido/` e imágenes.
El código (`lib/`, `templates/`, `partials/`, `index.php`, `sitemap.php`) no se toca.

Requisitos: PHP 8.1+ (usa `str_starts_with`, `match` no; probado en 8.4), Apache con mod_rewrite o `php -S`.

## 1. Estructura

```
config.php                 Marca, dominio, país, contacto, WhatsApp, dirección, horario, redes, GA4, schema, logos, COLORES, SMTP, menú.
data/servicios.php         Servicios: slug, nombre, descripción, sinónimos, entidades, FAQ base, imagen, beneficios, zonas donde existe.
data/zonas.php             Zonas con jerarquía (padre): país > departamento/provincia > ciudad > barrio. Vecinos, referencias, notas, llegada.
data/contenido/
  {servicio}/{zona}.php    Contenido único de una página servicio×zona (opcional; sin archivo se usan fórmulas + texto automático).
  servicios/{servicio}.php Contenido de la página troncal /{servicio} (opcional).
  zonas/{zona}.php         Contenido del hub /{zona} (opcional).
  paginas/home.php         Hero y FAQ de la home.
  paginas/comun.php        Bloques compartidos: pasos, quiénes somos, diferenciadores, textos de secciones, cifras, reseñas reales.
  paginas/nosotros.php     Página /nosotros.
  paginas/zonas.php        Texto opcional de /zonas (índice de zonas).
  paginas/contacto.php     Página /contacto y /contacto/gracias.
  paginas/legales.php      /privacidad y /terminos (texto genérico con placeholders).
templates/                 Un archivo por tipo de página: home, servicio, servicio-zona, zona, zonas (índice), nosotros, contacto, legales, 404.
partials/                  head, header, footer, end, hero, breadcrumbs, page-intro, servicios, pasos, nosotros, diferenciadores,
                           zonas (chips), texto (secciones H2), faq, enlaces-relacionados, cta-whatsapp, testimonios, stats, schema.
lib/
  bootstrap.php            Carga config y helpers: cfg(), url(), canonical(), asset(), t() (placeholders), e(), telefono_visible().
  data.php                 Lee y valida datos: servicios(), zonas(), zona_padre(), zonas_hijas(), zona_vecinos(), combinacion_existe(),
                           zona_nombre_seo(), contenido_*(), paginas_todas().
  seo.php                  Fórmulas de title/description/H1/eyebrow/subtítulo/canonical/robots y migas de pan.
  texto.php                Texto automático (secciones) para páginas sin archivo de contenido.
  schema.php               JSON-LD: LocalBusiness (subtipo de config), WebSite, WebPage, Service, BreadcrumbList, FAQPage, Review.
  links.php                Enlazado interno: vecinas, otros servicios en la zona, padre, hub, hijas. Anchors variados por semilla.
  wsp.php                  Mensaje de WhatsApp por página, wsp_href(), wsp_attrs() (data-* para GA4), tel_attrs().
  pagina.php               pagina_armar(): combina datos + fórmulas en el array $pagina que reciben los templates.
  view.php                 render(), partial(), css_colores().
  router.php               Rutas, normalización 301, 404, robots.txt, llms.txt.
  contacto.php             POST del formulario (PHPMailer SMTP o mail()).
index.php                  Entrada única (también router para `php -S`).
sitemap.php                Sitemap dinámico; índice + sitemap-N.xml si pasa de 1.000 URLs.
.htaccess                  Rewrite a index.php, https/www (COMPLETAR dominio), headers, cache, bloqueo de carpetas internas.
assets/css/style.css       Diseño original. Sin colores fijos: todo var(--color-*) definido en config.php.
assets/js/main.js          Evento GA4 click_wsp/click_tel, menú, header, contadores, carrusel de reseñas, buscador del 404.
assets/img/                logo/, hero/, servicios/ (ver assets/img/README.md).
bin/verificar.php          Verificación: php -l, datos, títulos/H1 únicos, JSON-LD, links, HTTP 200/404/301, rastros de otro sitio.
bin/indexnow.php           Avisa a IndexNow (Bing/Copilot) las URLs del sitio. Requiere analitica.indexnow_key.
vendor/                    PHPMailer (solo para el formulario opcional).
INVENTARIO.md              Relevamiento del sitio original del que se derivó la plantilla.
```

## 2. URLs y router

| URL | Página | Existe si |
|---|---|---|
| `/` | home | siempre |
| `/{servicio}` | troncal del servicio | el slug está en `data/servicios.php` |
| `/{servicio}/{zona}` | servicio × zona | la zona está en `zonas` de ese servicio (`'*'` o lista) |
| `/{zona}` | hub de zona | la zona tiene `paginas => true` y al menos un servicio |
| `/zonas` | índice HTML de todas las zonas (sitemap navegable) | siempre |
| `/nosotros`, `/contacto`, `/privacidad`, `/terminos` | fijas | siempre (legales si `ui.legales`) |
| `/sitemap.xml` (índice), `/sitemap-paginas.xml`, `/sitemap-servicios.xml`, `/sitemap-hubs.xml`, `/sitemap-{servicio}.xml` | generados, segmentados por tipo | siempre |
| `/robots.txt`, `/llms.txt`, `/{indexnow_key}.txt` | generados | siempre / si hay clave |

Cualquier otra URL devuelve **404 real** (código 404 + página 404). Normalización con 301: barra final (`/plomero/` → `/plomero`), mayúsculas, dobles barras, `/index.php`, `/?url=...` del sitio viejo. Query strings (UTM) se conservan. Canonical = dominio de config + ruta normalizada.

Slugs reservados (no se pueden usar como servicio ni zona): nosotros, contacto, privacidad, terminos, assets, sitemap, robots, llms, index, servicios, zonas, vendor, lib, data, templates, partials, bin. Un slug no puede ser servicio y zona a la vez. `lib/data.php` lanza excepción si los datos son inválidos.

## 3. Formato de los datos

### 3.1 `config.php`
Devuelve un array. Secciones: `marca`, `sitio`, `pais`, `contacto`, `direccion`, `horario`, `redes`, `analitica`, `schema`, `logos`, `imagenes`, `colores`, `email`, `ui`. Cada clave está comentada en el archivo. Puntos clave:

- `sitio.dominio` sin barra final. Base de canonicals, sitemap, schema `@id` y OG. **También hay que escribirlo en `.htaccess`** (redirección https/www), único dato duplicado fuera de config.
- `contacto.wsp_mensaje`: plantilla del mensaje precargado en páginas servicio×zona. Por defecto `Hola, necesito {servicio} en {zona}`. Hay variantes para troncal (`wsp_mensaje_servicio`), hub (`wsp_mensaje_zona`) y páginas sin servicio ni zona (`wsp_mensaje_generico`).
- `contacto.whatsapp` vacío → todos los botones llevan a `/contacto?origen=whatsapp&msg=...` (etapa de posicionamiento sin número).
- `analitica.ga4_id` carga gtag; `evento_wsp` (default `click_wsp`) se dispara en todo `<a data-wsp>` con parámetros `servicio`, `zona`, `pagina`, `ubicacion`, `path`. Si no hay gtag pero hay `dataLayer` (GTM), se hace push del mismo evento.
- `schema.tipo`: subtipo de LocalBusiness (Locksmith, MovingCompany, Plumber, RoofingContractor, GeneralContractor, HomeAndConstructionBusiness...).
- `direccion.zona_principal`: slug de la zona que representa toda la cobertura (textos genéricos y `{zona}` en páginas sin zona).
- `colores`: nombres fijos, cambiar solo valores. Los de marca son `primario`, `primario-hover`, `secundario`, `acento`, `acento-claro`, `acento-oscuro`, `acento-fondo`, `whatsapp`. El resto son neutros y estados. Se imprimen en `:root` como `--color-{nombre}` y `--color-{nombre}-rgb` (para transparencias).
- `email.formulario` true + SMTP → aparece el formulario en el bloque de contacto. `smtp_password` nunca va al repo.

### 3.2 `data/servicios.php` — ejemplo completo
```php
'plomero' => [
    'nombre'      => 'plomero',                 // obligatorio. En frases: "plomero en Barrio Norte"
    'nombre_h1'   => 'Plomero',                 // opcional. Para títulos. Default: ucfirst(nombre)
    'descripcion' => 'Plomero a domicilio para destapaciones, pérdidas de agua y grifería. Precio por WhatsApp antes de ir.', // obligatorio
    'zonas'       => '*',                       // obligatorio. '*' = todas las zonas con páginas, o ['barrio-norte','barrio-sur']
    'excluir_zonas' => [],                      // opcional, con '*'
    'sinonimos'   => ['plomería', 'sanitario'], // opcional. Anchors variados, alternateName del schema
    'entidades'   => ['destapación', 'pérdidas de agua', 'termotanque'], // opcional. Texto automático
    'faq'         => [['q' => '¿Cuánto cuesta un {servicio} en {zona}?', 'a' => 'Depende del trabajo...']], // opcional. Se suman a las FAQ de cada página
    'beneficios'  => [['titulo' => 'Precio antes de ir', 'texto' => '...'], ...],  // opcional (3). Reemplazan a comun.diferenciadores en sus páginas
    'imagen'      => 'plomero.webp',            // opcional, relativa a assets/img/servicios/
    'imagen_alt'  => 'Plomero de {marca} en {zona}',
    'wsp_mensaje' => '',                        // opcional. Pisa contacto.wsp_mensaje
    'schema_extra'=> ['additionalType' => 'https://es.wikipedia.org/wiki/Fontanería'], // opcional
    'orden'       => 1,                         // opcional
],
```

### 3.3 `data/zonas.php` — ejemplo completo
```php
'argentina'  => ['nombre' => 'Argentina', 'tipo' => 'pais', 'padre' => null, 'paginas' => false, 'wikidata' => 'Q414'],
'buenos-aires' => ['nombre' => 'Buenos Aires', 'tipo' => 'provincia', 'padre' => 'argentina', 'paginas' => false, 'codigo' => 'AR-B'],
'la-plata' => [
    'nombre'         => 'La Plata',             // obligatorio
    'tipo'           => 'ciudad',               // obligatorio: pais | departamento | provincia | ciudad | localidad | barrio
    'padre'          => 'buenos-aires',         // obligatorio (null solo para el país)
    'paginas'        => true,                   // opcional. Default: false para 'pais', true para el resto
    'nombre_en'      => 'La Plata',             // opcional. Forma para "en ...": 'el Centro' → "en el Centro"
    'vecinos'        => ['berisso', 'ensenada'],// opcional. Default: hermanas del mismo padre
    'referencias'    => ['la Catedral', 'Plaza Moreno'], // opcional. Texto automático
    'tiempo_llegada' => '20 a 30 minutos',      // opcional
    'notas'          => 'Ciudad de cuadrícula con casas bajas en las afueras y edificios en el casco.', // opcional
    'vivienda'       => 'mixto',                // opcional: casas | apartamentos | mixto | rural | comercial
    'lat' => '-34.92', 'lng' => '-57.95',       // opcional (schema geo)
    'wikidata' => 'Q44210', 'wikipedia' => 'La Plata', // opcional (sameAs)
    'orden' => 1,
],
'barrio-norte' => ['nombre' => 'Barrio Norte', 'tipo' => 'barrio', 'padre' => 'la-plata', 'vecinos' => ['barrio-sur'], ...],
```
Si dos zonas con páginas tienen el mismo nombre, title y H1 usan "Nombre, Padre" automáticamente.

### 3.4 `data/contenido/{servicio}/{zona}.php` — todos los campos opcionales
```php
return [
    'title'         => 'Plomero en Barrio Norte: destapaciones en el día | Marca',
    'description'   => '140-158 caracteres.',
    'h1'            => 'Plomero en Barrio Norte',
    'eyebrow'       => 'Plomería a domicilio en Barrio Norte',
    'subtitulo'     => 'Bajada del hero.',
    'secciones'     => [
        ['h2' => 'Título', 'parrafos' => ['...', '...'], 'lista' => ['...']],
        ['h2' => 'Precios en Barrio Norte', 'parrafos' => ['...'],
         'tabla' => ['cabecera' => ['Trabajo', 'Desde', 'Incluye'], 'filas' => [['Destapación', '$ 2.500', 'Visita y máquina']], 'nota' => 'Vigente a septiembre de 2026'],
         'parrafos_despues' => ['...']],   // tabla: trabajos recientes, precios con fecha, tiempos de llegada
    ],
    'actualizado'   => '2026-09-01',   // "Actualizado: septiembre de 2026" visible + dateModified en el schema WebPage
    'testimonios'   => [['nombre' => 'María G.', 'texto' => '...', 'estrellas' => 5, 'fecha' => '2026-08-15', 'fuente' => 'Google']], // reseñas REALES de esta zona; pisan a las globales
    'faq'           => [['q' => '...', 'a' => '...']],
    'faq_reemplaza' => false,   // true = no sumar las FAQ base del servicio
    'wsp_mensaje'   => '',      // pisa el mensaje de WhatsApp de esta página
    'imagen'        => '',      // relativa a assets/img/ (OG + hero + schema)
    'imagen_alt'    => '',
    'noindex'       => false,
];
```
Mismo formato para `servicios/{servicio}.php` y `zonas/{zona}.php`. Placeholders en cualquier texto: `{servicio}` `{Servicio}` `{sinonimo}` `{zona}` `{zona_en}` `{zona_seo}` `{padre}` `{llegada}` `{marca}` `{marca_corta}` `{rubro}` `{Rubro}` `{ciudad}` `{telefono}` `{dominio}` `{email}` `{pais}` `{razon_social}`.

### 3.5 Fórmulas por defecto (lib/seo.php)
| Página | Title | H1 | Description |
|---|---|---|---|
| servicio×zona | `{Servicio} en {zona_seo} \| {marca}` | `{Servicio} en {zona}` | `{Servicio} en {zona}, {padre}: {descripcion del servicio}` |
| servicio | `{Servicio} \| {marca}` | `{Servicio}` | descripción del servicio |
| zona | `Servicios de {rubro} en {zona_seo} \| {marca}` | `{marca} en {zona}` | marca + lista de servicios en la zona |
| home | `sitio.titulo_home` o `{marca} \| {slogan}` | hero.titulo_em + hero.titulo | `sitio.descripcion_home` o marca.descripcion |

Los H2 salen de `secciones` (o del texto automático) y de los títulos de sección en `comun.php`. `bin/verificar.php` falla si un title, H1 o description se repite.

Si el contenido trae `title`, se usa **tal cual** (sin sufijo de marca): quien lo escribe controla el largo. Las fórmulas agregan " – {marca}" (`sitio.sufijo_title`; `false` = nunca).

## 4. Cómo agregar cosas

- **Un servicio**: agregar la entrada en `data/servicios.php`. Ya existe `/{slug}` y `/{slug}/{zona}` para todas sus zonas, aparece en home, footer, hubs, sitemap, llms.txt, schema y formulario. Opcional: `data/contenido/servicios/{slug}.php` e imagen en `assets/img/servicios/`.
- **Una zona**: agregar la entrada en `data/zonas.php` con su `padre`. Todos los servicios con `zonas => '*'` la toman solos; los que tienen lista, agregarla a la lista. Aparece en chips, hubs, enlaces de vecinas y sitemap.
- **Contenido único de una página**: crear `data/contenido/{servicio}/{zona}.php`. Lo que no se defina sigue saliendo de las fórmulas.
- **Un tipo de página nuevo**: es código (router + template). No lo hace una skill.
- **Sacar el hub de una zona**: `paginas => false` (la zona queda solo como jerarquía) o no darle servicios.

## 5. Qué toca una skill para armar un sitio nuevo

**Toca (datos):** `config.php`, `data/servicios.php`, `data/zonas.php`, `data/contenido/**`, `assets/img/**`, el dominio en `.htaccess`.
Pasos: copiar `_plantilla-base/` → completar `config.php` → cargar servicios y zonas → escribir contenido único de las páginas principales (zona padre × cada servicio, y barrios prioritarios) → reemplazar logos/OG/hero → borrar todo `[EJEMPLO]` → `php bin/verificar.php --base=http://localhost:8000 --grep="Plomero Ejemplo,plomero-ejemplo,Ciudad Ejemplo"` hasta que no haya errores ni avisos de `[EJEMPLO]`.

**No toca nunca:** `lib/`, `templates/`, `partials/`, `index.php`, `sitemap.php`, `assets/js/main.js`, `assets/css/style.css` (salvo rediseño deliberado), `vendor/`, `bin/`.

## 6. Verificación
```bash
php -S localhost:8000 index.php            # servidor local (index.php sirve también los estáticos)
php bin/verificar.php --base=http://localhost:8000 --grep="marca vieja,dominio viejo,teléfono viejo,ciudad vieja"
```
Comprueba sintaxis, datos, títulos/H1/descriptions únicos, JSON-LD válido, links internos existentes, mensaje de WhatsApp con la zona, todas las URLs del sitemap en 200, URLs inventadas en 404, redirecciones 301 y rastros del sitio anterior.

## 7. Limitaciones conocidas y pendientes

1. **Diseño**: el CSS es el del sitio original (4.800 líneas) con los colores tokenizados. Quedan secciones sin uso (`.lti-*`, `.proyectos`, `.pj-*`, `.articulo-*`, reseñas de Google) que se pueden borrar en el rediseño. Único cambio deliberado de color: los `rgba(227,30,36,…)` (rojo de otro sitio) ahora derivan de `--color-primario`. Algunos grises casi idénticos se unificaron en un mismo token (diferencias de 1-4 unidades, imperceptibles).
2. **Dominio en `.htaccess`**: hay que escribirlo a mano (Apache no lee config.php). Si no se hace, simplemente no redirige www/http.
3. **Módulos del original que no se migraron**: blog/artículos, galería de proyectos, sistema de reseñas con envío a Google, panel de métricas propio, conexión MySQL. Si hacen falta, se agregan como nuevos tipos de página.
4. **Texto automático**: es un respaldo con 3 variantes por bloque. Con cientos de barrios sin contenido propio, los textos se parecen entre sí (cambian zona, referencias, notas y llegada). Para rankear conviene escribir `data/contenido/{servicio}/{zona}.php` al menos en las zonas importantes.
5. **Reseñas y cifras**: solo se muestran si hay datos reales en `comun.php`. No hay relleno.
6. **Formulario de contacto**: opcional, depende de SMTP o `mail()` del hosting. Sin probar envío real en esta sesión.
7. **Imágenes**: los logos/OG son placeholders SVG/PNG generados. El hero no tiene foto (fondo oscuro) hasta que se cargue en `config.php`.
8. **Hreflang**: solo `{idioma}-{PAIS}` + x-default en la misma URL (sitio monolingüe).
9. **Sitemap `lastmod`**: fecha de modificación del archivo de contenido o de los datos globales; en deploys que reescriben todos los archivos, todas las fechas cambian.
10. **Rendimiento**: los datos se cargan en cada request (arrays PHP). Con 5.000 zonas sigue siendo rápido, pero no hay cache de HTML.
11. **Zona horaria**: en los comentarios de `config.php` figura `America/Montevideo` como valor de referencia para Uruguay (único rastro de esa palabra en la plantilla).
