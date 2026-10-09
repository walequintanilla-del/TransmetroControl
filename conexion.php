<?php
// CONTROL DE AUDITORÍA VISIBLE EN EL NAVEGADOR
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CREDENCIALES COMPROBADAS DE TU NUEVA BASE DE DATOS DE HOY
$host = "zephyr.proxy.rlwy.net"; 
$user = "root"; 
$password = "kpgzWuwjRetPaoBhbSGDVKIjaJpcoQPl"; // Tu contraseña real de fábrica de hoy
$database = "railway"; 
$port = 55544; // Tu puerto público real de hoy de tu captura

// Enlace transaccional hacia la nube
$conexion = mysqli_connect($host, $user, $password, $database, $port);

if (!$conexion) {
    die("<div class='alert alert-danger text-center my-3'>Error de enlace con la nube: " . mysqli_connect_error() . "</div>");
}

mysqli_set_charset($conexion, "utf8");
?>
