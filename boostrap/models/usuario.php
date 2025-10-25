<?php
require_once "sistem.php";
class Usuario extends Sistema {
    function create($data){
        $this->connect();
        $this -> _BD -> beginTransaction();
        try {
            $sql= ("INSERT INTO usuario (correo, password, token, fecha_token) 
                    VALUES (:correo, :password, null, null)");
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $pwd = md5($data['password']);
            $sth->bindParam(":password", $pwd, PDO::PARAM_STR);
            $sth->execute();
            $affected_rows = $sth->rowCount();
            $this->_BD -> commit();
            return $affected_rows;
        } catch (Exception $ex) {
            $this->_BD -> rollback();
        }
        return null;
    }
    function read(){
        $this->connect();
        $sth = $this->_BD->prepare("Select * from usuario");
        $sth->execute();
        $data = $sth->fetchAll();
        return $data;
    }
    function readOne($id){
        $this->connect();
        $sth = $this->_BD->prepare("SELECT *
                                    FROM usuario
                                    WHERE id_usuario = :id_usuario");
        $sth->bindParam(":id_usuario", $id, PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    function update($data, $id){
        if (!is_numeric($id)) {
            return null;    
        }  
        if ($this->validate($data)) {
            $this->connect(); 
            $this -> _BD -> beginTransaction();
            try {
                $sql = "UPDATE usuario set correo = :correo, password = :password 
                        WHERE id_usuario = :id_usuario";
                $sth = $this->_BD->prepare($sql);
                $sth -> bindParam(":correo", $data['correo'], PDO::PARAM_STR);
                $pwd = md5($data['password']);
                $sth -> bindParam(":password", $pwd, PDO::PARAM_STR);
                $sth -> bindParam(":id_usuario", $id, PDO::PARAM_INT);
                $sth -> execute(); 
                $affected_rows = $sth->rowCount();  
                $this->_BD -> commit();
                return $affected_rows;
            } catch (Exception $ex) {
                $this -> _BD ->rollback();
            }
            return null;
        } 
        return null;
    }
    function delete($id){
        if (is_numeric($id)) {
            $this->connect();
            $this-> _BD -> beginTransaction();
            try {
                $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
                $sth = $this->_BD->prepare($sql);
                $sth->bindParam(":id_usuario", $id, PDO::PARAM_INT);
                $sth->execute();
                $affected_rows = $sth->rowCount();
                $this->_BD -> commit();
                return $affected_rows;
            } catch (Exception $ex) {
                $this->_BD -> rollback();
            }
            return null;
        }else {
            return null;
        }
    }
    function validate($data){
        
        return true;
    }
}
?>