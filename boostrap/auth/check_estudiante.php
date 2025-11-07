<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verificar que el usuario sea estudiante
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'estudiante') {
    $_SESSION['error'] = 'Acceso denegado. Solo estudiantes pueden acceder a esta sección.';
    header("Location: ../auth/login.php");
    exit();
}

// Función de ayuda para verificar permisos (opcional, para uso futuro)
function verificarPermiso($permiso) {
    // Aquí podrías agregar lógica adicional de permisos si es necesario
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'estudiante';
}
?>