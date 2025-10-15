<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../models/tratamiento.php');
$app = new Tratamiento();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$data = array();

include_once('./views/header.php');

switch ($action) {
    case 'create':
        if(isset($_POST['enviar'])) {
            try {
                $data['tratamiento'] = trim($_POST['tratamiento']);
                $filas = $app->create($data);
                if ($filas){
                    $alerta['mensaje'] = "Tratamiento agregado correctamente.";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "Error al agregar el tratamiento.";
                    $alerta['tipo'] = "danger";
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
                $alerta['tipo'] = "danger";
            }
            include_once('./views/alert.php');
            $data = $app->read();
            include_once('views/tratamiento/index.php');
        } else {
            include_once('views/tratamiento/_form.php');
        }
        break;

    case 'update':
        if(isset($_POST['enviar'])) {
            try {
                $data['tratamiento'] = trim($_POST['tratamiento']);
                $id = $_GET['id'];
                $filas = $app->update($data, $id);
                if ($filas){
                    $alerta['mensaje'] = "Tratamiento modificado correctamente.";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "No se realizaron cambios.";
                    $alerta['tipo'] = "info";
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
                $alerta['tipo'] = "danger";
            }
            include_once('./views/alert.php');
            $data = $app->read();
            include_once('views/tratamiento/index.php');
        } else {
            $id = $_GET['id'];
            $data = $app->readOne($id);
            if($data){
                include_once('views/tratamiento/_form_update.php');
            } else {
                $alerta['mensaje'] = "Tratamiento no encontrado.";
                $alerta['tipo'] = "danger";
                include_once('./views/alert.php');
                $data = $app->read();
                include_once('views/tratamiento/index.php');
            }
        }
        break;

    case 'delete':
        if(isset($_GET['id'])) {
            try {
                $id = $_GET['id'];
                $filas = $app->delete($id);
                if ($filas){
                    $alerta['mensaje'] = "Tratamiento eliminado correctamente.";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "Error al eliminar el tratamiento.";
                    $alerta['tipo'] = "danger";
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
                $alerta['tipo'] = "danger";
            }
            include_once('./views/alert.php');
        }
        $data = $app->read();
        include_once('views/tratamiento/index.php');
        break;
        
    case 'read':
    default:
        $data = $app->read();
        include_once('views/tratamiento/index.php');
        break;
}

include_once('./views/footer.php');
?>