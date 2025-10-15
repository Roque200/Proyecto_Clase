<?php
require_once "sistem.php";

class Tratamiento extends Sistema{
    
    function create($data){
        if(!$this->validate($data)){
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            $sql = "INSERT INTO tratamiento(tratamiento) VALUES (:tratamiento)";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":tratamiento", $data['tratamiento'], PDO::PARAM_STR);
            $sth->execute();
            $rowsAffected = $sth->rowCount();
            $this->_BD->commit();
            return $rowsAffected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en create tratamiento: " . $ex->getMessage());
            return null;
        }
    }

    function read() {
        $this->connect();
        $sth = $this->_BD->prepare("SELECT * FROM tratamiento ORDER BY tratamiento ASC");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
        
    function readOne($id){
        if(!is_numeric($id)){
            return null;
        }
        
        $this->connect();
        $sth = $this->_BD->prepare("SELECT * FROM tratamiento WHERE id_tratamiento = :id_tratamiento");
        $sth->bindParam(":id_tratamiento", $id, PDO::PARAM_INT);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function update($data, $id){
        if (!is_numeric($id)) {
            return null;
        }
        
        if(!$this->validate($data)){
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            $sql = "UPDATE tratamiento SET tratamiento = :tratamiento WHERE id_tratamiento = :id_tratamiento";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":tratamiento", $data['tratamiento'], PDO::PARAM_STR);
            $sth->bindParam(":id_tratamiento", $id, PDO::PARAM_INT);
            $sth->execute();
            $rowsAffected = $sth->rowCount();
            $this->_BD->commit();
            return $rowsAffected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en update tratamiento: " . $ex->getMessage());
            return null;
        }
    }

    function delete($id){
        if (!is_numeric($id)) {
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            // Verificar si hay investigadores usando este tratamiento
            $checkSql = "SELECT COUNT(*) as total FROM investigador WHERE id_tratamiento = :id_tratamiento";
            $checkSth = $this->_BD->prepare($checkSql);
            $checkSth->bindParam(":id_tratamiento", $id, PDO::PARAM_INT);
            $checkSth->execute();
            $result = $checkSth->fetch(PDO::FETCH_ASSOC);
            
            if($result['total'] > 0){
                $this->_BD->rollback();
                throw new Exception("No se puede eliminar. Hay investigadores con este tratamiento.");
            }
            
            $sql = "DELETE FROM tratamiento WHERE id_tratamiento = :id_tratamiento";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_tratamiento", $id, PDO::PARAM_INT);
            $sth->execute();
            $rowsAffected = $sth->rowCount();
            $this->_BD->commit();
            return $rowsAffected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en delete tratamiento: " . $ex->getMessage());
            throw $ex;
        }
    }
    
    function validate($data){
        if (empty($data['tratamiento']) || trim($data['tratamiento']) === '') {
            throw new Exception("El tratamiento es requerido");
        }
        
        if (strlen(trim($data['tratamiento'])) < 2) {
            throw new Exception("El tratamiento debe tener al menos 2 caracteres");
        }
        
        if (strlen($data['tratamiento']) > 50) {
            throw new Exception("El tratamiento no puede exceder 50 caracteres");
        }
        
        return true;
    }
}
?>