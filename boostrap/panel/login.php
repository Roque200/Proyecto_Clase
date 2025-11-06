<?php 
include_once("../models/sistem.php");
$app = new Sistema();
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {

    case 'recuperar':
        require_once("./views/login/recuperar.php");
        break;

    case 'cambio':
        $data = $_POST;
        $cambio = $app->cambiarContrasena($data);
        if ($cambio) {
            $alerta['mensaje'] = "Se ha enviado un correo con instrucciones para cambiar tu contraseña.";
            $alerta['tipo'] = "success";
            include_once("./views/alert.php");
            include_once("./views/login/login.php");
        } else {
            $alerta['mensaje'] = "No se pudo recuperar la contraseña. Verifique el correo ingresado.";
            $alerta['tipo'] = "danger";
            include_once("./views/alert.php");
            include_once("./views/login/recuperar.php");
        }
        break;

    case 'token':
        $peticion = $_GET;
        require_once("./views/login/token.php");
        break;

    case 'restablecer':
        $data = $_POST;
        if(!isset($data['correo']) || !isset($data['token']) || !isset($data['contrasena'])){
            $alerta['mensaje'] = "Datos incompletos.";
            $alerta['tipo'] = "danger";
            include_once("./views/alert.php");
            include_once("./views/login/login.php");
            break;
        }
        
        $restablecer = $app->restablecerContrasena($data);
        if ($restablecer) {
            $alerta['mensaje'] = "Contraseña restablecida correctamente. Por favor inicia sesión.";
            $alerta['tipo'] = "success";
            include_once("./views/alert.php");
            include_once("./views/login/login.php");
        } else {
            $alerta['mensaje'] = "No se pudo restablecer la contraseña. El enlace puede estar expirado.";
            $alerta['tipo'] = "danger";
            $peticion = array('token' => $data['token'], 'correo' => $data['correo']);
            include_once("./views/alert.php");
            include_once("./views/login/token.php");
        }
        break;

    case 'logout':
        $app->logout();
        header("Location: login.php");
        break;

    case 'login':
        if (isset($_POST['enviar'])) {
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $login = $app->login($correo, $contrasena);
            if ($login) {
                header("Location: index.php");
            } else {
                $alerta['mensaje'] = "Correo o contraseña incorrecta";
                $alerta['tipo'] = "danger";
                include_once("./views/alert.php");
                include_once("./views/login/login.php");
            }
        } else {
            include_once("./views/login/login.php");
        }
        break;

    default:
        include_once("./views/login/login.php");
        break;
}
?>