# Cerrajero Montevideo — sitio web

Contenido adaptado a Cerrajero Montevideo, conservando el diseño y las plantillas existentes.

Dominio: https://cerrajero.uy. Correo de contacto: contacto@cerrajero.uy. Sin teléfono ni WhatsApp: los botones llevan al formulario. Horarios a coordinar hasta confirmar los horarios comerciales. Las imágenes de contenido se conservan; los logos se generaron desde el nuevo SVG de Cerrajero Montevideo. La cobertura principal es Montevideo; otras localidades quedan sujetas a consulta.

El transporte SMTP existente se conserva. Verificar entrega a contacto@cerrajero.uy antes de publicar; no se enviaron correos de prueba.

## Dónde se completa cada cosa

| Qué | Dónde |
|---|---|
| Nombre, teléfono, WhatsApp, email, dominio, redes, horarios, colores | `config/variables.php` (marcado con COMPLETAR) |
| Dominio para redirección https/www y sitemap | `.htaccess`, `robots.txt` |
| Logos y favicon | `public/images/logo/cerrajero-montevideo.svg` es el original vectorial. `scripts/generate-logos.cjs` genera `logo.webp/png` (color transparente, 800 px), `logo-original.png` (1650 px), `logo-blanco.webp/png` (blanco transparente), `icono.webp/png` (llave y casa, 512 × 512), `apple-touch-icon.png` (180 × 180), `favicon.ico` (16–256 px) y `og-image.png` (1200 × 630). Ejecutar con Node.js y el paquete `sharp` disponible; usar `NODE_PATH` si está instalado fuera del proyecto. |
| Home: hero, servicios, pasos, cifras, quiénes somos, precios, FAQ, CTA | `src/vista/compact/*.php` (cada archivo tiene un array al principio) |
| FAQ de la home (visible + schema) | `src/vista/compact/faq-data.php` |
| Servicios con landing por ciudad | `src/controlador/Local_Controller.php` → `CITY_SERVICES` y `contenidoServicio()` |
| Servicios extra y landing nacional asociada | `src/controlador/Local_Datos.php` → `EXTRA_SERVICES`, `NACIONAL_DE` |
| Ciudades / barrios (ya cargados: Montevideo, Canelones, Maldonado, etc.) | `Local_Controller::CITY_DATA`, `Local_Datos::CITY_DATA_EXTRA`, `ZONAS_PADRE` |
| Landings nacionales (`/cerrajero-montevideo`, etc.) | `src/controlador/Landings_Controller.php` (hay un ejemplo comentado) |
| Trabajos realizados con fotos | `data/proyectos.json` + fotos en `public/images/proyectos/` |
| Artículos / blog | `data/articulos/YYYY-MM-DD-slug.php` (copiar `_plantilla.php`) |

## Sistemas que quedaron

- **Ruteo MVC** (`src/libs/App.php`): `/controlador/metodo`, landings de un segmento y `/articulos/{slug}`.
- **Landings locales generadas**: cada servicio de `CITY_SERVICES` × cada ciudad genera `/local/{servicio}-{ciudad}` automáticamente, con schema Service + FAQPage y links relacionados. Con `CITY_SERVICES` vacío no se genera ninguna.
- **Sitemap** (`/sitemap.xml`) y **llms.txt** (`/llms.txt`): se arman solos a partir de controladores, artículos y proyectos.
- **Artículos**: listado, detalle, RSS (`/articulos/feed`), schema Article.
- **Proyectos**: listado y detalle con galería, desde `data/proyectos.json`.
- **Contacto y reseñas**: formularios por email (PHPMailer, SMTP en `variables.php`).
- **Métricas** (`/metricas`): panel privado con contraseña; datos en `data/metrics/*.ndjson` (vacío).

## Verificar en local

```bash
php -l src/controlador/Local_Controller.php
php tests/metrics-source.php
```
