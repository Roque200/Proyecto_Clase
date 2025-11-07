<?php
// ============================================================================
// ARCHIVO: src/siga-itc/api/descargar_archivo.php
// Descarga archivos de reportes de forma segura
// ============================================================================

session_start();

try {
    // Validar autenticación
    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(401);
        die('No autenticado');
    }

    // Obtener nombre del archivo
    $archivo = $_GET['archivo'] ?? null;
    
    if (!$archivo) {
        http_response_code(400);
        die('Archivo no especificado');
    }

    // Validar que sea un nombre de archivo válido (sin ..)
    if (strpos($archivo, '..') !== false || strpos($archivo, '/') !== false) {
        http_response_code(403);
        die('Nombre de archivo no válido');
    }

    // Ruta completa
    $ruta = __DIR__ . "/../reportes/" . $archivo;

    // Validar que el archivo existe
    if (!file_exists($ruta)) {
        http_response_code(404);
        die('Archivo no encontrado');
    }

    // Obtener extensión
    $ext = pathinfo($archivo, PATHINFO_EXTENSION);

    // Headers según tipo de archivo
    switch ($ext) {
        case 'pdf':
            header('Content-Type: application/pdf; charset=utf-8');
            break;
        case 'txt':
            header('Content-Type: text/plain; charset=utf-8');
            break;
        case 'csv':
            header('Content-Type: text/csv; charset=utf-8');
            break;
        default:
            header('Content-Type: application/octet-stream');
    }

    header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
    header('Content-Length: ' . filesize($ruta));
    
    // Enviar archivo
    readfile($ruta);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
?>