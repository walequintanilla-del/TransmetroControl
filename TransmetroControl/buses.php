<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); 

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit(); }

$mensaje = "";
if (isset($_GET['eliminar'])) {
    $id_del = intval($_GET['eliminar']);
    $sql_del = "DELETE FROM bus WHERE id_bus = $id_del";
    if (mysqli_query($conexion, $sql_del)) {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'><i class='fa-solid fa-trash me-2'></i>Unidad removida del parque automotor municipal.</div>";
    } else {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'><i class='fa-solid fa-circle-xmark me-2'></i>No se puede eliminar la unidad: Está en servicio activo.</div>";
    }
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = mysqli_real_escape_string($conexion, $_POST['placa']);
    $id_linea = intval($_POST['id_linea']);
    $capacidad = intval($_POST['capacidad_maxima']);
    $id_parqueo = intval($_POST['id_parqueo']);
    
    $linea_sql = ($id_linea > 0) ? $id_linea : "NULL";

    $sql_ins = "INSERT INTO bus (placa, id_linea, capacidad_maxima, id_parqueo) VALUES ('$placa', $linea_sql, $capacidad, $id_parqueo)";
    if (mysqli_query($conexion, $sql_ins)) {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'><i class='fa-solid fa-circle-check me-2'></i>Bus inyectado al inventario con éxito.</div>";
    } else {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'>Error: Placa duplicada o parqueo inválido.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transmetro - Control de Flota</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        :root { --bg-main: #090d16; --bg-sidebar: #0f1524; --bg-card: #141b2d; --text-muted: #64748b; --lime-neon: #a3e635; }
        body { font-family: 'Urbanist', sans-serif; background-color: var(--bg-main); color: #f8fafc; overflow-x: hidden; }
        .sidebar { min-width: 280px; max-width: 280px; background-color: var(--bg-sidebar); min-height: 100vh; border-right: 1px solid rgba(255, 255, 255, 0.05); }
        .sidebar .nav-link { color: #94a3b8; padding: 14px 24px; display: flex; align-items: center; font-weight: 500; border-radius: 0 50px 50px 0; margin-right: 15px; transition: all 0.3s ease; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #000000 !important; background: linear-gradient(90deg, var(--lime-neon) 0%, #c084fc 100%); font-weight: 700; box-shadow: 0 4px 15px rgba(163, 230, 53, 0.2); }
        .card-elegant { border: 1px solid rgba(255, 255, 255, 0.03); border-radius: 20px; background-color: var(--bg-card); box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .form-control, .form-select { background-color: #1f293d !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; color: #ffffff !important; border-radius: 12px !important; padding: 12px; }
        .btn-lime { background: linear-gradient(135deg, var(--lime-neon) 0%, #84cc16 100%); color: #000000 !important; border: none; border-radius: 12px; padding: 13px; font-weight: 700; }
        .table-custom { color: #cbd5e1; }
        .table-custom thead th { background-color: rgba(255,255,255,0.02) !important; color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; }
        .table-custom tbody td { border-bottom: 1px solid rgba(255, 255, 255, 0.02); padding: 14px 12px; }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar d-flex flex-column p-0">
        <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
            <h3 class="fw-extrabold mb-0" style="color: var(--lime-neon);"><i class="fa-solid fa-bus-simple me-2"></i>TRANSMETRO</h3>
            <small class="text-uppercase tracking-widest text-muted fw-bold" style="font-size: 10px;">Centro de Monitoreo</small>
        </div>
        <ul class="nav flex-column mt-4 flex-grow-1">
            <a href="index.php" class="nav-link"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard General</a>
            <a href="simulador.php" class="nav-link"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            <div class="px-4 pt-4 pb-1 text-uppercase tracking-wider text-muted fw-bold" style="font-size: 10px;">Ingreso de Datos</div>
            <a href="municipalidades.php" class="nav-link"><i class="fa-solid fa-landmark-dome me-3"></i>Municipalidades</a>
            <a href="rutas.php" class="nav-link"><i class="fa-solid fa-route me-3"></i>Líneas (Rutas)</a>
            <a href="estaciones.php" class="nav-link"><i class="fa-solid fa-building-user me-3"></i>Estaciones</a>
            <a href="buses.php" class="nav-link active"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
            <a href="seguridad.php" class="nav-link"><i class="fa-solid fa-shield-halved me-3"></i>Guardias y Accesos</a>
            <a href="terminales.php" class="nav-link"><i class="fa-solid fa-laptop-code me-3"></i>Computadoras de Andén</a>
        </ul>
        <div class="p-3 border-top border-secondary border-opacity-10 text-center">
            <small class="text-muted d-block mb-2">© 2026 La Sierra, S.A.</small>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100"><i class="fa-solid fa-power-off me-2"></i>Cerrar Sesión</a>
        </div>
    </nav>

    <main class="flex-grow-1 p-5">
        <div class="mb-5">
            <h1 class="fw-bold tracking-tight text-white mb-1">Inventario General de Material Móvil</h1>
            <p class="text-muted mb-0">Alta de autobuses BRT, control de capacidades físicas y amarre obligatorio a patios de parqueo.</p>
        </div>
        <div class="mb-4"><?php echo $mensaje; ?></div>
        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-plus text-success me-2"></i>Registrar Unidad</h5>
                    <form action="buses.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">NÚMERO DE PLACA ÚNICO</label>
                            <input type="text" name="placa" class="form-control" placeholder="Ej: TRM-852-BRT" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CAPACIDAD MAX PASAJEROS</label>
                            <input type="number" name="capacidad_maxima" class="form-control" value="80" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PLAZA DE PARQUEO FIJO</label>
                            <select name="id_parqueo" class="form-select" required>
                                <?php 
                                $check_p = mysqli_query($conexion, "SELECT id_parqueo FROM parqueo LIMIT 1");
                                if($check_p && mysqli_num_rows($check_p) == 0) { mysqli_query($conexion, "INSERT INTO parqueo (id_estacion, codigo_parqueo) VALUES (1, 'PRQ-CENTRASUR-Z12')"); }
                                $res_p = mysqli_query($conexion, "SELECT * FROM parqueo");
                                while($p = mysqli_fetch_assoc($res_p)) { echo "<option value='{$p['id_parqueo']}'>{$p['codigo_parqueo']}</option>"; }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">LÍNEA ASIGNADA</label>
                            <select name="id_linea" class="form-select">
                                <option value="0">-- Ninguna / En Reserva --</option>
                                <?php 
                                $res_l = mysqli_query($conexion, "SELECT * FROM linea");
                                while($l = mysqli_fetch_assoc($res_l)) { echo "<option value='{$l['id_linea']}'>{$l['nombre']}</option>"; }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-lime w-100 fw-bold">Ingresar Bus</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-xl-8">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-bus text-muted me-2"></i>Inventario de Flota</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Capacidad</th>
                                    <th>Plaza Fija</th>
                                    <th>Línea Asignada</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
