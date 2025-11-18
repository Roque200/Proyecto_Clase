<?php
require_once "sistem.php";

class Usuario extends Sistema {
    
    /**
     * CREATE - Crear nuevo usuario
     * @param array $data Datos del usuario (correo, password)
     * @return int|null Número de filas afectadas o null en error
     */
    function create($data){
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            // Validar que el email no exista
            if($this->emailExists($data['correo'])){
                throw new Exception("El email ya está registrado");
            }
            
            $sql = "INSERT INTO usuario (correo, password, token, fecha_token) 
                    VALUES (:correo, :password, null, null)";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            // Usar password_hash en lugar de MD5 - tu BD usa varchar(32) pero usaremos hash más seguro
            $pwd = password_hash($data['password'], PASSWORD_BCRYPT);
            $sth->bindParam(":password", $pwd, PDO::PARAM_STR);
            $sth->execute();
            
            $affected_rows = $sth->rowCount();
            $this->_BD->commit();
            return $affected_rows;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en create usuario: " . $ex->getMessage());
            return null;
        }
    }
    
    /**
     * READ - Obtener todos los usuarios
     * @return array Lista de usuarios
     */
    function read(){
        $this->connect();
        try {
            $sql = "SELECT id_usuario, correo, fecha_token FROM usuario ORDER BY id_usuario DESC";
            $sth = $this->_BD->prepare($sql);
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (Exception $ex) {
            error_log("Error en read usuarios: " . $ex->getMessage());
            return array();
        }
    }
    
    /**
     * READ ONE - Obtener un usuario específico
     * @param int $id ID del usuario
     * @return array|null Datos del usuario
     */
    function readOne($id){
        if (!is_numeric($id)) {
            return null;
        }
        
        $this->connect();
        try {
            $sql = "SELECT id_usuario, correo, fecha_token FROM usuario WHERE id_usuario = :id_usuario";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_usuario", $id, PDO::PARAM_INT);
            $sth->execute();
            $data = $sth->fetch(PDO::FETCH_ASSOC);
            return $data;
        } catch (Exception $ex) {
            error_log("Error en readOne usuario: " . $ex->getMessage());
            return null;
        }
    }
    
    /**
     * UPDATE - Actualizar usuario
     * @param array $data Datos a actualizar
     * @param int $id ID del usuario
     * @return int|null Número de filas afectadas
     */
    function update($data, $id){
        if (!is_numeric($id)) {
            return null;
        }
        
        if (!$this->validate($data)) {
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            // Verificar que el email no esté en uso por otro usuario
            if(isset($data['correo'])){
                $existente = $this->readOne($id);
                if($existente['correo'] != $data['correo'] && $this->emailExists($data['correo'])){
                    throw new Exception("El email ya está registrado");
                }
            }
            
            $sql = "UPDATE usuario SET correo = :correo";
            $params = array(":correo" => $data['correo']);
            
            // Solo actualizar contraseña si se proporciona
            if(!empty($data['password'])){
                $sql .= ", password = :password";
                $params[":password"] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            
            $sql .= " WHERE id_usuario = :id_usuario";
            $params[":id_usuario"] = $id;
            
            $sth = $this->_BD->prepare($sql);
            $sth->execute($params);
            
            $affected_rows = $sth->rowCount();
            $this->_BD->commit();
            return $affected_rows;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en update usuario: " . $ex->getMessage());
            return null;
        }
    }
    
    /**
     * DELETE - Eliminar usuario
     * @param int $id ID del usuario
     * @return int|null Número de filas afectadas
     */
    function delete($id){
        if (!is_numeric($id)) {
            return null;
        }
        
        $this->connect();
        $this->_BD->beginTransaction();
        try {
            // Los roles se eliminan automáticamente por cascada
            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_usuario", $id, PDO::PARAM_INT);
            $sth->execute();
            
            $affected_rows = $sth->rowCount();
            $this->_BD->commit();
            return $affected_rows;
        } catch (Exception $ex) {
            $this->_BD->rollback();
            error_log("Error en delete usuario: " . $ex->getMessage());
            return null;
        }
    }
    
    /**
     * VALIDATE - Validar datos del usuario
     * @param array $data Datos a validar
     * @return bool True si es válido
     */
    function validate($data){
        // Validar correo
        if(empty($data['correo'])){
            return false;
        }
        
        if(!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)){
            return false;
        }
        
        // Validar contraseña (solo si es creación nueva)
        if(empty($data['password']) && !isset($data['id_usuario'])){
            return false;
        }
        
        if(!empty($data['password']) && strlen($data['password']) < 6){
            return false;
        }
        
        return true;
    }
    
    /**
     * EMAIL EXISTS - Verificar si email existe
     * @param string $email Email a verificar
     * @return bool True si existe
     */
    function emailExists($email){
        $this->connect();
        try {
            $sql = "SELECT id_usuario FROM usuario WHERE correo = :correo";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":correo", $email, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount() > 0;
        } catch (Exception $ex) {
            error_log("Error verificando email: " . $ex->getMessage());
            return false;
        }
    }
    
    /**
     * GET ROLES BY USER - Obtener roles del usuario
     * @param int $id_usuario ID del usuario
     * @return array Lista de roles
     */
    function getRolesByUser($id_usuario){
        if (!is_numeric($id_usuario)) {
            return array();
        }
        
        $this->connect();
        try {
            $sql = "SELECT r.id_role, r.role 
                    FROM role r
                    INNER JOIN usuario_role ur ON r.id_role = ur.id_role
                    WHERE ur.id_usuario = :id_usuario
                    ORDER BY r.role";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            error_log("Error obteniendo roles: " . $ex->getMessage());
            return array();
        }
    }
    
    /**
     * GET ALL ROLES - Obtener todos los roles disponibles
     * @return array Lista de todos los roles
     */
    function getAllRoles(){
        $this->connect();
        try {
            $sql = "SELECT id_role, role FROM role ORDER BY role ASC";
            $sth = $this->_BD->prepare($sql);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            error_log("Error obteniendo todos los roles: " . $ex->getMessage());
            return array();
        }
    }
    
    /**
     * ASSIGN ROLE - Asignar rol a usuario
     * @param int $id_usuario ID del usuario
     * @param int $id_role ID del rol
     * @return bool True si éxito
     */
    function assignRole($id_usuario, $id_role){
        if (!is_numeric($id_usuario) || !is_numeric($id_role)) {
            return false;
        }
        
        $this->connect();
        try {
            // Verificar que no exista la relación
            $sql_check = "SELECT * FROM usuario_role 
                         WHERE id_usuario = :id_usuario AND id_role = :id_role";
            $sth_check = $this->_BD->prepare($sql_check);
            $sth_check->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth_check->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $sth_check->execute();
            
            if($sth_check->rowCount() > 0){
                throw new Exception("El usuario ya tiene este rol");
            }
            
            // Insertar nuevo rol
            $sql = "INSERT INTO usuario_role (id_usuario, id_role) 
                    VALUES (:id_usuario, :id_role)";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $sth->execute();
            
            return true;
        } catch (Exception $ex) {
            error_log("Error asignando rol: " . $ex->getMessage());
            return false;
        }
    }
    
    /**
     * REMOVE ROLE - Remover rol de usuario
     * @param int $id_usuario ID del usuario
     * @param int $id_role ID del rol
     * @return bool True si éxito
     */
    function removeRole($id_usuario, $id_role){
        if (!is_numeric($id_usuario) || !is_numeric($id_role)) {
            return false;
        }
        
        $this->connect();
        try {
            $sql = "DELETE FROM usuario_role 
                    WHERE id_usuario = :id_usuario AND id_role = :id_role";
            $sth = $this->_BD->prepare($sql);
            $sth->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $sth->bindParam(":id_role", $id_role, PDO::PARAM_INT);
            $sth->execute();
            
            return true;
        } catch (Exception $ex) {
            error_log("Error removiendo rol: " . $ex->getMessage());
            return false;
        }
    }
}
?>