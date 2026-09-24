<?php

use benjamin\plantillaweb\libs\Controlador;
use benjamin\plantillaweb\libs\Conexion;


class Index_Controller extends Controlador
{
  // index
  public function index()
  {
    $this->cargarVista("index/index");
  }

}