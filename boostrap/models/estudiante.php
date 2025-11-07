<?php
require_once(__DIR__ . "/sistema.php");

class Estudiante extends Sistema {
    
    function create($data) {
        $this->connect();
        $sql = "INSERT INTO estudiante (id_usuario_FK, matricula, carrera, semestre, fecha_ingreso) 
                VALUES (:id_usuario_FK, :matricula, :carrera, :semestre, :fecha_ingreso)";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_usuario_FK", $data['id_usuario_FK'], PDO::PARAM_INT);
        $sth->bindParam(":matricula", $data['matricula'], PDO::PARAM_STR);
        $sth->bindParam(":carrera", $data['carrera'], PDO::PARAM_STR);
        $sth->bindParam(":semestre", $data['semestre'], PDO::PARAM_INT);
        $sth->bindParam(":fecha_ingreso", $data['fecha_ingreso'], PDO::PARAM_STR);
        $sth->execute();
        $rowsAffected = $sth->rowCount();
        return $rowsAffected;
    }

    function read() {
        $this->connect();
        $sql = "SELECT e.*, u.nombre, u.apellidos, u.email, u.activo
                FROM estudiante e
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                WHERE u.activo = 1
                ORDER BY e.matricula";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // NUEVO MÉTODO: Leer estudiantes por carrera del docente
    function readByCarrera($carrera) {
        $this->connect();
        $sql = "SELECT e.*, u.nombre, u.apellidos, u.email, u.activo
                FROM estudiante e
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                WHERE u.activo = 1 AND e.carrera = :carrera
                ORDER BY e.matricula";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":carrera", $carrera, PDO::PARAM_STR);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // NUEVO MÉTODO: Leer estudiante por ID de usuario
    function readByUsuario($id_usuario) {
        $this->connect();
        $sql = "SELECT e.*, u.nombre, u.apellidos, u.email
                FROM estudiante e
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                WHERE u.id_usuario_PK = :id_usuario";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $sth->execute();
        
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            error_log("ERROR: No se encontró estudiante con ID de usuario: " . $id_usuario);
        } else {
            error_log("Estudiante encontrado por usuario: " . print_r($data, true));
        }
        
        return $data;
    }
    
    function readOne($id) {
        $this->connect();
        $sql = "SELECT e.*, u.nombre, u.apellidos, u.email
                FROM estudiante e
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                WHERE e.id_estudiante_PK = :id_estudiante";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_estudiante", $id, PDO::PARAM_INT);
        $sth->execute();
        
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            error_log("ERROR: No se encontró estudiante con ID: " . $id);
        } else {
            error_log("Estudiante encontrado: " . print_r($data, true));
        }
        
        return $data;
    }

    function update($data, $id) {
        $this->connect();
        $sql = "UPDATE estudiante SET matricula = :matricula, carrera = :carrera, 
                semestre = :semestre, fecha_ingreso = :fecha_ingreso 
                WHERE id_estudiante_PK = :id_estudiante";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":matricula", $data['matricula'], PDO::PARAM_STR);
        $sth->bindParam(":carrera", $data['carrera'], PDO::PARAM_STR);
        $sth->bindParam(":semestre", $data['semestre'], PDO::PARAM_INT);
        $sth->bindParam(":fecha_ingreso", $data['fecha_ingreso'], PDO::PARAM_STR);
        $sth->bindParam(":id_estudiante", $id, PDO::PARAM_INT);
        $sth->execute();
        $rowsAffected = $sth->rowCount();
        return $rowsAffected;
    }

    function delete($id) {
        if (is_numeric($id)) {
            $this->connect();
            $sql = "SELECT id_usuario_FK FROM estudiante WHERE id_estudiante_PK = :id_estudiante";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":id_estudiante", $id, PDO::PARAM_INT);
            $sth->execute();
            $estudiante = $sth->fetch(PDO::FETCH_ASSOC);
            
            if ($estudiante) {
                $sql = "UPDATE usuario SET activo = 0 WHERE id_usuario_PK = :id_usuario";
                $sth = $this->_DB->prepare($sql);
                $sth->bindParam(":id_usuario", $estudiante['id_usuario_FK'], PDO::PARAM_INT);
                $sth->execute();
                return $sth->rowCount();
            }
            return 0;
        } else {
            return null;
        }
    }

    function getCalificaciones($id_estudiante) {
        $this->connect();
        $sql = "SELECT c.*, m.codigo, m.nombre as materia, m.creditos
                FROM calificacion c
                INNER JOIN materia m ON c.id_materia_FK = m.id_materia_PK
                WHERE c.id_estudiante_FK = :id_estudiante
                ORDER BY c.periodo DESC, m.nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_estudiante", $id_estudiante, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    function getHorarios($id_estudiante) {
        $this->connect();
        $sql = "SELECT h.*, m.codigo, m.nombre as materia, 
                CONCAT(u.nombre, ' ', u.apellidos) as docente
                FROM horario h
                INNER JOIN materia m ON h.id_materia_FK = m.id_materia_PK
                INNER JOIN docente d ON h.id_docente_FK = d.id_docente_PK
                INNER JOIN usuario u ON d.id_usuario_FK = u.id_usuario_PK
                WHERE h.periodo = '2025-1'
                ORDER BY FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'), 
                h.hora_inicio";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // NUEVO MÉTODO: Actualizar perfil del estudiante
    function updatePerfil($id_estudiante, $data) {
        $this->connect();
        
        try {
            $this->_DB->beginTransaction();
            
            // Obtener id_usuario del estudiante
            $sql = "SELECT id_usuario_FK FROM estudiante WHERE id_estudiante_PK = :id";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":id", $id_estudiante, PDO::PARAM_INT);
            $sth->execute();
            $estudiante = $sth->fetch(PDO::FETCH_ASSOC);
            
            if (!$estudiante) {
                throw new Exception("Estudiante no encontrado");
            }
            
            // Actualizar datos en tabla usuario
            $sql = "UPDATE usuario SET nombre = :nombre, apellidos = :apellidos 
                    WHERE id_usuario_PK = :id_usuario";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":apellidos", $data['apellidos'], PDO::PARAM_STR);
            $sth->bindParam(":id_usuario", $estudiante['id_usuario_FK'], PDO::PARAM_INT);
            $sth->execute();
            
            $this->_DB->commit();
            return true;
            
        } catch (Exception $e) {
            $this->_DB->rollBack();
            error_log("Error al actualizar perfil: " . $e->getMessage());
            return false;
        }
    }
    
    // NUEVO MÉTODO: Actualizar foto de perfil
    function updateFotoPerfil($id_estudiante, $ruta_foto) {
        $this->connect();
        
        $sql = "UPDATE estudiante SET foto_perfil = :foto WHERE id_estudiante_PK = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":foto", $ruta_foto, PDO::PARAM_STR);
        $sth->bindParam(":id", $id_estudiante, PDO::PARAM_INT);
        $sth->execute();
        
        return $sth->rowCount() > 0;
    }
    
    // NUEVO MÉTODO: Actualizar documentos
    function updateDocumento($id_estudiante, $tipo, $ruta_documento) {
        $this->connect();
        
        $campo = '';
        switch ($tipo) {
            case 'acta':
                $campo = 'acta_nacimiento';
                break;
            case 'carta':
                $campo = 'carta_compromiso';
                break;
            case 'curp':
                $campo = 'curp_documento';
                break;
            default:
                return false;
        }
        
        $sql = "UPDATE estudiante SET $campo = :documento WHERE id_estudiante_PK = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":documento", $ruta_documento, PDO::PARAM_STR);
        $sth->bindParam(":id", $id_estudiante, PDO::PARAM_INT);
        $sth->execute();
        
        return $sth->rowCount() > 0;
    }
    
    // NUEVO MÉTODO: Obtener documentos del estudiante
    function getDocumentos($id_estudiante) {
        $this->connect();
        
        $sql = "SELECT foto_perfil, acta_nacimiento, carta_compromiso, curp_documento 
                FROM estudiante WHERE id_estudiante_PK = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_estudiante, PDO::PARAM_INT);
        $sth->execute();
        
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}
?>