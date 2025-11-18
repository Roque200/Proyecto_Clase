<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start();

class Sistema {
    var $_DSN = "mysql:host=mariadb;dbname=database";
    var $_USER = "user";
    var $_PASSWORD = "password";
    var $_BD = null;
    
    function connect() {
        try {
            $this->_BD = new PDO($this->_DSN, $this->_USER, $this->_PASSWORD);
            $this->_BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    function login($correo, $contrasena) {
        $contrasena = md5($contrasena);
        
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        try {
            $this->connect();
            $sql = "SELECT * FROM usuario WHERE correo = :correo AND password = :password";
            $stmt = $this->_BD->prepare($sql);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->bindParam(":password", $contrasena, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['validado'] = true;
                $_SESSION['correo'] = $correo;
                $roles = $this->getroles($correo);
                $permisos = $this->getPermisos($correo);
                $_SESSION['roles'] = $roles;
                $_SESSION['permisos'] = $permisos;
                return true;
            }
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
        
        return false;
    }
    
    function logout() {
        unset($_SESSION);
        session_destroy();
    }
    
    function getroles($correo) {
        $roles = array();
        
        try {
            $this->connect();
            $sql = "SELECT r.role FROM usuario u 
                    JOIN usuario_role ur ON u.id_usuario = ur.id_usuario
                    JOIN role r ON ur.id_role = r.id_role 
                    WHERE u.correo = :correo";
            $stmt = $this->_BD->prepare($sql);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $roles[] = $fila['role'];
                }
            }
        } catch (PDOException $e) {
            error_log("Error en getroles: " . $e->getMessage());
        }
        
        return $roles;
    }
    
    function getPermisos($correo) {
        $permisos = array();
        
        try {
            $this->connect();
            $sql = "SELECT DISTINCT p.privilege FROM usuario u 
                    JOIN usuario_role ur ON u.id_usuario = ur.id_usuario
                    LEFT JOIN role r ON ur.id_role = r.id_role
                    LEFT JOIN role_privilege rp ON r.id_role = rp.id_role
                    LEFT JOIN privilege p ON rp.id_privilege = p.id_privilege
                    WHERE u.correo = :correo AND p.privilege IS NOT NULL";
            $stmt = $this->_BD->prepare($sql);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $permisos[] = $fila['privilege'];
                }
            }
        } catch (PDOException $e) {
            error_log("Error en getPermisos: " . $e->getMessage());
        }
        
        return $permisos;
    }
    
    function cargarFotografia($carpeta, $nombre) {
        $tipos = array("image/jpg", "image/jpeg", "image/png", "image/gif");
        $maximo = 1000 * 1024;
        
        if (isset($_FILES[$nombre])) {
            $imagen = $_FILES[$nombre];
            
            if ($imagen["error"] == 0) {
                if (in_array($imagen["type"], $tipos)) {
                    if ($imagen["size"] <= $maximo) {
                        $n = rand(1, 999999);
                        $nombreArchivo = md5($imagen["name"] . $imagen["size"] . $n);
                        $extension = explode(".", $imagen["name"]);
                        $extension = $extension[count($extension) - 1];
                        $nombreArchivo .= "." . $extension;
                        $rutaFinal = '../images/' . $carpeta . '/' . $nombreArchivo;
                        
                        if (!file_exists($rutaFinal)) {
                            if (move_uploaded_file($imagen["tmp_name"], $rutaFinal)) {
                                return $nombreArchivo;
                            }
                        }
                    }
                }
            }
        }
        
        return null;
    }
    
    function checarRol($rol) {
        $roles = isset($_SESSION['roles']) ? $_SESSION['roles'] : array();
        
        if (!in_array($rol, $roles)) {
            $alerta['mensaje'] = "Usted no tiene el rol adecuado";
            $alerta['tipo'] = "danger";
            include_once("./views/error.php");
            die();
        }
    }
    
    function enviarCorreo($destinatario, $asunto, $mensaje, $nombre) {
        try {
            require_once __DIR__ . '/../vendor/autoload.php';
            
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->Username = '21031190@itcelaya.edu.mx';
            $mail->Password = 'zoqs rkzj pcrp aazr';
            $mail->setFrom('21031190@itcelaya.edu.mx', 'Perez Roque');
            $mail->addAddress($destinatario, $nombre ? $nombre : 'Red de investigación');
            $mail->Subject = $asunto;
            $mail->msgHTML($mensaje);
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }
    
    function cambiarContrasena($data) {
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        try {
            $this->connect();
            $token = bin2hex(random_bytes(16));
            $token = md5($token);
            
            $sqlVerify = "SELECT id_usuario FROM usuario WHERE correo = :correo";
            $stmtVerify = $this->_BD->prepare($sqlVerify);
            $stmtVerify->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $stmtVerify->execute();
            
            if ($stmtVerify->rowCount() == 0) {
                return false;
            }
            
            $sql = "UPDATE usuario SET token = :token WHERE correo = :correo";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":token", $token, PDO::PARAM_STR);
            $sth->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $sth->execute();
            $affected_rows = $sth->rowCount();
            
            if ($affected_rows) {
                $destinatario = $data['correo'];
                $asunto = "Recuperación de contraseña";
                $token_encoded = urlencode($token);
                $correo_encoded = urlencode($data['correo']);
                $enlace = "http://localhost:8080/boostrap/panel/login.php?action=token&token=" . $token_encoded . "&correo=" . $correo_encoded;
                $mensaje = 'Para restablecer su contraseña, haga clic en el siguiente enlace:
                <br><br>
                <a href="' . $enlace . '">Restablecer contraseña</a>
                <br><br>
                Si no solicitó este cambio, ignore este correo.
                <br>
                El enlace expirará en 24 horas.';
                
                return $this->enviarCorreo($destinatario, $asunto, $mensaje, null);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error en cambiarContrasena: " . $e->getMessage());
            return false;
        }
    }
    
    function verificarToken($token, $correo) {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        try {
            $this->connect();
            $sql = "SELECT id_usuario FROM usuario WHERE token = :token AND correo = :correo";
            $stmt = $this->_BD->prepare($sql);
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error en verificarToken: " . $e->getMessage());
            return false;
        }
    }
    
    function restablecerContrasena($data) {
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        if (!isset($data['token']) || !isset($data['correo'])) {
            return false;
        }
        
        if (!$this->verificarToken($data['token'], $data['correo'])) {
            return false;
        }
        
        try {
            $this->connect();
            $contraseña = md5($data['contrasena']);
            $sql = "UPDATE usuario SET password = :password, token = NULL 
                    WHERE correo = :correo AND token = :token";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":password", $contraseña, PDO::PARAM_STR);
            $sth->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $sth->bindParam(":token", $data['token'], PDO::PARAM_STR);
            $sth->execute();
            $affected_rows = $sth->rowCount();
            
            return $affected_rows > 0;
        } catch (PDOException $e) {
            error_log("Error en restablecerContrasena: " . $e->getMessage());
            return false;
        }
    }
}
?>