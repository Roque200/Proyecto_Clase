<?php
// ============================================================================
// ARCHIVO: src/siga-itc/api/generar_reporte.php
// Genera reportes TXT, CSV y estadísticas
// ============================================================================

session_start();
ob_clean();

header('Content-Type: application/json; charset=utf-8');

try {
    // ========== VALIDACIÓN ==========
    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(401);
        echo json_encode(['exito' => false, 'error' => 'No autenticado']);
        exit();
    }

    if (!isset($_POST['tipo'])) {
        http_response_code(400);
        echo json_encode(['exito' => false, 'error' => 'Tipo no especificado']);
        exit();
    }

    $tipo = $_POST['tipo'];

    // ========== CONECTAR A BD ==========
    $pdo = new PDO(
        "mysql:host=mariadb;dbname=siga_itc;charset=utf8mb4",
        "user",
        "password"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ========== CREAR CARPETA REPORTES ==========
    $carpeta_reportes = __DIR__ . "/../reportes";
    if (!is_dir($carpeta_reportes)) {
        mkdir($carpeta_reportes, 0755, true);
    }

    // ========== PROCESAR SOLICITUD ==========
    switch ($tipo) {
        
        // ----- REPORTE DE USUARIOS (TXT) -----
        case 'pdf_usuarios':
            $sql = "SELECT id_usuario_PK, nombre, apellidos, email, tipo_usuario 
                    FROM usuario WHERE activo = 1 ORDER BY nombre LIMIT 100";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($datos)) {
                throw new Exception('No hay usuarios en la BD');
            }
            
            $nombre_archivo = 'reporte_usuarios_' . date('Y-m-d_H-i-s') . '.txt';
            $ruta_archivo = $carpeta_reportes . '/' . $nombre_archivo;
            
            $contenido = "═══════════════════════════════════════════════════════════════════\n";
            $contenido .= "REPORTE DE USUARIOS - SIGA ITC\n";
            $contenido .= "═══════════════════════════════════════════════════════════════════\n";
            $contenido .= "Generado: " . date('d/m/Y H:i:s') . "\n";
            $contenido .= "Total de usuarios: " . count($datos) . "\n";
            $contenido .= "───────────────────────────────────────────────────────────────────\n\n";
            
            $contenido .= sprintf("%-5s | %-25s | %-25s | %-30s | %-15s\n", 
                "ID", "NOMBRE", "APELLIDOS", "EMAIL", "TIPO");
            $contenido .= str_repeat("-", 110) . "\n";
            
            foreach ($datos as $usuario) {
                $contenido .= sprintf("%-5s | %-25s | %-25s | %-30s | %-15s\n",
                    $usuario['id_usuario_PK'],
                    substr($usuario['nombre'], 0, 25),
                    substr($usuario['apellidos'], 0, 25),
                    substr($usuario['email'], 0, 30),
                    $usuario['tipo_usuario']
                );
            }
            
            $contenido .= "\n" . str_repeat("═", 110) . "\n";
            $contenido .= "Fin del reporte\n";
            
            file_put_contents($ruta_archivo, $contenido);
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Reporte de usuarios generado correctamente',
                'archivo' => 'reportes/' . $nombre_archivo,
                'cantidad' => count($datos)
            ]);
            break;
        
        // ----- REPORTE DE ESTUDIANTES (TXT) -----
        case 'pdf_estudiantes':
            $carrera = $_POST['carrera'] ?? 'Ingeniería en Sistemas';
            
            $sql = "SELECT e.id_estudiante_PK, e.matricula, e.semestre, u.nombre, u.apellidos, u.email
                    FROM estudiante e
                    INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                    WHERE e.carrera = :carrera AND u.activo = 1
                    ORDER BY e.matricula
                    LIMIT 100";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':carrera', $carrera, PDO::PARAM_STR);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($datos)) {
                throw new Exception('No hay estudiantes en la carrera: ' . $carrera);
            }
            
            $nombre_archivo = 'reporte_estudiantes_' . date('Y-m-d_H-i-s') . '.txt';
            $ruta_archivo = $carpeta_reportes . '/' . $nombre_archivo;
            
            $contenido = "═══════════════════════════════════════════════════════════════════\n";
            $contenido .= "REPORTE DE ESTUDIANTES - $carrera\n";
            $contenido .= "═══════════════════════════════════════════════════════════════════\n";
            $contenido .= "Generado: " . date('d/m/Y H:i:s') . "\n";
            $contenido .= "Total de estudiantes: " . count($datos) . "\n";
            $contenido .= "───────────────────────────────────────────────────────────────────\n\n";
            
            $contenido .= sprintf("%-15s | %-25s | %-25s | %-30s | %-10s\n",
                "MATRÍCULA", "NOMBRE", "APELLIDOS", "EMAIL", "SEMESTRE");
            $contenido .= str_repeat("-", 115) . "\n";
            
            foreach ($datos as $est) {
                $contenido .= sprintf("%-15s | %-25s | %-25s | %-30s | %-10s\n",
                    $est['matricula'],
                    substr($est['nombre'], 0, 25),
                    substr($est['apellidos'], 0, 25),
                    substr($est['email'], 0, 30),
                    $est['semestre']
                );
            }
            
            $contenido .= "\n" . str_repeat("═", 115) . "\n";
            $contenido .= "Fin del reporte\n";
            
            file_put_contents($ruta_archivo, $contenido);
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Reporte de estudiantes generado correctamente',
                'archivo' => 'reportes/' . $nombre_archivo,
                'carrera' => $carrera,
                'cantidad' => count($datos)
            ]);
            break;
        
        // ----- ESTADÍSTICAS POR CARRERA -----
        case 'estadisticas_carrera':
            $carrera = $_POST['carrera'] ?? 'Ingeniería en Sistemas';
            
            $sql = "SELECT 
                        COUNT(DISTINCT e.id_estudiante_PK) as total_estudiantes,
                        ROUND(AVG(c.calificacion), 2) as promedio,
                        MIN(c.calificacion) as minimo,
                        MAX(c.calificacion) as maximo,
                        COUNT(DISTINCT c.id_calificacion_PK) as total_calificaciones
                    FROM estudiante e
                    LEFT JOIN calificacion c ON e.id_estudiante_PK = c.id_estudiante_FK
                    WHERE e.carrera = :carrera";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':carrera', $carrera, PDO::PARAM_STR);
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$stats) {
                throw new Exception('No hay datos para esa carrera');
            }
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Estadísticas generadas correctamente',
                'carrera' => $carrera,
                'datos' => $stats
            ]);
            break;
        
        // ----- EXCEL DE USUARIOS (CSV) -----
        case 'excel_usuarios':
            $sql = "SELECT id_usuario_PK, nombre, apellidos, email, tipo_usuario, fecha_registro
                    FROM usuario 
                    WHERE activo = 1 
                    ORDER BY nombre
                    LIMIT 100";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($datos)) {
                throw new Exception('No hay usuarios');
            }
            
            $nombre_archivo = 'reporte_usuarios_' . date('Y-m-d_H-i-s') . '.csv';
            $ruta_archivo = $carpeta_reportes . '/' . $nombre_archivo;
            
            $contenido = "\xEF\xBB\xBF";
            $contenido .= "ID,NOMBRE,APELLIDOS,EMAIL,TIPO_USUARIO,FECHA_REGISTRO\n";
            
            foreach ($datos as $usuario) {
                $contenido .= sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                    $usuario['id_usuario_PK'],
                    str_replace('"', '""', $usuario['nombre']),
                    str_replace('"', '""', $usuario['apellidos']),
                    str_replace('"', '""', $usuario['email']),
                    $usuario['tipo_usuario'],
                    $usuario['fecha_registro']
                );
            }
            
            file_put_contents($ruta_archivo, $contenido);
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Excel de usuarios generado correctamente',
                'archivo' => 'reportes/' . $nombre_archivo
            ]);
            break;
        
        default:
            throw new Exception('Tipo de reporte no válido: ' . $tipo);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'exito' => false,
        'error' => $e->getMessage()
    ]);
    exit();
}
?>