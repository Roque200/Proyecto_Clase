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
            
            // Manejo de la imagen
            if(isset($_FILES['logotipo']) && $_FILES['logotipo']['error'] == 0) {
                $logotipo = $app->cargarFotografia($_FILES['logotipo'], 'instituciones');
                if($logotipo) {
                    $data['logotipo'] = $logotipo;
                } else {
                    $data['logotipo'] = '';
                }
            } else {
                $data['logotipo'] = '';
            }
            
            try {
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
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
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
            $id = $_GET['id'];
            
            // Obtener la institución actual
            $institucion_actual = $app->readOne($id);
            
            // Manejo de la imagen
            if(isset($_FILES['logotipo']) && $_FILES['logotipo']['error'] == 0) {
                $logotipo = $app->cargarFotografia($_FILES['logotipo'], 'instituciones');
                if($logotipo) {
                    // Eliminar la imagen anterior si existe
                    if(!empty($institucion_actual['logotipo'])) {
                        $ruta_anterior = '../images/instituciones/' . $institucion_actual['logotipo'];
                        if(file_exists($ruta_anterior)) {
                            unlink($ruta_anterior);
                        }
                    }
                    $data['logotipo'] = $logotipo;
                } else {
                    $data['logotipo'] = $institucion_actual['logotipo'];
                }
            } else {
                $data['logotipo'] = $institucion_actual['logotipo'];
            }
            
            try {
                $filas = $app->update($data, $id);
                if ($filas){
                    $alerta['mensaje'] = "Institución modificada correctamente.";
                    $alerta['tipo'] = "success";
                    include_once('./views/Alert.php');
                }else{
                    $alerta['mensaje'] = "No se realizaron cambios.";
                    $alerta['tipo'] = "info";
                    include_once('./views/Alert.php');
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
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
            $institucion = $app->readOne($id);
            
            // Eliminar la imagen si existe
            if(!empty($institucion['logotipo'])) {
                $ruta_imagen = '../images/instituciones/' . $institucion['logotipo'];
                if(file_exists($ruta_imagen)) {
                    unlink($ruta_imagen);
                }
            }
            
            try {
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
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
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