<?php 
include_once("../models/sistem.php");
include_once("./views/header.php");
$app = new Sistema();
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
switch ($action) {
    default:
        include_once("./views/index/index.php");
        break;
}
include_once("./views/footer.php");
?>