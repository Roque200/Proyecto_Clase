<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../models/investigador.php");
require_once("../models/institucion.php");
require_once("../models/tratamiento.php");

$app = new Investigador();
$institucionApp = new Institucion();
$tratamientoApp = new Tratamiento();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$data = array();

include_once("./views/header.php");

switch ($action) {
    case 'create':
        if (isset($_POST['enviar'])) {
            try {
                $data=$_POST;
                if(isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == 0) {
                    $fotografia = $app->cargarFotografia($_FILES['fotografia'], 'investigadores');
                    if($fotografia) {
                        $data['fotografia'] = $fotografia;
                    }
                }
                
                $row = $app->create($data);
                if ($row){
                    $alerta['mensaje'] = "Investigador agregado correctamente";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "El investigador no fue agregado";
                    $alerta['tipo'] = "danger";
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
                $alerta['tipo'] = "danger";
            }
            include_once("./views/alert.php");
            $data = $app->read();
            include_once("./views/investigador/index.php");
        } else {
            $instituciones = $institucionApp->read();
            $tratamientos = $tratamientoApp->read();
            include_once("./views/investigador/_form.php");
        }
        break;

    case 'update':
        if (isset($_POST['enviar'])) {
            try {
                $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : null;
                
                if (!$id) {
                    throw new Exception("ID de investigador no válido");
                }
                
                $investigador_actual = $app->readOne($id);
                
                if (!$investigador_actual) {
                    throw new Exception("Investigador no encontrado");
                }
                
                $data = $_POST;
                $data['fotografia'] = $investigador_actual['fotografia'];
                
                // Manejo de la fotografía
                if(isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == 0) {
                    $fotografia = $app->cargarFotografia($_FILES['fotografia'], 'investigadores');
                    if($fotografia) {
                        // Eliminar foto anterior si existe
                        if(!empty($investigador_actual['fotografia'])) {
                            $ruta_anterior = '../images/investigadores/' . $investigador_actual['fotografia'];
                            if(file_exists($ruta_anterior)) {
                                unlink($ruta_anterior);
                            }
                        }
                        $data['fotografia'] = $fotografia;
                    }
                }
                
                $row = $app->update($data, $id);
                if ($row){
                    $alerta['mensaje'] = "Investigador modificado correctamente";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "No se realizaron cambios";
                    $alerta['tipo'] = "info";
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
                $alerta['tipo'] = "danger";
            }
            include_once("./views/alert.php");
            $data = $app->read();
            include_once("./views/investigador/index.php");
        } else {
            $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : null;
            
            if (!$id) {
                $alerta['mensaje'] = "ID de investigador no válido.";
                $alerta['tipo'] = "danger";
                include_once("./views/alert.php");
                $data = $app->read();
                include_once("./views/investigador/index.php");
            } else {
                $data = $app->readOne($id);
                if($data){
                    $instituciones = $institucionApp->read();
                    $tratamientos = $tratamientoApp->read();
                    include_once("./views/investigador/_form_update.php");
                } else {
                    $alerta['mensaje'] = "Investigador no encontrado.";
                    $alerta['tipo'] = "danger";
                    include_once("./views/alert.php");
                    $data = $app->read();
                    include_once("./views/investigador/index.php");
                }
            }
        }
        break;

    case 'delete':
        if(isset($_GET['id']) && is_numeric($_GET['id'])){
            try {
                $id = $_GET['id'];
                $investigador = $app->readOne($id);
                
                if ($investigador) {
                    // Eliminar fotografía si existe
                    if(!empty($investigador['fotografia'])) {
                        $ruta_imagen = '../images/investigadores/' . $investigador['fotografia'];
                        if(file_exists($ruta_imagen)) {
                            unlink($ruta_imagen);
                        }
                    }
                }
                
                $row = $app->delete($id);
                if ($row){
                    $alerta['mensaje'] = "Investigador eliminado correctamente";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "El investigador no fue eliminado";
                    $alerta['tipo'] = "danger";
                }
            } catch (Exception $e) {
                $alerta['mensaje'] = "Error: " . $e->getMessage();
                $alerta['tipo'] = "danger";
            }
            include_once("./views/alert.php");
        }
        $data = $app->read();
        include_once("./views/investigador/index.php");
        break;
    
    case 'read':
    default:
        $data = $app->read();
        include_once("./views/investigador/index.php");
        break;
}

include_once("./views/footer.php");
?>