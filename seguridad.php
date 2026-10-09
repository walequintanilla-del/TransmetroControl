<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); 

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit(); }

$mensaje = "";
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $dpi = mysqli_real_escape_string($conexion, $_POST['dpi']);
    
    $sql = "INSERT INTO guardia (nombre, dpi) VALUES ('$nombre', '$dpi')";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'><i class='fa-solid fa-circle-check me-2'></i>Guardia asignado con éxito.</div>";
    } else {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'>Error: DPI duplicado.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transmetro - Despliegue de Seguridad</title>
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
        .form-control { background-color: #1f293d !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; color: #ffffff !important; border-radius: 12px !important; padding: 12px; }
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
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
            <a href="seguridad.php" class="nav-link active"><i class="fa-solid fa-shield-halved me-3"></i>Guardias y Accesos</a>
            <a href="terminales.php" class="nav-link"><i class="fa-solid fa-laptop-code me-3"></i>Computadoras de Andén</a>
        </ul>
        <div class="p-3 border-top border-secondary border-opacity-10 text-center">
            <small class="text-muted d-block mb-2">© 2026 La Sierra, S.A.</small>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100"><i class="fa-solid fa-power-off me-2"></i>Cerrar Sesión</a>
        </div>
    </nav>

    <main class="flex-grow-1 p-5">
        <div class="mb-5">
            <h1 class="fw-bold tracking-tight text-white mb-1">Cuerpo de Seguridad y Vigilancia</h1>
            <p class="text-muted mb-0">Alta de personal de guardia asignado a los accesos fijos de las estaciones metropolitanas.</p>
        </div>
        <div class="mb-4"><?php echo $mensaje; ?></div>
        <div class="row g-4">
            <div class="col-12 col-md-5">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-user-shield text-success me-2"></i>Contratar Personal</h5>
                    <form action="seguridad.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">NOMBRE DEL GUARDIA</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Mario Gómez" required autocomplete="off">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">DOCUMENTO DE IDENTIFICACIÓN (DPI)</label>
                            <input type="text" name="dpi" class="form-control" placeholder="2514896350101" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-lime w-100 fw-bold">Registrar Guardia de Turno</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-shield text-muted me-2"></i>Fuerzas de Seguridad</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID Guardia</th>
                                    <th>Nombre Guardia</th>
                                    <th>DPI Corporativo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $res = mysqli_query($conexion, "SELECT * FROM guardia ORDER BY id_guardia DESC");
                                while($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>
                                            <td><code style='color: #c084fc;'>#SEC-0{$row['id_guardia']}</code></td>
                                            <td class='fw-semibold text-white'>{$row['nombre']}</td>
                                            <td><span class='font-monospace small text-info'>{$row['dpi']}</span></td>
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