<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/sistem.php';
require_once __DIR__ . '/../models/institucion.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Reportes extends Sistema {
    private $content;
    
   
    /**
     * Genera reporte PDF de instituciones e investigadores
     */
    public function institucionesInvestigadores() {
        try {
            // Validar rol
            $this->checarRol('Administrador');
            
            // Conectar a BD
            $this->connect();
            
            // Obtener datos
            $sql = "SELECT 
                        i.id_institucion,
                        i.nombre_institucion as instituto,
                        CONCAT(inv.nombre, ' ', inv.apellido) as investigador,
                        inv.correo_investigador as correo
                    FROM institucion i
                    LEFT JOIN investigador inv ON i.id_institucion = inv.id_institucion
                    ORDER BY i.nombre_institucion ASC, inv.nombre ASC";
            
            $stmt = $this->_BD->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Generar HTML
            $html = $this->generarHTMLReporte($data);
            
            // Crear PDF
            $pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
            $pdf->addPage('P', 'A4');
            $pdf->writeHTML($html);
            
            // Descargar PDF
            $nombreArchivo = 'instituciones_investigadores_' . date('YmdHis') . '.pdf';
            $pdf->output($nombreArchivo, 'D');
            exit;
            
        } catch (Html2PdfException $e) {
            header('Content-Type: text/html; charset=utf-8');
            echo "<h1>Error al generar PDF</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<pre>" . print_r($e, true) . "</pre>";
        } catch (PDOException $e) {
            header('Content-Type: text/html; charset=utf-8');
            die("Error en base de datos: " . $e->getMessage());
        } catch (Exception $e) {
            header('Content-Type: text/html; charset=utf-8');
            die("Error: " . $e->getMessage());
        }
    }
    
    /**
     * Genera el HTML del reporte
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
                    border-bottom: 2px solid #007bff;
                    padding-bottom: 15px;
                }
                
                .encabezado h1 {
                    font-size: 18px;
                    color: #007bff;
                    margin-bottom: 5px;
                }
                
                .encabezado p {
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
                
                thead tr {
                    border: 1px solid #0056b3;
                }
                
                thead th {
                    padding: 10px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #0056b3;
                    font-size: 11px;
                }
                
                tbody tr {
                    border: 1px solid #ddd;
                }
                
                tbody tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                
                tbody td {
                    padding: 8px;
                    border: 1px solid #ddd;
                    font-size: 10px;
                }
                
                .vacio {
                    text-align: center;
                    color: #999;
                    font-style: italic;
                    padding: 20px;
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
                    width: 40%;
                }
                
                .col-investigador {
                    width: 30%;
                }
                
                .col-correo {
                    width: 30%;
                }
            </style>
        </head>
        <body>
            <div class="encabezado">
                <h1>Reporte: Instituciones e Investigadores</h1>
                <p>Generado: ' . date('d/m/Y H:i:s') . '</p>
                <p>Total de registros: ' . count($data) . '</p>
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
                $institucion = isset($fila['instituto']) && !empty($fila['instituto']) ? 
                               htmlspecialchars($fila['instituto'], ENT_QUOTES, 'UTF-8') : 'Sin institución';
                $investigador = isset($fila['investigador']) && !empty($fila['investigador']) ? 
                                htmlspecialchars($fila['investigador'], ENT_QUOTES, 'UTF-8') : 'Sin investigador';
                $correo = isset($fila['correo']) && !empty($fila['correo']) ? 
                          htmlspecialchars($fila['correo'], ENT_QUOTES, 'UTF-8') : 'Sin correo';
                
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
                <p style="margin-top: 10px; font-size: 8px;">Sistema de Gestión - © 2025</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Genera reporte PDF de investigadores por institución
     */
    public function investigadoresPorInstitucion($id_institucion) {
        try {
            $this->checarRol('Administrador');
            $this->connect();
            
            // Validar que el ID sea numérico
            if (!is_numeric($id_institucion)) {
                throw new Exception("ID de institución inválido");
            }
            
            // Obtener datos de institución
            $sqlInst = "SELECT nombre_institucion, direccion, telefono 
                        FROM institucion 
                        WHERE id_institucion = :id";
            $stmtInst = $this->_BD->prepare($sqlInst);
            $stmtInst->bindParam(':id', $id_institucion, PDO::PARAM_INT);
            $stmtInst->execute();
            $institucion = $stmtInst->fetch(PDO::FETCH_ASSOC);
            
            if (!$institucion) {
                throw new Exception("Institución no encontrada");
            }
            
            // Obtener investigadores
            $sqlInv = "SELECT nombre, apellido, correo_investigador, especialidad 
                       FROM investigador 
                       WHERE id_institucion = :id 
                       ORDER BY nombre ASC";
            $stmtInv = $this->_BD->prepare($sqlInv);
            $stmtInv->bindParam(':id', $id_institucion, PDO::PARAM_INT);
            $stmtInv->execute();
            $investigadores = $stmtInv->fetchAll(PDO::FETCH_ASSOC);
            
            $html = $this->generarHTMLInvestigadores($institucion, $investigadores);
            
            $pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 0);
            $pdf->addPage('P', 'A4');
            $pdf->writeHTML($html);
            
            $nombreArchivo = 'investigadores_' . $id_institucion . '_' . date('YmdHis') . '.pdf';
            $pdf->output($nombreArchivo, 'D');
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: text/html; charset=utf-8');
            die("Error: " . $e->getMessage());
        }
    }
    
    /**
     * Genera HTML para reporte de investigadores
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
                    border-bottom: 2px solid #28a745;
                    padding-bottom: 15px;
                }
                
                .encabezado h1 {
                    font-size: 16px;
                    color: #28a745;
                    margin-bottom: 10px;
                }
                
                .institucion-info {
                    background-color: #f0f8ff;
                    padding: 10px;
                    margin-bottom: 20px;
                    border-left: 4px solid #28a745;
                    font-size: 10px;
                }
                
                .institucion-info p {
                    margin: 5px 0;
                }
                
                .institucion-info strong {
                    color: #28a745;
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
                    padding: 10px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #1e7e34;
                    font-size: 11px;
                }
                
                tbody tr {
                    border: 1px solid #ddd;
                }
                
                tbody tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                
                tbody td {
                    padding: 8px;
                    border: 1px solid #ddd;
                    font-size: 10px;
                }
                
                .vacio {
                    text-align: center;
                    color: #999;
                    padding: 20px;
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
                <p>Generado: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            
            <div class="institucion-info">
                <p><strong>Institución:</strong> ' . htmlspecialchars($institucion['nombre_institucion'], ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Dirección:</strong> ' . htmlspecialchars($institucion['direccion'], ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Teléfono:</strong> ' . htmlspecialchars($institucion['telefono'], ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Total de investigadores:</strong> ' . count($investigadores) . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Nombre</th>
                        <th style="width: 25%;">Apellido</th>
                        <th style="width: 30%;">Correo</th>
                        <th style="width: 20%;">Especialidad</th>
                    </tr>
                </thead>
                <tbody>';
        
        if (!empty($investigadores)) {
            foreach ($investigadores as $inv) {
                $nombre = htmlspecialchars($inv['nombre'], ENT_QUOTES, 'UTF-8');
                $apellido = htmlspecialchars($inv['apellido'], ENT_QUOTES, 'UTF-8');
                $correo = htmlspecialchars($inv['correo_investigador'], ENT_QUOTES, 'UTF-8');
                $especialidad = htmlspecialchars($inv['especialidad'], ENT_QUOTES, 'UTF-8');
                
                $html .= '<tr>
                    <td>' . $nombre . '</td>
                    <td>' . $apellido . '</td>
                    <td>' . $correo . '</td>
                    <td>' . $especialidad . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr>
                <td colspan="4" class="vacio">No hay investigadores registrados en esta institución</td>
            </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="pie">
                <p>Este es un reporte confidencial. Proteja según las políticas de su institución.</p>
                <p style="margin-top: 10px; font-size: 8px;">Sistema de Gestión - © 2025</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
?>