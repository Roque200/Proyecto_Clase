<?php
require_once(__DIR__ . "/sistema.php");

use Spipu\Html2Pdf\Html2Pdf;
use TCPDF;

class Reporte extends Sistema {
    
    /**
     * Obtener datos de usuarios para reportes
     */
    public function obtenerUsuarios() {
        $this->connect();
        $sql = "SELECT id_usuario_PK, nombre, apellidos, email, tipo_usuario, activo, fecha_registro 
                FROM usuario 
                WHERE activo = 1 
                ORDER BY nombre
                LIMIT 100";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        $datos = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($datos)) {
            throw new Exception('No hay usuarios para generar reporte');
        }
        
        return $datos;
    }
    
    /**
     * Obtener estudiantes por carrera
     */
    public function obtenerEstudiantesPorCarrera($carrera) {
        $this->connect();
        $sql = "SELECT e.id_estudiante_PK, e.matricula, e.carrera, e.semestre, e.fecha_ingreso,
                       u.nombre, u.apellidos, u.email
                FROM estudiante e
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                WHERE e.carrera = :carrera AND u.activo = 1
                ORDER BY e.matricula";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":carrera", $carrera, PDO::PARAM_STR);
        $sth->execute();
        $datos = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($datos)) {
            throw new Exception('No hay estudiantes para esa carrera');
        }
        
        return $datos;
    }
    
    /**
     * Obtener calificaciones por estudiante
     */
    public function obtenerCalificaciones($id_estudiante) {
        $this->connect();
        $sql = "SELECT c.id_calificacion_PK, c.calificacion, c.fecha_calificacion,
                       m.nombre as materia, m.codigo
                FROM calificacion c
                INNER JOIN materia m ON c.id_materia_FK = m.id_materia_PK
                WHERE c.id_estudiante_FK = :id_estudiante
                ORDER BY c.fecha_calificacion DESC";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_estudiante", $id_estudiante, PDO::PARAM_INT);
        $sth->execute();
        $datos = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($datos)) {
            throw new Exception('No hay calificaciones para este estudiante');
        }
        
        return $datos;
    }
    
    /**
     * Obtener estadísticas de calificaciones por carrera
     */
    public function obtenerEstadisticasCarrera($carrera) {
        $this->connect();
        $sql = "SELECT e.carrera, COUNT(DISTINCT e.id_estudiante_PK) as total_estudiantes,
                       AVG(c.calificacion) as promedio_calificaciones,
                       MIN(c.calificacion) as calificacion_minima,
                       MAX(c.calificacion) as calificacion_maxima
                FROM estudiante e
                INNER JOIN usuario u ON e.id_usuario_FK = u.id_usuario_PK
                LEFT JOIN calificacion c ON e.id_estudiante_PK = c.id_estudiante_FK
                WHERE e.carrera = :carrera AND u.activo = 1
                GROUP BY e.carrera";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":carrera", $carrera, PDO::PARAM_STR);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Generar PDF con TCPDF
     */
    public function generarPDFTCPDF($titulo, $datos, $columnas, $nombreArchivo) {
        try {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator('SIGA-ITC');
            $pdf->SetAuthor('Sistema Integral de Gestión Académica');
            $pdf->SetTitle($titulo);
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(TRUE, 15);
            $pdf->AddPage();
            
            // Título
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->SetTextColor(41, 128, 185);
            $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
            
            // Fecha
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0, 5, 'Generado: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
            $pdf->Ln(5);
            
            // Tabla
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(41, 128, 185);
            $pdf->SetTextColor(255, 255, 255);
            
            $ancho_total = 180;
            $ancho_columna = $ancho_total / count($columnas);
            
            foreach ($columnas as $columna) {
                $pdf->Cell($ancho_columna, 8, substr($columna, 0, 15), 1, 0, 'C', true);
            }
            $pdf->Ln();
            
            // Datos
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(240, 240, 240);
            $relleno = false;
            
            foreach ($datos as $fila) {
                foreach ($columnas as $columna) {
                    $valor = isset($fila[$columna]) ? $fila[$columna] : '';
                    $pdf->Cell($ancho_columna, 7, substr($valor, 0, 20), 1, 0, 'L', $relleno);
                }
                $pdf->Ln();
                $relleno = !$relleno;
            }
            
            $ruta = __DIR__ . "/../reportes/" . $nombreArchivo;
            if (!is_dir(__DIR__ . "/../reportes")) {
                mkdir(__DIR__ . "/../reportes", 0755, true);
            }
            
            $pdf->Output($ruta, 'F');
            return str_replace(__DIR__ . "/../", "", $ruta);
        } catch (Exception $e) {
            throw new Exception("Error generando PDF: " . $e->getMessage());
        }
    }
    
    /**
     * Generar reporte en Excel nativo
     */
    public function generarExcel($titulo, $datos, $columnas, $nombreArchivo) {
        try {
            $contenido = "Reporte: $titulo\n";
            $contenido .= "Generado: " . date('d/m/Y H:i:s') . "\n\n";
            
            $contenido .= implode("\t", $columnas) . "\n";
            
            foreach ($datos as $fila) {
                $row = [];
                foreach ($columnas as $columna) {
                    $row[] = isset($fila[$columna]) ? $fila[$columna] : '';
                }
                $contenido .= implode("\t", $row) . "\n";
            }
            
            $ruta = __DIR__ . "/../reportes/" . $nombreArchivo;
            if (!is_dir(__DIR__ . "/../reportes")) {
                mkdir(__DIR__ . "/../reportes", 0755, true);
            }
            
            file_put_contents($ruta, $contenido);
            return str_replace(__DIR__ . "/../", "", $ruta);
        } catch (Exception $e) {
            throw new Exception("Error generando Excel: " . $e->getMessage());
        }
    }
}
?>