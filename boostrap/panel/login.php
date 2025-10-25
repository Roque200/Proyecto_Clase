<?php 
include_once("../models/sistem.php");
$app = new Sistema();
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
switch ($action) {
    case 'logout':
        $app->logout();
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