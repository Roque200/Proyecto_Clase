<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: /siga-itc/auth/login.php");
exit();
?>