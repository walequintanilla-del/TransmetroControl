<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); 

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit(); }

$mensaje = "";
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = mysqli_real_escape_string($conexion, $_POST['codigo']);
    $id_estacion = intval($_POST['id_estacion']);
    $ip = mysqli_real_escape_string($conexion, $_POST['ip_estatica']);
    
    $sql = "INSERT INTO pc (codigo, id_estacion, ip_estatica) VALUES ('$codigo', $id_estacion, '$ip')";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'><i class='fa-solid fa-circle-check me-2'></i>Terminal enlazada de forma exitosa.</div>";
    } else {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'>Error: La estación ya cuenta con una PC activa.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transmetro - Terminales de Estación</title>
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
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
            <a href="seguridad.php" class="nav-link"><i class="fa-solid fa-shield-halved me-3"></i>Guardias y Accesos</a>
            <a href="terminales.php" class="nav-link active"><i class="fa-solid fa-laptop-code me-3"></i>Computadoras de Andén</a>
        </ul>
        <div class="p-3 border-top border-secondary border-opacity-10 text-center">
            <small class="text-muted d-block mb-2">© 2026 La Sierra, S.A.</small>
            <a href="logout.php" class="btn btn-outline-danger btn-sm w-100"><i class="fa-solid fa-power-off me-2"></i>Cerrar Sesión</a>
        </div>
    </nav>

    <main class="flex-grow-1 p-5">
        <div class="mb-5">
            <h1 class="fw-bold tracking-tight text-white mb-1">Infraestructura Tecnológica de Estación</h1>
            <p class="text-muted mb-0">Mapeo de hardware distribuido e inventario de terminales fijas (Req 16 y 17).</p>
        </div>
        <div class="mb-4"><?php echo $mensaje; ?></div>
        <div class="row g-4">
            <div class="col-12 col-md-5">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-laptop text-success me-2"></i>Instalar Nodo PC</h5>
                    <form action="terminales.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">CÓDIGO DE INVENTARIO PC</label>
                            <input type="text" name="codigo" class="form-control" placeholder="PC-EST-XYZ" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ESTACIÓN DE DESTINO</label>
                            <select name="id_estacion" class="form-select" required>
                                <?php 
                                $res_e = mysqli_query($conexion, "SELECT id_estacion, nombre FROM estacion");
                                while($e = mysqli_fetch_assoc($res_e)) { echo "<option value='{$e['id_estacion']}'>{$e['nombre']}</option>"; }
                                ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">DIRECCIÓN IP ESTÁTICA</label>
                            <input type="text" name="ip_estatica" class="form-control" placeholder="192.168.12.50" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-lime w-100 fw-bold">Dar de Alta Nodo de Red</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="card card-elegant p-4">
                    <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-network-wired text-muted me-2"></i>Red de Terminales</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nodo Estación</th>
                                    <th>Código Hardware</th>
                                    <th>IP Estática</th>
                                    <th>Enlace (Req 17)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $res = mysqli_query($conexion, "SELECT p.*, e.nombre as est FROM pc p INNER JOIN estacion e ON p.id_estacion = e.id_estacion ORDER BY p.id_pc DESC");
                                while($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>
                                            <td class='fw-semibold text-white'>{$row['est']}</td>
                                            <td><code>{$row['codigo']}</code></td>
                                            <td><span class='font-monospace small text-info'><i class='fa-solid fa-ethernet me-1.5 opacity-50'></i>{$row['ip_estatica']}</span></td>
                                            <td><span class='badge bg-white bg-opacity-5 text-muted border border-white border-opacity-10 py-1.5 px-2.5 rounded-3 small fw-medium'>{$row['tipo_conexion']}</span></td>
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
