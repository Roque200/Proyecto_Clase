<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['tipo_usuario'] == 'docente') {
        header("Location: ../docente.php");
        exit();
    } elseif ($_SESSION['tipo_usuario'] == 'estudiante') {
        header("Location: ../estudiante.php");
        exit();
    } elseif ($_SESSION['tipo_usuario'] == 'administrador') {
        header("Location: ../admin.php");
        exit();
    }
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("../models/usuario.php");
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Por favor, completa todos los campos.";
    } else {
        $app = new Usuario();
        $usuario = $app->login($email, $password);
        
        if ($usuario) {
            error_log("Usuario obtenido: " . print_r($usuario, true));
            
            // Datos comunes
            $_SESSION['usuario_id'] = $usuario['id_usuario_PK'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellidos'] = $usuario['apellidos'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['ultimo_acceso'] = time();
            
            if ($usuario['tipo_usuario'] === 'estudiante') {
                if (empty($usuario['id_estudiante_PK'])) {
                    $error = "Error crítico: No se encontró el ID del estudiante.";
                    error_log($error);
                } elseif (empty($usuario['matricula'])) {
                    $error = "Error crítico: No se encontró la matrícula del estudiante.";
                    error_log($error);
                } else {
                    $_SESSION['estudiante_id'] = $usuario['id_estudiante_PK'];
                    $_SESSION['matricula'] = $usuario['matricula'];
                    
                    error_log("Sesión iniciada - Estudiante ID: " . $_SESSION['estudiante_id']);
                    
                    header("Location: ../estudiante.php");
                    exit();
                }
            } elseif ($usuario['tipo_usuario'] === 'docente') {
                if (empty($usuario['id_docente_PK']) || empty($usuario['numero_empleado'])) {
                    $error = "Error: El usuario no tiene datos de docente asociados.";
                } else {
                    $_SESSION['docente_id'] = $usuario['id_docente_PK'];
                    $_SESSION['numero_empleado'] = $usuario['numero_empleado'];
                    $_SESSION['docente_carrera'] = $usuario['docente_carrera'];
                    
                    error_log("Sesión iniciada - Docente ID: " . $_SESSION['docente_id'] . " - Carrera: " . $_SESSION['docente_carrera']);
                    
                    header("Location: ../docente.php");
                    exit();
                }
            } elseif ($usuario['tipo_usuario'] === 'administrador') {
                error_log("Sesión iniciada - Admin ID: " . $_SESSION['usuario_id']);
                
                header("Location: ../admin.php");
                exit();
            } else {
                $error = "Tipo de usuario no reconocido.";
            }
        } else {
            $error = "Credenciales incorrectas.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGA-ITC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: #1e3c72;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
        }
        .btn-login {
            background: #1e3c72;
            color: white;
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border: none;
        }
        .btn-login:hover {
            background: #2a5298;
            color: white;
        }
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        .forgot-password a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="bi bi-building" style="font-size: 48px;"></i>
            <h1>Instituto Tecnológico de Celaya</h1>
            <p class="mb-0">Sistema Integral de Gestión Académica</p>
        </div>
        
        <div class="login-body">
            <h3 class="text-center mb-4">Iniciar Sesión</h3>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Institucional</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="usuario@itcelaya.edu.mx" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="********" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </button>
            </form>
            
            <div class="forgot-password">
                <a href="recuperar_password.php">
                    <i class="bi bi-key"></i> ¿Olvidaste tu contraseña?
                </a>
            </div>
        </div>
        
        <div class="text-center pb-3 text-muted small">
            © 2025 Instituto Tecnológico de Celaya. Todos los derechos reservados.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>