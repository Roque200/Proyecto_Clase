<?php
// ============================================================================
// ARCHIVO: src/siga-itc/api/debug_completo.php
// Diagnóstico completo del sistema
// ============================================================================

session_start();

echo "<h1>🔍 DEBUG COMPLETO</h1>";

// 1. Verificar PHP
echo "<h2>1. Información PHP</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Extensión PDO: " . (extension_loaded('pdo') ? "✅" : "❌") . "<br>";
echo "Extensión MySQL: " . (extension_loaded('pdo_mysql') ? "✅" : "❌") . "<br>";

// 2. Sesión
echo "<h2>2. Sesión</h2>";
echo "Usuario ID: " . ($_SESSION['usuario_id'] ?? "❌ NO DEFINIDO") . "<br>";
echo "Tipo Usuario: " . ($_SESSION['tipo_usuario'] ?? "❌ NO DEFINIDO") . "<br>";

// 3. Archivos
echo "<h2>3. Archivos Existe</h2>";
echo "vendor/autoload.php: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? "✅" : "❌") . "<br>";
echo "api/generar_reporte.php: " . (file_exists(__DIR__ . '/generar_reporte.php') ? "✅" : "❌") . "<br>";
echo "api/generar_pdf.php: " . (file_exists(__DIR__ . '/generar_pdf.php') ? "✅" : "❌") . "<br>";

// 4. Intentar cargar autoload
echo "<h2>4. Cargar Autoload</h2>";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoload cargado<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// 5. Verificar TCPDF
echo "<h2>5. Verificar TCPDF</h2>";
if (class_exists('TCPDF')) {
    echo "✅ Clase TCPDF existe<br>";
} else {
    echo "❌ Clase TCPDF NO existe<br>";
}

// 6. Conectar BD
echo "<h2>6. Conectar BD</h2>";
try {
    $pdo = new PDO(
        "mysql:host=mariadb;dbname=siga_itc;charset=utf8mb4",
        "user",
        "password"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa a BD<br>";
    
    // Contar registros
    echo "<h2>7. Datos en BD</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuario");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Usuarios: " . $row['total'] . "<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM estudiante");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Estudiantes: " . $row['total'] . "<br>";
    
    // Probar generar PDF
    echo "<h2>8. Test Generar PDF</h2>";
    $stmt = $pdo->query("SELECT id_usuario_PK, nombre, apellidos, email FROM usuario LIMIT 2");
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (class_exists('TCPDF')) {
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'TEST PDF', 0, 1, 'C');
        
        $carpeta = __DIR__ . '/../reportes';
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        
        $archivo = $carpeta . '/test_' . date('Y-m-d_H-i-s') . '.pdf';
        $pdf->Output($archivo, 'F');
        
        echo "✅ PDF de prueba creado: " . $archivo . "<br>";
    } else {
        echo "❌ TCPDF no disponible<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error BD: " . $e->getMessage() . "<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// 9. Test API
echo "<h2>9. Test API generar_reporte.php</h2>";
if (file_exists(__DIR__ . '/generar_reporte.php')) {
    echo "Archivo existe<br>";
    
    // Simulan petición POST
    $_POST['tipo'] = 'pdf_usuarios';
    ob_start();
    include __DIR__ . '/generar_reporte.php';
    $output = ob_get_clean();
    
    echo "Respuesta:<br>";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
} else {
    echo "❌ Archivo generar_reporte.php no existe<br>";
}

?>