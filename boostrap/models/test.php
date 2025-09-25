<?php
require_once ('models/institucion.php');
$app = new Institucion();
//prueba de delete
//$filas = $app-> delete(4);
//print_r($filas);

//prueba de create
//$data ['instituto'] = "Instituto de prueba";
//$data ['logotipo'] = "logotivo_prueba.png";
//$filas = $app-> create($data);
//print_r($filas);

//prueba de Update
$data ['instituto'] = "Instituto de prueba";
$data ['logotipo'] = "logotivo_prueba.png";
$fila = $app-> update($data,3);
print_r($fila);
?>