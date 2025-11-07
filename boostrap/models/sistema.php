<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class Sistema {

    var $_DSN = "mysql:host=mariadb;dbname=siga_itc";
    var $_USER = "user";
    var $_PASSWORD = "password";
    var $_DB = null;
    
    function connect() {
        try {
            $this->_DB = new PDO($this->_DSN, $this->_USER, $this->_PASSWORD);
            $this->_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>