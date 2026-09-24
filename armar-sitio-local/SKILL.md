---
name: armar-sitio-local
description: "Arma un sitio completo de servicios locales (rank and rent o cliente) a partir de la plantilla PHP `_plantilla-base`: entrevista al usuario con preguntas de opción múltiple, investiga la SERP, diseña el árbol servicio × zona con seo-local-semantico-2026 y cheloseo, copia la plantilla, completa config y datos, escribe el contenido de todas las páginas, corre el QA automático y entrega ENTREGA.md con lo que falta y el plan de lanzamiento. Usar SIEMPRE que el usuario diga \"armá un sitio nuevo\", \"nuevo rank and rent\", \"hacé la web de [nicho] en [zona]\", \"usá la plantilla para…\", \"levantá un sitio de [servicio]\", \"quiero un sitio para [oficio] en [ciudad]\", o pida producir un sitio de servicio local desde cero, aunque no nombre la plantilla ni el SEO. No usar para retocar una página suelta ni para auditar un sitio ya publicado (eso es seo-local-semantico-2026)."
---

# armar-sitio-local

Produce un sitio listo para publicar a partir de `_plantilla-base` cambiando **solo datos** (config, servicios, zonas, contenido, imágenes). El usuario después hace el diseño. La plantilla base nunca se modifica: se copia.

Esta skill **no contiene reglas de SEO**. En cada paso le dice a Claude qué archivo de `seo-local-semantico-2026` (cómo estructurar y escribir) y de `cheloseo` (qué importa primero) tiene que leer. Así, cuando esas skills se actualizan, esta mejora sola. Antes de empezar, ubicar las tres cosas:

1. `_plantilla-base/` (buscar `PLANTILLA.md` en el directorio actual, sus padres o sus hijos; si no aparece, preguntar la ruta). Leer `references/plantilla.md` de esta skill: resume qué archivos se tocan y en qué formato.
2. La skill `seo-local-semantico-2026` (leer su `SKILL.md`; sus references se leen por fase, ver tabla abajo).
3. La skill `cheloseo` (leer su `SKILL.md`; `references/factores.md` solo si hay que decidir prioridades).

| Fase | Leer de seo-local-semantico-2026 | Leer de cheloseo |
|---|---|---|
| 1 Entrevista | `SKILL.md` (principios y reglas duras) | `SKILL.md` flujos A y B |
| 2 Investigación | `references/topical-map.md`, `references/location-pages.md` §1–2 y §5, `references/zonas-rioplatenses.md`, `references/entidades-y-schema.md` §2–3 | `SKILL.md` flujo A (evaluar nicho) |
| 3 Construcción | `references/on-page-formulas.md`, `references/location-pages.md` §3–4, `references/eeat-servicios.md`, `references/geo-ia.md` §3, `assets/brief-zona.md` | `references/factores.md` §4–5 si hay dudas de contenido |
| 4 QA | `references/qa-checklist.md` | — |
| 5 Entrega | `references/geo-ia.md` §2 y §4, `references/location-pages.md` §6 | `SKILL.md` flujo D (ficha) y E (enlaces) |

Si una regla de esta skill choca con la de SEO, gana la de SEO (es la que se actualiza). Si choca con una regla dura de abajo (honestidad, un servicio por página, no tocar diseño), gana la regla dura.

## Reglas duras

- **Honestidad.** Nada de reseñas inventadas, direcciones falsas, años o certificaciones inventadas, cifras ("+500 trabajos"), ni "24 horas" si no es real. Donde falta un dato real: formulación neutra en la página y `[COMPLETAR]` en `config.php`/datos + lista en ENTREGA.md. Las páginas públicas nunca muestran `[COMPLETAR]`.
- **Una página por servicio × zona.** Nunca se juntan dos servicios en una URL. Las *variantes* de un mismo servicio se fusionan (lo decide el topical map).
- **Cero contenido duplicado entre zonas.** Cada página servicio × zona tiene datos propios (geografía, tipo de construcción, accesos, problema típico, FAQ local). Sinónimos y párrafos reordenados no cuentan. Si la escala elegida no permite eso, se avisa en el punto de control con una alternativa (menos URLs, resto como secciones en la zona madre).
- **No tocar el diseño.** Solo los colores de `config.php`. No se editan `templates/`, `partials/`, `lib/`, `assets/css`, `assets/js`.
- **Idioma.** Español rioplatense (vos); jerga del oficio y del país (UY: "durlock", "tinglado", "cerrajero", "flete"; AR: "plomero", "gasista matriculado", "flete"); nombres reales de barrios, avenidas y rutas. Sin "líder", sin "cerca de mí" en títulos, sin listas de barrios como texto.
- **Datos públicos sí, datos del negocio no.** Se puede afirmar lo verificable sobre la zona (dónde queda, por qué rutas se llega, qué tipo de vivienda o industria predomina, qué exige la intendencia). No se afirma nada del negocio que el usuario no haya confirmado.

## Fase 1 — Entrevista

Herramienta: preguntas de opción múltiple (`AskUserQuestion`), rondas de hasta 4 preguntas. En cada pregunta que lo admita, incluir la opción **"Decidí vos"** (y hacerla la recomendada cuando haya una respuesta obvia). Lo que el usuario ya dijo en su mensaje no se vuelve a preguntar: se confirma en una línea al mostrar el árbol. Banco de preguntas, orden y cómo se usa cada respuesta: `references/preguntas.md`.

Rondas típicas (saltar lo ya respondido):
1. Identidad: dominio · marca · rank and rent o cliente · mercado (UY / AR / otro).
2. Nicho: servicio principal · lista de servicios (o "decidí vos": se propone en la fase 2 a partir del topical map y la SERP, y se confirma en el punto de control) · tipo de cliente (hogares, empresas, campo).
3. Cobertura: departamentos/provincias · hasta qué nivel bajar. **Antes de preguntar el nivel, correr** `python3 scripts/zonas.py contar --pais uy|ar --raices <slugs> --nivel <nivel> --servicios <n>` para cada opción y poner la cantidad de páginas en la etiqueta de cada opción ("Barrios (≈118 zonas → ≈240 páginas con 2 servicios)").
4. Contacto: WhatsApp · teléfono · email · dirección física o área de servicio · horario real.
5. Diferenciales reales: años · garantía · tiempo de llegada · precios de referencia · pago · matrícula/certificación. Rank and rent sin datos → "sin datos, usar formulaciones neutras".
6. Técnico: GA4 · colores · logo y fotos reales · carpeta destino.
7. Competencia conocida (opcional).

Al terminar, resumir en 8 líneas lo recibido y pasar a la fase 2 sin esperar.

## Fase 2 — Investigación y árbol SEO

1. **SERP** (búsqueda web, 6–10 consultas: "[servicio] [ciudad]", "[servicio] [departamento]", "[subservicio] [ciudad]", "cuánto cuesta [servicio] [país]", "[servicio] precio m2 [país]"; agregar "Uruguay"/"Argentina" a cada consulta porque el buscador no acepta `gl=`). Anotar: dominios del top 5 (¿EMD, marca, MercadoLibre/directorio?), si tienen páginas por zona, patrón de title/H1, preguntas de PAA y búsquedas relacionadas. Si un directorio o marketplace domina, decirlo: cambia la expectativa (cheloseo flujo A).
2. **Entidad central y topical map** con `topical-map.md` §2: Source Context, Central Entity, Central Search Intent, 12–20 atributos EAV, query templates, core/outer. Dimensionar según `topical-map.md` §2.9.
3. **Servicios finales**: servicio principal (matriz completa × zona) + subservicios (troncal + solo zonas top, `location-pages.md` §2.4). Nombres en singular tal como se buscan.
4. **Zonas finales**: partir del JSON de `assets/` (`zonas-uy.json` o `zonas-caba-amba.json`; verificado con INE/IM/GCBA/INDEC) filtrado por raíces y nivel elegidos. Aplicar `location-pages.md` §2: URL propia solo con demanda (prioridad 1–2 del JSON o evidencia de la SERP) y con datos; las de prioridad 3 van como sección del hub madre salvo que el usuario haya elegido bajar a ese nivel y acepte el riesgo. Tope de fase 1: 30–50 páginas de zona por servicio.
5. **Árbol de URLs**: `/` · `/{servicio}` · `/{servicio}/{zona}` · `/{zona}` (hub) · `/zonas` · `/nosotros` · `/contacto` · `/privacidad` · `/terminos`. La plantilla fija esta estructura (multi-servicio anidado); no se cambia.
6. **Plan de enlazado** (`location-pages.md` §5): lo automático de la plantilla (vecinas del JSON, otros servicios en la zona, padre, hub, troncal) + qué enlaces contextuales van en el cuerpo de cada texto (1 troncal, 1 madre, 2–3 vecinas con anchor natural). Tandas de publicación si el sitio supera 50 páginas de zona.
7. **Páginas de soporte**: nosotros y contacto siempre; `/zonas` la genera la plantilla; una página de precios o guía informativa solo si `topical-map.md` §4 la recomienda para el nicho **y** hay datos; se implementa como sección larga de la troncal (la plantilla no tiene blog).

**Único punto de control.** Mostrar y esperar el OK:
- cantidad de páginas por tipo; lista de servicios con slug; lista de zonas con URL y las que quedan como sección;
- 5 ejemplos de URL con su title y H1 (fórmulas de `on-page-formulas.md` §3);
- riesgos etiquetados: si la cantidad pedida supera el tope o faltan datos para el 20 % local, decir "riesgo de contenido escalado/doorway" y proponer la alternativa concreta (N zonas con URL ahora, el resto en el hub; segunda tanda cuando haya indexación);
- lo que quedará como `[COMPLETAR]`.
Después del OK no se pregunta nada más hasta terminar.

## Fase 3 — Construcción (autónoma)

1. **Copiar**: `cp -r _plantilla-base <carpeta-destino>`; borrar `data/contenido/plomero/`, `data/contenido/servicios/plomero.php`, `data/contenido/zonas/ciudad-ejemplo.php`, `INVENTARIO.md`. Crear `PROGRESO.md` de inmediato (formato abajo). Si la carpeta ya existe con `PROGRESO.md`, **retomar** desde ahí sin rehacer.
2. **`config.php`**: mapear cada respuesta según `references/preguntas.md` § "Dónde va cada respuesta". Dominio también en `.htaccess` (las dos líneas `RewriteCond`). `schema.tipo` de la tabla de `entidades-y-schema.md` §3. Mensaje de WhatsApp `Hola, necesito {servicio} en {zona}` (o el que dio el usuario); sin número todavía, los botones llevan al formulario de `/contacto` con el mensaje precargado y `email.formulario = true`. `sitio.sufijo_title = false` porque todos los titles se escriben a mano (45–58 caracteres, también el de la home). Colores: paleta del usuario o una neutra coherente (sin tocar nombres de variables).
3. **`data/zonas.php`**: `python3 scripts/zonas.py generar --pais … --raices … --nivel … --prioridad-max … --excluir … --salida <sitio>/data/zonas.php`, y completar a mano `tiempo_llegada` (solo si el usuario lo dio), `notas` (1–2 frases con hechos públicos de la zona relevantes al servicio) y ajustar `vecinos` si hace falta. Zonas sin URL: no van en el archivo; se nombran en el texto del hub madre.
4. **`data/servicios.php`**: por servicio: `nombre` (como se busca), `nombre_h1`, `descripcion`, `sinonimos` (de la SERP y el topical map), `entidades` (≥ 8 del oficio), `faq` base (3–4 con placeholders), `beneficios` (3, reales o neutros), `zonas` (`'*'` para el principal; lista para los secundarios).
5. **Contenido por tandas**. Primero escribir **un brief compartido** (plantilla en `references/redaccion.md` § "Plantilla del brief") en un archivo de trabajo fuera del sitio: qué se puede afirmar del negocio y qué no, formato de salida, reglas de enlaces, rutas de los archivos de datos y de las references de la skill de SEO. Después repartir las páginas en tandas de 8–10 por tipo (servicio principal × zona; subservicio × zona; hubs; troncales), cada tanda con zonas distintas y la orden explícita de no tocar otros archivos. Con herramienta de subagentes, lanzar todas las tandas en paralelo pasando la ruta del brief y la lista exacta de archivos a crear; pedir que verifiquen `php -l` y que cada href apunte a combinaciones que existen en `data/servicios.php` y `data/zonas.php`. Sin subagentes, escribir las tandas en orden (hub departamental → ciudades → barrios) actualizando `PROGRESO.md` al cerrar cada una. Los hubs de zona (`zonas/{zona}.php`) apuntan a "galpones/servicios en {zona}" y no compiten con la página servicio principal × zona (title y H1 distintos). Al terminar todas las tandas, la similitud y los anchors repetidos se revisan con `scripts/qa.py` (fase 4), porque los redactores no ven lo que escriben los demás.
6. **Páginas fijas**: `paginas/home.php`, `paginas/comun.php` (pasos, diferenciadores, textos de sección; sin cifras ni reseñas si no hay reales), `paginas/nosotros.php` (bloque "quién te atiende" con lo real; en rank and rent, marca del sitio + operador cuando exista, `eeat-servicios.md` §4), `paginas/contacto.php`, `paginas/legales.php` (ley del país), `paginas/zonas.php`. Contenido de troncales (`servicios/{slug}.php`) y hubs (`zonas/{slug}.php`).
7. **Imágenes**: no generar; dejar la lista de qué va en cada ruta en ENTREGA.md. Logos placeholder de la plantilla se quedan hasta que el usuario los reemplace (se listan).
8. Correr `php bin/verificar.php --base=… ` al final de cada tanda grande para atrapar errores de datos temprano.

`PROGRESO.md`:
```
# Progreso — {dominio}
Estado: en construcción | QA | entregado
## Hecho
- [x] config.php  - [x] zonas.php (N zonas)  - [x] servicios.php (N)  - [x] home/comun/nosotros/contacto/legales
- [x] {servicio}: hub departamento, ciudades (n/n), barrios (n/n)
## Falta
- [ ] {servicio}: barrios (lista de slugs pendientes)
## Decisiones
- (por qué se dejó una zona como sección, qué quedó [COMPLETAR], etc.)
```

## Fase 4 — QA automático

```bash
python3 <skill>/scripts/qa.py --sitio <carpeta> --reporte <carpeta>/QA.md
```
Revisa sintaxis, marcadores y restos de la plantilla, sitemap 200/404/301, title/H1/description únicos y con largo correcto, largo mínimo por tipo, similitud entre páginas (5-shingles del texto propio, umbral 50 %), JSON-LD, enlaces rotos y huérfanas, WhatsApp con `data-wsp` y mensaje con la zona. Arreglar todo lo que marque **E** y repetir hasta "SIN ERRORES". Los **A** se resuelven si es razonable; los que no, se explican en ENTREGA.md. Antes de dar por terminado, pasar mentalmente `qa-checklist.md` §B–F sobre 3 páginas al azar.

Si la similitud marca pares: no sinonimizar; agregar datos distintos (referencias, accesos, tipo de construcción, problema típico, FAQ propia) o fusionar la zona en el hub.

## Fase 5 — Entrega

Escribir `ENTREGA.md` en la carpeta del sitio y resumirlo en el chat (≤ 15 líneas):
1. Páginas por tipo y árbol final (servicios, zonas con URL, zonas como sección).
2. Lo que queda para el usuario: diseño; fotos reales o imágenes generadas (ruta exacta de cada una: `assets/img/logo/…`, `assets/img/hero/…`, `assets/img/servicios/{slug}.webp`); logo; lista de `[COMPLETAR]` con archivo y línea (`grep -n COMPLETAR config.php`); datos del operador que convertirían formulaciones neutras en datos (brief de `assets/brief-zona.md` de la skill de SEO); si no hay número de WhatsApp, decir que al cargarlo todos los botones pasan solos de `/contacto` a `wa.me`.
3. Pasos de lanzamiento: `references/lanzamiento.md` (Search Console y sitemap, Bing Webmaster + IndexNow, ficha de Google si corresponde, citaciones, qué zonas mirar primero).
4. Cómo probar en local: `php -S localhost:8000 index.php` y `python3 …/qa.py --sitio .`.

## Qué hay en esta skill

- `references/preguntas.md` — banco de preguntas, opciones sugeridas, y a qué campo va cada respuesta.
- `references/plantilla.md` — resumen de PLANTILLA.md: qué se toca, formato de cada archivo, qué no se toca.
- `references/redaccion.md` — brief de página y mapeo bloque SEO → campo de la plantilla; es lo que se le pasa a un subagente redactor.
- `references/lanzamiento.md` — checklist posterior a la publicación.
- `assets/zonas-uy.json` — 19 departamentos, 62 barrios de Montevideo (INE), localidades de Canelones (con Ciudad de la Costa desglosada), Maldonado y capitales, con vecinos y referencias.
- `assets/zonas-caba-amba.json` — 48 barrios de CABA con comuna, 40 partidos del AMBA y sus localidades, con vecinos.
- `scripts/zonas.py` — cuenta páginas por opción de cobertura y genera `data/zonas.php`.
- `scripts/qa.py` — control de calidad automático.
