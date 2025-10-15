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
    function login($correo, $password){
        $password = md5($password);
        // Send the email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email sent successfully!";
        $this->connect();
        $sql = "SELECT * FROM usuario WHERE correo = :correo AND password = :password LIMIT 1";
        $stmt = $this->_BD->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['validado'] = true;
            $_SESSION['correo'] = $correo;
            $roles = $this->getroles($correo);
            $permisos = $this ->getpermisos($correo);
            $_SESSION['roles'] = $roles;
            $_SESSION['permisos'] = $permisos;
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
        $sql = "SELECT r.role FROM usuario u  
        JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN r ON ur.id_role = r.id_role
        LEFT JOIN role_privilege ON r.id_usuario = u.id
        WHERE u.correo = :correo";
        $stmt = $this->_BD->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $roles = array();
        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $roles[] = $row['role'];
            }
        }
        return $roles;
    }
    function getpermisos($correo){
        $permisos = array();
        $this->connect();
        $sql = "SELECT p.permiso FROM usuario u  
        JOIN usuario_rol ur ON u.id_usuario= ur.id_rol
        JOIN rol_permiso rp ON ur.id_rol = rp.id_rol
        JOIN permiso p ON rp.id_permiso = p.id
        WHERE u.correo = :correo";
        $stmt = $this->_BD->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $permisos = array();
        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $permisos[] = $row['privilege'];
            }
        }
        return $permisos;
    }
    function cargarFotografia($carpeta, $nombre){
        $tipos = array('image/jpeg', 'image/gif', 'image/png');
        $maximo = 1000 * 1024;
        if(isset($_FILES[$nombre])){
            $imagen= $_FILES[$nombre];
            if($imagen["error"] == 0){
                if(in_array($imagen["type"], $tipos)){
                    if($imagen["size"] <= $maximo){
                        $n = rand (1,1000000);
                        $nombreArchivo = $md5 ($imagen['name'].$imagen['size'].$n."cruz azul gana la final"); 
                        $extencion = explode ('.', $imagen['name']);
                        $extencion = $extencion[count($extencion)-1];
                        $nombreArchivo = $nombreArchivo.".".$extencion;
                        $rutaFinal = '../images/'.$carpeta.'/'.$nombreArchivo;
                        if(!file_exists($rutaFinal)){
                            if(move_uploaded_file($imagen["tmp_name"],$rutaFinal)){
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