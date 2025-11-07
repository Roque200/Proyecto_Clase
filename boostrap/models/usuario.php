<?php
require_once(__DIR__ . "/sistema.php");

class Usuario extends Sistema {
    
    function connect() {
        try {
            $this->_DB = new PDO($this->_DSN, $this->_USER, $this->_PASSWORD);
            $this->_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    public function create($data) {
        $this->connect();
        
        $sql = "INSERT INTO usuario (nombre, apellidos, email, password, tipo_usuario) 
                VALUES (:nombre, :apellidos, :email, :password, :tipo_usuario)";
        
        $stmt = $this->_DB->prepare($sql);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellidos', $data['apellidos']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':tipo_usuario', $data['tipo_usuario']);
        
        $stmt->execute();
        return $this->_DB->lastInsertId();
    }
    
    public function read() {
        $this->connect();
        
        $sql = "SELECT * FROM usuario ORDER BY apellidos, nombre";
        $stmt = $this->_DB->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function readOne($id) {
        $this->connect();
        
        $sql = "SELECT * FROM usuario WHERE id_usuario_PK = :id";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function readByEmail($email) {
        $this->connect();
        
        $sql = "SELECT * FROM usuario WHERE email = :email AND activo = 1";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
        
    public function update($data, $id) {
        $this->connect();
        
        $sql = "UPDATE usuario 
                SET nombre = :nombre, 
                    apellidos = :apellidos, 
                    email = :email
                WHERE id_usuario_PK = :id";
        
        $stmt = $this->_DB->prepare($sql);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellidos', $data['apellidos']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    public function delete($id) {
        $this->connect();
        
        $sql = "DELETE FROM usuario WHERE id_usuario_PK = :id";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // MÉTODO LOGIN
    public function login($email, $password) {
        $this->connect();
        
        error_log("DEBUG LOGIN - Email: $email");
        
        $sql = "SELECT u.*, 
                       e.id_estudiante_PK, e.matricula, e.carrera, e.semestre,
                       d.id_docente_PK, d.numero_empleado, d.carrera as docente_carrera
                FROM usuario u
                LEFT JOIN estudiante e ON u.id_usuario_PK = e.id_usuario_FK
                LEFT JOIN docente d ON u.id_usuario_PK = d.id_usuario_FK
                WHERE u.email = :email AND u.activo = 1";
        
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("DEBUG LOGIN - Usuario encontrado: " . ($usuario ? 'SÍ' : 'NO'));
        
        if ($usuario) {
            error_log("DEBUG LOGIN - Verificando password...");
            
            if (password_verify($password, $usuario['password'])) {
                error_log("DEBUG LOGIN - Password correcto");
                return $usuario;
            } else {
                error_log("DEBUG LOGIN - Password incorrecto");
            }
        }
        
        return false;
    }
    
    // MÉTODO AUTHENTICATE (ALIAS DE LOGIN)
    public function authenticate($email, $password) {
        return $this->login($email, $password);
    }
    
    // NUEVO: Guardar token de recuperación
    public function guardarTokenRecuperacion($id_usuario, $token, $expiracion) {
        $this->connect();
        
        try {
            // Primero eliminar tokens anteriores del usuario
            $sql_delete = "DELETE FROM token_recuperacion WHERE id_usuario_FK = :id_usuario";
            $stmt_delete = $this->_DB->prepare($sql_delete);
            $stmt_delete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_delete->execute();
            
            // Insertar nuevo token
            $sql = "INSERT INTO token_recuperacion (id_usuario_FK, token, fecha_expiracion, activo) 
                    VALUES (:id_usuario, :token, :expiracion, 1)";
            
            $stmt = $this->_DB->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':expiracion', $expiracion, PDO::PARAM_STR);
            
            $stmt->execute();
            error_log("Token guardado exitosamente para usuario: " . $id_usuario);
            return true;
        } catch (Exception $e) {
            error_log("Error al guardar token: " . $e->getMessage());
            return false;
        }
    }
    
    // NUEVO: Validar token de recuperación
    public function validarTokenRecuperacion($id_usuario, $token) {
        $this->connect();
        
        $sql = "SELECT * FROM token_recuperacion 
                WHERE id_usuario_FK = :id_usuario 
                AND token = :token 
                AND activo = 1 
                AND fecha_expiracion > NOW()";
        
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    // NUEVO: Cambiar contraseña con recuperación
    public function cambiarPasswordRecuperacion($id_usuario, $password_hash, $token) {
        $this->connect();
        
        try {
            $this->_DB->beginTransaction();
            
            // Actualizar contraseña
            $sql = "UPDATE usuario SET password = :password WHERE id_usuario_PK = :id_usuario";
            $stmt = $this->_DB->prepare($sql);
            $stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            
            // Desactivar token
            $sql = "UPDATE token_recuperacion SET activo = 0 
                    WHERE id_usuario_FK = :id_usuario AND token = :token";
            $stmt = $this->_DB->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            
            $this->_DB->commit();
            return true;
        } catch (Exception $e) {
            $this->_DB->rollBack();
            error_log("Error al cambiar contraseña: " . $e->getMessage());
            return false;
        }
    }
    
    public function verificarPasswordActual($id_usuario, $password_actual) {
        $this->connect();
        
        $sql = "SELECT password FROM usuario WHERE id_usuario_PK = :id";
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id', $id_usuario);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password_actual, $usuario['password'])) {
            return true;
        }
        
        return false;
    }
    
    public function cambiarPassword($id_usuario, $nueva_password, $password_anterior = null) {
        $this->connect();
        
        try {
            $this->_DB->beginTransaction();
            
            if ($password_anterior) {
                $sql_historial = "INSERT INTO historial_password (id_usuario_FK, password_anterior, ip_cambio) 
                                 VALUES (:id_usuario, :password_anterior, :ip)";
                $stmt_historial = $this->_DB->prepare($sql_historial);
                $stmt_historial->bindParam(':id_usuario', $id_usuario);
                $stmt_historial->bindParam(':password_anterior', $password_anterior);
                $ip = $_SERVER['REMOTE_ADDR'];
                $stmt_historial->bindParam(':ip', $ip);
                $stmt_historial->execute();
            }
            
            $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET password = :password WHERE id_usuario_PK = :id";
            $stmt = $this->_DB->prepare($sql);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':id', $id_usuario);
            $stmt->execute();
            
            $this->_DB->commit();
            return true;
        } catch (Exception $e) {
            $this->_DB->rollBack();
            return false;
        }
    }
    
    public function validarFortalezaPassword($password) {
        $errores = [];
        
        if (strlen($password) < 8) {
            $errores[] = "Debe tener al menos 8 caracteres";
        }
        
        if (!preg_match("/[A-Z]/", $password)) {
            $errores[] = "Debe contener al menos una letra mayúscula";
        }
        
        if (!preg_match("/[a-z]/", $password)) {
            $errores[] = "Debe contener al menos una letra minúscula";
        }
        
        if (!preg_match("/[0-9]/", $password)) {
            $errores[] = "Debe contener al menos un número";
        }
        
        if (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
            $errores[] = "Debe contener al menos un carácter especial (!@#$%^&*...)";
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
    
    public function verificarPasswordNoReutilizada($id_usuario, $nueva_password) {
        $this->connect();
        
        $sql = "SELECT password_anterior FROM historial_password 
                WHERE id_usuario_FK = :id_usuario 
                ORDER BY fecha_cambio DESC 
                LIMIT 5";
        
        $stmt = $this->_DB->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        
        $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($historial as $registro) {
            if (password_verify($nueva_password, $registro['password_anterior'])) {
                return false;
            }
        }
        
        return true;
    }
}
?>