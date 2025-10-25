<?php
session_start();
class Sistema{
    var $_DSN = "mysql:host=mariadb;dbname=database";
    var $_USER = "user";
    var $_PASSWORD = "password";
    var $_BD = null;
    function connect(){
        $this->_BD = new PDO($this->_DSN, $this->_USER, $this->_PASSWORD);
    }
    function login($correo, $contrasena){
        $contrasena= md5($contrasena);
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->connect();
            $sql= "SELECT * FROM usuario
                    WHERE correo= :correo AND password= :password";
            $stmt= $this->_BD->prepare($sql);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->bindParam(":password", $contrasena, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                $_SESSION['validado']= true;
                $_SESSION['correo']= $correo;
                $roles = $this->getroles($correo);
                $permisos = $this->getPermisos($correo);
                $_SESSION['roles']= $roles;
                $_SESSION['permisos']= $permisos;
                return true;
            }
        }
        
        return false;
    }
    function logout(){
        unset($_SESSION);
        session_destroy();
    }
    function getroles($correo){
        $roles = array();
        $this->connect();
        $sql= "SELECT r.role FROM usuario u join usuario_role ur on u.id_usuario = ur.id_usuario
                join role r on ur.id_role = r.id_role where correo = :correo";
        $stmt= $this->_BD->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            while($fila= $stmt->fetch(PDO::FETCH_ASSOC)){
                $roles[] = $fila['role'];
            }
        }
        return $roles;
    }

    function getPermisos($correo){
        $permisos = array();
        $this->connect();
        $sql= "SELECT distinct p.privilege  from usuario u join usuario_role ur on u.id_usuario = ur.id_usuario
                            left join role r on ur.id_role = r.id_role
                            left join role_privilege rp on r.id_role = rp.id_role
                            left join privilege p on rp.id_privilege = p.id_privilege
                            where correo = :correo";
        $stmt= $this->_BD->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            while($fila= $stmt->fetch(PDO::FETCH_ASSOC)){
                $permisos[] = $fila['privilege'];
            }
        }
        return $permisos;
    }
    
    function cargarFotografia($carpeta, $nombre){
        $tipos = array("image/jpg", "image/jpeg", "image/png", "image/gif");
        $maximo= 1000 * 1024;
        if(isset($_FILES[$nombre])){
            $imagen=$_FILES[$nombre];
            if($imagen["error"] == 0){
                if(in_array($imagen["type"], $tipos)){
                    if($imagen["size"] <= $maximo){
                        $n= rand(1, 999999);
                        $nombreArchivo= md5($imagen["name"].$imagen["size"].$n);
                        $extension= explode(".", $imagen["name"]);
                        $extension= $extension[count($extension)-1];
                        $nombreArchivo .= ".".$extension;
                        $rutaFinal= '../images/'.$carpeta.'/'.$nombreArchivo;
                        if(!file_exists($rutaFinal)){
                            if(move_uploaded_file($imagen["tmp_name"], $rutaFinal)){
                                return $nombreArchivo;
                            }
                        }
                        
                    }
                }
            }
        }
        return null;
    }
}
?>