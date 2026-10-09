<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("conexion.php");

$error = "";
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = isset($_POST['username']) ? mysqli_real_escape_string($conexion, $_POST['username']) : '';
    $pass = isset($_POST['password']) ? mysqli_real_escape_string($conexion, $_POST['password']) : '';

    // Validar credenciales de forma directa contra la base de datos
    $sql = "SELECT * FROM usuario WHERE username = '$user' AND password = '$pass' LIMIT 1";
    $result = mysqli_query($conexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $userdata = mysqli_fetch_assoc($result);
        $_SESSION['usuario'] = $userdata['username'];
        $_SESSION['nombre_usuario'] = $userdata['nombre'];
        header("Location: index.php");
        exit();
    } else {
        $error = "<div class='alert alert-danger text-center small shadow-sm' style='border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);'><i class='fa-solid fa-circle-xmark me-2'></i>Usuario o contraseña incorrectos.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmetro - Autenticación</title>
    <!-- CORRECCIÓN: Rutas de diseño completas y funcionales para Bootstrap 5 y FontAwesome -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <!-- Google Fonts - Urbanist (Tipografía moderna de alta gama) -->
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #090d16;
            --bg-card: #141b2d;
            --lime-neon: #a3e635; /* Verde Limón */
        }
        
        body { 
            font-family: 'Urbanist', sans-serif; 
            background: linear-gradient(135deg, #090d16 0%, #0f1524 50%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        
        /* Tarjeta de Login Centrada Estilo Premium */
        .card-login { 
            border: 1px solid rgba(255, 255, 255, 0.05); 
            border-radius: 24px; 
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4); 
            background-color: var(--bg-card);
            padding: 45px;
            width: 100%;
            max-width: 420px;
            transition: all 0.3s ease;
        }

        .card-login:hover {
            border-color: rgba(163, 230, 53, 0.2);
            box-shadow: 0 25px 60px rgba(163, 230, 53, 0.05);
        }
        
        .icon-badge {
            background-color: rgba(163, 230, 53, 0.1);
            color: var(--lime-neon);
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px auto;
        }

        .form-label {
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .input-group-text {
            background-color: #1f293d !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-right: none !important;
            color: #64748b !important;
            border-radius: 12px 0 0 12px !important;
        }

        .form-control {
            background-color: #1f293d !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-left: none !important;
            color: #ffffff !important;
            border-radius: 0 12px 12px 0 !important;
            padding: 12px;
            font-size: 15px;
        }

        .form-control:focus {
            box-shadow: none !important;
            border-color: rgba(163, 230, 53, 0.3) !important;
        }

        .form-control::placeholder {
            color: #475569;
        }

        /* Botón de Envío Verde Limón Neón */
        .btn-lime { 
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
        
        .btn-lime:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163, 230, 53, 0.3);
            opacity: 0.95;
        }

        .btn-lime:active {
            transform: translateY(0);
        }

        .copyright-text {
            color: #475569;
            font-size: 11px;
            font-weight: 500;
            margin-top: 30px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="card card-login text-center">
    <div class="mb-4">
        <div class="icon-badge">
            <i class="fa-solid fa-bus-simple fa-2x"></i>
        </div>
        <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">TRANSMETRO</h3>
        <p style="color: #64748b; font-size: 13px; font-weight: 500; text-uppercase: tracking-wider; text-transform: uppercase; letter-spacing: 1px;">Autenticación Municipal</p>
    </div>

    <!-- Contenedor para mostrar fallos de contraseña -->
    <div class="mb-3">
        <?php echo $error; ?>
    </div>

    <form action="login.php" method="POST">
        <!-- CAMPO USUARIO -->
        <div class="mb-3 text-start">
            <label class="form-label">USUARIO</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Ingresa tu usuario (admin)" required autocomplete="off">
            </div>
        </div>
        
        <!-- CAMPO CONTRASEÑA -->
        <div class="mb-4 text-start">
            <label class="form-label">CONTRASEÑA</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        
        <!-- BOTÓN ENTRAR -->
        <button type="submit" class="btn btn-lime w-100 shadow-sm">Iniciar Sesión</button>
    </form>

    <div class="copyright-text">
        © 2026 La Sierra, S.A. • Derechos Reservados
    </div>
</div>

</body>
</html>
