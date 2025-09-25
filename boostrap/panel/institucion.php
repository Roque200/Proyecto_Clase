<?php
require_once ('../models/institucion.php');
$app = new Institucion();
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$data = array();
include_once ('./views/header.php');

switch ($action) {
    case 'create':
        if(!isset($_POST['enviar'])) {
            $data['intituto'] = $_POST['intituto'];
            $data['logotipo'] = $_POST['logotipo'];
            $filas = $_app -> create($data);
            $data = $app->read();
            include_once ('views/institucion/index.php');
        }else{
            include_once ('views/institucion/_form.php');
        }

        $data['intituto'] = $_POST['intituto'];
        $data['logotipo'] = $_POST['logotipo'];
        $app = new Institucion();
        $filas = $app->create($data);
        echo $filas;
        break;

    case 'update':
        if(isset($_POST['enviar'])) {
        $data['intituto'] = $_POST['intituto'];
        $data['logotipo'] = $_POST['logotipo'];
        $id = $_GET['id'];
        $filas = $app->update($data, $id);
        $data = $app->read();
        include_once ('views/institucion/index.php');
        }else{
                $id = $_GET['id'];
                $data = $app->readOne($id);
                include_once ('views/institucion/_form_update.php');
        }
        break;

    case 'delete':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $filas = $app->delete($id);
        }
        $data = $app->read();
        include_once ('views/institucion/index.php');
        break;
        
    case 'read':
        default:
        $data = $app->read();
        include_once ('views/institucion/index.php');
        break;
    }
include_once ('./views/footer.php');
?>