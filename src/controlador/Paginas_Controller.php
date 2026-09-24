<?php

use benjamin\plantillaweb\libs\Controlador;

class Paginas_Controller extends Controlador
{
    public function servicios()
    {
        $this->cargarVista('paginas/servicios');
    }

    public function proyectos()
    {
        \benjamin\plantillaweb\libs\App::error404(); // Oculto por ahora
    }
    private function proyectos_oculto()
    {
        header('Location: ' . ($GLOBALS['url'] ?? '/') . 'proyectos', true, 301);
        exit;
    }

    public function zonas()
    {
        $this->cargarVista('paginas/zonas');
    }

    public function diferenciales()
    {
        $this->cargarVista('paginas/diferenciales');
    }
}
