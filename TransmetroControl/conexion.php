<?php
// CONTROL DE AUDITORÍA VISIBLE
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$password = ""; // Contraseña vacía estándar de XAMPP
$database = "transmetro"; // Sincronizado con tu esquema real
$port = 3307; // Puerto real de tu XAMPP

// VARIABLE UNIFICADA CORRECTA: $conexion
$conexion = mysqli_connect($host, $user, $password, $database, $port);

if (!$conexion) {
    die("<div class='alert alert-danger text-center my-3'>Error de enlace: " . mysqli_connect_error() . "</div>");
}

mysqli_set_charset($conexion, "utf8");
?>
