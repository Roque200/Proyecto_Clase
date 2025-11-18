<?php
include_once(__DIR__."./views/header.php"); 
require_once("./models/institucion.php");
$app = new Institucion();
$instituciones = $app -> read();
include_once(__DIR__."./views/institucion/index.php");
include_once(__DIR__."./views/footer.php"); ;
?>