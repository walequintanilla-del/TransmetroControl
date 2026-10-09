<?php 
// 1. FORZAR REGISTRO DE ERRORES VISIBLES PARA AUDITORÍA
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. INCLUIR LA CONEXIÓN CON TUS CREDENCIALES DE WALDEMAR
include("conexion.php"); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Control de seguridad: Si no hay login, expulsar al usuario
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
// Detectar el envío del formulario de manera segura para XAMPP
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_recorrido = isset($_POST['id_recorrido']) ? intval($_POST['id_recorrido']) : 0;
    $id_estacion  = isset($_POST['id_estacion']) ? intval($_POST['id_estacion']) : 0;
    $pasajeros_estacion = isset($_POST['pasajeros_en_estacion']) ? intval($_POST['pasajeros_en_estacion']) : 0;
    $pasajeros_bus = isset($_POST['pasajeros_en_bus']) ? intval($_POST['pasajeros_en_bus']) : 0;

    // Insertar la telemetría en caliente. ¡El Trigger de MySQL Workbench procesará las alertas al instante!
    $sql = "INSERT INTO visita_estacion (id_recorrido, id_estacion, pasajeros_en_estacion, pasajeros_en_bus) 
            VALUES ('$id_recorrido', '$id_estacion', '$pasajeros_estacion', '$pasajeros_bus')";
    
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert alert-success border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'>
                        <i class='fa-solid fa-circle-check me-2'></i>Telemetría transmitida. ¡El motor automático evaluó el tráfico con éxito!
                    </div>";
    } else {
        $mensaje = "<div class='alert alert-danger border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'>
                        <i class='fa-solid fa-circle-xmark me-2'></i>Error en la simulación: " . mysqli_error($conexion) . "
                    </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmetro - Simulador Operativo</title>
    <!-- CDNs Oficiales Completos para Carga de Estilos Premium -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #090d16;
            --bg-sidebar: #0f1524;
            --bg-card: #141b2d;
            --text-muted: #64748b;
            --lime-neon: #a3e635; /* Verde Limón */
        }
        
        body { 
            font-family: 'Urbanist', sans-serif; 
            background-color: var(--bg-main); 
            color: #f8fafc;
            overflow-x: hidden; 
        }
        
        /* Sidebar Ejecutivo Unificado */
        .sidebar { 
            min-width: 280px; 
            max-width: 280px; 
            background-color: var(--bg-sidebar); 
            min-height: 100vh; 
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .sidebar .nav-link { 
            color: #94a3b8; 
            padding: 14px 24px; 
            display: flex; 
            align-items: center; 
            font-weight: 500;
            border-radius: 0 50px 50px 0;
            margin-right: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: #000000 !important; 
            background: linear-gradient(90deg, var(--lime-neon) 0%, #c084fc 100%); 
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(163, 230, 53, 0.2);
        }
        
        /* Tarjeta Central del Simulador */
        .card-custom { 
            border: 1px solid rgba(255, 255, 255, 0.03); 
            border-radius: 24px; 
            background-color: var(--bg-card);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3); 
        }

        .form-label {
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-select, .form-control {
            background-color: #1f293d !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            padding: 12px;
            font-size: 15px;
        }

        .form-select:focus, .form-control:focus {
            box-shadow: none !important;
            border-color: rgba(163, 230, 53, 0.4) !important;
        }

        .form-text {
            color: var(--text-muted) !important;
            font-size: 11px;
            line-height: 1.4;
            margin-top: 6px;
        }

        /* Botón de Transmisión */
        .btn-gradient { 
            background: linear-gradient(135deg, var(--lime-neon) 0%, #84cc16 100%); 
            color: #000000 !important; 
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163, 230, 53, 0.3);
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- BARRA LATERAL (SIDEBAR UNIFICADO) -->
    <nav class="sidebar d-flex flex-column p-0">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
            <h3 class="fw-extrabold mb-0" style="color: var(--lime-neon); letter-spacing: -1px;"><i class="fa-solid fa-bus-simple me-2"></i>TRANSMETRO</h3>
            <small class="text-uppercase tracking-widest text-muted fw-bold" style="font-size: 10px;">Centro de Monitoreo</small>
            <div class="mt-3 small text-white bg-white bg-opacity-5 py-1.5 px-3 rounded-pill d-inline-block border border-white border-opacity-10">
                <i class="fa-solid fa-circle-user text-success me-1.5 small"></i> <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>
            </div>
        </div>
        
        <!-- Menú Modular Sincronizado de 8 Botones -->
        <ul class="nav flex-column mt-4 flex-grow-1">
            <a href="index.php" class="nav-link"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard General</a>
            <a href="simulador.php" class="nav-link active"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            
            <div class="px-4 pt-4 pb-1 text-uppercase tracking-wider text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Ingreso de Datos</div>
            <a href="municipalidades.php" class="nav-link"><i class="fa-solid fa-landmark-dome me-3"></i>Municipalidades</a>
            <a href="rutas.php" class="nav-link"><i class="fa-solid fa-route me-3"></i>Líneas (Rutas)</a>
            <a href="estaciones.php" class="nav-link"><i class="fa-solid fa-building-user me-3"></i>Estaciones</a>
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
            <a href="seguridad.php" class="nav-link"><i class="fa-solid fa-shield-halved me-3"></i>Guardias y Accesos</a>
            <a href="terminales.php" class="nav-link"><i class="fa-solid fa-laptop-code me-3"></i>Computadoras de Andén</a>
        </ul>
        
        <!-- PIE DE PÁGINA CORPORATIVO -->
        <div class="p-3 border-top border-secondary border-opacity-10 text-center">
            <div class="mb-3" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">
                © 2026 La Sierra, S.A.<br>Derechos Reservados.
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 fw-semibold rounded-3"><i class="fa-solid fa-power-off me-2"></i>Cerrar Sesión</a>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL DEL SIMULADOR -->
    <main class="flex-grow-1 p-5 d-flex align-items-center justify-content-center">
        <div class="container-fluid" style="max-width: 680px;">
            
            <div class="card card-custom p-5">
                <div class="text-center mb-4">
                    <div class="icon-box-lime mx-auto mb-3" style="width: 65px; height: 65px; background: rgba(163, 230, 53, 0.1); border-radius: 50%;">
                        <i class="fa-solid fa-tower-broadcast fa-xl"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">Consola de Telemetría en Vivo</h3>
                    <p class="text-muted small mb-0">Modula los aforos de andén y pasajeros a bordo para detonar las alertas del centro de monitoreo.</p>
                </div>

                <!-- Despliegue de mensajes de éxito o fallo -->
                <div class="mb-3">
                    <?php echo $mensaje; ?>
                </div>

                <form action="simulador.php" method="POST">
                    <!-- SELECCIONAR RECORRIDO/BUS -->
                    <div class="mb-3 text-start">
                        <label class="form-label text-uppercase">Unidad y Ruta Activa</label>
                        <select name="id_recorrido" class="form-select" required>
                            <option value="">-- Escoger Unidad BRT --</option>
                            <?php
                            // Asegurar un recorrido inicial para las pruebas automáticas
                            $check_rec = mysqli_query($conexion, "SELECT id_recorrido FROM recorrido LIMIT 1");
