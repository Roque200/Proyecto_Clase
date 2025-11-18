<?php 
include_once(__DIR__."../models/sistem.php");
include_once(__DIR__."../models/investigador.php");

$app = new Sistema();
$investigador = new Investigador();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['validado']) || $_SESSION['validado'] !== true) {
    header('Location: login.php');
    exit;
}

include_once(__DIR__."./views/header.php");

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
    default:
        $data = $investigador->read();
        include_once(__DIR__."./views/panel/investigadores.php");
        break;
}

include_once(__DIR__."./views/footer.php");
?>