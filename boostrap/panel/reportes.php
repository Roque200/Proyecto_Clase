<?php
if (ob_get_level()) {
    ob_end_clean();
}

require_once __DIR__ . '/../models/sistem.php';
require_once __DIR__ . '/../models/reporte.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$app = new Reportes();

$accion = isset($_GET['accion']) ? trim($_GET['accion']) : '';

try {
    switch ($accion) {
        
        case 'instituciones':
            $app->institucionesInvestigadores();
            break;
        
        case 'investigadores':
            $id_institucion = isset($_GET['id']) ? intval($_GET['id']) : null;
            
            if (!$id_institucion || $id_institucion <= 0) {
                header('Content-Type: text/html; charset=utf-8');
                http_response_code(400);
                die("Error: ID de institución inválido");
            }
            
            $app->investigadoresPorInstitucion($id_institucion);
            break;
        
        default:
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(400);
            echo "Error: Acción no válida.\n";
            echo "Acciones disponibles: instituciones, investigadores\n";
            echo "Ejemplo: reportes.php?accion=instituciones\n";
            echo "Ejemplo: reportes.php?accion=investigadores&id=1\n";
            break;
    }
} catch (Exception $e) {
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(500);
    die("Error al procesar reporte: " . htmlspecialchars($e->getMessage()));
}
?>