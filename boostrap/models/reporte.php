<?php 
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
require_once "sistem.php";
class Reporte extends Sistema{
    var $content;
    function __construct(){
        $this->content= ob_get_clean();
    }
    function institucionesInvestigadores(){
        $instituciones = new Institucion();
        $data = $instituciones->reporteInstitucionesInvestigadores();
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $this->content="
        <h1 style='color:blue'> Cantidad de investigadores en cada institución</h1>
        <table style='width:100%; border:1px solid black; border-collapse: collapse;'>
            <thead>
                <tr style='width:100%; border:1px solid black;'>
                    <th style='width:350px'>Institución</th>
                    <th>Investigadores</th>
                </tr>
            </thead>
            <tbody>";
            foreach ($data as $institucion):
                $this->content.="
                <tr>
                    <td>".
                    $institucion['institucion'];
                    $this->content.="</td>
                    <td>".
                    $institucion['total_investigadores'];
                    $this->content.=" </td>
                </tr>";
            endforeach;
            $this->content.="
            </tbody>
        </table>";
        $html2pdf->writeHTML($this->content);
        $html2pdf->output('ReporteInstitucion.pdf');
    }
}
?>