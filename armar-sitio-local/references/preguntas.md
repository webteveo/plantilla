# Banco de preguntas de la entrevista

Reglas: rondas de hasta 4 preguntas con la herramienta de opción múltiple; siempre que tenga sentido, una opción "Decidí vos"; no repetir lo que el usuario ya dijo; cuando una respuesta es obvia, marcarla como recomendada. Las opciones de abajo son sugerencias: adaptar a lo que ya se sabe.

## Ronda 1 — Identidad

| # | Pregunta | Opciones sugeridas | Se usa en |
|---|---|---|---|
| 1.1 | ¿Cuál es el dominio? (texto libre) | — | `sitio.dominio` (con https://, sin barra), `.htaccess`, emails por defecto `contacto@{dominio}` |
| 1.2 | ¿Nombre de marca? | "El del dominio" (ej. galponesprefabricados.com → "Galpones Prefabricados") · texto libre · Decidí vos | `marca.nombre`, `marca.nombre_corto`, title de home |
| 1.3 | ¿Rank and rent o cliente real? | Rank and rent (sin operador todavía) · Rank and rent con operador ya definido · Cliente real | Define tono de "nosotros", si hay datos del negocio, bloque "quién te atiende" (`eeat-servicios.md` §4) |
| 1.4 | ¿Mercado? | Uruguay · Argentina (CABA/AMBA) · Argentina (otra provincia) · Otro | `pais.*` (código, moneda, prefijo, zona horaria, wikidata), JSON de zonas, legislación en legales, jerga |

## Ronda 2 — Nicho

| # | Pregunta | Opciones | Se usa en |
|---|---|---|---|
| 2.1 | ¿Servicio principal? (como lo busca la gente) | texto libre · Decidí vos (se toma del dominio) | primer servicio de `data/servicios.php` (matriz completa × zona), rubro, `schema.tipo` |
| 2.2 | ¿Lista de servicios? | "Solo el principal" · texto libre · Decidí vos (propuesta en fase 2 con topical map + SERP, confirmada en el punto de control) | resto de `data/servicios.php` (troncal + zonas top) |
| 2.3 | ¿A quién le vendés? | Hogares · Empresas y comercios · Campo/industria · Mixto | vocabulario, FAQ, `vivienda` que se prioriza, ejemplos en textos |
| 2.4 | ¿Rubro para el schema? | se infiere (`entidades-y-schema.md` §3) · Decidí vos | `schema.tipo` |

## Ronda 3 — Cobertura (correr `scripts/zonas.py contar` antes)

| # | Pregunta | Opciones | Se usa en |
|---|---|---|---|
| 3.1 | ¿Qué departamentos / provincias / partidos? | multiSelect con los del JSON (Montevideo, Canelones, Maldonado, San José, …) · CABA · AMBA norte/oeste/sur · Decidí vos | `--raices` de `zonas.py`, `direccion.zona_principal`, `EMPRESA_ZONAS` del schema |
| 3.2 | ¿Hasta qué nivel bajar? | Solo departamentos/ciudades grandes (≈N páginas) · Ciudades y localidades (≈N) · Hasta barrios (≈N) · Decidí vos (la skill de SEO: solo zonas con demanda y datos, tope 30–50 por servicio) | `--nivel`, `--prioridad-max` |
| 3.3 | ¿Zona principal / base? | la capital elegida · texto libre | `direccion.zona_principal`, `direccion.ciudad`, H1 de la home, textos "desde nuestra base en…" |
| 3.4 | ¿Excluir alguna zona? | ninguna · texto libre | `--excluir` |

Etiqueta de cada opción de nivel: "Hasta barrios: ≈118 zonas → ≈240 páginas con 1 servicio" (salida de `zonas.py contar`). Si alguna opción supera el tope de la skill de SEO, decirlo en la etiqueta.

## Ronda 4 — Contacto

| # | Pregunta | Opciones | Se usa en |
|---|---|---|---|
| 4.1 | ¿WhatsApp? (internacional sin +) | texto libre · "Todavía no hay número" (los botones llevan al formulario de contacto) | `contacto.whatsapp`; sin número → `contacto.whatsapp = ''` y `email.formulario = true` si hay SMTP, si no `[COMPLETAR]` |
| 4.2 | ¿Teléfono para llamar? | mismo que WhatsApp · otro · ninguno | `contacto.telefono` |
| 4.3 | ¿Email? | contacto@{dominio} · otro | `contacto.email` |
| 4.4 | ¿Dirección física o área de servicio? | Sin local, a domicilio (área de servicio) · Local con dirección (texto) | `direccion.tiene_local`, `direccion.calle/numero`, `direccion.texto`; nunca inventar dirección |
| 4.5 | ¿Horario real? | Lun–vie 8–18 · Lun–sáb 8–20 · 24 horas reales (confirmar) · texto libre | `horario.*`, `urgencias_24h` solo si es real (`on-page-formulas.md` §1) |

## Ronda 5 — Diferenciales reales (nunca inventar)

| # | Pregunta | Opciones | Se usa en |
|---|---|---|---|
| 5.1 | ¿Años de experiencia? | número · "sin dato" | `marca.anio_inicio`, "nosotros"; sin dato → no se menciona |
| 5.2 | ¿Garantía? | escrita X meses/años · "sin dato" | beneficios, FAQ, legales |
| 5.3 | ¿Tiempo de llegada / plazo de entrega? | texto · "sin dato" | `tiempo_llegada` de zonas, hero, FAQ |
| 5.4 | ¿Precios de referencia? | "desde $ X" con fecha · "no publicar precios" · "sin dato" | tablas de precios con fecha (`geo-ia.md` §3); sin dato → sección "qué define el precio" sin cifras |
| 5.5 | ¿Formas de pago? | efectivo/transferencia/tarjeta · otras | `schema.pagos`, FAQ |
| 5.6 | ¿Matrícula, registro, seguro? | texto · "no aplica" · "sin dato" | bloque "quién te atiende", schema |
| 5.7 | ¿Reseñas reales para publicar? | "sí, las paso" · "no todavía" | `comun.testimonios` solo si las pasa; nunca de relleno |

Rank and rent sin operador: aceptar "sin datos" en bloque y pasar a formulaciones neutras ("presupuesto por WhatsApp", "coordinamos día y horario"), sin promesas de tiempo, precio ni experiencia.

## Ronda 6 — Técnico

| # | Pregunta | Opciones | Se usa en |
|---|---|---|---|
| 6.1 | ¿ID de GA4? | G-XXXX · "todavía no" | `analitica.ga4_id`; el evento `click_wsp` ya viene armado |
| 6.2 | ¿Colores de marca? | hex primario/acento · "los del logo" · Decidí vos (paleta neutra) | `colores.primario`, `primario-hover`, `secundario`, `acento`, `acento-claro/oscuro/fondo` |
| 6.3 | ¿Logo y fotos reales? | "Tengo logo y fotos" · "Solo logo" · "Nada todavía" | rutas en ENTREGA.md; placeholders hasta que lleguen |
| 6.4 | ¿En qué carpeta creo el sitio? | "al lado de _plantilla-base con el nombre del dominio" · texto libre | destino de `cp -r` |
| 6.5 | ¿Dónde está _plantilla-base? (solo si no se detecta) | texto libre | origen de la copia |

## Ronda 7 — Competencia (opcional)

| # | Pregunta | Opciones | Se usa en |
|---|---|---|---|
| 7.1 | ¿Competidores que conozcas? | texto libre · "buscá vos" | fase 2: se analizan sus páginas por zona, titles y bloques |

## Dónde va cada respuesta (mapa rápido a config.php)

```
marca:      nombre, nombre_corto, slogan (armar: "{Servicio} en {zona principal}" o lo que diga el usuario), descripcion (2 frases, sin cifras), rubro, anio_inicio
sitio:      dominio, titulo_home (fórmula home de on-page-formulas §3a), descripcion_home, sufijo_title (false si los titles se escriben a mano)
pais:       UY → codigo UY, nombre Uruguay, wikidata Q77, moneda UYU, simbolo $U, zona_horaria America/Montevideo, prefijo_tel 598, gentilicio uruguayo
            AR → AR, Argentina, Q414, ARS, $, America/Argentina/Buenos_Aires, 54, argentino
contacto:   whatsapp, telefono, email, wsp_mensaje ("Hola, necesito {servicio} en {zona}"), wsp_mensaje_generico, cta_label ("Pedir presupuesto por WhatsApp" o el del usuario), llamar_label
direccion:  tiene_local, calle, numero, ciudad, region, texto, lat/lng (centro de la zona principal), google_maps (URL de la ficha si existe), zona_principal (slug)
horario:    lunes…domingo ('HH:MM-HH:MM' | 'Cerrado'), texto, urgencias_24h
redes:      solo las que existan
analitica:  ga4_id, indexnow_key (generar: php -r 'echo bin2hex(random_bytes(16));')
schema:     tipo (tabla nicho → @type), price_range, pagos
colores:    primario, primario-hover, secundario, acento, acento-claro, acento-oscuro, acento-fondo (el resto no se toca)
email:      formulario (true solo si hay SMTP o el usuario lo pide), smtp_* ([COMPLETAR] si no hay)
ui:         menu (dejar), footer_nota, legales true
```
