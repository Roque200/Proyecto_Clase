<?php 
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

require_once __DIR__ . "/sistem.php";
require_once __DIR__ . "/institucion.php";

class Reporte extends Sistema {
    private $content;
    
    public function __construct() {
        ob_start();
        $this->content = '';
    }
    
    /**
     * Genera reporte PDF de instituciones e investigadores
     */
    public function institucionesInvestigadores() {
        try {
            // Obtener datos
            $institucion = new Institucion();
            $data = $institucion->reporteInstitucionesInvestigadores();
            
            // Validar que hay datos
            if (empty($data)) {
                throw new Exception("No hay datos para generar el reporte");
            }
            
            // Construir HTML
            $this->content = '
            <page>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h1 { 
                        color: #0d6efd; 
                        text-align: center; 
                        border-bottom: 3px solid #0d6efd;
                        padding-bottom: 10px;
                        margin-bottom: 20px;
                    }
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin-top: 20px;
                    }
                    thead { 
                        background-color: #0d6efd; 
                        color: white; 
                    }
                    th, td { 
                        border: 1px solid #ddd; 
                        padding: 12px; 
                        text-align: left; 
                    }
                    tr:nth-child(even) { 
                        background-color: #f2f2f2; 
                    }
                    .footer {
                        position: absolute;
                        bottom: 20px;
                        width: 100%;
                        text-align: center;
                        font-size: 10px;
                        color: #666;
                    }
                    .total {
                        font-weight: bold;
                        background-color: #e9ecef;
                    }
                </style>
                
                <h1>Reporte de Instituciones e Investigadores</h1>
                
                <p style="text-align: center; color: #666; margin-bottom: 20px;">
                    Fecha de generación: ' . date('d/m/Y H:i:s') . '
                </p>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">No.</th>
                            <th style="width: 60%;">Institución</th>
                            <th style="width: 30%;">Total Investigadores</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $total_general = 0;
            $contador = 1;
            
            foreach ($data as $institucion) {
                $total = (int)$institucion['total_investigadores'];
                $total_general += $total;
                
                $this->content .= '
                        <tr>
                            <td style="text-align: center;">' . $contador . '</td>
                            <td>' . htmlspecialchars($institucion['instituto']) . '</td>
                            <td style="text-align: center;">' . $total . '</td>
                        </tr>';
                $contador++;
            }
            
            $this->content .= '
                        <tr class="total">
                            <td colspan="2" style="text-align: right; padding-right: 20px;">
                                <strong>TOTAL GENERAL:</strong>
                            </td>
                            <td style="text-align: center;">
                                <strong>' . $total_general . '</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="footer">
                    Red de Investigación TecNM - Sistema de Gestión | 
                    Página [[page_cu]] de [[page_nb]]
                </div>
            </page>';
            
            // Generar PDF
            $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($this->content);
            
            // Nombre del archivo
            $nombreArchivo = 'Reporte_Instituciones_' . date('Y-m-d_H-i-s') . '.pdf';
            
            // Descargar PDF
            $html2pdf->output($nombreArchivo, 'D');
            
            return true;
            
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo '<h1>Error al generar PDF</h1>';
            echo $formatter->getHtmlMessage();
            return false;
            
        } catch (Exception $e) {
            echo '<h1>Error</h1>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            return false;
        }
    }
    
    /**
     * Genera reporte de investigadores por institución
     */
    public function investigadoresPorInstitucion($id_institucion) {
        try {
            $institucion = new Institucion();
            $inst_data = $institucion->readOne($id_institucion);
            
            if (!$inst_data) {
                throw new Exception("Institución no encontrada");
            }
            
            // Aquí puedes agregar la consulta para obtener investigadores
            // por institución si tienes ese método
            
            $this->content = '
            <page>
                <h1>Investigadores de ' . htmlspecialchars($inst_data['instituto']) . '</h1>
                <!-- Agregar contenido aquí -->
            </page>';
            
            $html2pdf = new Html2Pdf('P', 'A4', 'es');
            $html2pdf->writeHTML($this->content);
            $html2pdf->output('Investigadores_' . date('Y-m-d') . '.pdf', 'D');
            
            return true;
            
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>