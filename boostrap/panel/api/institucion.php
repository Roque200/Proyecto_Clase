<?php
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__.'/../../models/institucion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1); 
$app = new Institucion();
$action = $_SERVER['REQUEST_METHOD'];
$data = array();
$id = isset($_GET['id']) ? $_GET['id'] : null;
switch ($action) {
    case 'POST':
        $data = $_POST;
        if(!is_null($id)){
            $filas = $app->update($data, $id);
            $data ['message'] = "Institución actualizada correctamente.";
        }else{
            $filas = $app->create($data, $id);
            $data ['message'] = "Institución creada correctamente.";    
        }
        break;

    case 'DELETE':
        if(!is_null($id)) {
            try {
                $filas = $app->delete($id);
                if ($filas){
                    $data ['message'] = "Institución eliminada correctamente.";
                }else{
                    $data ['message'] = "No se pudo eliminar la institución.";
                }
            } catch (Exception $e) {
                $data ['message'] = "Error al eliminar la institución.";
            }
        }
        break;
        
    case 'GET':
    default:
    if(is_null ($id)) {
            $data = $app->read();
        } else {
            $data = $app->readOne($id);
        }
        break;
}
print(json_encode($data,JSON_PRETTY_PRINT));
?>