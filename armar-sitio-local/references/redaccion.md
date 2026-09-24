# Brief de redacción de una página (lo que recibe quien escribe, sea Claude o un subagente)

Objetivo: un archivo `data/contenido/{servicio}/{zona}.php` (o `servicios/{servicio}.php`, `zonas/{zona}.php`) que cumpla las reglas de `seo-local-semantico-2026` **sin inventar datos del negocio**. Antes de escribir, leer de esa skill: `references/on-page-formulas.md` §3 (fórmulas y ejemplos), `references/location-pages.md` §4 (bloques locales), `references/eeat-servicios.md` §3 (reglas duras), `references/geo-ia.md` §3 (pasajes citables). Si no se pueden abrir, aplicar el resumen de abajo.

## Entradas que tiene que traer el brief

- Config resumida: marca, dominio, servicio (nombre, nombre_h1, sinónimos, entidades, descripción), tipo de cliente, país, ciudad base, mensaje de WhatsApp, horario real, diferenciales reales (o "sin datos").
- Fila de la zona: nombre, tipo, padre, vecinas (slugs y nombres), referencias, vivienda, notas, tiempo_llegada (si existe), prioridad.
- Qué zonas vecinas y qué troncal enlazar en el cuerpo (2–4 enlaces contextuales).
- Lo que dijo la SERP/PAA para ese servicio (3–6 preguntas reales de la gente).
- Ruta de salida del archivo.

## Mapeo bloque de la skill de SEO → campo de la plantilla

| Bloque (location-pages §4 / on-page §3c) | Campo | Regla corta |
|---|---|---|
| Title | `title` | 45–58 caracteres; servicio + zona en los primeros 30; un gancho propio de la zona (acceso, tipo de terreno, plazo real si existe); guion como separador; sin marca si no entra; sin pipe, teléfono ni emoji. Nunca el mismo gancho en dos zonas. |
| Meta description | `description` | 120–150; propuesta concreta en los primeros 120; "por WhatsApp"; precio "desde" solo si es real y con fecha; sin teléfono. |
| H1 | `h1` | ≤ 70; misma keyword + zona que el title + promesa cumplible. |
| Eyebrow / bajada del hero | `eyebrow`, `subtitulo` | Rubro + zona; bajada de 1–2 frases con lo concreto (qué se hace, cómo se coordina). |
| Primer párrafo answer-first (40–70 palabras) | `secciones[0]` (`h2` + primer párrafo) | Qué, dónde, cómo se coordina, desde dónde se atiende, CTA, marca nombrada, **un dato que solo aplique a esa zona**. El H2 en forma de pregunta o "{Servicio} en {Zona}: cómo trabajamos". |
| Servicios en esta zona | `secciones[]` con `lista` | Solo los subservicios que se dan ahí; enlace `<a>` a la troncal cuando exista. |
| Trabajos recientes en {zona} | `secciones[]` con `tabla` | **Solo con datos reales** (fecha, trabajo, referencia, tiempo). Sin datos: se omite el bloque, no se inventa. |
| Reseñas de la zona | `testimonios` | Solo reales (nombre, texto, estrellas, fecha, fuente). Sin datos: vacío. |
| Cómo llegamos / cobertura | `secciones[]` | Por qué rutas o avenidas se llega, referencias reales del JSON, tipo de vivienda o industria y qué cambia en el trabajo, subzonas cubiertas desde esa página (en prosa, sin lista de barrios). Tiempo de llegada solo si el usuario lo dio. |
| Precios | `secciones[]` con `tabla` + `nota` con fecha | Con datos: tabla "desde" con fecha de vigencia y qué incluye. Sin datos: sección "Qué define el precio en {zona}" con los factores (tamaño, terreno, accesos, permisos) sin cifras. |
| Proceso | lo cubre `comun.pasos` | No repetir en la página salvo que cambie por zona. |
| FAQ local (3–5) | `faq` | Preguntas reales (PAA, WhatsApp del usuario, lógica de la zona); respuestas de 2–4 líneas; al menos 2 mencionan la zona o algo propio de ella; sin prometer lo no confirmado. |
| Zonas cercanas | automático + 2–3 enlaces contextuales en párrafos | Anchor natural dentro de una oración: "también hacemos galpones en <a href="/galpones-prefabricados/barros-blancos">Barros Blancos</a> y Toledo". |
| Fecha de actualización | `actualizado` | Fecha del día en que se escribe (ISO). |

## Qué hace única a una página sin datos del operador

Hechos públicos y verificables de la zona, cruzados con el servicio:
- Geografía y accesos: rutas, avenidas, distancia a la ciudad base, cómo entra un camión o un equipo.
- Tipo de construcción o uso del suelo: torres con portería, casas con jardín, quintas, zona industrial, balneario con casas cerradas fuera de temporada, campo.
- Qué exige la intendencia o el municipio para ese servicio (permisos de construcción, habilitación de galpones, horarios de carga y descarga en CABA), siempre como orientación y sin citar números de expediente inventados.
- Problema típico derivado de lo anterior (humedad en zonas bajas, salinidad en la costa, calles de arena, terrenos con pendiente).
- Referencias reales del JSON de zonas (plazas, rutas, comercios conocidos) usadas en frases, no en listas.

Lo que **no** puede aparecer sin confirmación: tiempos de llegada, precios, años, cantidad de trabajos, nombres de técnicos, reseñas, "24 horas", "llegamos en 20 minutos", "más de N clientes". Se reemplaza por: "coordinamos día y horario por WhatsApp", "te pasamos el presupuesto antes de empezar", "trabajamos en {zona} y alrededores".

## Estilo

- Español rioplatense, voseo, frases cortas, párrafos de 2–4 oraciones. Sin "líder", "excelencia", "soluciones integrales", "cerca de mí".
- Servicio + zona en el texto visible 3–5 veces, natural. Variantes y jerga en H2/H3/FAQ.
- Cada sección autocontenida (100–300 palabras), sin "como dijimos arriba".
- Nombrar la marca en el primer párrafo y en al menos una FAQ (la IA cita el pasaje con la marca).
- Longitud objetivo del texto propio (secciones + FAQ): servicio × zona 350–600 palabras; troncal 600–900; hub de zona 250–400. Más largo no es mejor.
- Ganchos de title distintos por zona: rotar entre acceso/ruta, tipo de terreno o vivienda, subzonas cubiertas, plazo real (si existe), permisos, "presupuesto por WhatsApp".

## Test antes de guardar

1. Si borro el nombre de la zona, ¿la página sigue reconocible como de esa zona? Si no, faltan datos propios.
2. ¿Hay algún número, fecha o promesa que el usuario no confirmó? Sacarlo.
3. ¿Title 45–58, H1 ≤ 70, description 120–150, un solo H1, ≥ 3 H2, ≥ 3 FAQ?
4. ¿2–4 enlaces contextuales a vecinas/troncal con anchors distintos?
5. ¿`actualizado` con fecha de hoy?

## Plantilla del brief para una tanda (llenar y guardar fuera del sitio; se pasa a cada redactor)

```
# Brief — {dominio} ({rank and rent | cliente}, {país}, {con/sin operador})
Sitio: {ruta}. NO tocar lib/, templates/, partials/, config.php, data/servicios.php, data/zonas.php; solo crear los archivos asignados.
Leer antes: {ruta skill}/references/redaccion.md · {ruta skill}/references/plantilla.md §formato · seo-local-semantico-2026/references/on-page-formulas.md §1–3 · location-pages.md §1 y §4
Datos: data/servicios.php (nombre, sinónimos, entidades, faq base, 'zonas' de cada servicio) · data/zonas.php (nombre exacto, vecinos, referencias, vivienda) · {ruta skill}/assets/zonas-{uy|caba-amba}.json

## Marca y negocio (lo único que se puede afirmar)
- Marca, rubro, cobertura, cómo se coordina (WhatsApp, formulario), horario real.
- Cómo trabaja (proceso neutro válido para cualquier operador del rubro).
- NO EXISTEN y NO se escriben: {años, cantidad de trabajos, técnicos, reseñas, precios, plazos, tiempos de llegada, "24 hs", direcciones, marcas, garantías…}. Sustitutos: "presupuesto por WhatsApp", "se define en el presupuesto según…".
- SÍ se puede escribir de cada zona: {accesos y rutas, tipo de predio/vivienda, usos típicos del servicio ahí, qué intendencia/municipio da el permiso, referencias reales del JSON}. Si no estás seguro de una referencia, no la uses.

## Formato de salida
Archivo PHP `<?php return [...]` con: title (45–58; keyword + zona en los primeros 30; gancho DISTINTO por zona; guion; sin marca si no entra), description (120–150, "por WhatsApp" en los primeros 120), h1 (≤ 70), eyebrow, subtitulo, secciones (3–5: answer-first con marca + dato propio; cómo llegamos / qué encontramos con 2–3 vecinas enlazadas; precio-factores o usos; permisos o subservicios), faq (3–4 propias, distintas de las base), actualizado (hoy).
Enlaces: 1 troncal (/{servicio}), 1 hub (/{zona}), 2–3 vecinas (/{servicio}/{vecina}) solo con combinaciones existentes; anchors distintos entre páginas.
Largo del texto propio: servicio × zona 350–600; hub 250–400; troncal 600–900. Español rioplatense, sin relleno. Cada archivo pasa `php -l`.

## Asignación
Servicio {slug} × zonas: {lista de slugs}. Salida: data/contenido/{servicio}/{zona}.php
Reportar al terminar: archivos, title y caracteres, qué datos no pudiste afirmar.
```

Notas de la prueba de punta a punta (galponesprefabricados.com, 117 páginas, 13 tandas en paralelo): con este brief los redactores produjeron páginas con similitud máxima 14 % entre sí y cero enlaces rotos; los avisos típicos fueron titles con la zona adelante ("Pando: galpones…", válido) y referencias geográficas que el redactor marcó como "a confirmar": pasarlas a ENTREGA.md tal cual las reporta.
