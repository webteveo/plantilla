# Checklist de lanzamiento (después de subir el sitio al hosting)

Etiquetas: [TRÁMITE] hacer una vez · [RANKING] mueve posiciones · [CONVERSIÓN] mueve leads · [GEO] visibilidad en IA. Detalle y evidencia en `seo-local-semantico-2026/references/geo-ia.md` §2 y `location-pages.md` §6; prioridades en `cheloseo`.

## Antes de subir
- [ ] [TRÁMITE] `config.php`: dominio real, `sitio.indexar = true`, `analitica.ga4_id`, `analitica.indexnow_key`; `.htaccess` con el dominio.
- [ ] [TRÁMITE] `python3 qa.py --sitio .` sin errores; `php bin/verificar.php --base=…` sin errores.
- [ ] [TRÁMITE] HTTPS activo; `curl -I https://dominio/` → 200; `https://www.dominio/` → 301; `http://` → 301.
- [ ] [TRÁMITE] `curl https://dominio/robots.txt`: si hay Cloudflare, revisar que no anteponga bloqueos a bots de IA (Security → Bot traffic).
- [ ] [CONVERSIÓN] Probar 3 botones de WhatsApp desde el celular: abre el chat con "Hola, necesito {servicio} en {zona}".

## Día 1
- [ ] [TRÁMITE] Google Search Console: propiedad de dominio (DNS) → enviar `https://dominio/sitemap.xml` (índice; los segmentos se leen solos). Inspeccionar 3–5 URLs clave (home, troncal, 2 zonas prioridad 1) y "Solicitar indexación" solo en esas.
- [ ] [TRÁMITE][GEO] Bing Webmaster Tools: importar desde Search Console; enviar sitemap; `php bin/indexnow.php` (envía todas las URLs a IndexNow: Bing, Copilot, Yandex).
- [ ] [TRÁMITE] GA4: marcar `click_wsp` como evento clave (conversión); crear canal personalizado "AI Assistants" con la regex de `geo-ia.md` §4.
- [ ] [RANKING] Enlazado: confirmar en GSC que las zonas prioridad 1 tienen ≥ 3 enlaces internos (Enlaces → páginas más enlazadas). La plantilla ya los genera; verificar que no haya huérfanas (QA lo revisa).

## Semana 1
- [ ] [RANKING] Ficha de Google Business Profile **solo si hay negocio real** (operador con nombre, teléfono y base): categoría primaria = la de la competencia que rankea; servicios con los mismos nombres que las troncales; área de servicio = zonas raíz; horario real; verificación por video (vehículo, herramientas, documentos). Rank and rent sin operador: **no crear ficha** (lead-gen no elegible; suspensión). Ver cheloseo flujo D.
- [ ] [RANKING] Citaciones NAP idénticas (nombre, teléfono, dirección o ciudad): UY 8–10 (Google, Facebook, Instagram, Bing Places, Apple Business Connect, Cybo, Yelu, Cylex, Infoisinfo, Páginas Amarillas si vigente); AR sumar Habitissimo, Páginas Amarillas AR, cámara del rubro. Solo con negocio real.
- [ ] [TRÁMITE][GEO] Bing Places y Apple Business Connect (importar desde GBP) si hay ficha.
- [ ] [CONVERSIÓN] Reseñas: pedir a clientes reales que mencionen servicio + zona; goteo (5/mes), nunca de golpe.

## Día 30
- [ ] [RANKING] GSC → Páginas / Sitemaps: % indexado por segmento (`sitemap-{servicio}.xml`, `sitemap-hubs.xml`). Si > 30 % de una tanda está en "Descubierta/Rastreada, no indexada", frenar la siguiente tanda y mejorar datos locales o fusionar en el hub.
- [ ] [RANKING] Rendimiento: queries por página; anotar qué zonas ya tienen impresiones.

## Día 60–90: qué zonas mirar primero
- [ ] Ordenar las páginas servicio × zona por impresiones y por `click_wsp` (GA4 → exploración con parámetro `zona`). Las zonas que primero traen consultas suelen ser: la capital/hub, las ciudades con SERP propia (prioridad 1 del JSON) y las zonas industriales o de obra para nichos B2B.
- [ ] Redoblar en las que traccionan: pedir al operador datos reales (trabajos, fotos, reseñas de esa zona) y completar tablas y `testimonios`.
- [ ] Las zonas sin impresiones a 6 meses se fusionan en el hub con 301 (revisión trimestral).
- [ ] GSC "Generative AI performance": qué URLs aparecen en AI Overviews / AI Mode; reforzar pasajes answer-first y tablas con fecha en esas.
- [ ] Actualizar `actualizado` y precios al revisar (AR cada 3 meses, UY cada 6).

## Enlaces (solo cuando el sitio ya da consultas; cheloseo flujo E)
- Directorios del rubro → medios locales → cámaras → colaboraciones reales. Anchors de marca y URL cruda; exactos como excepción. Nada comprado al peso.
