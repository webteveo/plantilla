# ENTREGA — galponesprefabricados.com

Fecha: 2026-09-24 · Estado: construido y con QA en verde (`QA.md`), pendiente de diseño, imágenes y datos del operador.
Prueba de punta a punta de la skill `armar-sitio-local`. Respuestas de la entrevista asumidas como "decidí vos" salvo dominio, país y cobertura (Montevideo + Canelones).

## 1. Árbol final (117 páginas)

| Tipo | Cantidad | URL |
|---|---|---|
| Home | 1 | `/` |
| Troncales de servicio | 5 | `/galpones-prefabricados`, `/galpones-metalicos`, `/tinglados`, `/galpones-agricolas`, `/galpones-industriales` |
| Servicio principal × zona | 41 | `/galpones-prefabricados/{zona}` |
| Subservicio × zona | 24 | metálicos ×6 (montevideo, canelones, las-piedras, pando, ciudad-de-la-costa, santa-lucia) · tinglados ×6 (montevideo, canelones, las-piedras, pando, ciudad-de-la-costa, san-ramon) · agrícolas ×6 (canelones, san-ramon, tala, santa-rosa, sauce, san-jacinto) · industriales ×6 (montevideo, canelones, pando, las-piedras, barros-blancos, colonia-nicolich) |
| Hubs de zona | 41 | `/{zona}` |
| Soporte | 5 | `/zonas`, `/nosotros`, `/contacto`, `/privacidad`, `/terminos` |

Zonas con URL (41): Montevideo (hub) + 9 barrios industriales/periféricos: Bañados de Carrasco, Cerro, Colón, Lezica, Manga, Paso de la Arena, Peñarol, Punta de Rieles, Villa García. Canelones (hub) + 30 localidades: 18 de Mayo, Atlántida, Barros Blancos, Canelones (ciudad), Ciudad de la Costa, Colonia Nicolich, Empalme Olmos, Joaquín Suárez, Juanicó, La Floresta, La Paz, Las Piedras, Los Cerrillos, Migues, Montes, Pando, Parque del Plata, Paso Carrasco, Progreso, Salinas, San Antonio, San Bautista, San Jacinto, San Ramón, Santa Lucía, Santa Rosa, Sauce, Soca, Tala, Toledo.
Zonas que quedaron como mención en los hubs (sin URL): los barrios residenciales de Montevideo (Pocitos, Centro, Carrasco…, sin demanda de galpones por barrio), los balnearios de Ciudad de la Costa y los balnearios chicos de la Costa de Oro (Marindia, Neptunia, Pinamar, Las Toscas, Costa Azul, Cuchilla Alta, Jaureguiberry…). Se promueven a URL si aparecen en Search Console.

Criterio SEO aplicado (seo-local-semantico-2026): matriz completa solo para el servicio principal; subservicios con troncal + 6 zonas con demanda; tope de 30–50 páginas de zona por servicio en fase 1; cada página con datos propios de la zona (accesos, tipo de predio, usos, permisos), sin texto reciclado (similitud máxima entre páginas: 14 %).

## 2. Lo que queda para vos

### Diseño
Todo el diseño visual (CSS/JS) es el de la plantilla. Los colores salen de `config.php → colores` (naranja `#e8730c` y azul `#1f3a5f` provisorios).

### Fotos reales o imágenes generadas (ruta exacta)
- `assets/img/logo/logo.svg` (header y footer, 376×104 aprox.), `assets/img/logo/favicon.svg`, `assets/img/logo/apple-touch-icon.png` (180×180), `assets/img/logo/og-image.png` (1200×630). Hoy son placeholders con la leyenda "LOGO".
- `assets/img/hero/galpon-desktop.webp` (1700×956) y `assets/img/hero/galpon-mobile.webp` (720×1279): foto real de un galpón montado; después cargar las rutas en `config.php → imagenes.hero_desktop / hero_mobile`. Si se usa imagen generada, que sea claramente ilustrativa y con alt honesto (no "obra realizada").
- `assets/img/servicios/galpones-prefabricados.webp`, `galpones-metalicos.webp`, `tinglados.webp`, `galpones-agricolas.webp`, `galpones-industriales.webp` (1200×675) y cargar `imagen` en `data/servicios.php`.
- `assets/img/nosotros.webp` (900×1100) → `config.php → imagenes.nosotros`, solo si es foto real del equipo/obra.

### Datos marcados [COMPLETAR] (config.php)
- `config.php:13` 'razon_social'  => '',                        // [COMPLETAR] razón social o nombre del operador cuan
- `config.php:15` 'anio_inicio'   => '',                        // [COMPLETAR] solo si es real
- `config.php:42` 'whatsapp'       => '',                        // [COMPLETAR] número del operador (598…). Mientras e
- `config.php:43` 'telefono'       => '',                        // [COMPLETAR]
- `config.php:65` 'google_maps'    => '',                       // [COMPLETAR] ficha del operador, si existe
- `config.php:77` 'texto'     => 'Lunes a viernes de 8 a 18 h',   // [COMPLETAR] confirmar con el operador
- `config.php:84` 'ga4_id'            => '',                    // [COMPLETAR] G-XXXXXXXXXX
- `config.php:100` 'header'      => 'img/logo/logo.svg',           // [COMPLETAR] logo real
- `config.php:108` 'hero_desktop'  => '',                        // [COMPLETAR] img/hero/galpon-desktop.webp (1700×956)
- `config.php:109` 'hero_mobile'   => '',                        // [COMPLETAR] img/hero/galpon-mobile.webp (720×1279)
- `config.php:112` 'nosotros'      => '',                        // [COMPLETAR] foto real del equipo o de una obra
- `config.php:157` 'formulario'   => true,                       // sin SMTP usa mail() del hosting; [COMPLETAR] SMTP s

### Datos del operador que convierten formulaciones neutras en datos (cuando exista)
Brief de `seo-local-semantico-2026/assets/brief-zona.md` por zona prioritaria: tiempo/plazo real, trabajos hechos (fecha, tipo, localidad) para la tabla "Trabajos recientes", reseñas reales con nombre y fecha (`testimonios` de cada página), precios "desde" con fecha (tabla en la sección de precio), garantía, matrícula/RUT y nombre del constructor para `/nosotros`. Con el número de WhatsApp cargado, todos los botones pasan de `/contacto` a `wa.me` con el mensaje "Hola, quiero presupuesto para un galpón prefabricado en {zona}".

## 3. Pasos de lanzamiento (detalle en `armar-sitio-local/references/lanzamiento.md`)
1. Subir al hosting con PHP 8.1+ y mod_rewrite; comprobar `https://galponesprefabricados.com/` 200, `www` y `http` → 301; `robots.txt` visible.
2. **Search Console**: propiedad de dominio, enviar `https://galponesprefabricados.com/sitemap.xml` (índice con `sitemap-paginas`, `sitemap-servicios`, `sitemap-hubs`, `sitemap-galpones-prefabricados`, uno por subservicio). Inspeccionar y pedir indexación solo de: home, `/galpones-prefabricados`, `/galpones-prefabricados/montevideo`, `/galpones-prefabricados/canelones`, `/galpones-prefabricados/pando`.
3. **Bing Webmaster + IndexNow**: importar desde GSC; `php bin/indexnow.php` (la clave ya está en `config.php` y se sirve en `/a3f1…c34.txt`).
4. **GA4**: crear propiedad, cargar `analitica.ga4_id`, marcar `click_wsp` como evento clave; canal "AI Assistants" con la regex de `geo-ia.md` §4.
5. **Ficha de Google**: NO crear hasta tener operador real (rank and rent sin negocio = lead-gen no elegible, suspensión). Cuando exista: ficha del operador con categoría "Empresa de construcción" o la que use la competencia, servicios con los nombres de las 5 troncales, área de servicio Montevideo + Canelones, enlazando este sitio.
6. **Citaciones** (solo con operador): 8–10 con NAP idéntico: Google, Facebook, Instagram, Bing Places, Apple Business Connect, Cybo, Yelu, Cylex, Infoisinfo, Páginas Amarillas UY; directorios del rubro construcción.
7. **Qué zonas mirar primero** (día 30–90 en GSC y `click_wsp`): `/galpones-prefabricados/montevideo` y `/canelones` (hubs), Pando, Las Piedras, Barros Blancos, Colonia Nicolich, Ciudad de la Costa (industriales/logística) y el eje rural Sauce–Santa Rosa–San Ramón–Tala (agrícolas). Si a 60 días > 30 % de las páginas están "rastreadas, no indexadas", no sumar zonas: completar datos del operador en las que traccionan y fusionar las que no.

## 4. Probar en local
```bash
cd _pruebas/galponesprefabricados.com
php -S localhost:8000 index.php
python3 ../../armar-sitio-local/scripts/qa.py --sitio . --reporte QA.md
php bin/verificar.php --base=http://localhost:8000
```
QA actual: 163 archivos PHP sin errores, 117 URLs en 200, titles/H1/descriptions únicos, 0 huérfanas, 2.592 enlaces internos, similitud máxima 14 %, JSON-LD válido en todas, 0 errores / 13 avisos (los `[COMPLETAR]` de arriba).
