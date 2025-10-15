<?php
include_once("./views/header.php"); 
require_once("./models/investigador.php");
$app = new Investigador();
$investigadores = $app -> read();
include_once("./panel/views/investigador/index.php");
include_once("./views/footer.php"); 
?>
