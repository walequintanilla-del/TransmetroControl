<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); 

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit(); }

$mensaje = "";
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $id_muni = intval($_POST['id_municipalidad']);
    $distancia = floatval($_POST['distancia_total_km']);
    
    $sql = "INSERT INTO linea (nombre, id_municipalidad, distancia_total_km) VALUES ('$nombre', $id_muni, $distancia)";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'><i class='fa-solid fa-circle-check me-2'></i>Línea de Transmetro guardada exitosamente.</div>";
    } else {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'><i class='fa-solid fa-circle-xmark me-2'></i>Error al registrar: " . mysqli_error($conexion) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transmetro - Líneas Operativas</title>
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
            <a href="rutas.php" class="nav-link active"><i class="fa-solid fa-route me-3"></i>Líneas (Rutas)</a>
            <a href="estaciones.php" class="nav-link"><i class="fa-solid fa-building-user me-3"></i>Estaciones</a>
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
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
            <h1 class="fw-bold tracking-tight text-white mb-1">Módulo de Corredores Metropolitanos</h1>
            <p class="text-muted mb-0">Gestión de las líneas operativas del sistema de control del Transmetro de la capital.</p>
        </div>
        <div class="mb-4"><?php echo $mensaje; ?></div>
        <div class="row g-4">
            <div class="col-12 col-md-5">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-plus text-success me-2"></i>Nueva Línea</h5>
                    <form action="rutas.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">NOMBRE DEL CORREDOR</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Línea 12 - Centra Sur" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">MUNICIPALIDAD RESPONSABLE</label>
                            <select name="id_municipalidad" class="form-select" required>
                                <?php 
                                $res_m = mysqli_query($conexion, "SELECT * FROM municipalidad");
                                while($m = mysqli_fetch_assoc($res_m)) { echo "<option value='{$m['id_municipalidad']}'>{$m['nombre']}</option>"; }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">DISTANCIA TOTAL (KM)</label>
                            <input type="number" step="0.01" name="distancia_total_km" class="form-control" placeholder="Ej: 14.50" required>
                        </div>
                        <button type="submit" class="btn btn-lime w-100 fw-bold">Crear Línea Operativa</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-route text-muted me-2"></i>Líneas Activas</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre Línea</th>
                                    <th>Sede Municipal</th>
                                    <th>Extensión</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $res = mysqli_query($conexion, "SELECT l.*, m.nombre as muni FROM linea l INNER JOIN municipalidad m ON l.id_municipalidad = m.id_municipalidad ORDER BY l.id_linea DESC");
                                while($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>
                                            <td><code style='color: #c084fc;'>#LIN-0{$row['id_linea']}</code></td>
                                            <td class='fw-semibold text-white'>{$row['nombre']}</td>
                                            <td><span class='badge bg-white bg-opacity-5 text-muted border border-white border-opacity-10 py-1.5 px-2.5 rounded-3 fw-medium'>{$row['muni']}</span></td>
                                            <td><strong style='color: var(--lime-neon);'>{$row['distancia_total_km']} Km</strong></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
