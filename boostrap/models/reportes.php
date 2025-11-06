<?php
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
require_once "sistem.php";

class Reportes extends Sistema {
    var $content;
    
    public function __construct() {
        $this->content = ob_get_clean();
    }
    
    public function institucionesInvestigadores() {
        require_once __DIR__.'/../config/reportes.php';
        $institucion = new Institucion();
        $data = $institucion->reporteInstitucionesInvestigadores();
        
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        
        $this->content = '<h1>Instituciones e Investigadores</h1>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Institución</th>
                    <th>Investigador</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>';
        
        if (!empty($data)) {
            foreach($data as $institucion) {
                $this->content .= '<tr>
                    <td>'.$institucion['instituto'].'</td>
                    <td>'.$institucion['investigador'].'</td>
                    <td>'.$institucion['correo'].'</td>
                </tr>';
            }
        } else {
            $this->content .= '<tr>
                <td colspan="3" style="text-align: center;">No hay instituciones registradas</td>
            </tr>';
        }
        
        $this->content .= '</tbody></table>';
        
        try {
            $html2pdf->writeHTML($this->content);
            $html2pdf->output('instituciones_investigadores.pdf', 'D');
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }
}
?>