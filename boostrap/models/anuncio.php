<?php
require_once "sistema.php";

class Anuncio extends Sistema {
    
    function create($data) {
        $this->connect();
        $sql = "INSERT INTO anuncio (titulo, contenido, id_usuario_FK, prioridad, fecha_expiracion) 
                VALUES (:titulo, :contenido, :id_usuario_FK, :prioridad, :fecha_expiracion)";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":titulo", $data['titulo'], PDO::PARAM_STR);
        $sth->bindParam(":contenido", $data['contenido'], PDO::PARAM_STR);
        $sth->bindParam(":id_usuario_FK", $data['id_usuario_FK'], PDO::PARAM_INT);
        $sth->bindParam(":prioridad", $data['prioridad'], PDO::PARAM_STR);
        $sth->bindParam(":fecha_expiracion", $data['fecha_expiracion'], PDO::PARAM_STR);
        $sth->execute();
        $rowsAffected = $sth->rowCount();
        return $rowsAffected;
    }

    function read() {
        $this->connect();
        $sql = "SELECT a.*, CONCAT(u.nombre, ' ', u.apellidos) as autor
                FROM anuncio a
                INNER JOIN usuario u ON a.id_usuario_FK = u.id_usuario_PK
                WHERE a.activo = 1
                ORDER BY a.fecha_publicacion DESC";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function readOne($id) {
        $this->connect();
        $sql = "SELECT a.*, CONCAT(u.nombre, ' ', u.apellidos) as autor
                FROM anuncio a
                INNER JOIN usuario u ON a.id_usuario_FK = u.id_usuario_PK
                WHERE a.id_anuncio_PK = :id_anuncio";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_anuncio", $id, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function update($data, $id) {
        $this->connect();
        $sql = "UPDATE anuncio SET titulo = :titulo, contenido = :contenido, 
                prioridad = :prioridad, fecha_expiracion = :fecha_expiracion 
                WHERE id_anuncio_PK = :id_anuncio";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":titulo", $data['titulo'], PDO::PARAM_STR);
        $sth->bindParam(":contenido", $data['contenido'], PDO::PARAM_STR);
        $sth->bindParam(":prioridad", $data['prioridad'], PDO::PARAM_STR);
        $sth->bindParam(":fecha_expiracion", $data['fecha_expiracion'], PDO::PARAM_STR);
        $sth->bindParam(":id_anuncio", $id, PDO::PARAM_INT);
        $sth->execute();
        $rowsAffected = $sth->rowCount();
        return $rowsAffected;
    }

    function delete($id) {
        if (is_numeric($id)) {
            $this->connect();
            $sql = "UPDATE anuncio SET activo = 0 WHERE id_anuncio_PK = :id_anuncio";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":id_anuncio", $id, PDO::PARAM_INT);
            $sth->execute();
            $rowsAffected = $sth->rowCount();
            return $rowsAffected;
        } else {
            return null;
        }
    }
}
?>