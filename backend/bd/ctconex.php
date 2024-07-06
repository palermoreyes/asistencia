<?php
// Desactiva la visualización de errores en la pantalla
ini_set('display_errors', 0);
// Registra los errores en el log de errores del servidor
ini_set('log_errors', 1);
// Establece el nivel de error que se registrará (opcional)
error_reporting(E_ALL & ~E_NOTICE);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define database
define('dbhost', 'localhost');
define('dbuser', 'root');
define('dbpass', '');
define('dbname', 'asistencia');
// Connecting database
try {
    $connect = new PDO("mysql:host=".dbhost.";dbname=".dbname, dbuser, dbpass);
    $connect->query("set names utf8;");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch(PDOException $e) {
    // Manejar el error de conexión de alguna manera
    // Puedes redirigir a una página de error o mostrar un mensaje
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

// Cierre explícito de la sesión (opcional)
// session_write_close();
?>
