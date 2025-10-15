<?php
require_once "sistem.php";

class Investigador extends Sistema{
    
    function create($data){
        if(!$this->validate($data)){
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            $sql = "INSERT INTO investigador (primer_apellido, segundo_apellido, nombre, fotografia, 
                    id_institucion, semblance, id_tratamiento) 
                    VALUES (:primer_apellido, :segundo_apellido, :nombre, :fotografia, 
                    :id_institucion, :semblance, :id_tratamiento)";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":primer_apellido", $data['primer_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":segundo_apellido", $data['segundo_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":id_institucion", $data['id_institucion'], PDO::PARAM_INT);
            $sth->bindParam(":semblance", $data['semblance'], PDO::PARAM_STR);
            $sth->bindParam(":id_tratamiento", $data['id_tratamiento'], PDO::PARAM_INT);
            $fotografia = $this -> cargarFotografia('investigadores', 'fotografía');
            $sth -> bindParam(":fotografia", $fotografia, PDO::PARAM_STR);
            $sth->execute();
            $rows_affected = $sth->rowCount();
            $this->_BD->commit();
            return $rows_affected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en create investigador: " . $ex->getMessage());
            return null;
        }
    }

    function read(){
        $this->connect();
        $sth = $this->_BD->prepare("
            SELECT 
                inv.*,
                i.instituto as nombre_institucion,
                t.tratamiento as nombre_tratamiento
            FROM investigador inv
            LEFT JOIN institucion i ON inv.id_institucion = i.id_institucion
            LEFT JOIN tratamiento t ON inv.id_tratamiento = t.id_tratamiento
            ORDER BY inv.primer_apellido, inv.nombre
        ");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function readOne($id){
        if(!is_numeric($id)){
            return null;
        }
        
        $this->connect();
        $sth = $this->_BD->prepare("
            SELECT 
                inv.*,
                i.instituto as nombre_institucion,
                t.tratamiento as nombre_tratamiento
            FROM investigador inv
            LEFT JOIN institucion i ON inv.id_institucion = i.id_institucion
            LEFT JOIN tratamiento t ON inv.id_tratamiento = t.id_tratamiento
            WHERE inv.id_investigador = :id_investigador
        ");
        $sth->bindParam(":id_investigador", $id, PDO::PARAM_INT);
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
            $sql = "UPDATE investigador SET 
                    primer_apellido = :primer_apellido, 
                    segundo_apellido = :segundo_apellido, 
                    nombre = :nombre, 
                    fotografia = :fotografia, 
                    id_institucion = :id_institucion,
                    semblance = :semblance,
                    id_tratamiento = :id_tratamiento
                    WHERE id_investigador = :id_investigador";
                    if(isset ($_FILES['fotografia'])){
                    if($_FILES['fotografia']['error'] == 0){
                    $fotografia = $this -> cargarFotografia('investigadores', 'fotografía');
                    $fotografia = $data['fotografia'];
                }
            }

            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":primer_apellido", $data['primer_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":segundo_apellido", $data['segundo_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":id_institucion", $data['id_institucion'], PDO::PARAM_INT);
            $sth->bindParam(":semblance", $data['semblance'], PDO::PARAM_STR);
            $sth->bindParam(":id_tratamiento", $data['id_tratamiento'], PDO::PARAM_INT);
            $sth->bindParam(":id_investigador", $id, PDO::PARAM_INT);
            if(isset ($_FILES['fotografia'])){
                if($_FILES['fotografia']['error'] == 0){
                    $fotografia -> bin('investigadores', 'fotografía');
                }
            }
            $fotografia = $this -> cargarFotografia('investigadores', 'fotografía');
            $sth -> bindParam(":fotografia", $fotografia, PDO::PARAM_STR);
            $sth->execute();
            $rows_affected = $sth->rowCount();
            $this->_BD->commit();
            return $rows_affected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en update investigador: " . $ex->getMessage());
            return null;
        }
    }

    function delete($id){
        if(!is_numeric($id)){
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            $sql = "DELETE FROM investigador WHERE id_investigador = :id_investigador";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_investigador", $id, PDO::PARAM_INT);
            $sth->execute();
            $rows_affected = $sth->rowCount();
            $this->_BD->commit();
            return $rows_affected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en delete investigador: " . $ex->getMessage());
            return null;
        }
    }
    
    function validate($data){
        if (empty($data['nombre']) || trim($data['nombre']) === '') {
            throw new Exception("El nombre es requerido");
        }
        
        if (empty($data['primer_apellido']) || trim($data['primer_apellido']) === '') {
            throw new Exception("El primer apellido es requerido");
        }
        
        if (empty($data['id_institucion']) || !is_numeric($data['id_institucion'])) {
            throw new Exception("Debe seleccionar una institución válida");
        }
        
        if (empty($data['id_tratamiento']) || !is_numeric($data['id_tratamiento'])) {
            throw new Exception("Debe seleccionar un tratamiento válido");
        }
        
        return true;
    }
}
?>