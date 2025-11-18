<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de PDFs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .container-test {
            max-width: 600px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .btn-link-pdf {
            display: block;
            margin: 15px 0;
            padding: 15px;
            text-decoration: none;
            border: 2px solid #007bff;
            border-radius: 5px;
            color: #007bff;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-link-pdf:hover {
            background: #007bff;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container-test">
        <h1 class="text-center mb-4">
            <i class="fa fa-file-pdf-o"></i> Generador de Reportes PDF
        </h1>
        
        <div class="alert alert-info">
            <strong>Instrucciones:</strong> Haz clic en los botones para descargar los reportes en PDF.
        </div>
        
        <h3 class="mt-4">Reportes Disponibles:</h3>
        
        <!-- Reporte General -->
        <a href="panel/reportes.php?accion=instituciones" target="_blank" class="btn-link-pdf">
            <i class="fa fa-print"></i> Descargar Reporte General (Instituciones e Investigadores)
        </a>
        
        <!-- Reporte por ID (Ejemplo) -->
        <a href="panel/reportes.php?accion=investigadores&id=1" target="_blank" class="btn-link-pdf">
            <i class="fa fa-print"></i> Descargar Investigadores (Institución ID 1)
        </a>
        
        <a href="panel/reportes.php?accion=investigadores&id=2" target="_blank" class="btn-link-pdf">
            <i class="fa fa-print"></i> Descargar Investigadores (Institución ID 2)
        </a>
        
        <div class="alert alert-warning mt-4">
            <strong>Nota:</strong> Los PDFs se descargarán automáticamente. Si no funciona, verifica:
            <ul>
                <li>Que Composer esté instalado con las dependencias</li>
                <li>Que la carpeta /vendor exista</li>
                <li>Que los datos existan en la base de datos</li>
            </ul>
        </div>
        
        <div class="alert alert-success">
            <strong>URLs disponibles:</strong>
            <pre>
reportes.php?accion=instituciones
reportes.php?accion=investigadores&id=1
reportes.php?accion=investigadores&id=2
            </pre>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>