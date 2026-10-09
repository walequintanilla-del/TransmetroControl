<?php 
// 1. FORZAR REGISTRO DE ERRORES VISIBLES
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. INCLUIR LA CONEXIÓN CON TUS CREDENCIALES
include("conexion.php"); 

$mensaje = "";
// Detectar el envío del formulario de manera tradicional y segura para XAMPP
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    $id_bus   = intval(filter_input(INPUT_POST, 'id_bus', FILTER_SANITIZE_NUMBER_INT));
    $id_linea = intval(filter_input(INPUT_POST, 'id_linea', FILTER_SANITIZE_NUMBER_INT));


    // Requisito 4 y 5: Asignar bus a línea
    $sql = "UPDATE bus SET id_linea = " . ($id_linea > 0 ? $id_linea : "NULL") . " WHERE id_bus = $id_bus";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div class='alert alert-success shadow-sm'><i class='fa-solid fa-circle-check me-2'></i>Flota actualizada con éxito.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger shadow-sm'>Error al actualizar flota: " . mysqli_error($conexion) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmetro - Gestión de Operaciones</title>
    <!-- CDNs Oficiales de Diseño -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }
        .sidebar { min-width: 260px; max-width: 260px; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); min-height: 100vh; color: #94a3b8; }
        .sidebar .nav-link { color: #94a3b8; padding: 12px 20px; display: flex; align-items: center; border-left: 4px solid transparent; transition: all 0.2s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background: rgba(255, 255, 255, 0.05); border-left-color: #f59e0b; }
        .card-elegant { border: none; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- BARRA LATERAL (SIDEBAR) -->
    <nav class="sidebar d-flex flex-column p-0">
        <div class="p-4 text-center border-bottom border-secondary">
            <h4 class="text-white fw-bold mb-0"><i class="fa-solid fa-bus text-warning me-2"></i>Transmetro</h4>
            <small class="text-muted text-uppercase">Sistema de Control</small>
        </div>
        <ul class="nav flex-column mt-3">
            <a href="index.php" class="nav-link"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard General</a>
            <a href="simulador.php" class="nav-link"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            <a href="operaciones.php" class="nav-link active"><i class="fa-solid fa-route me-3"></i>Líneas y Flota</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Control de Pilotos</a>
            <a href="infraestructura.php" class="nav-link"><i class="fa-solid fa-laptop-code me-3"></i>Infraestructura y Seguridad</a>
        </ul>
        <div class="mt-auto p-3 text-center border-top border-secondary">
            <small class="text-muted">Área Metropolitana GUA</small>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-grow-1 p-5">
        <div class="mb-4">
            <h1 class="h2 fw-bold text-dark mb-1">Control de Líneas, Rutas y Flota</h1>
            <p class="text-muted mb-0">Administración de distancias totales, orden de paradas intermedias y asignación de buses.</p>
        </div>

        <?php echo $mensaje; ?>

        <div class="row g-4">
            <!-- TABLA DE RUTAS Y DISTANCIAS -->
            <div class="col-12 col-xl-7">
                <div class="card card-elegant p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-road text-muted me-2"></i>Trazado de Rutas y Distancias</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Línea (Req 13)</th>
                                    <th>Estación (Req 2)</th>
                                    <th>Orden (Req 1)</th>
                                    <th>Distancia Tramo (Req 12)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sql_rutas = "SELECT l.nombre as linea, l.distancia_total_km, e.nombre as estacion, le.orden, le.distancia_siguiente_estacion_km 
                                              FROM linea_estacion le 
                                              INNER JOIN linea l ON le.id_linea = l.id_linea 
                                              INNER JOIN estacion e ON le.id_estacion = e.id_estacion 
                                              ORDER BY le.id_linea, le.orden";
                                $res_rutas = mysqli_query($conexion, $sql_rutas);
                                if ($res_rutas && mysqli_num_rows($res_rutas) > 0) {
                                    while($ruta = mysqli_fetch_assoc($res_rutas)){
                                        echo "<tr>
                                                <td><strong>" . htmlspecialchars($ruta['linea']) . "</strong> <small class='text-muted'>(" . htmlspecialchars($ruta['distancia_total_km']) . " km total)</small></td>
                                                <td>" . htmlspecialchars($ruta['estacion']) . "</td>
                                                <td><span class='badge bg-dark rounded-pill'>Parada " . htmlspecialchars($ruta['orden']) . "</span></td>
                                                <td><i class='fa-solid fa-arrows-left-right text-muted me-1'></i> " . htmlspecialchars($ruta['distancia_siguiente_estacion_km']) . " km</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-muted small text-center py-3'>No hay trayectos mapeados.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO DE ASIGNACIÓN Y PARQUEOS -->
            <div class="col-12 col-xl-5">
                <div class="card card-elegant p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-truck-ramp-box text-muted me-2"></i>Asignar Unidad a Ruta</h5>
                    <form action="operaciones.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Seleccionar Bus</label>
                            <select name="id_bus" class="form-select" required>
                                <option value="">-- Escoger Unidad --</option>
                                <?php 
                                $res_b = mysqli_query($conexion, "SELECT id_bus, placa FROM bus");
                                if ($res_b) {
                                    while($b = mysqli_fetch_assoc($res_b)) { 
                                        echo "<option value='{$b['id_bus']}'>Placa: " . htmlspecialchars($b['placa']) . "</option>"; 
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Línea Operativa de Destino</label>
                            <select name="id_linea" class="form-select" required>
                                <option value="0">-- Retirar de Servicio / Ninguna Línea --</option>
                                <?php 
                                $res_l = mysqli_query($conexion, "SELECT id_linea, nombre FROM linea");
                                if ($res_l) {
                                    while($l = mysqli_fetch_assoc($res_l)) { 
                                        echo "<option value='{$l['id_linea']}'>" . htmlspecialchars($l['nombre']) . "</option>"; 
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning text-white w-100 fw-medium shadow-sm">Modificar Despacho</button>
                    </form>
                </div>

                <!-- UBICACIÓN DE PARQUEOS -->
                <div class="card card-elegant p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-square-parking text-muted me-2"></i>Ubicación de Parqueos Fijos</h5>
                    <ul class="list-group list-group-flush">
                        <?php 
                        $sql_p = "SELECT b.placa, p.codigo_parqueo, e.nombre as estacion 
                                  FROM bus b 
                                  INNER JOIN parqueo p ON b.id_parqueo = p.id_parqueo 
                                  LEFT JOIN estacion e ON p.id_estacion = e.id_estacion";
                        $res_p = mysqli_query($conexion, $sql_p);
                        if ($res_p && mysqli_num_rows($res_p) > 0) {
                            while($p = mysqli_fetch_assoc($res_p)) {
                                                                echo "<li class='list-group-item d-flex justify-content-between align-items-center px-0 py-3'>
                                        <div>
                                            <span class='fw-medium text-dark d-block'>Bus Placa: " . htmlspecialchars($p['placa']) . "</span>
                                            <small class='text-muted'><i class='fa-solid fa-location-dot me-1'></i>Asociado a: " . htmlspecialchars($p['estacion']) . "</small>
                                        </div>
                                        <span class='badge bg-light text-primary border border-primary-subtle rounded-3'><i class='fa-solid fa-warehouse me-1'></i>" . htmlspecialchars($p['codigo_parqueo']) . "</span>
                                      </li>";
                            }
                        } else {
                            echo "<li class='list-group-item text-muted small py-3 px-0'>No hay parqueos mapeados a buses.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://jsdelivr.net"></script>
</body>
</html>

                           