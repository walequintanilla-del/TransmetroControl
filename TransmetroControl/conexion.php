<?php
// CONTROL DE AUDITORÍA VISIBLE EN EL NAVEGADOR
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CREDENCIALES REMOTAS REALES DE TU NUBE DE RAILWAY
$host = "zephyr.proxy.rlwy.net"; // Servidor público de internet
$user = "root"; // Usuario administrador por defecto
$password = "admin"; // La contraseña que guardaste en tu Raw Editor
$database = "railway"; // El esquema por defecto de tu Railway
$port = 23526; // Tu puerto público de 5 dígitos asignado

// Enlace transaccional hacia la nube
$conexion = mysqli_connect($host, $user, $password, $database, $port);

if (!$conexion) {
    die("<div class='alert alert-danger text-center my-3'>Error de enlace con la nube: " . mysqli_connect_error() . "</div>");
}

mysqli_set_charset($conexion, "utf8");
?>
