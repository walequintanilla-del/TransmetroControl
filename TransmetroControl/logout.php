<?php
// 1. INICIALIZAR EL ENTORNO DE SESIÓN
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. COMPROBAR Y VACIAR TODAS LAS VARIABLES DE OPERADOR
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. DESTRUIR LA SESIÓN FÍSICA EN EL SERVIDOR
session_destroy();

// 4. REDIRIGIR AL OPERADOR DE INMEDIATO AL LOGIN PREMIUM
header("Location: login.php");
exit();
?>
