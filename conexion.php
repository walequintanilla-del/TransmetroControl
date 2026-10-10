<?php

$host = getenv('MYSQLHOST');
$usuario = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$basedatos = getenv('MYSQLDATABASE');
$puerto = getenv('MYSQLPORT');

if (!$host || !$usuario || $password === false || !$basedatos || !$puerto) {
    die("Faltan variables de conexion de MySQL en Railway.");
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = mysqli_connect(
        $host,
        $usuario,
        $password,
        $basedatos,
        (int)$puerto
    );

    mysqli_set_charset($conexion, "utf8mb4");

} catch (mysqli_sql_exception $e) {
    error_log("Error de conexion MySQL: " . $e->getMessage());
    die("No se pudo conectar con la base de datos.");
}
?>
