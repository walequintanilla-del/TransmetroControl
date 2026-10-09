<?php 
// 1. FORZAR REGISTRO DE ERRORES VISIBLES PARA AUDITORÍA
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. INCLUIR LA CONEXIÓN CON TUS CREDENCIALES DE XAMPP
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
// Detectar el envío del formulario de manera tradicional y segura para XAMPP
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($conexion, $_POST['nombre']) : '';
    $direccion = isset($_POST['direccion']) ? mysqli_real_escape_string($conexion, $_POST['direccion']) : '';
    $telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($conexion, $_POST['telefono']) : '';
    $correo = isset($_POST['correo']) ? mysqli_real_escape_string($conexion, $_POST['correo']) : '';
    $titulo = isset($_POST['titulo']) ? mysqli_real_escape_string($conexion, $_POST['titulo']) : '';
    $institucion = isset($_POST['institucion']) ? mysqli_real_escape_string($conexion, $_POST['institucion']) : '';
    $ano = isset($_POST['ano']) ? intval($_POST['ano']) : 0;

    // Inserción directa y segura sin transacciones bloqueantes (Requisito 9)
    $sql_piloto = "INSERT INTO piloto (nombre, direccion_residencia, telefono, correo_electronico) VALUES ('$nombre', '$direccion', '$telefono', '$correo')";
    $p1 = mysqli_query($conexion, $sql_piloto);
    $id_piloto = mysqli_insert_id($conexion);
    
    if ($p1 && $id_piloto > 0) {
        $sql_edu = "INSERT INTO educacion (id_piloto, titulo_obtenido, institucion, ano_graduacion) VALUES ('$id_piloto', '$titulo', '$institucion', '$ano')";
        mysqli_query($conexion, $sql_edu);
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(163, 230, 53, 0.1); color: #a3e635; border-radius: 12px;'>
                        <i class='fa-solid fa-circle-check me-2'></i>Piloto y expediente académico registrados correctamente.
                    </div>";
    } else {
        $mensaje = "<div class='alert border-0 shadow-sm py-3' style='background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;'>
                        <i class='fa-solid fa-circle-xmark me-2'></i>Error al registrar expediente: " . mysqli_error($conexion) . "
                    </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmetro - Gestión de Pilotos</title>
    <!-- CORRECCIÓN: CDNs Oficiales Completos para Carga de Estilos Premium -->
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
        
        /* Tarjetas de Datos de Alta Gama */
        .card-profile { 
            border: 1px solid rgba(255, 255, 255, 0.03); 
            border-radius: 16px; 
            background-color: var(--bg-card);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2); 
        }
        
        .avatar-circle { 
            width: 50px; 
            height: 50px; 
            background-color: rgba(163, 230, 53, 0.1); 
            color: var(--lime-neon); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 20px; 
            font-weight: 700; 
            border-radius: 50%; 
        }

        .form-label {
            color: #cbd5e1;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .form-control {
            background-color: #1f293d !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-radius: 10px !important;
            padding: 10px;
            font-size: 14px;
        }

        .form-control:focus {
            box-shadow: none !important;
            border-color: rgba(163, 230, 53, 0.4) !important;
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
            <a href="simulador.php" class="nav-link"><i class="fa-solid fa-tower-broadcast me-3"></i>Simulador Tráfico</a>
            
            <div class="px-4 pt-4 pb-1 text-uppercase tracking-wider text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Ingreso de Datos</div>
            <a href="municipalidades.php" class="nav-link"><i class="fa-solid fa-landmark-dome me-3"></i>Municipalidades</a>
            <a href="rutas.php" class="nav-link"><i class="fa-solid fa-route me-3"></i>Líneas (Rutas)</a>
            <a href="estaciones.php" class="nav-link"><i class="fa-solid fa-building-user me-3"></i>Estaciones</a>
            <a href="buses.php" class="nav-link"><i class="fa-solid fa-truck-monster me-3"></i>Buses e Inventario</a>
            <a href="pilotos.php" class="nav-link active"><i class="fa-solid fa-id-card me-3"></i>Pilotos y Educación</a>
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

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-grow-1 p-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold tracking-tight text-white mb-1">Recursos Humanos y Expedientes</h1>
                <p class="text-muted mb-0">Historial educativo, residencia y datos corporativos de los pilotos del sistema (Req 9).</p>
            </div>
            <button class="btn btn-warning text-black fw-bold px-4 py-2 shadow-sm" style="background-color: var(--lime-neon); border: none; border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalPiloto">
                <i class="fa-solid fa-user-plus me-2"></i>Contratar Piloto
            </button>
        </div>

        <div class="mb-4">
            <?php echo $mensaje; ?>
        </div>

        <!-- GRID DE EXPEDIENTES DE PILOTOS -->
        <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-folder-open text-muted me-2"></i>Expedientes Activos</h5>
        <div class="row g-4">
            <?php
            $sql_p = "SELECT p.*, e.titulo_obtenido, e.institucion, e.ano_graduacion FROM piloto p LEFT JOIN educacion e ON p.id_piloto = e.id_piloto ORDER BY p.id_piloto DESC";
                            if(!$res_p || mysqli_num_rows($res_p) == 0) {
                    echo "<div class='col-12'><p class='text-muted small p-4 rounded-4 text-center' style='background-color: var(--bg-card);'><i class='fa-solid fa-circle-info me-2'></i>No hay expedientes de pilotos ingresados en el sistema.</p></div>";
                } else {
                    while($p = mysqli_fetch_assoc($res_p)) {
                        $inicial = strtoupper(substr($p['nombre'], 0, 1));
                        ?>
                        <div class="col-12 col-xl-6">
                            <div class="card card-profile p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="avatar-circle me-3"><?php echo $inicial; ?></div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-white mb-1"><?php echo htmlspecialchars($p['nombre']); ?></h5>
                                        <span class="badge bg-white bg-opacity-5 text-muted border border-white border-opacity-10 px-2 py-1.5"><i class="fa-solid fa-phone me-1.5"></i><?php echo htmlspecialchars($p['telefono']); ?></span>
                                        <span class="badge bg-white bg-opacity-5 text-muted border border-white border-opacity-10 px-2 py-1.5 ms-1"><i class="fa-solid fa-envelope me-1.5"></i><?php echo htmlspecialchars($p['correo_electronico']); ?></span>
                                    </div>
                                </div>
                                <div class="border-top border-secondary border-opacity-25 pt-3 mt-2">
                                    <p class="small mb-3" style="color: #94a3b8;"><i class="fa-solid fa-house-chimney me-2 text-muted"></i><strong>Residencia:</strong> <?php echo htmlspecialchars($p['direccion_residencia']); ?></p>
                                    <div class="p-3 rounded-3 border-start border-3" style="background-color: rgba(255,255,255,0.02); border-color: var(--lime-neon) !important;">
                                        <span class="d-block text-uppercase tracking-wider text-muted fw-bold mb-1" style="font-size: 10px;">Historial Académico</span>
                                        <p class="small text-white mb-0 fw-semibold"><i class="fa-solid fa-user-graduate me-2" style="color: var(--lime-neon);"></i><?php echo htmlspecialchars($p['titulo_obtenido']); ?></p>
                                        <small class="text-muted d-block mt-0.5"><?php echo htmlspecialchars($p['institucion']); ?> • Graduación: <?php echo htmlspecialchars($p['ano_graduacion']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
    </main>
</div>

<!-- MODAL EMERGENTE PREMIUM PARA REGISTRAR PILOTOS -->
<div class="modal fade" id="modalPiloto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background-color: var(--bg-card); border-radius: 20px;">
            <div class="modal-header border-bottom border-secondary border-opacity-25 p-4">
                <h5 class="modal-title fw-bold text-white"><i class="fa-solid fa-id-card-clip me-2" style="color: var(--lime-neon);"></i>Nuevo Expediente de Piloto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="pilotos.php" method="POST">
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-uppercase tracking-wider text-muted small mb-3">Datos Personales</h6>
                    <div class="mb-3">
                        <label class="form-label">NOMBRE COMPLETO</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Carlos Mendoza" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">DIRECCIÓN DE RESIDENCIA</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Ej: 3ra Calle Zona 1, Mixco" required autocomplete="off">
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">TELÉFONO DE CONTACTO</label>
                            <input type="text" name="telefono" class="form-control" placeholder="4125-8963" required autocomplete="off">
                        </div>
                        <div class="col-6">
                            <label class="form-label">CORREO ELECTRÓNICO</label>
                            <input type="email" name="correo" class="form-control" placeholder="carlos@muniguate.com" autocomplete="off">
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-uppercase tracking-wider text-muted small mb-3">Historial Académico (Req 9)</h6>
                    <div class="mb-3">
                        <label class="form-label">TÍTULO COMERCIAL OBTENIDO</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ej: Bachiller en Ciencias y Letras" required autocomplete="off">
                    </div>
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label">INSTITUCIÓN EDUCATIVA</label>
                            <input type="text" name="institucion" class="form-control" placeholder="Ej: Instituto Central de Varones" required autocomplete="off">
                        </div>
                        <div class="col-4">
                            <label class="form-label">AÑO EGRESO</label>
                            <input type="number" name="ano" class="form-control" value="2026" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 p-3" style="background-color: rgba(0,0,0,0.1); border-radius: 0 0 20px 20px;">
                    <button type="submit" class="btn btn-warning text-black w-100 py-2.5 fw-bold" style="background: linear-gradient(135deg, var(--lime-neon) 0%, #84cc16 100%); border: none; border-radius: 12px;">Registrar y Validar Contratación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://jsdelivr.net"></script>
</body>
</html>


                