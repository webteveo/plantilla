<?php

use benjamin\plantillaweb\libs\App;
use benjamin\plantillaweb\libs\Controlador;

/**
 * /proyectos          -> listado
 * /proyectos/{slug}   -> detalle (ruta dinamica declarada en App::RUTAS_DINAMICAS)
 * Los proyectos viven en data/proyectos.json; no hace falta un metodo por proyecto.
 */
class Proyectos_Controller extends Controlador
{
    public function index()
    {
        \benjamin\plantillaweb\libs\App::error404(); // Oculto por ahora
    }
    private function index_oculto()
    {
        $this->cargarVista('proyectos/index', [
            'proyectos' => $this->getProyectos(),
        ]);
    }

    public function ver(string $slug = '')
    {
        \benjamin\plantillaweb\libs\App::error404(); // Oculto por ahora
    }
    private function ver_oculto(string $slug = '')
    {
        $proyecto = $slug !== '' ? $this->findProyecto($slug) : null;
        if (!$proyecto) {
            App::error404();
        }

        $this->cargarVista('proyectos/detalle', [
            'proyecto' => $proyecto,
        ]);
    }

    private function findProyecto(string $slug): ?array
    {
        foreach ($this->getProyectos() as $proyecto) {
            if (($proyecto['slug'] ?? '') === $slug) {
                return $proyecto;
            }
        }

        return null;
    }

    private function getProyectos(): array
    {
        $json = @file_get_contents('data/proyectos.json');
        $proyectos = json_decode($json ?: '[]', true);

        return is_array($proyectos) ? $proyectos : [];
    }
}
