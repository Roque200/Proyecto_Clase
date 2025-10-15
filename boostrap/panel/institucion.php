<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../models/institucion.php');
$app = new Institucion();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$data = array();

include_once('./views/header.php');

switch ($action) {
    case 'create':
        if(isset($_POST['enviar'])) {
            $data['instituto'] = $_POST['instituto'];
            $data['logotipo'] = $_POST['logotipo'];
            $filas = $app->create($data);
            if ($filas){
                $alerta['mensaje'] = "Institución agregada correctamente.";
                $alerta['tipo'] = "success";
                include_once('./views/Alert.php');
            }else{
                $alerta['mensaje'] = "Error al agregar la institución.";
                $alerta['tipo'] = "danger";
                include_once('./views/Alert.php');
            }
            $data = $app->read();
            include_once('views/institucion/index.php');
        } else {
            include_once('views/institucion/_form.php');
        }
        break;

    case 'update':
        if(isset($_POST['enviar'])) {
            $data['instituto'] = $_POST['instituto'];
            $data['logotipo'] = $_POST['logotipo'];
            $id = $_GET['id'];
            $filas = $app->update($data, $id);
            if ($filas){
                $alerta['mensaje'] = "Institución Modificada correctamente.";
                $alerta['tipo'] = "success";
                include_once('./views/Alert.php');
            }else{
                $alerta['mensaje'] = "Error al Modificar la institución.";
                $alerta['tipo'] = "danger";
                include_once('./views/Alert.php');
            }
            $data = $app->read();
            include_once('views/institucion/index.php');
        } else {
            $id = $_GET['id'];
            $data = $app->readOne($id);
            include_once('views/institucion/_form_update.php');
        }
        break;

    case 'delete':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $filas = $app->delete($id);
            if ($filas){
                $alerta['mensaje'] = "Institución eliminada correctamente.";
                $alerta['tipo'] = "success";
                include_once('./views/Alert.php');
            }else{
                $alerta['mensaje'] = "Error al eliminar la institución.";
                $alerta['tipo'] = "danger";
                include_once('./views/Alert.php');
            }
        }
        $data = $app->read();
        include_once('views/institucion/index.php');
        break;
        
    case 'read':
    default:
        $data = $app->read();
        include_once('views/institucion/index.php');
        break;
}

include_once('./views/footer.php');
?>