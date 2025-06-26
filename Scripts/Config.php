<?php
// Iniciar sesión si no está iniciada
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Datos de conexión a la base de datos MySQL
$host = "mainline.proxy.rlwy.net";
$user = "root";
$password = "sypQDjSqRjtivZxfIGTKDFkteoCshGWo"; // En XAMPP suele estar vacío
$database = "railway"; // Cambialo si usás otro nombre de BD
$puerto = 29225; // Puerto de conexión, si es necesario

// Crear conexión
$conn = new mysqli($host, $user, $password, $database, $puerto);

// Verificar si hay error de conexión
if ($conn->connect_error) {
    die("Conexión fallida a MySQL: " . $conn->connect_error);
}
?>