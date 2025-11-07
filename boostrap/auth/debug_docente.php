<?php
session_start();

// Ajustar las rutas correctamente
require_once(__DIR__ . "/../models/usuario.php");
require_once(__DIR__ . "/../models/estudiante.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEBUG - Sesión Docente</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h3 {
            color: #e74c3c;
            margin-top: 20px;
        }
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .key {
            color: #e74c3c;
            font-weight: bold;
        }
        .value {
            color: #27ae60;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background: #3498db;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tr:hover {
            background: #f5f5f5;
        }
    </style>
</head>
<body>

<div class="section">
    <h2>🔍 DEBUG - Información de Sesión del Docente</h2>
    
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="alert alert-success">
            ✅ Sesión activa detectada
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            ❌ No hay sesión activa. Por favor inicia sesión como docente primero.
        </div>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['usuario_id'])): ?>

<div class="section">
    <h3>📋 Variables de Sesión Actuales</h3>
    <table>
        <thead>
            <tr>
                <th>Variable</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION as $key => $value): ?>
            <tr>
                <td class="key"><?php echo htmlspecialchars($key); ?></td>
                <td class="value"><?php echo htmlspecialchars(is_array($value) ? json_encode($value) : $value); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="section">
    <h3>🎓 Información Completa del Usuario desde Base de Datos</h3>
    <?php
    try {
        $appUsuario = new Usuario();
        $usuario = $appUsuario->readOne($_SESSION['usuario_id']);
        
        if ($usuario): ?>
            <table>
                <tr>
                    <td class="key">ID Usuario:</td>
                    <td class="value"><?php echo $usuario['id_usuario_PK']; ?></td>
                </tr>
                <tr>
                    <td class="key">Nombre Completo:</td>
                    <td class="value"><?php echo $usuario['nombre'] . ' ' . $usuario['apellidos']; ?></td>
                </tr>
                <tr>
                    <td class="key">Email:</td>
                    <td class="value"><?php echo $usuario['email']; ?></td>
                </tr>
                <tr>
                    <td class="key">Tipo Usuario:</td>
                    <td class="value"><?php echo $usuario['tipo_usuario']; ?></td>
                </tr>
            </table>
            
            <h4>🔎 Datos completos (JSON):</h4>
            <pre><?php print_r($usuario); ?></pre>
        <?php else: ?>
            <div class="alert alert-danger">
                ❌ No se pudo obtener información del usuario desde la base de datos
            </div>
        <?php endif;
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">❌ Error: ' . $e->getMessage() . '</div>';
    }
    ?>
</div>

<?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'docente'): ?>

<div class="section">
    <h3>👨‍🏫 Información Específica del Docente</h3>
    
    <?php if (isset($_SESSION['docente_carrera'])): ?>
        <div class="alert alert-info">
            📚 <strong>Carrera asignada:</strong> <?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>
        </div>
        
        <h4>👥 Estudiantes de la Carrera "<?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>"</h4>
        
        <?php
        try {
            $appEstudiante = new Estudiante();
            $estudiantes = $appEstudiante->readByCarrera($_SESSION['docente_carrera']);
            
            if ($estudiantes && count($estudiantes) > 0): ?>
                <div class="alert alert-success">
                    ✅ Se encontraron <strong><?php echo count($estudiantes); ?></strong> estudiante(s)
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Carrera</th>
                            <th>Semestre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td><?php echo $estudiante['id_estudiante_PK']; ?></td>
                            <td><?php echo $estudiante['matricula']; ?></td>
                            <td><?php echo $estudiante['nombre'] . ' ' . $estudiante['apellidos']; ?></td>
                            <td><?php echo $estudiante['carrera']; ?></td>
                            <td><?php echo $estudiante['semestre']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <h4>🔎 Datos completos del primer estudiante (JSON):</h4>
                <pre><?php print_r($estudiantes[0]); ?></pre>
                
            <?php else: ?>
                <div class="alert alert-danger">
                    ❌ No se encontraron estudiantes para la carrera: <strong><?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></strong>
                </div>
                
                <h4>🔍 Diagnóstico:</h4>
                <ul>
                    <li>Verifica que existan estudiantes con la carrera exacta: "<?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>"</li>
                    <li>Revisa que no haya espacios extras o diferencias de mayúsculas/minúsculas</li>
                    <li>Consulta directa a la base de datos:</li>
                </ul>
                <pre>SELECT * FROM estudiante WHERE carrera = '<?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>';</pre>
            <?php endif;
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">❌ Error al consultar estudiantes: ' . $e->getMessage() . '</div>';
        }
        ?>
        
    <?php else: ?>
        <div class="alert alert-danger">
            ❌ No hay carrera asignada al docente en la sesión
        </div>
    <?php endif; ?>
</div>

<div class="section">
    <h3>📊 Diagnóstico de Problemas Comunes</h3>
    
    <h4>✅ Checklist de Verificación:</h4>
    <ul>
        <li><?php echo isset($_SESSION['docente_carrera']) ? '✅' : '❌'; ?> Variable <code>docente_carrera</code> en sesión</li>
        <li><?php echo isset($_SESSION['docente_id']) ? '✅' : '❌'; ?> Variable <code>docente_id</code> en sesión</li>
        <li><?php echo isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'docente' ? '✅' : '❌'; ?> Tipo de usuario es "docente"</li>
        <li><?php echo class_exists('Estudiante') ? '✅' : '❌'; ?> Clase Estudiante cargada</li>
        <li><?php echo class_exists('Usuario') ? '✅' : '❌'; ?> Clase Usuario cargada</li>
    </ul>
    
    <?php if (isset($_SESSION['docente_carrera'])): ?>
    <h4>🔍 Consulta SQL Sugerida:</h4>
    <pre>
-- Ejecuta esto en phpMyAdmin para verificar:
SELECT * FROM estudiante WHERE carrera = '<?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>';

-- Para ver todas las carreras disponibles:
SELECT DISTINCT carrera FROM estudiante;

-- Para ver si hay coincidencias aproximadas:
SELECT carrera FROM estudiante WHERE carrera LIKE '%<?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>%';
    </pre>
    <?php endif; ?>
</div>

<?php else: ?>
<div class="section">
    <div class="alert alert-danger">
        ❌ El usuario actual NO es un docente. Tipo: <?php echo isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 'No definido'; ?>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>

<div class="section">
    <h3>🔗 Acciones</h3>
    <p>
        <a href="../views/login.php" style="color: #3498db; text-decoration: none;">← Volver al Login</a> | 
        <a href="logout.php" style="color: #e74c3c; text-decoration: none;">Cerrar Sesión</a>
    </p>
</div>

</body>
</html>