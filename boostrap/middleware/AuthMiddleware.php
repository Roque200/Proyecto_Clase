<?php
class AuthMiddleware {
    
    // Definir permisos por rol
    private static $permissions = [
        'administrador' => [
            'estudiantes' => ['create', 'read', 'update', 'delete'],
            'calificaciones' => ['create', 'read', 'update', 'delete'],
            'anuncios' => ['create', 'read', 'update', 'delete'],
            'usuarios' => ['create', 'read', 'update', 'delete'],
            'materias' => ['create', 'read', 'update', 'delete'],
            'horarios' => ['create', 'read', 'update', 'delete'],
            'docentes' => ['create', 'read', 'update', 'delete']
        ],
        'docente' => [
            'calificaciones' => ['create', 'read', 'update'], // Solo de sus materias
            'anuncios' => ['create', 'read', 'update'], // Solo los suyos
            'estudiantes' => ['read'], // Solo consulta
            'horarios' => ['read'], // Solo consulta
            'materias' => ['read'] // Solo las que imparte
        ],
        'estudiante' => [
            'calificaciones' => ['read'], // Solo las propias
            'horarios' => ['read'], // Solo el propio
            'anuncios' => ['read'], // Solo lectura
            'perfil' => ['read', 'update'] // Su propio perfil
        ]
    ];
    
    /**
     * Verificar si el usuario tiene permiso
     */
    public static function hasPermission($recurso, $accion) {
        if (!isset($_SESSION['tipo_usuario'])) {
            return false;
        }
        
        $rol = $_SESSION['tipo_usuario'];
        
        if (!isset(self::$permissions[$rol][$recurso])) {
            return false;
        }
        
        return in_array($accion, self::$permissions[$rol][$recurso]);
    }
    
    /**
     * Verificar autenticación
     */
    public static function requireAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /siga-itc/auth/login.php");
            exit();
        }
        
        // Verificar timeout (1 hora)
        if (isset($_SESSION['ultimo_acceso'])) {
            $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];
            if ($tiempo_inactivo > 3600) {
                session_destroy();
                header("Location: /siga-itc/auth/login.php?timeout=1");
                exit();
            }
        }
        $_SESSION['ultimo_acceso'] = time();
    }
    
    /**
     * Verificar que el usuario tenga un rol específico
     */
    public static function requireRole($rol_requerido) {
        self::requireAuth();
        
        if ($_SESSION['tipo_usuario'] !== $rol_requerido) {
            header("HTTP/1.1 403 Forbidden");
            die("Acceso denegado. No tienes permisos para acceder a este recurso.");
        }
    }
    
    /**
     * Verificar que el usuario tenga al menos uno de los roles especificados
     */
    public static function requireAnyRole($roles_permitidos) {
        self::requireAuth();
        
        if (!in_array($_SESSION['tipo_usuario'], $roles_permitidos)) {
            header("HTTP/1.1 403 Forbidden");
            die("Acceso denegado. No tienes permisos para acceder a este recurso.");
        }
    }
    
    /**
     * Bloquear acceso si no tiene permiso
     */
    public static function requirePermission($recurso, $accion) {
        self::requireAuth();
        
        if (!self::hasPermission($recurso, $accion)) {
            header("HTTP/1.1 403 Forbidden");
            die("Acceso denegado. No tienes permiso para realizar esta acción.");
        }
    }
}
?>