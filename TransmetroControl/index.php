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

// Consultas dinámicas para indicadores de andén
$q_lineas = mysqli_query($conexion, "SELECT COUNT(*) as total FROM linea");
$cant_lineas = ($q_lineas) ? mysqli_fetch_assoc($q_lineas)['total'] : 0;

$q_estaciones = mysqli_query($conexion, "SELECT COUNT(*) as total FROM estacion");
$cant_estaciones = ($q_estaciones) ? mysqli_fetch_assoc($q_estaciones)['total'] : 0;

$q_buses = mysqli_query($conexion, "SELECT COUNT(*) as total FROM bus");
$cant_buses = ($q_buses) ? mysqli_fetch_assoc($q_buses)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmetro - Control Inteligente</title>
    <!-- CDNs Oficiales de Diseño de Interfaz Premium -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #090d16;
            --bg-sidebar: #0f1524;
            --bg-card: #141b2d;
            --text-muted: #64748b;
            --lime-neon: #a3e635; /* Verde Limón Principal */
            --lime-hover: #84cc16;
        }
        
        body { 
            font-family: 'Urbanist', sans-serif; 
            background-color: var(--bg-main); 
            color: #f8fafc;
            overflow-x: hidden; 
        }
        
        /* Sidebar Ejecutivo */
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
        
        /* Tarjetas de Datos Metropolitano */
        .card-stat { 
            border: 1px solid rgba(255, 255, 255, 0.03); 
            border-radius: 16px; 
            background-color: var(--bg-card);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        
        .card-stat:hover { 
            transform: translateY(-5px);
            border-color: rgba(163, 230, 53, 0.3);
            box-shadow: 0 12px 40px rgba(163, 230, 53, 0.08);
        }
        
        .icon-box-lime {
            background-color: rgba(163, 230, 53, 0.1);
            color: var(--lime-neon);
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        /* Alertas de Tráfico Dinámicas (Req 14 y 15) */
        .alert-item-critical { 
            border-left: 4px solid #ef4444; 
            background: rgba(239, 68, 68, 0.05); 
            border-radius: 12px;
            animation: pulse-border-red 2s infinite;
        }
        
        .alert-item-warning { 
            border-left: 4px solid var(--lime-neon); 
            background: rgba(163, 230, 53, 0.05); 
            border-radius: 12px;
        }

        @keyframes pulse-border-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.2); }
            70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        
        /* Tablas Modificadas */
        .table-custom {
            color: #cbd5e1;
        }
        .table-custom thead th {
            background-color: rgba(255,255,255,0.02) !important;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .table-custom tbody td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            padding: 14px 12px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- BARRA LATERAL (SIDEBAR) -->
    <nav class="sidebar d-flex flex-column p-0">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
            <h3 class="fw-extrabold mb-0" style="color: var(--lime-neon); letter-spacing: -1px;"><i class="fa-solid fa-bus-simple me-2"></i>TRANSMETRO</h3>
            <small class="text-uppercase tracking-widest text-muted fw-bold" style="font-size: 10px;">Centro de Monitoreo</small>
            <div class="mt-3 small text-white bg-white bg-opacity-5 py-1.5 px-3 rounded-pill d-inline-block border border-white border-opacity-10">
                <i class="fa-solid fa-circle-user text-success me-1.5 small"></i> <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>
            </div>
        </div>
        
        <!-- Menú de Navegación por Módulos -->
        <ul class="nav flex-column mt-4 flex-grow-1">
            <a href="index.php" class="nav-link active"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard General</a>
            <a href="simulador.php" class="nav-link"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            
            <div class="px-4 pt-4 pb-1 text-uppercase tracking-wider text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Ingreso de Datos</div>
            <a href="municipalidades.php" class="nav-link"><i class="fa-solid fa-landmark-dome me-3"></i>Municipalidades</a>
            <a href="rutas.php" class="nav-link"><i class="fa-solid fa-route me-3"></i>Líneas (Rutas)</a>
            <a href="estaciones.php" class="nav-link"><i class="fa-solid fa-building-user me-3"></i>Estaciones</a>
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
            <a href="seguridad.php" class="nav-link"><i class="fa-solid fa-shield-halved me-3"></i>Guardias y Accesos</a>
            <a href="terminales.php" class="nav-link"><i class="fa-solid fa-laptop-code me-3"></i>Computadoras de Andén</a>
        </ul>
        
        <!-- PIE DE PÁGINA: LA SIERRA S.A. -->
        <div class="p-3 border-top border-secondary border-opacity-10 text-center">
            <div class="mb-3" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">
                © 2026 La Sierra, S.A.<br>Derechos Reservados.
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 fw-semibold rounded-3"><i class="fa-solid fa-power-off me-2"></i>Cerrar Sesión</a>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-grow-1 p-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold tracking-tight text-white mb-1">Cuadro de Mando Ejecutivo</h1>
                <p class="text-muted mb-0">Telemetría operativa e índice de tráfico metropolitano en vivo.</p>
            </div>
            <div class="p-3 rounded-4 shadow-sm text-end border border-white border-opacity-5" style="background-color: var(--bg-sidebar);">
                <span class="d-block text-muted small fw-medium mb-1">Base de Datos Local</span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2 px-3 rounded-pill fw-bold">
                    <i class="fa-solid fa-database text-success me-1.5 small"></i> MySQL Activo (Waldemar)
                </span>
            </div>
        </div>

        <!-- INDICADORES DIGITALES DESDE LA BASE DE DATOS -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-md-4">
                <div class="card card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="text-muted d-block text-uppercase small fw-bold tracking-wider">Rutas Habilitadas</span>
                        <h2 class="fw-extrabold text-white mt-1 mb-0"><?php echo $cant_lineas; ?> Corredores</h2>
                    </div>
                    <div class="icon-box-lime"><i class="fa-solid fa-map-location-dot fa-xl"></i></div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="text-muted d-block text-uppercase small fw-bold tracking-wider">Estaciones en Red</span>
                        <h2 class="fw-extrabold text-white mt-1 mb-0"><?php echo $cant_estaciones; ?> Andenes</h2>
                    </div>
