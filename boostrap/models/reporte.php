<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/sistem.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;

class Reportes extends Sistema {
    
    /**
     * Genera reporte PDF de instituciones e investigadores
     */
    public function institucionesInvestigadores() {
        try {
            // Conectar a BD
            $this->connect();
            
            // Obtener datos - CORRECCIÓN: usar columnas correctas de la tabla investigador
            $sql = "SELECT 
                        i.id_institucion,
                        i.instituto as nombre_institucion,
                        CONCAT(inv.nombre, ' ', inv.primer_apellido, ' ', inv.segundo_apellido) as investigador,
                        u.correo as correo_investigador
                    FROM institucion i
                    LEFT JOIN investigador inv ON i.id_institucion = inv.id_institucion
                    LEFT JOIN usuario u ON inv.id_usuario = u.id_usuario
                    ORDER BY i.instituto ASC, inv.primer_apellido ASC, inv.nombre ASC";
            
            $stmt = $this->_BD->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Generar HTML
            $html = $this->generarHTMLReporte($data);
            
            // Crear PDF
            $pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
            $pdf->addPage('P', 'A4');
            $pdf->writeHTML($html);
            
            // Limpiar buffer
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Descargar PDF
            $nombreArchivo = 'instituciones_investigadores_' . date('YmdHis') . '.pdf';
            $pdf->output($nombreArchivo, 'D');
            exit;
            
        } catch (Html2PdfException $e) {
            header('Content-Type: text/html; charset=utf-8');
            echo "<h1>Error al generar PDF (Html2Pdf)</h1>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        } catch (PDOException $e) {
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(500);
            die("Error en base de datos: " . htmlspecialchars($e->getMessage()));
        } catch (Exception $e) {
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(500);
            die("Error: " . htmlspecialchars($e->getMessage()));
        }
    }
    
    /**
     * Genera el HTML del reporte de instituciones
     */
    private function generarHTMLReporte($data) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    color: #333;
                }
                
                .encabezado {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 3px solid #007bff;
                    padding-bottom: 15px;
                }
                
                .encabezado h1 {
                    font-size: 18px;
                    color: #007bff;
                    margin-bottom: 5px;
                }
                
                .encabezado .fecha {
                    font-size: 10px;
                    color: #666;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                
                thead {
                    background-color: #007bff;
                    color: white;
                }
                
                thead th {
                    padding: 12px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #0056b3;
                    font-size: 11px;
                }
                
                tbody tr {
                    border-bottom: 1px solid #ddd;
                }
                
                tbody tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                
                tbody td {
                    padding: 10px;
                    border: 1px solid #ddd;
                    font-size: 10px;
                }
                
                .vacio {
                    text-align: center;
                    color: #999;
                    font-style: italic;
                    padding: 30px;
                }
                
                .pie {
                    text-align: center;
                    margin-top: 30px;
                    font-size: 9px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
                
                .col-institucion {
                    width: 35%;
                }
                
                .col-investigador {
                    width: 35%;
                }
                
                .col-correo {
                    width: 30%;
                }
            </style>
        </head>
        <body>
            <div class="encabezado">
                <h1>Reporte: Instituciones e Investigadores</h1>
                <p class="fecha">Red de Investigación TecNM</p>
                <p class="fecha">Generado: ' . date('d/m/Y H:i:s') . '</p>
                <p class="fecha">Total de registros: ' . count($data) . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th class="col-institucion">Institución</th>
                        <th class="col-investigador">Investigador</th>
                        <th class="col-correo">Correo</th>
                    </tr>
                </thead>
                <tbody>';
        
        if (!empty($data)) {
            foreach ($data as $fila) {
                $institucion = !empty($fila['nombre_institucion']) ? 
                               htmlspecialchars($fila['nombre_institucion'], ENT_QUOTES, 'UTF-8') : 
                               'Sin institución';
                
                $investigador = !empty($fila['investigador']) ? 
                                htmlspecialchars($fila['investigador'], ENT_QUOTES, 'UTF-8') : 
                                'Sin investigador';
                
                $correo = !empty($fila['correo_investigador']) ? 
                          htmlspecialchars($fila['correo_investigador'], ENT_QUOTES, 'UTF-8') : 
                          'Sin correo';
                
                $html .= '<tr>
                    <td class="col-institucion">' . $institucion . '</td>
                    <td class="col-investigador">' . $investigador . '</td>
                    <td class="col-correo">' . $correo . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr>
                <td colspan="3" class="vacio">No hay instituciones ni investigadores registrados</td>
            </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="pie">
                <p>Este es un reporte confidencial. Proteja según las políticas de su institución.</p>
                <p style="margin-top: 10px; font-size: 8px;">Red de Investigación TecNM - © 2025</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Genera reporte de investigadores por institución
     */
    public function investigadoresPorInstitucion($id_institucion) {
        try {
            $this->connect();
            
            if (!is_numeric($id_institucion) || $id_institucion <= 0) {
                throw new Exception("ID de institución inválido");
            }
            
            // Obtener datos de institución
            $sqlInst = "SELECT id_institucion, instituto, semblanza 
                        FROM institucion 
                        WHERE id_institucion = :id";
            $stmtInst = $this->_BD->prepare($sqlInst);
            $stmtInst->bindParam(':id', $id_institucion, PDO::PARAM_INT);
            $stmtInst->execute();
            $institucion = $stmtInst->fetch(PDO::FETCH_ASSOC);
            
            if (!$institucion) {
                throw new Exception("Institución no encontrada");
            }
            
            // Obtener investigadores - CORRECCIÓN: usar columnas correctas
            $sqlInv = "SELECT 
                            inv.nombre, 
                            inv.primer_apellido, 
                            inv.segundo_apellido,
                            u.correo,
                            inv.fotografia
                       FROM investigador inv
                       LEFT JOIN usuario u ON inv.id_usuario = u.id_usuario
                       WHERE inv.id_institucion = :id 
                       ORDER BY inv.primer_apellido ASC, inv.nombre ASC";
            $stmtInv = $this->_BD->prepare($sqlInv);
            $stmtInv->bindParam(':id', $id_institucion, PDO::PARAM_INT);
            $stmtInv->execute();
            $investigadores = $stmtInv->fetchAll(PDO::FETCH_ASSOC);
            
            $html = $this->generarHTMLInvestigadores($institucion, $investigadores);
            
            $pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
            $pdf->addPage('P', 'A4');
            $pdf->writeHTML($html);
            
            // Limpiar buffer
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            $nombreArchivo = 'investigadores_' . $id_institucion . '_' . date('YmdHis') . '.pdf';
            $pdf->output($nombreArchivo, 'D');
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(500);
            die("Error: " . htmlspecialchars($e->getMessage()));
        }
    }
    
    /**
     * Genera HTML para reporte de investigadores por institución
     */
    private function generarHTMLInvestigadores($institucion, $investigadores) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    color: #333;
                }
                
                .encabezado {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 3px solid #28a745;
                    padding-bottom: 15px;
                }
                
                .encabezado h1 {
                    font-size: 16px;
                    color: #28a745;
                    margin-bottom: 10px;
                }
                
                .institucion-info {
                    background-color: #f0f8ff;
                    padding: 15px;
                    margin-bottom: 20px;
                    border-left: 4px solid #28a745;
                    font-size: 10px;
                }
                
                .institucion-info p {
                    margin: 8px 0;
                    line-height: 1.6;
                }
                
                .institucion-info strong {
                    color: #28a745;
                    font-weight: bold;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                thead {
                    background-color: #28a745;
                    color: white;
                }
                
                thead th {
                    padding: 12px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #1e7e34;
                    font-size: 11px;
                }
                
                tbody tr {
                    border-bottom: 1px solid #ddd;
                }
                
                tbody tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                
                tbody td {
                    padding: 10px;
                    border: 1px solid #ddd;
                    font-size: 10px;
                }
                
                .vacio {
                    text-align: center;
                    color: #999;
                    padding: 30px;
                }
                
                .pie {
                    text-align: center;
                    margin-top: 30px;
                    font-size: 9px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="encabezado">
                <h1>Investigadores por Institución</h1>
                <p style="font-size: 10px; color: #666;">Red de Investigación TecNM</p>
                <p style="font-size: 10px; color: #666;">Generado: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            
            <div class="institucion-info">
                <p><strong>Institución:</strong> ' . htmlspecialchars($institucion['instituto'], ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Total de investigadores:</strong> ' . count($investigadores) . '</p>';
        
        if (!empty($institucion['semblanza'])) {
            $html .= '<p><strong>Descripción:</strong> ' . htmlspecialchars($institucion['semblanza'], ENT_QUOTES, 'UTF-8') . '</p>';
        }
        
        $html .= '
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Nombre</th>
                        <th style="width: 25%;">Apellidos</th>
                        <th style="width: 50%;">Correo</th>
                    </tr>
                </thead>
                <tbody>';
        
        if (!empty($investigadores)) {
            foreach ($investigadores as $inv) {
                $nombre = htmlspecialchars($inv['nombre'], ENT_QUOTES, 'UTF-8');
                $apellidos = htmlspecialchars(
                    trim($inv['primer_apellido'] . ' ' . $inv['segundo_apellido']), 
                    ENT_QUOTES, 
                    'UTF-8'
                );
                $correo = !empty($inv['correo']) ? 
                          htmlspecialchars($inv['correo'], ENT_QUOTES, 'UTF-8') : 
                          'Sin correo';
                
                $html .= '<tr>
                    <td>' . $nombre . '</td>
                    <td>' . $apellidos . '</td>
                    <td>' . $correo . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr>
                <td colspan="3" class="vacio">No hay investigadores registrados en esta institución</td>
            </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="pie">
                <p>Este es un reporte confidencial. Proteja según las políticas de su institución.</p>
                <p style="margin-top: 10px; font-size: 8px;">Red de Investigación TecNM - © 2025</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
?>