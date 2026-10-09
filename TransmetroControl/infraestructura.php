<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transmetro - Infraestructura y Seguridad</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .sidebar { min-width: 260px; max-width: 260px; background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); min-height: 100vh; color: #94a3b8; }
        .sidebar .nav-link { color: #94a3b8; padding: 12px 20px; display: flex; align-items: center; border-left: 4px solid transparent; transition: all 0.2s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background: rgba(255, 255, 255, 0.05); border-left-color: #f59e0b; }
        .card-elegant { border: none; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- SIDEBAR UNIFICADO -->
    <nav class="sidebar d-flex flex-column p-0">
        <div class="p-4 text-center border-bottom border-secondary">
            <h4 class="text-white fw-bold mb-0"><i class="fa-solid fa-bus text-warning me-2"></i>Transmetro</h4>
            <small class="text-muted text-uppercase">Sistema de Control</small>
        </div>
        <ul class="nav flex-column mt-3">
            <a href="index.php" class="nav-link"><i class="fa-solid fa-chart-pie me-3"></i>Dashboard General</a>
            <a href="simulador.php" class="nav-link"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            <a href="operaciones.php" class="nav-link"><i class="fa-solid fa-route me-3"></i>Líneas y Flota</a>
            <a href="pilotos.php" class="nav-link"><i class="fa-solid fa-id-card me-3"></i>Control de Pilotos</a>
            <a href="infraestructura.php" class="nav-link active"><i class="fa-solid fa-laptop-code me-3"></i>Infraestructura y Seguridad</a>
        </ul>
    </nav>

    <main class="flex-grow-1 p-5">
        <div class="mb-5">
            <h1 class="h2 fw-bold text-dark mb-1">Auditoría de Infraestructura y Personal</h1>
            <p class="text-muted mb-0">Control de guardias asignados por acceso, operadores de PC de estación y asignaciones municipales territoriales.</p>
        </div>

        <div class="row g-4">
            <!-- REQUISITOS 3, 8 Y 10: AUDITORÍA DE SEGURIDAD EN ACCESOS -->
            <div class="col-12 col-xl-6">
                <div class="card card-elegant p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-shield-halved text-muted me-2"></i>Despliegue de Seguridad en Accesos de la Línea</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Estación</th>
                                    <th>Acceso (Req 3)</th>
                                    <th>Guardias (Req 10)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Insertar datos de prueba para la simulación visual si las tablas están vacías
                                $check_acc = mysqli_query($conexion, "SELECT id_acceso FROM acceso LIMIT 1");
                                if (mysqli_num_rows($check_acc) == 0) {
                                    mysqli_query($conexion, "INSERT INTO acceso (id_estacion, nombre_acceso) VALUES (1, 'Acceso Norte Rampas'), (1, 'Acceso Sur Taquillas'), (2, 'Andén Central Puerta A')");
                                    mysqli_query($conexion, "INSERT INTO guardia (nombre, dpi) VALUES ('Guardia Juan Pérez', '2514896350101'), ('Guardia Mario Gómez', '3021458960101')");
                                    mysqli_query($conexion, "INSERT INTO acceso_guardia (id_acceso, id_guardia) VALUES (1, 1), (2, 2)");
                                }

                                $sql_sec = "SELECT e.nombre as estacion, a.nombre_acceso, COUNT(ag.id_guardia) as total_guardias 
                                            FROM acceso a 
                                            INNER JOIN estacion e ON a.id_estacion = e.id_estacion 
                                            LEFT JOIN acceso_guardia ag ON a.id_acceso = ag.id_acceso 
                                            GROUP BY a.id_acceso, e.nombre, a.nombre_acceso";
                                $res_sec = mysqli_query($conexion, $sql_sec);
                                while($sec = mysqli_fetch_assoc($res_sec)) {
                                    $status_guardia = ($sec['total_guardias'] >= 1) ? "<span class='badge bg-success-subtle text-success border border-success-subtle'><i class='fa-solid fa-user-shield me-1'></i>{$sec['total_guardias']} Guardia(s) Activo(s)</span>" : "<span class='badge bg-danger-subtle text-danger border border-danger-subtle'><i class='fa-solid fa-triangle-exclamation me-1'></i> Cero Guardias (Vulnerabilidad)</span>";
                                    echo "<tr>
                                            <td class='fw-medium'>{$sec['estacion']}</td>
                                            <td><code>{$sec['nombre_acceso']}</code></td>
                                            <td>$status_guardia</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REQUISITOS 11, 16 Y 17: CONTROL MUNICIPAL Y OPERADORES DE PC -->
            <div class="col-12 col-xl-6">
                <div class="card card-elegant p-4 bg-white h-100">
                    <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-users-gear text-muted me-2"></i>Operadores de Turno de Estación (Req 16)</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Estación (Req 11)</th>
                                    <th>Operador PC</th>
                                    <th>Turno Asignado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Carga automática de operadores para la simulación visual
                                $check_op = mysqli_query($conexion, "SELECT id_operador FROM operador LIMIT 1");
                                if (mysqli_num_rows($check_op) == 0) {
                                    mysqli_query($conexion, "INSERT INTO operador (nombre, dpi) VALUES ('Operadora Andrea López', '1425369850101'), ('Operador Kevin Ruiz', '2589631470101')");
                                    mysqli_query($conexion, "INSERT INTO asignacion_operador_pc (id_operador, id_pc, turno) VALUES (1, 1, 'Matutino (05:00 - 13:00)'), (2, 2, 'Vespertino (13:00 - 21:00)')");
                                }

                                $sql_ops = "SELECT e.nombre as estacion, m.nombre as muni, o.nombre as operador, aop.turno 
                                            FROM asignacion_operador_pc aop 
                                            INNER JOIN operador o ON aop.id_operador = o.id_operador 
                                            INNER JOIN pc p ON aop.id_pc = p.id_pc 
                                            INNER JOIN estacion e ON p.id_estacion = e.id_estacion 
                                            INNER JOIN municipalidad m ON e.id_municipalidad = m.id_municipalidad";
                                $res_ops = mysqli_query($conexion, $sql_ops);
                                while($op = mysqli_fetch_assoc($res_ops)) {
                                    echo "<tr>
                                            <td>
                                                <span class='fw-medium d-block text-dark'>{$op['estacion']}</span>
                                                <small class='text-muted'><i class='fa-solid fa-landmark-dome me-1'></i>{$op['muni']}</small>
                                            </td>
                                            <td><i class='fa-solid fa-user-tie text-muted me-2'></i>{$op['operador']}</td>
                                            <td><span class='badge bg-light text-dark border text-wrap'>{$op['turno']}</span></td>
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
<script src="https://jsdelivr.net"></script>
</body>
</html>
