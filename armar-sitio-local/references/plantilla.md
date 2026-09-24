# La plantilla base, resumida (leer PLANTILLA.md completo si algo no cierra)

Estructura de un sitio = copia de `_plantilla-base/`. Todo se arma con datos; el código es fijo.

## Qué se toca (una skill trabaja solo acá)

| Archivo | Qué es |
|---|---|
| `config.php` | Array de configuración: marca, sitio, país, contacto, dirección, horario, redes, analítica, schema, logos, imágenes, colores, email, ui. Cada clave comentada. |
| `.htaccess` | Reemplazar `mi-dominio.uy` por el dominio real (dos veces). Único dato fuera de config. |
| `data/servicios.php` | Servicios (slug → datos). |
| `data/zonas.php` | Zonas con jerarquía (`padre`). Se genera con `scripts/zonas.py generar` y se retoca. |
| `data/contenido/{servicio}/{zona}.php` | Contenido único de cada página servicio × zona. |
| `data/contenido/servicios/{servicio}.php` | Contenido de la troncal `/{servicio}`. |
| `data/contenido/zonas/{zona}.php` | Contenido del hub `/{zona}`. |
| `data/contenido/paginas/home.php`, `comun.php`, `nosotros.php`, `contacto.php`, `legales.php`, `zonas.php` | Páginas fijas y bloques compartidos. |
| `assets/img/**` | Logos, hero, imágenes de servicios (ver `assets/img/README.md`). |

## Qué no se toca nunca

`lib/`, `templates/`, `partials/`, `index.php`, `sitemap.php`, `assets/css/style.css`, `assets/js/main.js`, `vendor/`, `bin/`. Y la carpeta `_plantilla-base` original.

## URLs que genera

`/` · `/{servicio}` · `/{servicio}/{zona}` (solo combinaciones declaradas en `zonas` del servicio) · `/{zona}` (hub, si la zona tiene páginas y algún servicio) · `/zonas` · `/nosotros` · `/contacto` · `/privacidad` · `/terminos` · `/sitemap.xml` (índice) + `/sitemap-{paginas|servicios|hubs|servicio}.xml` · `/robots.txt` · `/llms.txt`. Todo lo demás: 404 real. Barra final, mayúsculas y `index.php` → 301.

Slugs reservados (no usar como servicio ni zona): nosotros, contacto, privacidad, terminos, assets, sitemap, robots, llms, index, servicios, zonas, vendor, lib, data, templates, partials, bin. Un slug no puede ser servicio y zona a la vez.

## Formato de `data/servicios.php`

```php
'galpones-prefabricados' => [
    'nombre'      => 'galpones prefabricados',      // como se dice en la frase "… en Pando"
    'nombre_h1'   => 'Galpones prefabricados',       // para títulos
    'descripcion' => '1–2 frases sin cifras inventadas.',
    'zonas'       => '*',                            // principal: todas las zonas; secundarios: ['montevideo','canelones','pando']
    'sinonimos'   => ['galpones metálicos', 'tinglados', 'galpones industriales'],
    'entidades'   => ['estructura metálica', 'chapa trapezoidal', 'platea de hormigón', 'perfiles C', ...],  // ≥ 8
    'faq'         => [['q' => '¿…{servicio} en {zona}?', 'a' => '…']],   // 3–4 base; se suman a las de cada página
    'beneficios'  => [['titulo' => '…', 'texto' => '…'], …],            // 3, reales o neutros
    'imagen'      => '', 'imagen_alt' => '…{marca}… en {zona}',
    'wsp_mensaje' => '',                              // vacío = el de config
    'schema_extra'=> [],
],
```

## Formato de `data/zonas.php` (lo genera `zonas.py`; retocar)

```php
'pando' => [
    'nombre' => 'Pando', 'tipo' => 'ciudad', 'padre' => 'canelones',
    'nombre_en' => '',                        // "en Pando"; para "en el Centro" poner 'el Centro'
    'vecinos' => ['barros-blancos', 'toledo', 'empalme-olmos'],
    'referencias' => ['Ruta 8', 'Ruta 75', 'zona industrial'],
    'tiempo_llegada' => '',                   // solo si el usuario lo dio
    'notas' => 'Hechos públicos de la zona relevantes al servicio (1–2 frases).',
    'vivienda' => 'mixto',                    // casas | apartamentos | mixto | rural | comercial
    'codigo' => 'UY-CA',                      // solo departamentos/provincias
],
```
`tipo`: pais | departamento | provincia | ciudad | localidad | barrio. `paginas => false` en país y en niveles que solo son jerarquía. Si dos zonas se llaman igual, la plantilla desambigua title/H1 con el padre.

## Formato de un archivo de contenido (todos los campos opcionales)

```php
return [
    'title'       => '…',            // 45–58 caracteres, se usa tal cual (sin sufijo)
    'description' => '…',            // 120–150
    'h1'          => '…',            // ≤ 70, misma keyword + zona que el title
    'eyebrow'     => '…',            // línea chica sobre el H1
    'subtitulo'   => '…',            // bajada del hero (1–2 frases)
    'secciones'   => [
        ['h2' => '…', 'parrafos' => ['…', '…'], 'lista' => ['…'],
         'tabla' => ['cabecera' => ['…'], 'filas' => [['…','…']], 'nota' => 'Vigente a …'],
         'parrafos_despues' => ['…']],
    ],
    'faq'         => [['q' => '…', 'a' => '…']],   // propias de la página; se suman a las base del servicio
    'faq_reemplaza' => false,
    'actualizado' => '2026-09-24',                // fecha visible + dateModified
    'testimonios' => [],                          // solo reseñas reales de esta zona
    'wsp_mensaje' => '',                          // pisa el mensaje de WhatsApp de esta página
    'imagen' => '', 'imagen_alt' => '',
];
```
Placeholders válidos en cualquier texto: `{servicio}` `{Servicio}` `{sinonimo}` `{zona}` `{zona_en}` `{zona_seo}` `{padre}` `{llegada}` `{marca}` `{marca_corta}` `{rubro}` `{Rubro}` `{ciudad}` `{telefono}` `{dominio}` `{email}` `{pais}`. HTML permitido dentro de párrafos, listas y FAQ: `<a>`, `<strong>`, `<em>` (para enlaces contextuales usar rutas relativas: `<a href="/galpones-prefabricados/las-piedras">`).

Lo que no se define sale de fórmulas: title `{Servicio} en {zona} – {marca}`, H1 `{Servicio} en {zona}`, description `{Servicio} en {zona}, {padre}: {descripcion del servicio}`, texto automático de 3 párrafos con notas/referencias/vecinas. **Las fórmulas son respaldo, no contenido**: el objetivo es que cada página tenga archivo propio.

## `paginas/comun.php`

pasos (3), nosotros_lead/texto, diferenciadores (4), stats (vacío si no hay cifras reales), testimonios (vacío si no hay reales), textos de sección (`servicios_titulo`, `zonas_titulo`, `faq_lead`, `contacto_lead`, `relacionados_*`). Admite placeholders.

## Lo que la plantilla ya hace sola

Canonical, robots, hreflang, OG; schema LocalBusiness (subtipo de config) + WebSite + WebPage + Service (areaServed = zona con containedInPlace) + BreadcrumbList + FAQPage con `@id` consistentes y sin aggregateRating; migas; enlaces relacionados (vecinas, otros servicios, padre, hub, troncal, hijas) con anchors variados; chips de zonas; botones de WhatsApp con `data-wsp`, mensaje por página y evento GA4 `click_wsp` (params servicio, zona, pagina, ubicacion); sitemap segmentado; robots.txt; llms.txt; página 404 con buscador de zonas; `/zonas`.

## Verificar

```bash
cd <sitio> && php -S 127.0.0.1:8000 index.php &
php bin/verificar.php --base=http://127.0.0.1:8000 --grep="Plomero Ejemplo,plomero-ejemplo,Ciudad Ejemplo"
python3 <skill>/scripts/qa.py --sitio <sitio> --reporte QA.md
php bin/indexnow.php        # después de publicar, con analitica.indexnow_key cargada
```

## Errores que aparecieron en la prueba y cómo evitarlos

- El title de la home de `config.php → sitio.titulo_home` también cuenta para el largo (≤ 58): no repetir "Presupuesto a medida" si no entra.
- `.htaccess`: reemplazar el dominio en las dos líneas `RewriteCond` (el comentario puede quedar).
- `bin/verificar.php --grep` no revisa `PLANTILLA.md` ni `INVENTARIO.md`; `qa.py` no revisa `bin/`.
- Los hubs (`zonas/{zona}.php`) necesitan title y H1 propios ("Galpones en {Zona} – …", "Galpones y tinglados en {Zona}"); si se dejan las fórmulas compiten con la página servicio × zona.
- Zonas seleccionadas sin vecinas dentro de la selección (ej. Cerro con vecinos fuera de Montevideo elegido): la plantilla enlaza a las hermanas del mismo padre; no hace falta editar `vecinos`.
