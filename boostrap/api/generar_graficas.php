<?php
// ============================================================================
// ARCHIVO: src/siga-itc/api/generar_pdf_html2pdf.php
// Genera PDF usando HTML2PDF (mejor que TCPDF para HTML)
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

    // ========== AUTOLOAD ==========
    require_once __DIR__ . '/../vendor/autoload.php';
    use Spipu\Html2Pdf\Html2Pdf;

    // ========== CONECTAR A BD ==========
    $pdo = new PDO(
        "mysql:host=mariadb;dbname=siga_itc;charset=utf8mb4",
        "user",
        "password"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ========== CREAR CARPETA REPORTES ==========
    $carpeta = __DIR__ . "/../reportes";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }

    // ========== GENERAR PDF CON HTML2PDF ==========
    switch ($tipo) {
        
        case 'pdf_usuarios':
            // Obtener datos
            $sql = "SELECT id_usuario_PK, nombre, apellidos, email, tipo_usuario 
                    FROM usuario WHERE activo = 1 ORDER BY nombre LIMIT 100";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($datos)) {
                throw new Exception('No hay usuarios');
            }
            
            // Crear HTML
            $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .header {
                        text-align: center;
                        border-bottom: 3px solid #2980B9;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }
                    .header h1 {
                        color: #2980B9;
                        margin: 0 0 5px 0;
                    }
                    .header p {
                        margin: 0;
                        font-size: 12px;
                        color: #666;
                    }
                    .info {
                        background: #ecf0f1;
                        padding: 10px;
                        margin-bottom: 20px;
                        border-radius: 5px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table th {
                        background: #2980B9;
                        color: white;
                        padding: 12px;
                        text-align: left;
                        font-weight: bold;
                    }
                    table td {
                        padding: 10px 12px;
                        border-bottom: 1px solid #ddd;
                    }
                    table tbody tr:nth-child(even) {
                        background: #f9f9f9;
                    }
                    table tbody tr:hover {
                        background: #f0f0f0;
                    }
                    .footer {
                        margin-top: 20px;
                        text-align: center;
                        font-size: 11px;
                        color: #999;
                        border-top: 1px solid #ddd;
                        padding-top: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>REPORTE DE USUARIOS</h1>
                    <p>Sistema Integral de Gestión Académica - ITC</p>
                    <p>Generado: ' . date('d/m/Y H:i:s') . '</p>
                </div>
                
                <div class="info">
                    <strong>Total de usuarios:</strong> ' . count($datos) . '
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%">ID</th>
                            <th style="width: 25%">Nombre</th>
                            <th style="width: 25%">Apellidos</th>
                            <th style="width: 30%">Email</th>
                            <th style="width: 10%">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach ($datos as $usuario) {
                $html .= '<tr>
                            <td>' . $usuario['id_usuario_PK'] . '</td>
                            <td>' . htmlspecialchars($usuario['nombre']) . '</td>
                            <td>' . htmlspecialchars($usuario['apellidos']) . '</td>
                            <td>' . htmlspecialchars($usuario['email']) . '</td>
                            <td>' . $usuario['tipo_usuario'] . '</td>
                        </tr>';
            }
            
            $html .= '
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Este documento fue generado automáticamente por el sistema SIGA-ITC</p>
                </div>
            </body>
            </html>';
            
            // Generar PDF con HTML2PDF
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->writeHTML($html);
            
            $nombre = 'reporte_usuarios_' . date('Y-m-d_H-i-s') . '.pdf';
            $ruta = $carpeta . '/' . $nombre;
            $html2pdf->output($ruta, 'F');
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'PDF de usuarios generado con HTML2PDF',
                'archivo' => 'reportes/' . $nombre
            ]);
            break;
        
        case 'pdf_estudiantes':
            $carrera = $_POST['carrera'] ?? 'Ingeniería en Sistemas';
            
            $sql = "SELECT e.id_estudiante_PK, e.matricula, e.semestre, u.nombre, u.apellidos, u.email
                    FROM estudiante e
                    INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                    WHERE e.carrera = :carrera AND u.activo = 1
                    ORDER BY e.matricula LIMIT 100";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':carrera', $carrera);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($datos)) {
                throw new Exception('No hay estudiantes en: ' . $carrera);
            }
            
            // Crear HTML
            $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .header {
                        text-align: center;
                        border-bottom: 3px solid #27ae60;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }
                    .header h1 {
                        color: #27ae60;
                        margin: 0 0 5px 0;
                    }
                    .header p {
                        margin: 0;
                        font-size: 12px;
                        color: #666;
                    }
                    .info {
                        background: #d5f4e6;
                        padding: 10px;
                        margin-bottom: 20px;
                        border-radius: 5px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table th {
                        background: #27ae60;
                        color: white;
                        padding: 12px;
                        text-align: left;
                        font-weight: bold;
                    }
                    table td {
                        padding: 10px 12px;
                        border-bottom: 1px solid #ddd;
                    }
                    table tbody tr:nth-child(even) {
                        background: #f9f9f9;
                    }
                    table tbody tr:hover {
                        background: #f0f0f0;
                    }
                    .footer {
                        margin-top: 20px;
                        text-align: center;
                        font-size: 11px;
                        color: #999;
                        border-top: 1px solid #ddd;
                        padding-top: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>REPORTE DE ESTUDIANTES</h1>
                    <p>Carrera: ' . htmlspecialchars($carrera) . '</p>
                    <p>Generado: ' . date('d/m/Y H:i:s') . '</p>
                </div>
                
                <div class="info">
                    <strong>Total de estudiantes:</strong> ' . count($datos) . '
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%">Matrícula</th>
                            <th style="width: 20%">Nombre</th>
                            <th style="width: 20%">Apellidos</th>
                            <th style="width: 30%">Email</th>
                            <th style="width: 15%">Semestre</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach ($datos as $est) {
                $html .= '<tr>
                            <td>' . htmlspecialchars($est['matricula']) . '</td>
                            <td>' . htmlspecialchars($est['nombre']) . '</td>
                            <td>' . htmlspecialchars($est['apellidos']) . '</td>
                            <td>' . htmlspecialchars($est['email']) . '</td>
                            <td>' . $est['semestre'] . '</td>
                        </tr>';
            }
            
            $html .= '
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Este documento fue generado automáticamente por el sistema SIGA-ITC</p>
                </div>
            </body>
            </html>';
            
            // Generar PDF con HTML2PDF
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->writeHTML($html);
            
            $nombre = 'reporte_estudiantes_' . date('Y-m-d_H-i-s') . '.pdf';
            $ruta = $carpeta . '/' . $nombre;
            $html2pdf->output($ruta, 'F');
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'PDF de estudiantes generado con HTML2PDF',
                'archivo' => 'reportes/' . $nombre,
                'carrera' => $carrera
            ]);
            break;
        
        default:
            throw new Exception('Tipo no válido: ' . $tipo);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'exito' => false,
        'error' => $e->getMessage()
    ]);
}

exit();
?>