<?php
require_once "sistema.php";

class Calificacion extends Sistema {
    
    function create($data) {
        $this->connect();
        $sql = "INSERT INTO calificacion (id_estudiante_FK, id_materia_FK, periodo, calificacion, estatus) 
                VALUES (:id_estudiante_FK, :id_materia_FK, :periodo, :calificacion, :estatus)";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_estudiante_FK", $data['id_estudiante_FK'], PDO::PARAM_INT);
        $sth->bindParam(":id_materia_FK", $data['id_materia_FK'], PDO::PARAM_INT);
        $sth->bindParam(":periodo", $data['periodo'], PDO::PARAM_STR);
        $sth->bindParam(":calificacion", $data['calificacion'], PDO::PARAM_STR);
        $sth->bindParam(":estatus", $data['estatus'], PDO::PARAM_STR);
        $sth->execute();
        $rowsAffected = $sth->rowCount();
        return $rowsAffected;
    }

    function read() {
        $this->connect();
        $sql = "SELECT c.*, 
                e.matricula, CONCAT(u.nombre, ' ', u.apellidos) as estudiante, e.carrera,
                m.codigo, m.nombre as materia
                FROM calificacion c
                INNER JOIN estudiante e ON c.id_estudiante_FK = e.id_estudiante_PK
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                INNER JOIN materia m ON c.id_materia_FK = m.id_materia_PK
                ORDER BY c.periodo DESC, e.matricula";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // NUEVO: Leer calificaciones filtradas por carrera del docente
    function readByCarrera($carrera) {
        $this->connect();
        $sql = "SELECT c.*, 
                e.matricula, CONCAT(u.nombre, ' ', u.apellidos) as estudiante, e.carrera,
                m.codigo, m.nombre as materia
                FROM calificacion c
                INNER JOIN estudiante e ON c.id_estudiante_FK = e.id_estudiante_PK
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                INNER JOIN materia m ON c.id_materia_FK = m.id_materia_PK
                WHERE e.carrera = :carrera
                ORDER BY c.periodo DESC, e.matricula";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":carrera", $carrera, PDO::PARAM_STR);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function readOne($id) {
        $this->connect();
        $sql = "SELECT c.*, 
                e.id_estudiante_PK, e.matricula, CONCAT(u.nombre, ' ', u.apellidos) as estudiante, e.carrera,
                m.id_materia_PK, m.codigo, m.nombre as materia
                FROM calificacion c
                INNER JOIN estudiante e ON c.id_estudiante_FK = e.id_estudiante_PK
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                INNER JOIN materia m ON c.id_materia_FK = m.id_materia_PK
                WHERE c.id_calificacion_PK = :id_calificacion";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_calificacion", $id, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function update($data, $id) {
        $this->connect();
        $sql = "UPDATE calificacion SET calificacion = :calificacion, estatus = :estatus 
                WHERE id_calificacion_PK = :id_calificacion";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":calificacion", $data['calificacion'], PDO::PARAM_STR);
        $sth->bindParam(":estatus", $data['estatus'], PDO::PARAM_STR);
        $sth->bindParam(":id_calificacion", $id, PDO::PARAM_INT);
        $sth->execute();
        $rowsAffected = $sth->rowCount();
        return $rowsAffected;
    }

    function delete($id) {
        if (is_numeric($id)) {
            $this->connect();
            $sql = "DELETE FROM calificacion WHERE id_calificacion_PK = :id_calificacion";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":id_calificacion", $id, PDO::PARAM_INT);
            $sth->execute();
            $rowsAffected = $sth->rowCount();
            return $rowsAffected;
        } else {
            return null;
        }
    }

    // ACTUALIZADO: Obtener solo estudiantes de una carrera específica
    function getEstudiantes($carrera = null) {
        $this->connect();
        
        if ($carrera) {
            $sql = "SELECT e.id_estudiante_PK, e.matricula, e.carrera,
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_completo
                    FROM estudiante e
                    INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                    WHERE u.activo = 1 AND e.carrera = :carrera
                    ORDER BY e.matricula";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":carrera", $carrera, PDO::PARAM_STR);
        } else {
            $sql = "SELECT e.id_estudiante_PK, e.matricula, e.carrera,
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_completo
                    FROM estudiante e
                    INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                    WHERE u.activo = 1
                    ORDER BY e.matricula";
            $sth = $this->_DB->prepare($sql);
        }
        
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    function getMaterias() {
        $this->connect();
        $sql = "SELECT id_materia_PK, codigo, nombre FROM materia ORDER BY nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>