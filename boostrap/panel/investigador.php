<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../models/investigador.php");
$app = new Investigador();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$data = array();

include_once("./views/header.php");

switch ($action) {
    case 'create':
        if (isset($_POST['enviar'])) {
            try {
                $data['primer_apellido'] = trim($_POST['primer_apellido']);
                $data['segundo_apellido'] = trim($_POST['segundo_apellido']);
                $data['nombre'] = trim($_POST['nombre']);
                $data['id_institucion'] = $_POST['id_institucion'];
                $data['semblance'] = trim($_POST['semblance']);
                $data['id_tratamiento'] = $_POST['id_tratamiento'];
                $row = $app->create($data);
                if ($row){
                    $alerta['mensaje'] = "Investigador dado de alta correctamente";
                    $alerta['tipo'] = "success";
                } else {
                    $alerta['mensaje'] = "El investigador no fue dado de alta";
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
            include_once("./views/investigador/_form.php");
        }
        break;

    case 'update':
        if (isset($_POST['enviar'])) {
            try {
                $data['primer_apellido'] = trim($_POST['primer_apellido']);
                $data['segundo_apellido'] = trim($_POST['segundo_apellido']);
                $data['nombre'] = trim($_POST['nombre']);
                $data['fotografia'] = trim($_POST['fotografia']);
                $data['id_institucion'] = $_POST['id_institucion'];
                $data['semblance'] = trim($_POST['semblance']);
                $data['id_tratamiento'] = $_POST['id_tratamiento'];
                $id = $_GET['id'];
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
            $id = $_GET['id'];
            $data = $app->readOne($id);
            if($data){
                include_once("./views/investigador/_form_update.php");
            } else {
                $alerta['mensaje'] = "Investigador no encontrado.";
                $alerta['tipo'] = "danger";
                include_once("./views/alert.php");
                $data = $app->read();
                include_once("./views/investigador/index.php");
            }
        }
        break;

    case 'delete':
        if(isset($_GET['id'])){
            try {
                $id = $_GET['id'];
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