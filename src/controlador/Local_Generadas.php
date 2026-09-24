<?php

/**
 * Landings generadas por datos (ciudades x servicios) para Local_Controller.
 *
 * No hay un metodo por URL: App.php consulta static::registro() cuando el metodo no existe,
 * y sitemap.php enumera _generadas(). Para sumar una ciudad basta agregarla a CITY_DATA_EXTRA;
 * para sumar un servicio, agregarlo a Local_Controller::CITY_SERVICES (o Local_Datos::EXTRA_SERVICES)
 * y escribir su contenido en Local_Controller::contenidoServicio().
 *
 * URL resultante: /local/{servicio}-{ciudad}  (ej. /local/cerrajero-pocitos)
 */
require_once 'src/controlador/Local_Datos.php';

trait Local_Generadas
{
    /** Todas las ciudades (las de CITY_DATA + las de CITY_DATA_EXTRA) */
    public static function ciudades(): array
    {
        return self::CITY_DATA + Local_Datos::CITY_DATA_EXTRA;
    }

    /** Ciudades + zonas padre, con la misma forma de dato */
    public static function zonasTodas(): array
    {
        return self::ciudades() + Local_Datos::ZONAS_PADRE;
    }

    /** Todos los servicios con landing local: slug => nombre */
    public static function servicios(): array
    {
        return self::CITY_SERVICES + Local_Datos::EXTRA_SERVICES;
    }

    /** Zona padre de una ciudad (para linkear la landing "grande" del departamento) */
    public static function padreDe(array $ciudad): ?string
    {
        foreach (Local_Datos::ZONAS_PADRE as $zk => $z) {
            if (($z['depto'] ?? '') === ($ciudad['depto'] ?? '')) return $zk;
        }
        return null;
    }

    /** Registro de landings generadas de este controlador: nombre_de_metodo => [metodo generador, args] */
    protected static function registro(): array
    {
        $r = [];
        foreach (array_keys(self::zonasTodas()) as $zk) {
            foreach (array_keys(self::servicios()) as $s) {
                $r[str_replace('-', '_', $s . '-' . $zk)] = ['cityLanding', [$s, $zk]];
            }
        }
        // Las que ya tienen metodo escrito a mano no se generan
        foreach (array_keys($r) as $k) {
            if (method_exists(self::class, $k)) unset($r[$k]);
        }
        return $r;
    }

    /** Usado por sitemap.php y App.php */
    public static function _generadas(): array
    {
        return static::registro();
    }

    /** Despacha una landing generada. Devuelve false si no existe. */
    public function _generada(string $metodo): bool
    {
        $r = static::registro();
        if (!isset($r[$metodo])) return false;
        [$fn, $args] = $r[$metodo];
        $this->{$fn}(...$args);
        return true;
    }
}
