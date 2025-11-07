<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../models/usuario.php");
require_once("../vendor/vendor/autoload.php");

$mensaje = '';
$tipo_mensaje = '';
$paso = isset($_GET['paso']) ? $_GET['paso'] : 1;

// Paso 1: Solicitar email
if ($paso == 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paso1'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        if (empty($email)) {
            $mensaje = "Por favor ingresa tu correo electrónico.";
            $tipo_mensaje = "danger";
        } else {
            $appUsuario = new Usuario();
            $usuario = $appUsuario->readByEmail($email);
            
            if (!$usuario) {
                $mensaje = "No encontramos una cuenta con ese correo.";
                $tipo_mensaje = "danger";
            } else {
                // Generar token
                $token = bin2hex(random_bytes(32));
                $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Guardar token en base de datos
                if ($appUsuario->guardarTokenRecuperacion($usuario['id_usuario_PK'], $token, $expiracion)) {
                    // Enviar email
                    if (enviarEmailRecuperacion($email, $usuario['nombre'], $token)) {
                        $mensaje = "Se ha enviado un enlace de recuperación a tu correo.";
                        $tipo_mensaje = "success";
                        $paso = 0;
                    } else {
                        $mensaje = "Error al enviar el correo. Intenta más tarde.";
                        $tipo_mensaje = "danger";
                    }
                } else {
                    $mensaje = "Error al procesar tu solicitud. Intenta más tarde.";
                    $tipo_mensaje = "danger";
                }
            }
        }
    }
}

// Paso 2: Validar token
if ($paso == 2) {
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    
    if (empty($token) || empty($email)) {
        $mensaje = "Enlace inválido o expirado.";
        $tipo_mensaje = "danger";
        $paso = 1;
    } else {
        $appUsuario = new Usuario();
        $usuario = $appUsuario->readByEmail($email);
        
        if (!$usuario) {
            $mensaje = "Usuario no encontrado.";
            $tipo_mensaje = "danger";
            $paso = 1;
        } else {
            $token_valido = $appUsuario->validarTokenRecuperacion($usuario['id_usuario_PK'], $token);
            
            if (!$token_valido) {
                $mensaje = "El enlace ha expirado o es inválido. Solicita uno nuevo.";
                $tipo_mensaje = "danger";
                $paso = 1;
            } else {
                // Token es válido, mostrar formulario para cambiar contraseña
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paso2'])) {
                    $nueva_password = $_POST['nueva_password'];
                    $confirmar_password = $_POST['confirmar_password'];
                    
                    if (empty($nueva_password) || empty($confirmar_password)) {
                        $mensaje = "Por favor completa todos los campos.";
                        $tipo_mensaje = "danger";
                    } elseif ($nueva_password !== $confirmar_password) {
                        $mensaje = "Las contraseñas no coinciden.";
                        $tipo_mensaje = "danger";
                    } elseif (strlen($nueva_password) < 6) {
                        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
                        $tipo_mensaje = "danger";
                    } else {
                        // Cambiar contraseña
                        $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                        
                        if ($appUsuario->cambiarPasswordRecuperacion($usuario['id_usuario_PK'], $password_hash, $token)) {
                            $mensaje = "¡Contraseña cambida exitosamente!";
                            $tipo_mensaje = "success";
                            $paso = 0;
                        } else {
                            $mensaje = "Error al cambiar la contraseña. Intenta más tarde.";
                            $tipo_mensaje = "danger";
                        }
                    }
                }
            }
        }
    }
}

function enviarEmailRecuperacion($email, $nombre, $token) {
    try {
        $mail = new PHPMailer(true);
        
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '21031190@itcelaya.edu.mx';
        $mail->Password = 'zoqs rkzj pcrp aazr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        
        // Remitente y destinatario
        $mail->setFrom('21031190@itcelaya.edu.mx', 'SIGA-ITC');
        $mail->addAddress($email, $nombre);
        
        // Contenido
        $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/siga-itc";
        $enlace = $base_url . "/auth/recuperar_password.php?paso=2&token=" . $token . "&email=" . urlencode($email);
        
        $mail->isHTML(false);
        $mail->Subject = 'Recuperar tu contraseña - SIGA-ITC';
        $mail->Body = "Hola $nombre,\n\n";
        $mail->Body .= "Para recuperar tu contraseña, haz clic en el siguiente enlace:\n";
        $mail->Body .= $enlace . "\n\n";
        $mail->Body .= "Este enlace expira en 1 hora.\n\n";
        $mail->Body .= "Si no solicitaste este cambio, ignora este correo.\n\n";
        $mail->Body .= "SIGA-ITC\n";
        
        $mail->send();
        error_log("Email enviado a: " . $email);
        return true;
        
    } catch (Exception $e) {
        error_log("Error al enviar email: " . $e->getMessage());
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - SIGA-ITC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Recuperar Contraseña</h2>
                
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <?php if ($paso == 1): ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <button type="submit" name="paso1" class="btn btn-primary">Enviar Enlace</button>
                    </form>
                    
                    <hr>
                    <p><a href="login.php">Volver al Login</a></p>

                <?php elseif ($paso == 2): ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nueva_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="nueva_password" name="nueva_password" required minlength="6">
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirmar_password" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmar_password" name="confirmar_password" required minlength="6">
                        </div>
                        
                        <button type="submit" name="paso2" class="btn btn-primary">Cambiar Contraseña</button>
                    </form>

                <?php else: ?>
                    <div class="alert alert-info">
                        <p>Ya puedes iniciar sesión con tu nueva contraseña.</p>
                        <a href="login.php" class="btn btn-primary">Ir al Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>