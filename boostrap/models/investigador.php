<?php
require_once "sistem.php";
class Investigador extends Sistema {
    function create($data){
        $this->connect();
        $this -> _BD -> beginTransaction();
        try {
            $sql= ("INSERT INTO investigador (primer_apellido, segundo_apellido, nombre, fotografia, id_institucion, semblanza, id_tratamiento) 
                    VALUES (:primer_apellido, :segundo_apellido, :nombre, :fotografia, :id_institucion, :semblanza, :id_tratamiento)");
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":primer_apellido", $data['primer_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":segundo_apellido", $data['segundo_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":semblanza", $data['semblanza'], PDO::PARAM_STR);
            $sth->bindParam(":id_institucion", $data['id_institucion'], PDO::PARAM_INT);
            $sth->bindParam(":id_tratamiento", $data['id_tratamiento'], PDO::PARAM_INT);
            $fotografia = $this->cargarFotografia('investigadores','fotografia');
            $sth->bindParam(":fotografia", $fotografia, PDO::PARAM_STR);
            $sth->execute();
            $affected_rows = $sth->rowCount();
            $sql = "INSERT INTO usuario (correo, password) 
                    VALUES (:correo, :password)";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $pwd = md5($data['password']);
            $sth->bindParam(":password", $pwd, PDO::PARAM_STR);
            $sth->execute();
            $sql = "SELECT * from usuario WHERE correo = :correo";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $sth->execute();
            $user = $sth->fetch(PDO::FETCH_ASSOC);
            $id_usuario = $user['id_usuario'];
            $sql = "INSERT INTO usuario_role (id_role, id_usuario)
                    VALUES (:id_role, :id_usuario)";
            $sth = $this->_BD->prepare($sql);
            $id_role=2;
            $sth->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth->execute();
            $id_role=3;
            $sth->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth->execute();
            $sql = "SELECT * from investigador order by id_investigador DESC LIMIT 1";
            $sth = $this->_BD->prepare($sql);
            $sth->execute();
            $investigador = $sth->fetch(PDO::FETCH_ASSOC);
            $id_investigador = $investigador['id_investigador'];
            $sql = "UPDATE investigador set id_usuario = :id_usuario 
                    WHERE id_investigador = :id_investigador";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth->bindParam(":id_investigador", $id_investigador, PDO::PARAM_INT);
            $sth->execute();
            $destinatario = $data['correo'];
            $asunto = "Bienvenido a la Red de investigación";
            $mensaje = "Hola " . $data['nombre'] ." ". $data['primer_apellido']. ",<br><br>
                        Gracias por registrarte en la Red de investigación del ITCelaya.<br><br>
                        Tus datos de acceso son:<br>
                        Correo: " . $data['correo'] . "<br>
                        Contraseña: " . $data['password'] . "<br><br>
                        Saludos,<br>
                        Red de investigación ITCelaya";
            $nombre = $data['nombre'] ." ". $data['primer_apellido']." ". $data['segundo_apellido'];
            $mail = $this->enviarCorreo($destinatario, $asunto, $mensaje, $nombre);
            $this->_BD -> commit();
            return $affected_rows;
        } catch (Exception $ex) {
            //print_r($data);
            $this->_BD -> rollback();
            //die();
        }
        return null;
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
            ORDER BY inv.primer_apellido ASC, inv.nombre ASC
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
            // Usar semblanza en lugar de semblance
            $sql = "UPDATE investigador SET 
                    primer_apellido = :primer_apellido, 
                    segundo_apellido = :segundo_apellido, 
                    nombre = :nombre, 
                    fotografia = :fotografia, 
                    id_institucion = :id_institucion,
                    semblanza = :semblanza,
                    id_tratamiento = :id_tratamiento
                    WHERE id_investigador = :id_investigador";

            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":primer_apellido", $data['primer_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":segundo_apellido", $data['segundo_apellido'], PDO::PARAM_STR);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":fotografia", $data['fotografia'], PDO::PARAM_STR);
            $sth->bindParam(":id_institucion", $data['id_institucion'], PDO::PARAM_INT);
            $sth->bindParam(":semblanza", $data['semblance'], PDO::PARAM_STR);
            $sth->bindParam(":id_tratamiento", $data['id_tratamiento'], PDO::PARAM_INT);
            $sth->bindParam(":id_investigador", $id, PDO::PARAM_INT);
            
            $sth->execute();
            $rows_affected = $sth->rowCount();
            $this->_BD->commit();
            return $rows_affected;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en update investigador: " . $ex->getMessage());
            throw new Exception("Error al actualizar investigador: " . $ex->getMessage());
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
            throw new Exception("Error al eliminar investigador: " . $ex->getMessage());
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
                    $extencion = strtolower($extencion[count($extencion) - 1]);
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
            throw new Exception("Error al subir el archivo: " . $file["error"]);
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