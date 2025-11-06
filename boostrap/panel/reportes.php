<?php 
require_once __DIR__.'/../models/sistem.php';
require_once __DIR__.'/../models/reporte.php';
require_once __DIR__.'/../models/institucion.php';
require_once __DIR__.'/../vendor/vendor/autoload.php';
$app = new Reporte();
ob_start();
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'read';
switch($accion){
    case 'institucionesInvestigadores':
        $app->checarRol('Index');
        $app->institucionesInvestigadores();
        break;
    default:
        echo "Acción no válida";
        break;
}
?>