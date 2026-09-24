<?php

namespace benjamin\plantillaweb\libs;

class Controlador
{
  public $datos;
  public function __construct()
  {
  }

  function cargarVista($vistaRuta, $datos = null)
  {
      global $ruta, $url;

      if ($datos) {
          extract($datos); // Convierte las claves del array en variables.
      }
      $vistaArchivo = "src/vista/{$vistaRuta}.php";
      require_once $vistaArchivo;
  }

}
