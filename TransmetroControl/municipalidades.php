<?php 
// 1. FORZAR REGISTRO DE ERRORES VISIBLES EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. INCLUIR LA CONEXIÓN CON TU BASE DE DATOS DE XAMPP
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
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, $_POST['nombre']) : '';
    
    if (!empty($nombre)) {
        $sql = "INSERT INTO municipalidad (nombre) VALUES ('$nombre')";
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'>
                            <i class='fa-solid fa-circle-check me-2'></i>Sede Municipal incorporada correctamente al sistema.
                        </div>";
        } else {
            $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'>
                            <i class='fa-solid fa-circle-xmark me-2'></i>Error al registrar: " . mysqli_error($conexion) . "
                        </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmetro - Municipalidades</title>
    <!-- CORRECCIÓN CDNS: Rutas de diseño completas y funcionales para Bootstrap 5 y FontAwesome -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #090d16;
            --bg-sidebar: #0f1524;
            --bg-card: #141b2d;
            --text-muted: #64748b;
            --lime-neon: #a3e635;
            --lime-hover: #84cc16;
        }
        body { font-family: 'Urbanist', sans-serif; background-color: var(--bg-main); color: #f8fafc; overflow-x: hidden; }
        .sidebar { min-width: 280px; max-width: 280px; background-color: var(--bg-sidebar); min-height: 100vh; border-right: 1px solid rgba(255, 255, 255, 0.05); }
        .sidebar .nav-link { color: #94a3b8; padding: 14px 24px; display: flex; align-items: center; font-weight: 500; border-radius: 0 50px 50px 0; margin-right: 15px; transition: all 0.3s ease; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #000000 !important; background: linear-gradient(90deg, var(--lime-neon) 0%, #c084fc 100%); font-weight: 700; box-shadow: 0 4px 15px rgba(163, 230, 53, 0.2); }
        .card-elegant { border: 1px solid rgba(255, 255, 255, 0.03); border-radius: 20px; background-color: var(--bg-card); box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .form-label { color: #cbd5e1; font-size: 13px; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 8px; }
        .form-control { background-color: #1f293d !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; color: #ffffff !important; border-radius: 12px !important; padding: 12px; font-size: 15px; }
        .form-control:focus { box-shadow: none !important; border-color: rgba(163, 230, 53, 0.4) !important; }
        .btn-lime { background: linear-gradient(135deg, var(--lime-neon) 0%, #84cc16 100%); color: #000000 !important; border: none; border-radius: 12px; padding: 13px; font-weight: 700; font-size: 15px; letter-spacing: 0.5px; transition: all 0.3s ease; }
        .btn-lime:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(163, 230, 53, 0.3); }
        .table-custom { color: #cbd5e1; }
        .table-custom thead th { background-color: rgba(255,255,255,0.02) !important; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .table-custom tbody td { border-bottom: 1px solid rgba(255, 255, 255, 0.02); padding: 14px 12px; }
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
        <ul class="nav flex-column mt-4 flex-grow-1">
            <a href="index.php" class="nav-link"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard General</a>
            <a href="simulador.php" class="nav-link"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            <div class="px-4 pt-4 pb-1 text-uppercase tracking-wider text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Ingreso de Datos</div>
            <a href="municipalidades.php" class="nav-link active"><i class="fa-solid fa-landmark-dome me-3"></i>Municipalidades</a>
            <a href="rutas.php" class="nav-link"><i class="fa-solid fa-route me-3"></i>Líneas (Rutas)</a>
            <a href="estaciones.php" class="nav-link"><i class="fa-solid fa-building-user me-3"></i>Estaciones</a>
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
            <a href="seguridad.php" class="nav-link"><i class="fa-solid fa-shield-halved me-3"></i>Guardias y Accesos</a>
            <a href="terminales.php" class="nav-link"><i class="fa-solid fa-laptop-code me-3"></i>Computadoras de Andén</a>
        </ul>
        <div class="p-3 border-top border-secondary border-opacity-10 text-center">
            <div class="mb-3" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">© 2026 La Sierra, S.A.<br>Derechos Reservados.</div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 fw-semibold rounded-3"><i class="fa-solid fa-power-off me-2"></i>Cerrar Sesión</a>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-grow-1 p-5">
        <div class="mb-5">
            <h1 class="fw-bold tracking-tight text-white mb-1">Módulo Político Territoriales</h1>
            <p class="text-muted mb-0">Gestión de las Municipalidades que integran y cofinancian los corredores del Transmetro (Req 11).</p>
        </div>
        <div class="mb-4"><?php echo $mensaje; ?></div>
        <div class="row g-4">
            <div class="col-12 col-md-5">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-plus text-success me-2"></i>Nueva Sede Municipal</h5>
                    <form action="municipalidades.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label">NOMBRE DE LA JURISDICCIÓN</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Municipalidad de Mixco" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-lime w-100 fw-bold shadow-sm">Guardar Municipalidad</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-landmark text-muted me-2"></i>Sedes en el Sistema</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Código Nodo</th>
                                    <th>Nombre Municipalidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $res = mysqli_query($conexion, "SELECT * FROM municipalidad ORDER BY id_municipalidad DESC");
                                if ($res && mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                        echo "<tr>
                                                <td><code style='color: #c084fc;'>#GUA-0" . htmlspecialchars($row['id_municipalidad']) . "</code></td>
                                                <td class='fw-semibold text-white'>" . htmlspecialchars($row['nombre']) . "</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2' class='text-muted small text-center py-4'>No hay sedes registradas.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
