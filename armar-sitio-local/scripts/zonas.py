#!/usr/bin/env python3
"""
Zonas: cuenta páginas por opción de cobertura y genera data/zonas.php desde assets/zonas-*.json.

Uso:
  python3 zonas.py contar   --pais uy --raices montevideo,canelones --nivel barrio [--servicios 4] [--prioridad-max 2]
  python3 zonas.py generar  --pais uy --raices montevideo,canelones --nivel barrio [--prioridad-max 3] [--excluir slug,slug] [--incluir slug,slug] --salida data/zonas.php

--nivel: hasta dónde bajar. Orden: departamento/partido < ciudad < localidad < barrio.
--incluir: zonas que entran igual aunque no pasen el nivel o la prioridad (ej. barrios industriales para un nicho B2B).
         (UY: departamento > ciudad > localidad/barrio.  AR: caba > barrio; amba > partido > localidad)
--prioridad-max: incluir solo zonas con prioridad <= N (1 = se buscan por nombre; 3 = todas).
--raices: slugs de las zonas de arriba (montevideo, canelones, maldonado, caba, amba, un partido...).
El archivo generado respeta el formato de la plantilla (ver references/plantilla.md). Las zonas por encima de la raíz
(país, provincia, región) quedan con paginas => false para armar el schema; las raíces y sus hijas generan páginas.
"""
import argparse, json, os, sys

NIVEL = {"pais": 0, "provincia": 0, "region": 0, "departamento": 1, "partido": 1, "ciudad": 2, "localidad": 3, "barrio": 4}
TIPO_PHP = {"partido": "ciudad", "region": "departamento", "provincia": "provincia"}  # tipos que la plantilla no conoce → equivalentes

def cargar(pais):
    ruta = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "assets", "zonas-uy.json" if pais == "uy" else "zonas-caba-amba.json")
    with open(ruta, encoding="utf-8") as f:
        return json.load(f)["zonas"]

def ancestros(z, slug):
    out = []
    p = z[slug]["padre"]
    while p:
        out.insert(0, p); p = z[p]["padre"]
    return out

def descendientes(z, slug):
    return [s for s, d in z.items() if slug in ancestros(z, s)]

def seleccionar(z, raices, nivel, prioridad_max, excluir, incluir=()):
    nivel_max = NIVEL[nivel] if nivel in NIVEL else int(nivel)
    sel = {}
    for r in raices:
        if r not in z: sys.exit(f"Raíz desconocida: {r}. Disponibles: " + ", ".join(s for s, d in z.items() if d['tipo'] in ('departamento','partido','region','provincia')))
        for a in ancestros(z, r):
            sel[a] = dict(z[a], paginas=False)      # jerarquía sin páginas
        sel[r] = dict(z[r], paginas=True)
        base = NIVEL[z[r]["tipo"]]
        for s in descendientes(z, r):
            d = z[s]
            if s in excluir: continue
            if s in incluir: sel[s] = dict(d, paginas=True); continue
            if NIVEL[d["tipo"]] > nivel_max: continue
            if d.get("prioridad", 2) > prioridad_max: continue
            if any(a in excluir for a in ancestros(z, s)): continue
            sel[s] = dict(d, paginas=True)
    # padres intermedios excluidos por prioridad pero necesarios como jerarquía
    for s in list(sel):
        for a in ancestros(z, s):
            if a not in sel: sel[a] = dict(z[a], paginas=False)
    return sel

def contar(sel, servicios):
    con = [s for s, d in sel.items() if d.get("paginas")]
    hubs = len(con)
    sz = hubs * servicios
    print(f"Zonas con páginas: {hubs}  (hubs /{{zona}}: {hubs}; servicio×zona: {hubs} × {servicios} servicios = {sz})")
    print(f"Total estimado con troncales, home, zonas, nosotros, contacto y legales: {hubs + sz + servicios + 5}")
    from collections import Counter
    print("Por tipo:", dict(Counter(sel[s]["tipo"] for s in con)))
    print("Por prioridad:", dict(Counter(sel[s].get("prioridad", 2) for s in con)))
    print("Zonas:", ", ".join(sorted(con)))

def php_str(s):
    return "'" + str(s).replace("\\", "\\\\").replace("'", "\\'") + "'"

def php_list(xs):
    return "[" + ", ".join(php_str(x) for x in xs) + "]"

def generar(sel, salida, pais):
    orden = sorted(sel, key=lambda s: (len(ancestros(sel, s)) if s in sel else 0, s))
    lines = ["<?php", "/**", " * ZONAS generadas por armar-sitio-local/scripts/zonas.py. Formato: ver PLANTILLA.md §3.3.", " * Editar vecinos, referencias, tiempo_llegada y notas con datos del operador antes de publicar.", " */", "return [", ""]
    for s in orden:
        d = sel[s]
        tipo = TIPO_PHP.get(d["tipo"], d["tipo"])
        vec = [v for v in d.get("vecinos", []) if v in sel and sel[v].get("paginas")]
        campos = [f"'nombre' => {php_str(d['nombre'])}", f"'tipo' => {php_str(tipo)}", f"'padre' => {php_str(d['padre']) if d['padre'] else 'null'}"]
        if not d.get("paginas", True): campos.append("'paginas' => false")
        if d.get("nombre_en"): campos.append(f"'nombre_en' => {php_str(d['nombre_en'])}")
        if vec: campos.append(f"'vecinos' => {php_list(vec)}")
        if d.get("referencias"): campos.append(f"'referencias' => {php_list(d['referencias'])}")
        campos.append("'tiempo_llegada' => ''")
        campos.append("'notas' => ''")
        campos.append(f"'vivienda' => {php_str(d.get('vivienda','mixto'))}")
        if d.get("codigo"): campos.append(f"'codigo' => {php_str(d['codigo'])}")
        if d.get("wikidata"): campos.append(f"'wikidata' => {php_str(d['wikidata'])}")
        lines.append(f"    {php_str(s)} => [")
        for c in campos: lines.append(f"        {c},")
        lines.append("    ],")
    lines += ["", "];", ""]
    os.makedirs(os.path.dirname(os.path.abspath(salida)), exist_ok=True)
    with open(salida, "w", encoding="utf-8") as f: f.write("\n".join(lines))
    print(f"Escrito {salida}: {sum(1 for d in sel.values() if d.get('paginas'))} zonas con páginas, {len(sel)} entradas")

def main():
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("accion", choices=["contar", "generar", "listar"])
    ap.add_argument("--pais", choices=["uy", "ar"], required=True)
    ap.add_argument("--raices", default="", help="slugs separados por coma")
    ap.add_argument("--nivel", default="barrio", help="departamento|ciudad|localidad|barrio")
    ap.add_argument("--prioridad-max", type=int, default=3)
    ap.add_argument("--servicios", type=int, default=1)
    ap.add_argument("--excluir", default="")
    ap.add_argument("--incluir", default="", help="slugs a incluir aunque no pasen el nivel o la prioridad")
    ap.add_argument("--salida", default="data/zonas.php")
    a = ap.parse_args()
    z = cargar(a.pais)
    if a.accion == "listar":
        for s, d in z.items():
            if d["tipo"] in ("departamento", "partido", "region", "provincia"):
                hijas = [x for x, y in z.items() if y["padre"] == s]
                print(f"{s:28s} {d['nombre']:32s} {d['tipo']:12s} hijas: {len(hijas)}")
        return
    raices = [r.strip() for r in a.raices.split(",") if r.strip()]
    if not raices: sys.exit("--raices es obligatorio")
    excluir = set(x.strip() for x in a.excluir.split(",") if x.strip())
    incluir = set(x.strip() for x in a.incluir.split(",") if x.strip())
    sel = seleccionar(z, raices, a.nivel, a.prioridad_max, excluir, incluir)
    if a.accion == "contar": contar(sel, a.servicios)
    else: generar(sel, a.salida, a.pais)

if __name__ == "__main__":
    main()
