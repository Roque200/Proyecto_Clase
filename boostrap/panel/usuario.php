<?php
require_once("../models/usuario.php");
$app = new Usuario();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$data = array();
$alerta = array();

include_once("./views/header.php");

switch ($action) {
    
    case 'create':
        if (isset($_POST['enviar'])) {
            $data['correo'] = trim($_POST['correo']);
            $data['password'] = $_POST['password'];
            
            if($app->validate($data)){
                $row = $app->create($data);
                if ($row){
                    $alerta['mensaje'] = "Usuario dado de alta correctamente";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "El usuario no fue dado de alta o ya existe";
                    $alerta['tipo'] = "danger";
                }
            } else {
                $alerta['mensaje'] = "Los datos del usuario no son válidos. Email válido y contraseña mín 6 caracteres";
                $alerta['tipo'] = "danger";
            }
            
            include_once("./views/alert.php");
            $data = $app->read();
            include_once("./views/usuario/index.php");
        } else {
            include_once("./views/usuario/_form.php");
        }
        break;

    case 'update':
        if (isset($_POST['enviar'])) {
            $data['correo'] = trim($_POST['correo']);
            $data['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $id = $_GET['id'];
            
            if($app->validate($data)){
                $row = $app->update($data, $id);
                if ($row){
                    $alerta['mensaje'] = "Usuario modificado correctamente";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "El usuario no fue modificado o el email ya existe";
                    $alerta['tipo'] = "danger";
                }
            } else {
                $alerta['mensaje'] = "Los datos del usuario no son válidos";
                $alerta['tipo'] = "danger";
            }
            
            include_once("./views/alert.php");
            $data = $app->read();
            include_once("./views/usuario/index.php");
        } else {
            $id = $_GET['id'];
            $data = $app->readOne($id);
            if(!$data){
                $alerta['mensaje'] = "Usuario no encontrado";
                $alerta['tipo'] = "danger";
                include_once("./views/alert.php");
                $data = $app->read();
                include_once("./views/usuario/index.php");
            } else {
                include_once("./views/usuario/_form_update.php");
            }
        }
        break;

    case 'delete':
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $row = $app->delete($id);
            if ($row){
                $alerta['mensaje'] = "Usuario eliminado correctamente";
                $alerta['tipo'] = "success";
            } else {
                $alerta['mensaje'] = "El usuario no fue eliminado";
                $alerta['tipo'] = "danger";
            }
            include_once("./views/alert.php");
        }
        $data = $app->read();
        include_once("./views/usuario/index.php");
        break;
    
    case 'readRole':
        if(isset($_GET['id'])){
            $id_usuario = $_GET['id'];
            $usuario = $app->readOne($id_usuario);
            $roles_usuario = $app->getRolesByUser($id_usuario);
            $todos_roles = $app->getAllRoles();
            
            if(!$usuario){
                $alerta['mensaje'] = "Usuario no encontrado";
                $alerta['tipo'] = "danger";
                include_once("./views/alert.php");
                $data = $app->read();
                include_once("./views/usuario/index.php");
            } else {
                include_once("./views/usuario/roles.php");
            }
        } else {
            $alerta['mensaje'] = "ID de usuario no especificado";
            $alerta['tipo'] = "danger";
            include_once("./views/alert.php");
            $data = $app->read();
            include_once("./views/usuario/index.php");
        }
        break;
    
    case 'assignRole':
        if(isset($_POST['id_usuario']) && isset($_POST['id_role'])){
            $id_usuario = $_POST['id_usuario'];
            $id_role = $_POST['id_role'];
            
            if($app->assignRole($id_usuario, $id_role)){
                $alerta['mensaje'] = "Rol asignado correctamente";
                $alerta['tipo'] = "success";
            } else {
                $alerta['mensaje'] = "Error al asignar el rol o ya existe";
                $alerta['tipo'] = "danger";
            }
        }
        
        if(isset($_GET['id'])){
            $id_usuario = $_GET['id'];
            $usuario = $app->readOne($id_usuario);
            $roles_usuario = $app->getRolesByUser($id_usuario);
            $todos_roles = $app->getAllRoles();
            
            include_once("./views/alert.php");
            include_once("./views/usuario/roles.php");
        }
        break;
    
    case 'removeRole':
        if(isset($_GET['id_usuario']) && isset($_GET['id_role'])){
            $id_usuario = $_GET['id_usuario'];
            $id_role = $_GET['id_role'];
            
            if($app->removeRole($id_usuario, $id_role)){
                $alerta['mensaje'] = "Rol removido correctamente";
                $alerta['tipo'] = "success";
            } else {
                $alerta['mensaje'] = "Error al remover el rol";
                $alerta['tipo'] = "danger";
            }
            
            $usuario = $app->readOne($id_usuario);
            $roles_usuario = $app->getRolesByUser($id_usuario);
            $todos_roles = $app->getAllRoles();
            
            include_once("./views/alert.php");
            include_once("./views/usuario/roles.php");
        }
        break;
    
    case 'read':
    default:
        $data = $app->read();
        include_once("./views/usuario/index.php");
        break;
}

include_once("./views/footer.php");
?>