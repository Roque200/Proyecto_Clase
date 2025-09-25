<?php
require_once "sistem.php";

class Institucion extends Sistema{
    
    function create($data){
        $this -> connect();
        $sql = ("INSERT INTO institucion (intituto, logotipo) VALUES (:intituto, :logotipo)");
        $sth = $this->_DB -> prepare($sql);
        $sth->bindParam(":intituto", $data['intituto'], PDO::PARAM_STR);
        $sth->bindParam(":logotipo", $data['logotipo'], PDO::PARAM_STR);
        $sth->execute();
        $filasAfectadas = $sth->rowCount();
        return $filasAfectadas;
    }
    function read(){
        $this -> connect();
        $sth = $this -> _BD -> prepare("SELECT * FROM institucion");
        $sth -> execute();
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function readOne($id){
        $this -> connect();
        $sth = $this->_DB -> prepare("SELECT * FROM intitucion WHERE id_institucion = :id_institucion");
        $sth->bindParam(":id_institucion", $id, PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    function update($data, $id){
        $this -> connect();
        $sql = ("UPDATE institucion SET intituto = :intituto, logotipo = :logotipo WHERE id_institucion = :id_institucion");
        $sth = $this->_DB -> prepare($sql);
        $sth->bindParam(":intituto", $data['intituto'], PDO::PARAM_STR);
        $sth->bindParam(":logotipo", $data['logotipo'], PDO::PARAM_STR);
        $sth->bindParam(":id_institucion", $id, PDO::PARAM_INT);
        $sth->execute();
        $filasAfectadas = $sth->rowCount();
        return $filasAfectadas;
    }
    function delete($id){
        if(is_numeric($id)){
        $this -> connect();
        $sql = ("DELETE FROM institucion WHERE id_institucion = :id_institucion");
        $sth = $this->_DB -> prepare($sql);
        $sth->bindParam(":id_institucion", $id, PDO::PARAM_INT);
        $sth->execute();
        $efectedRows = $sth->rowCount();
        return $efectedRows;
        }else{    
        return null; 
        }  
    }
}
?>