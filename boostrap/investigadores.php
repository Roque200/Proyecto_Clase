<?php
include_once(__DIR__."./views/header.php"); 
require_once("./models/investigador.php");
$app = new Investigador();
$investigadores = $app -> read();
include_once(__DIR__."./panel/views/investigador/index.php");
include_once(__DIR__."./views/footer.php"); 
?>
