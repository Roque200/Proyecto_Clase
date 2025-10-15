<?php
include_once '../models/sistem.php';
include_once '../views/login.php';
$app = new Sistema();
$action = $_GET['action'] ?? $_GET['action'] ?? 'login';
?>