<?php
require_once "sistem.php";
class Institucion extends Sistema{
    function create($data){
        try {
            $this->connect();
            $this->_BD->beginTransaction();
            
            $sql = "INSERT INTO institucion (instituto, logotipo) VALUES (:instituto, :logotipo)";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":instituto", $data['instituto'], PDO::PARAM_STR);
            $sth->bindParam(":logotipo", $data['logotipo'], PDO::PARAM_STR);
            $sth->execute();
            $filasAfectadas = $sth->rowCount();
            
            $this->_BD->commit();
            return $filasAfectadas;
        } catch (PDOException $e) {
            if ($this->_BD->inTransaction()) {
                $this->_BD->rollBack();
            }
            throw new Exception("Error al crear institución: " . $e->getMessage());
        }
    }

    function read(){
        $this->connect();
        // Primero intentamos con id_institucion, si falla será id_instituto
        try {
            $sth = $this->_BD->prepare("SELECT * FROM institucion ORDER BY id_institucion ASC");
            $sth->execute();
        } catch (PDOException $e) {
            // Si falla, probamos con id_instituto
            $sth = $this->_BD->prepare("SELECT * FROM institucion ORDER BY id_instituto ASC");
            $sth->execute();
        }
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function readOne($id){
        $this->connect();
        // Intentamos primero con id_institucion
        try {
            $sth = $this->_BD->prepare("SELECT * FROM institucion WHERE id_institucion = :id");
            $sth->bindParam(":id", $id, PDO::PARAM_INT);
            $sth->execute();
            $data = $sth->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                // Si no encuentra nada, probamos con id_instituto
                $sth = $this->_BD->prepare("SELECT * FROM institucion WHERE id_instituto = :id");
                $sth->bindParam(":id", $id, PDO::PARAM_INT);
                $sth->execute();
                $data = $sth->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            // Si falla, probamos directamente con id_instituto
            $sth = $this->_BD->prepare("SELECT * FROM institucion WHERE id_instituto = :id");
            $sth->bindParam(":id", $id, PDO::PARAM_INT);
            $sth->execute();
            $data = $sth->fetch(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    function update($data, $id){
        try {
            $this->connect();
            $this->_BD->beginTransaction();
            
            try {
                $sql = "UPDATE institucion SET instituto = :instituto, logotipo = :logotipo WHERE id_institucion = :id";
                $sth = $this->_BD->prepare($sql);
                $sth->bindParam(":instituto", $data['instituto'], PDO::PARAM_STR);
                $sth->bindParam(":logotipo", $data['logotipo'], PDO::PARAM_STR);
                $sth->bindParam(":id", $id, PDO::PARAM_INT);
                $sth->execute();
            } catch (PDOException $e) {
                // Si falla, probamos con id_instituto
                $sql = "UPDATE institucion SET instituto = :instituto, logotipo = :logotipo WHERE id_instituto = :id";
                $sth = $this->_BD->prepare($sql);
                $sth->bindParam(":instituto", $data['instituto'], PDO::PARAM_STR);
                $sth->bindParam(":logotipo", $data['logotipo'], PDO::PARAM_STR);
                $sth->bindParam(":id", $id, PDO::PARAM_INT);
                $sth->execute();
            }
            
            $filasAfectadas = $sth->rowCount();
            $this->_BD->commit();
            return $filasAfectadas;
        } catch (PDOException $e) {
            if ($this->_BD->inTransaction()) {
                $this->_BD->rollBack();
            }
            throw new Exception("Error al actualizar institución: " . $e->getMessage());
        }
    }

    function delete($id){
        if(!is_numeric($id)){
            return null;
        }
        
        try {
            $this->connect();
            $this->_BD->beginTransaction();
            
            try {
                $sql = "DELETE FROM institucion WHERE id_institucion = :id";
                $sth = $this->_BD->prepare($sql);
                $sth->bindParam(":id", $id, PDO::PARAM_INT);
                $sth->execute();
            } catch (PDOException $e) {
                // Si falla, probamos con id_instituto
                $sql = "DELETE FROM institucion WHERE id_instituto = :id";
                $sth = $this->_BD->prepare($sql);
                $sth->bindParam(":id", $id, PDO::PARAM_INT);
                $sth->execute();
            }
            
            $efectedRows = $sth->rowCount();
            $this->_BD->commit();
            return $efectedRows;
        } catch (PDOException $e) {
            if ($this->_BD->inTransaction()) {
                $this->_BD->rollBack();
            }
            throw new Exception("Error al eliminar institución: " . $e->getMessage());
        }
    }

    function cargarFotografia($file, $carpeta){
        $tipos_permitidos = array('image/jpeg', 'image/gif', 'image/png', 'image/webp');
        $tamanio_maximo = 5000000; // 5MB
        
        if($file["error"] == 0){
            if(in_array($file["type"], $tipos_permitidos)){
                if($file["size"] <= $tamanio_maximo){
                    $n = rand(1, 1000000);
                    $nombreArchivo = md5($file['name'] . $file['size'] . $n . time());
                    $extencion = explode('.', $file['name']);
                    $extencion = $extencion[count($extencion) - 1];
                    $nombreArchivo = $nombreArchivo . "." . $extencion;
                    
                    // Crear directorio si no existe
                    $rutaCarpeta = '../images/' . $carpeta . '/';
                    if(!is_dir($rutaCarpeta)){
                        mkdir($rutaCarpeta, 0755, true);
                    }
                    
                    $rutaFinal = $rutaCarpeta . $nombreArchivo;
                    
                    if(move_uploaded_file($file["tmp_name"], $rutaFinal)){
                        return $nombreArchivo;
                    } else {
                        throw new Exception("Error al guardar la imagen en el servidor");
                    }
                } else {
                    throw new Exception("El archivo es demasiado grande. Máximo 5MB");
                }
            } else {
                throw new Exception("Tipo de archivo no permitido. Use PNG, JPG, GIF o WebP");
            }
        } else {
            throw new Exception("Error al subir el archivo");
        }
    }
    
    function validate($data){
        // Validación del nombre de la institución
        if (empty($data['instituto']) || trim($data['instituto']) === '') {
            throw new Exception("El nombre de la institución es requerido");
        }
        
        // Validación de longitud mínima
        if (strlen(trim($data['instituto'])) < 3) {
            throw new Exception("El nombre de la institución debe tener al menos 3 caracteres");
        }
        
        // Validación de longitud máxima (ajusta según tu base de datos)
        if (strlen($data['instituto']) > 255) {
            throw new Exception("El nombre de la institución no puede exceder 255 caracteres");
        }
        
        // Validación del logotipo (opcional)
        if (isset($data['logotipo']) && !empty($data['logotipo'])) {
            // Verificar que sea una ruta válida o URL
            if (strlen($data['logotipo']) > 500) {
                throw new Exception("La ruta del logotipo es demasiado larga");
            }
        }
        
        return true;
    }
}
?>