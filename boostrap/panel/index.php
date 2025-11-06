<?php 
include_once("../models/sistem.php");
include_once("../models/institucion.php");
$app = new Sistema();
$institucion = new Institucion();
$app->checarRol('Index');
include_once("./views/header.php");
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
switch ($action) {
    default:
        $datosGraficas = $institucion -> reporteInstitucionesInvestigadores();
        include_once("./views/index/index.php");
        break;
}
include_once("./views/footer.php");
?>