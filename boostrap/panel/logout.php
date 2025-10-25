<?php
require_once('login.php');

$app = new Sistema();
$app->logout();
// Si llega aquí, la sesión fue cerrada correctamente
// El logout() redirige automáticamente a login.php
?>