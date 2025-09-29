<?php
// php/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_bd');
define('DB_PASS', 'tu_contrasena_bd');
define('DB_NAME', 'tu_nombre_bd');

// Conexión a la base de datos
function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>