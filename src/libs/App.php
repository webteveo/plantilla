<?php
namespace benjamin\plantillaweb\libs;

class App
{
  /**
   * Rutas "bonitas" de un solo segmento que NO coinciden con el nombre de un metodo.
   * clave = primer segmento de la URL, valor = [Controlador, metodo].
   * Normalmente no hace falta: cualquier metodo publico de un controlador de CONTROLADORES_RAIZ
   * ya se sirve como /{nombre-del-metodo-con-guiones}. Usar solo para alias.
   * Ejemplo: 'cerrajero' => ['Landings', 'cerrajero_montevideo'],
   */
  private const RUTAS = [];

  /** Controladores que atienden URLs de un solo segmento por nombre de metodo (/mi-landing -> Landings::mi_landing) */
  public const CONTROLADORES_RAIZ = ['Landings'];

  /**
   * Rutas de dos segmentos donde el 2do segmento es un parametro (/articulos/{slug} -> Articulos::ver($slug)).
   * clave = primer segmento, valor = [Controlador, metodo]. Solo aplica cuando seg1 no es un metodo publico del controlador.
   */
  private const RUTAS_DINAMICAS = [
    'articulos' => ['Articulos', 'ver'],
    'proyectos' => ['Proyectos', 'ver'],
  ];

  public static function iniciar()
  {
    // Lee la URL limpia tipo /blog/index
    $urlRaw = $_GET['url'] ?? 'index/index';
    $url = explode('/', trim($urlRaw, '/'));

    $seg0 = $url[0] ?? 'index';
    $seg1 = $url[1] ?? null;
    $param = null;

    if (isset(self::RUTAS[$seg0]) && $seg1 === null) {
      [$c, $m] = self::RUTAS[$seg0];
    } elseif ($seg1 === null && $seg0 !== '' && !file_exists('src/controlador/' . ucfirst(str_replace('-', '_', $seg0)) . '_Controller.php') && ($hit = self::resolverRaiz($seg0))) {
      [$c, $m] = $hit;
    } elseif (isset(self::RUTAS_DINAMICAS[$seg0]) && $seg1 !== null && !isset($url[2]) && preg_match('/^[a-z0-9-]+$/', $seg1) && !self::esMetodoPublico(self::RUTAS_DINAMICAS[$seg0][0], str_replace('-', '_', $seg1))) {
      [$c, $m] = self::RUTAS_DINAMICAS[$seg0];
      $param = $seg1;
    } else {
      $c = str_replace('-', '_', $seg0 ?: 'index');
      $m = str_replace('-', '_', $seg1 ?? 'index');
      // Los controladores raiz solo se sirven como /{slug}; /landings/{slug} seria contenido duplicado
      if (in_array(ucfirst($c), self::CONTROLADORES_RAIZ, true)) {
        self::error404();
      }
    }

    // Armado de clase controladora
    $con            = ucfirst($c) . "_Controller";
    $controllerPath = 'src/controlador/' . $con . ".php";

    if (!preg_match('/^[A-Za-z0-9_]+$/', $c) || !preg_match('/^[A-Za-z0-9_]+$/', $m) || !file_exists($controllerPath)) {
      self::error404();
    }

    require_once $controllerPath;
    $controller = new $con();

    // Solo metodos publicos, no magicos y no heredados de la clase base
    if (str_starts_with($m, '_')) {
      self::error404();
    }
    if (!method_exists($controller, $m)) {
      // Landings generadas por datos (ciudades x servicios): ver Local_Generadas
      if (method_exists($controller, '_generada') && $controller->_generada($m)) return;
      self::error404();
    }
    $ref = new \ReflectionMethod($controller, $m);
    if (!$ref->isPublic() || $ref->isStatic() || $ref->getDeclaringClass()->getName() === Controlador::class) {
      self::error404();
    }

    $param === null ? $controller->{$m}() : $controller->{$m}($param);
  }

  private static function esMetodoPublico(string $c, string $m): bool
  {
    $path = 'src/controlador/' . $c . '_Controller.php';
    if (!file_exists($path) || !preg_match('/^[a-z0-9_]+$/', $m) || str_starts_with($m, '_')) return false;
    require_once $path;
    $cls = $c . '_Controller';
    if (!method_exists($cls, $m)) return false;
    $ref = new \ReflectionMethod($cls, $m);
    return $ref->isPublic() && !$ref->isStatic() && $ref->getDeclaringClass()->getName() === $cls;
  }

  private static function resolverRaiz(string $seg): ?array
  {
    $m = str_replace('-', '_', $seg);
    if (!preg_match('/^[a-z0-9_]+$/', $m) || str_starts_with($m, '_')) return null;
    foreach (self::CONTROLADORES_RAIZ as $c) {
      $path = 'src/controlador/' . $c . '_Controller.php';
      if (!file_exists($path)) continue;
      require_once $path;
      $cls = $c . '_Controller';
      if (method_exists($cls, $m)) {
        $ref = new \ReflectionMethod($cls, $m);
        if ($ref->isPublic() && !$ref->isStatic() && $ref->getDeclaringClass()->getName() === $cls) return [$c, $m];
      } elseif (method_exists($cls, '_generadas') && isset($cls::_generadas()[$m])) {
        return [$c, $m];
      }
    }
    return null;
  }

  public static function error404(): void
  {
    http_response_code(404);
    global $ruta, $url;
    require 'src/vista/errores/404.php';
    exit;
  }
}
