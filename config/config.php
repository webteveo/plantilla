<?php

//define('URL', 'http://localhost/mvc/');
define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '//');
require_once __DIR__ . '/variables.php';

//conexion a la base de datos
define('HOST', 'localhost');
define('PORT_DB', '3307');
define('DB', 'destoconadorauy');
define('USER', 'root');
define('PASSWORD', '');
define('CHARSET', 'utf8mb4');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
