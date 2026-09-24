#!/usr/bin/env python3
"""
QA automático de un sitio armado con la plantilla base. Solo biblioteca estándar de Python 3.8+.

Uso:
  python3 qa.py --sitio /ruta/al/sitio [--base http://127.0.0.1:8123] [--reporte QA.md] [--umbral 0.5] [--json qa.json]

Si no se pasa --base, levanta `php -S 127.0.0.1:8123 index.php` dentro del sitio y lo apaga al terminar.

Qué revisa (E = error, bloquea; A = aviso):
  1. php -l en todos los .php (E).
  2. Marcadores: [EJEMPLO] en código, datos o HTML (E); [COMPLETAR] en HTML público (E) y en código/datos (A, se listan);
     restos de la plantilla ("Plomero Ejemplo", "plomero-ejemplo", "Ciudad Ejemplo", "mi-dominio.uy" en .htaccess) (E).
  3. Sitemap: índice + segmentos; todas las URLs 200 (E); URL inventada 404 (E); barra final 301 (E).
  4. Por página: title 1 y único, largo 30–70 (E fuera de rango, A fuera de 45–60); H1 exactamente 1, ≤ 70, único (E);
     meta description única, 80–170 (E), A fuera de 120–158; canonical = URL.
  5. Contenido: palabras del cuerpo (sin header/footer/nav) ≥ mínimo por tipo (E); H2 ≥ 3 y FAQ ≥ 3 en servicio×zona (A).
  6. Similitud entre páginas del mismo tipo: shingles de 5 palabras sobre el texto propio (secciones + FAQ + hero);
     Jaccard > umbral = E (reescribir). Se listan los pares peores.
  7. JSON-LD válido en todas (E); sin aggregateRating/review propios (E); Service con areaServed en servicio×zona (A).
  8. Enlaces internos: todos resuelven a una URL del sitemap o a assets (E); páginas huérfanas (E); servicio×zona con
     < 3 enlaces entrantes (A); enlaces del cuerpo a zonas vecinas ≥ 2 en servicio×zona (A).
  9. WhatsApp: todo <a> a wa.me lleva data-wsp (E); en servicio×zona el mensaje incluye servicio y zona (E); ≥ 3 botones (A).
Sale con código 1 si hay errores.
"""
import argparse, html, json, os, re, subprocess, sys, time, urllib.request, urllib.error, urllib.parse
from collections import defaultdict

MIN_PALABRAS = {"servicio-zona": 350, "servicio": 350, "zona": 200, "home": 250, "nosotros": 180, "contacto": 60, "zonas": 60, "privacidad": 120, "terminos": 120}
RESTOS = ["Plomero Ejemplo", "plomero-ejemplo", "Ciudad Ejemplo", "Barrio Norte [", "Barrio Sur [", "Provincia Ejemplo", "contacto@plomero", "LOGO [EJEMPLO]"]

errores, avisos = [], []
def E(m): errores.append(m)
def A(m): avisos.append(m)

def get(url, timeout=20):
    req = urllib.request.Request(url, headers={"User-Agent": "qa.py"})
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            return r.status, r.read().decode("utf-8", "replace"), r.geturl()
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode("utf-8", "replace"), url
    except Exception as e:
        return 0, str(e), url

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None
def code_sin_redirect(url):
    op = urllib.request.build_opener(NoRedirect)
    try:
        r = op.open(urllib.request.Request(url, headers={"User-Agent": "qa.py"}), timeout=20); return r.status
    except urllib.error.HTTPError as e: return e.code
    except Exception: return 0

def strip_tags(s):
    s = re.sub(r"<script.*?</script>|<style.*?</style>", " ", s, flags=re.S)
    s = re.sub(r"<[^>]+>", " ", s)
    return re.sub(r"\s+", " ", html.unescape(s)).strip()

import unicodedata
def sin_acentos(s): return "".join(c for c in unicodedata.normalize("NFD", s) if unicodedata.category(c) != "Mn")
def palabras(s): return re.findall(r"[a-záéíóúñü0-9]+", s.lower())

def shingles(txt, n=5):
    w = palabras(txt)
    return set(" ".join(w[i:i+n]) for i in range(max(0, len(w)-n+1)))

def main():
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("--sitio", required=True)
    ap.add_argument("--base", default="")
    ap.add_argument("--reporte", default="")
    ap.add_argument("--json", default="")
    ap.add_argument("--umbral", type=float, default=0.5)
    ap.add_argument("--puerto", type=int, default=8123)
    a = ap.parse_args()
    sitio = os.path.abspath(a.sitio)
    if not os.path.isfile(os.path.join(sitio, "index.php")): sys.exit(f"No hay index.php en {sitio}")

    # 1. php -l
    phps = []
    for root, dirs, files in os.walk(sitio):
        dirs[:] = [d for d in dirs if d not in ("vendor", ".git", "node_modules")]
        phps += [os.path.join(root, f) for f in files if f.endswith(".php")]
    for f in phps:
        r = subprocess.run(["php", "-l", f], capture_output=True, text=True)
        if r.returncode != 0: E(f"php -l: {os.path.relpath(f, sitio)}: {r.stdout.strip()[:200]}")
    print(f"1. {len(phps)} archivos PHP revisados, {len([e for e in errores if e.startswith('php -l')])} con errores")

    # 2. Marcadores en código y datos
    pendientes = []
    for root, dirs, files in os.walk(sitio):
        dirs[:] = [d for d in dirs if d not in ("vendor", ".git", "node_modules", "assets", "bin")]
        for f in files:
            if not f.endswith((".php", ".md", ".txt", ".json", ".htaccess")) and f != ".htaccess": continue
            p = os.path.join(root, f); rel = os.path.relpath(p, sitio)
            if rel in ("ENTREGA.md", "PROGRESO.md", "QA.md", "INVENTARIO.md", "PLANTILLA.md"): continue
            try: c = open(p, encoding="utf-8", errors="replace").read()
            except Exception: continue
            if "[EJEMPLO]" in c: E(f"[EJEMPLO] en {rel} ({c.count('[EJEMPLO]')} veces)")
            for i, line in enumerate(c.splitlines(), 1):
                if "[COMPLETAR]" in line: pendientes.append(f"{rel}:{i}: {line.strip()[:110]}")
            for r_ in RESTOS:
                if r_ in c and rel != "PLANTILLA.md": E(f"resto de la plantilla '{r_}' en {rel}")
            if f == ".htaccess" and any("mi-dominio" in l for l in c.splitlines() if not l.lstrip().startswith("#")):
                E(".htaccess: falta reemplazar mi-dominio.uy por el dominio real en las reglas RewriteCond")
    for p in pendientes: A(f"[COMPLETAR] pendiente → {p}")
    print(f"2. Marcadores: {len(pendientes)} [COMPLETAR] pendientes en código/datos")

    # Servidor
    proc = None
    base = a.base.rstrip("/")
    if not base:
        base = f"http://127.0.0.1:{a.puerto}"
        proc = subprocess.Popen(["php", "-S", f"127.0.0.1:{a.puerto}", "index.php"], cwd=sitio, stdout=subprocess.DEVNULL, stderr=subprocess.PIPE, text=True)
        for _ in range(30):
            time.sleep(0.2)
            if get(base + "/robots.txt")[0] == 200: break
    try:
        # 3. Sitemap
        code, xml, _ = get(base + "/sitemap.xml")
        if code != 200: E(f"/sitemap.xml → HTTP {code}"); return fin(a, proc, sitio)
        locs = re.findall(r"<loc>(.*?)</loc>", xml)
        dominio = None
        urls = []
        for loc in locs:
            loc = html.unescape(loc)
            m = re.match(r"(https?://[^/]+)(/.*)", loc); dominio = dominio or m.group(1)
            if "/sitemap-" in loc:
                c2, x2, _ = get(base + m.group(2))
                if c2 != 200: E(f"{m.group(2)} → HTTP {c2}"); continue
                urls += [urllib.parse.urlparse(html.unescape(l)).path for l in re.findall(r"<loc>(.*?)</loc>", x2)]
            else: urls.append(m.group(2))
        urls = list(dict.fromkeys(urls))
        if not urls: E("sitemap vacío"); return fin(a, proc, sitio)
        if "example" in (dominio or "") or "plomero-ejemplo" in (dominio or ""): E(f"dominio del sitemap es de la plantilla: {dominio}")
        paginas = {}
        for path in urls:
            c, h, _ = get(base + path)
            if c != 200: E(f"{path} → HTTP {c}"); continue
            paginas[path] = h
        print(f"3. Sitemap: {len(urls)} URLs, {len(paginas)} con 200")
        for u in ["/esta-pagina-no-existe", "/zzz/qqq", "/zzz/qqq/rrr"]:
            c = get(base + u)[0]
            if c != 404: E(f"{u} → HTTP {c} (esperado 404)")
        prueba = next((p for p in urls if p.count("/") == 2), next((p for p in urls if p != "/"), None))
        if prueba:
            c = code_sin_redirect(base + prueba + "/")
            if c != 301: E(f"{prueba}/ → HTTP {c} (esperado 301 a sin barra)")
            c = code_sin_redirect(base + prueba.upper())
            if c != 301: E(f"{prueba.upper()} → HTTP {c} (esperado 301 a minúsculas)")
        rob = get(base + "/robots.txt")[1]
        if "Sitemap:" not in rob: E("robots.txt sin línea Sitemap")
        if re.search(r"Disallow:\s*/\s*$", rob, re.M): E("robots.txt bloquea todo el sitio (Disallow: /)")

        # 4-9. Por página
        titles, h1s, descs = defaultdict(list), defaultdict(list), defaultdict(list)
        tipo_de, cuerpo, propio, links_de = {}, {}, {}, {}
        restos_html = defaultdict(list)
        rutas_ok = set(urls) | {"/contacto/gracias"}
        for path, h in paginas.items():
            m = re.search(r'window\.sitioConfig\s*=\s*(\{.*?\});', h, re.S)
            tipo = "?"
            if m:
                try: tipo = json.loads(m.group(1)).get("pagina", "?")
                except Exception: pass
            tipo_de[path] = tipo
            # head
            t = re.findall(r"<title>(.*?)</title>", h, re.S)
            title = html.unescape(t[0]).strip() if t else ""
            if len(t) != 1 or not title: E(f"{path}: title ausente o múltiple")
            else:
                titles[title].append(path)
                L = len(title)
                if L < 30 or L > 70: E(f"{path}: title de {L} caracteres → «{title}»")
                elif L < 45 or L > 60: A(f"{path}: title de {L} caracteres (ideal 45–58) → «{title}»")
                if "|" in title: A(f"{path}: title con pipe (Google lo reescribe más; usar guion) → «{title}»")
                if re.search(r"\d{2,3}[\s.-]?\d{3}[\s.-]?\d{3}", title): E(f"{path}: title con teléfono")
            d = re.findall(r'<meta name="description" content="(.*?)"', h)
            desc = html.unescape(d[0]).strip() if d else ""
            if not desc: E(f"{path}: sin meta description")
            else:
                descs[desc].append(path)
                L = len(desc)
                if L < 80 or L > 170: E(f"{path}: description de {L} caracteres")
                elif L < 120 or L > 158: A(f"{path}: description de {L} caracteres (ideal 120–150)")
                if re.search(r"\d{2,3}[\s.-]?\d{3}[\s.-]?\d{3}", desc): E(f"{path}: description con teléfono")
            h1 = [strip_tags(x) for x in re.findall(r"<h1[^>]*>(.*?)</h1>", h, re.S)]
            if len(h1) != 1: E(f"{path}: {len(h1)} H1")
            else:
                h1s[h1[0]].append(path)
                if len(h1[0]) > 70: E(f"{path}: H1 de {len(h1[0])} caracteres → «{h1[0]}»")
                if tipo == "servicio-zona" and title and len(set(palabras(h1[0])[:6]) & set(palabras(title)[:6])) < 2: A(f"{path}: H1 y title no comparten keyword + zona al principio")
            can = re.findall(r'<link rel="canonical" href="(.*?)"', h)
            if not can: E(f"{path}: sin canonical")
            elif urllib.parse.urlparse(html.unescape(can[0])).path.rstrip("/") != path.rstrip("/"): E(f"{path}: canonical apunta a {can[0]}")
            if 'name="robots"' in h and "noindex" in h and tipo not in ("404", "contacto-gracias"): A(f"{path}: noindex")
            # cuerpo
            main = re.search(r"<main[^>]*>(.*?)</main>", h, re.S)
            texto_main = strip_tags(main.group(1)) if main else strip_tags(h)
            cuerpo[path] = texto_main
            nw = len(palabras(texto_main))
            minimo = MIN_PALABRAS.get(tipo, 100)
            if nw < minimo: E(f"{path}: {nw} palabras en el cuerpo (mínimo {minimo} para {tipo})")
            # texto propio: secciones de texto + faq + hero subtítulo
            partes = re.findall(r'<section class="zona-texto".*?</section>|<section class="faq".*?</section>|<p class="hero__subtitle">.*?</p>|<p class="page-intro__desc">.*?</p>', h, re.S)
            propio[path] = strip_tags(" ".join(partes))
            if tipo == "servicio-zona":
                if len(re.findall(r"<h2", main.group(1) if main else h)) < 3: A(f"{path}: menos de 3 H2")
                if len(re.findall(r'class="faq__item"', h)) < 3: A(f"{path}: menos de 3 preguntas frecuentes")
                if not propio[path] or len(palabras(propio[path])) < 150: E(f"{path}: texto propio de la página menor a 150 palabras (contenido local insuficiente)")
            for r_ in RESTOS + ["[EJEMPLO]", "[COMPLETAR]"]:
                if r_ in h: restos_html[r_].append(path)
            # JSON-LD
            bl = re.findall(r'<script type="application/ld\+json">(.*?)</script>', h, re.S)
            if not bl: E(f"{path}: sin JSON-LD")
            tipos_ld = []
            for b in bl:
                try:
                    j = json.loads(b); tipos_ld.append(j.get("@type"))
                    if "aggregateRating" in b or '"Review"' in b: E(f"{path}: JSON-LD con aggregateRating/review propio (self-serving)")
                    if j.get("@type") == "Service" and not j.get("areaServed"): A(f"{path}: Service sin areaServed")
                except Exception: E(f"{path}: JSON-LD inválido")
            if tipo == "servicio-zona" and "Service" not in [str(x) for x in tipos_ld]: E(f"{path}: falta schema Service")
            # links internos (solo del cuerpo <main>)
            cuerpo_html = main.group(1) if main else h
            hrefs = re.findall(r'href="([^"#?]*)', h)
            internos = set()
            for hr in hrefs:
                if hr.startswith("http"):
                    if dominio and hr.startswith(dominio): hr = hr[len(dominio):] or "/"
                    else: continue
                if not hr.startswith("/") or hr.startswith("/assets/"): continue
                hr = hr.rstrip("/") or "/"
                internos.add(hr)
                if hr not in rutas_ok and not re.match(r"^/[a-f0-9]{32}\.txt$", hr) and hr not in ("/sitemap.xml", "/robots.txt", "/llms.txt"):
                    E(f"{path}: enlace interno roto → {hr}")
            links_de[path] = internos
            # WhatsApp
            # Botones de WhatsApp: wa.me con número, o /contacto?origen=whatsapp&msg=... cuando todavía no hay número
            wa = re.findall(r"<a\s[^>]*href=\"((?:https://wa\.me/|[^\"]*origen=whatsapp)[^\"]*)\"[^>]*>", h)
            wa_tags = re.findall(r"<a\s[^>]*href=\"(?:https://wa\.me/|[^\"]*origen=whatsapp)[^\"]*\"[^>]*>", h)
            for tag in wa_tags:
                if "data-wsp" not in tag: E(f"{path}: botón de WhatsApp sin data-wsp (no dispara click_wsp)")
            if tipo == "servicio-zona":
                if len(wa_tags) < 3: A(f"{path}: solo {len(wa_tags)} botones de WhatsApp")
                cfgm = json.loads(m.group(1)) if m else {}
                zona, srv = cfgm.get("zona", ""), cfgm.get("servicio", "")
                textos = [sin_acentos(urllib.parse.unquote(html.unescape(urllib.parse.urlparse(w).query)).lower()) for w in wa]
                nombre_zona = sin_acentos(h1[0].split(" en ")[-1].lower()) if h1 else zona
                zona_pal = sin_acentos(zona.replace("-", " ")).split()[0]
                if textos and not all(zona_pal in t or nombre_zona.split()[0].strip(":,") in t for t in textos):
                    E(f"{path}: hay botones de WhatsApp cuyo mensaje no menciona la zona")
            # contacto
            if tipo != "contacto-gracias" and "wa.me" not in h and "contacto" not in h: A(f"{path}: sin WhatsApp")
        for r_, ps in restos_html.items():
            E(f"'{r_}' aparece en el HTML público de {len(ps)} páginas (ej. {', '.join(ps[:3])})")
        for dic, nombre in ((titles, "title"), (h1s, "H1"), (descs, "description")):
            for k, ps in dic.items():
                if len(ps) > 1: E(f"{nombre} repetido en {', '.join(ps)} → «{k[:80]}»")
        print(f"4. {len(paginas)} páginas: {len(titles)} titles, {len(h1s)} H1, {len(descs)} descriptions distintos")

        # 6. Similitud
        por_tipo = defaultdict(list)
        for p, t in tipo_de.items(): por_tipo[t].append(p)
        peores = []
        for t, ps in por_tipo.items():
            if len(ps) < 2: continue
            sh = {p: shingles(propio.get(p, "")) for p in ps}
            for i in range(len(ps)):
                for j in range(i+1, len(ps)):
                    A_, B_ = sh[ps[i]], sh[ps[j]]
                    if not A_ or not B_: continue
                    jac = len(A_ & B_) / len(A_ | B_)
                    peores.append((jac, ps[i], ps[j]))
                    if jac > a.umbral: E(f"similitud {jac:.0%} entre {ps[i]} y {ps[j]} (umbral {a.umbral:.0%}): reescribir el contenido local")
        peores.sort(reverse=True)
        print("6. Similitud (5-shingles del texto propio), pares más parecidos:")
        for jac, p1, p2 in peores[:8]: print(f"   {jac:5.0%}  {p1}  ~  {p2}")

        # 8. Entrantes y huérfanas
        entrantes = defaultdict(set)
        for p, ls in links_de.items():
            for l in ls:
                if l != p: entrantes[l].add(p)
        for p in paginas:
            if p == "/": continue
            n = len(entrantes.get(p, ()))
            if n == 0: E(f"{p}: página huérfana (ningún enlace interno entrante)")
            elif tipo_de.get(p) == "servicio-zona" and n < 3: A(f"{p}: solo {n} enlaces entrantes (ideal ≥ 3: troncal, padre, vecina)")
        print(f"8. Enlazado: {sum(len(v) for v in links_de.values())} enlaces internos, {sum(1 for p in paginas if p != '/' and not entrantes.get(p))} huérfanas")
    finally:
        pass
    return fin(a, proc, sitio, tipo_de if 'tipo_de' in dir() else {})

def fin(a, proc, sitio, tipo_de=None):
    if proc: proc.terminate()
    print(f"\n{'SIN ERRORES' if not errores else str(len(errores)) + ' ERRORES'}, {len(avisos)} avisos")
    for e in errores[:60]: print("  E:", e)
    if len(errores) > 60: print(f"  ... y {len(errores)-60} más")
    for w in avisos[:40]: print("  A:", w)
    if len(avisos) > 40: print(f"  ... y {len(avisos)-40} avisos más")
    if a.reporte:
        from collections import Counter
        with open(a.reporte, "w", encoding="utf-8") as f:
            f.write(f"# QA — {os.path.basename(sitio)}\n\nFecha: {time.strftime('%Y-%m-%d %H:%M')}\n\n")
            if tipo_de: f.write("Páginas por tipo: " + ", ".join(f"{k}: {v}" for k, v in sorted(Counter(tipo_de.values()).items())) + "\n\n")
            f.write(f"## Errores ({len(errores)})\n\n" + "".join(f"- {e}\n" for e in errores) + f"\n## Avisos ({len(avisos)})\n\n" + "".join(f"- {w}\n" for w in avisos))
    if a.json:
        json.dump({"errores": errores, "avisos": avisos}, open(a.json, "w", encoding="utf-8"), ensure_ascii=False, indent=1)
    sys.exit(1 if errores else 0)

if __name__ == "__main__":
    main()
