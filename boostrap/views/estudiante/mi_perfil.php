<?php
require_once(__DIR__ . "/../../auth/check_estudiante.php");
require_once(__DIR__ . "/../../models/estudiante.php");
require_once(__DIR__ . "/../../models/usuario.php");

$appEstudiante = new Estudiante();
$appUsuario = new Usuario();

// Obtener datos del estudiante
$estudiante = $appEstudiante->readByUsuario($_SESSION['usuario_id']);
$usuario = $appUsuario->readOne($_SESSION['usuario_id']);
$documentos = $appEstudiante->getDocumentos($estudiante['id_estudiante_PK']);

$mensaje = '';
$tipo_mensaje = '';

// Procesar actualización de datos personales
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    switch ($_POST['action']) {
        case 'actualizar_datos':
            $data = [
                'nombre' => trim($_POST['nombre']),
                'apellidos' => trim($_POST['apellidos'])
            ];
            
            if ($appEstudiante->updatePerfil($estudiante['id_estudiante_PK'], $data)) {
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['apellidos'] = $data['apellidos'];
                $mensaje = "Datos actualizados correctamente";
                $tipo_mensaje = "success";
                // Recargar datos
                $estudiante = $appEstudiante->readByUsuario($_SESSION['usuario_id']);
            } else {
                $mensaje = "Error al actualizar datos";
                $tipo_mensaje = "danger";
            }
            break;
            
        case 'cambiar_password':
            $password_actual = $_POST['password_actual'];
            $password_nueva = $_POST['password_nueva'];
            $password_confirmar = $_POST['password_confirmar'];
            
            // Validar que la contraseña actual sea correcta
            if (!$appUsuario->verificarPasswordActual($_SESSION['usuario_id'], $password_actual)) {
                $mensaje = "La contraseña actual es incorrecta";
                $tipo_mensaje = "danger";
                break;
            }
            
            // Validar que las nuevas contraseñas coincidan
            if ($password_nueva !== $password_confirmar) {
                $mensaje = "Las contraseñas nuevas no coinciden";
                $tipo_mensaje = "danger";
                break;
            }
            
            // Validar fortaleza de la contraseña
            $validacion = $appUsuario->validarFortalezaPassword($password_nueva);
            if (!$validacion['valido']) {
                $mensaje = "Contraseña débil: " . implode(", ", $validacion['errores']);
                $tipo_mensaje = "danger";
                break;
            }
            
            // Verificar que no se reutilice una contraseña anterior
            if (!$appUsuario->verificarPasswordNoReutilizada($_SESSION['usuario_id'], $password_nueva)) {
                $mensaje = "No puedes reutilizar una contraseña reciente";
                $tipo_mensaje = "danger";
                break;
            }
            
            // Cambiar contraseña
            if ($appUsuario->cambiarPassword($_SESSION['usuario_id'], $password_nueva, $usuario['password'])) {
                $mensaje = "Contraseña actualizada correctamente. Por seguridad, vuelve a iniciar sesión.";
                $tipo_mensaje = "success";
                // Opcional: cerrar sesión automáticamente
                // header("Location: ../../auth/logout.php");
                // exit();
            } else {
                $mensaje = "Error al actualizar contraseña";
                $tipo_mensaje = "danger";
            }
            break;
            
        case 'subir_foto':
            $resultado = subirArchivo($_FILES['foto_perfil'], 'foto', $estudiante['id_estudiante_PK']);
            $mensaje = $resultado['mensaje'];
            $tipo_mensaje = $resultado['tipo'];
            if ($resultado['exito']) {
                $documentos = $appEstudiante->getDocumentos($estudiante['id_estudiante_PK']);
            }
            break;
            
        case 'subir_documento':
            $tipo_doc = $_POST['tipo_documento'];
            $resultado = subirArchivo($_FILES['documento'], $tipo_doc, $estudiante['id_estudiante_PK']);
            $mensaje = $resultado['mensaje'];
            $tipo_mensaje = $resultado['tipo'];
            if ($resultado['exito']) {
                $documentos = $appEstudiante->getDocumentos($estudiante['id_estudiante_PK']);
            }
            break;
    }
}

function subirArchivo($file, $tipo, $id_estudiante) {
    global $appEstudiante;
    
    // Validar que se subió un archivo
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['exito' => false, 'mensaje' => 'Error al subir el archivo', 'tipo' => 'danger'];
    }
    
    // Configurar según tipo de archivo
    $config = [
        'foto' => [
            'dir' => __DIR__ . '/../../uploads/fotos_perfil/',
            'extensiones' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_size' => 5 * 1024 * 1024, // 5MB
            'prefijo' => 'foto_'
        ],
        'acta' => [
            'dir' => __DIR__ . '/../../uploads/documentos/actas/',
            'extensiones' => ['pdf', 'jpg', 'jpeg', 'png'],
            'max_size' => 10 * 1024 * 1024, // 10MB
            'prefijo' => 'acta_'
        ],
        'carta' => [
            'dir' => __DIR__ . '/../../uploads/documentos/cartas/',
            'extensiones' => ['pdf', 'jpg', 'jpeg', 'png'],
            'max_size' => 10 * 1024 * 1024,
            'prefijo' => 'carta_'
        ],
        'curp' => [
            'dir' => __DIR__ . '/../../uploads/documentos/curps/',
            'extensiones' => ['pdf', 'jpg', 'jpeg', 'png'],
            'max_size' => 10 * 1024 * 1024,
            'prefijo' => 'curp_'
        ]
    ];
    
    if (!isset($config[$tipo])) {
        return ['exito' => false, 'mensaje' => 'Tipo de archivo no válido', 'tipo' => 'danger'];
    }
    
    $conf = $config[$tipo];
    
    // Validar tamaño
    if ($file['size'] > $conf['max_size']) {
        $max_mb = $conf['max_size'] / (1024 * 1024);
        return ['exito' => false, 'mensaje' => "El archivo es demasiado grande. Máximo {$max_mb}MB", 'tipo' => 'danger'];
    }
    
    // Validar extensión
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $conf['extensiones'])) {
        return ['exito' => false, 'mensaje' => 'Tipo de archivo no permitido. Use: ' . implode(', ', $conf['extensiones']), 'tipo' => 'danger'];
    }
    
    // Generar nombre único
    $nombre_archivo = $conf['prefijo'] . $id_estudiante . '_' . time() . '.' . $extension;
    $ruta_completa = $conf['dir'] . $nombre_archivo;
    $ruta_relativa = str_replace(__DIR__ . '/../../', '', $ruta_completa);
    
    // Crear directorio si no existe
    if (!file_exists($conf['dir'])) {
        mkdir($conf['dir'], 0777, true);
    }
    
    // Mover archivo
    if (move_uploaded_file($file['tmp_name'], $ruta_completa)) {
        // Actualizar base de datos
        if ($tipo === 'foto') {
            $appEstudiante->updateFotoPerfil($id_estudiante, $ruta_relativa);
        } else {
            $appEstudiante->updateDocumento($id_estudiante, $tipo, $ruta_relativa);
        }
        
        return ['exito' => true, 'mensaje' => 'Archivo subido correctamente', 'tipo' => 'success'];
    } else {
        return ['exito' => false, 'mensaje' => 'Error al guardar el archivo', 'tipo' => 'danger'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - SIGA ITC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .default-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 5px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
        }
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .password-requirements li {
            margin-bottom: 0.25rem;
        }
        .documento-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        .documento-subido {
            background: #d4edda;
            color: #155724;
        }
        .documento-pendiente {
            background: #fff3cd;
            color: #856404;
        }
        .btn-upload {
            position: relative;
            overflow: hidden;
        }
        .btn-upload input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            cursor: pointer;
        }
        .strength-meter {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s;
        }
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="estudiante.php">
                <i class="bi bi-mortarboard-fill"></i> SIGA ITC - Estudiante
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../../auth/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>-fill"></i>
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header del Perfil -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <?php if ($documentos && $documentos['foto_perfil']): ?>
                        <img src="../../<?php echo htmlspecialchars($documentos['foto_perfil']); ?>" 
                             alt="Foto de perfil" class="profile-photo">
                    <?php else: ?>
                        <div class="default-photo mx-auto">
                            <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <h2><?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellidos']); ?></h2>
                    <p class="mb-1"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p class="mb-1"><i class="bi bi-card-text"></i> Matrícula: <?php echo htmlspecialchars($estudiante['matricula']); ?></p>
                    <p class="mb-1"><i class="bi bi-book"></i> <?php echo htmlspecialchars($estudiante['carrera']); ?></p>
                    <p class="mb-0"><i class="bi bi-calendar3"></i> Semestre: <?php echo htmlspecialchars($estudiante['semestre']); ?>°</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-lg-6">
                
                <!-- Foto de Perfil -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-camera-fill"></i> Foto de Perfil
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="subir_foto">
                            <div class="mb-3">
                                <label class="form-label">Seleccionar foto (JPG, PNG, GIF - Máx. 5MB)</label>
                                <input type="file" class="form-control" name="foto_perfil" accept="image/*" required>
                                <small class="form-text text-muted">
                                    La foto debe ser clara y reciente para facilitar tu identificación
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Subir Foto
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Datos Personales -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person-badge-fill"></i> Datos Personales
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="actualizar_datos">
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre(s)</label>
                                <input type="text" class="form-control" name="nombre" 
                                       value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" 
                                       value="<?php echo htmlspecialchars($estudiante['apellidos']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                                <small class="form-text text-muted">
                                    El correo no puede ser modificado. Contacta al administrador si necesitas cambiarlo.
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Matrícula</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo htmlspecialchars($estudiante['matricula']); ?>" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Carrera</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo htmlspecialchars($estudiante['carrera']); ?>" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Semestre</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo htmlspecialchars($estudiante['semestre']); ?>°" disabled>
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-lg-6">
                
                <!-- Cambiar Contraseña -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock-fill"></i> Cambiar Contraseña
                    </div>
                    <div class="card-body">
                        <form method="POST" id="formPassword">
                            <input type="hidden" name="action" value="cambiar_password">
                            
                            <div class="mb-3">
                                <label class="form-label">Contraseña Actual</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_actual" 
                                           id="password_actual" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword('password_actual')">
                                        <i class="bi bi-eye" id="icon_password_actual"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_nueva" 
                                           id="password_nueva" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword('password_nueva')">
                                        <i class="bi bi-eye" id="icon_password_nueva"></i>
                                    </button>
                                </div>
                                <div class="strength-meter" id="strength-meter"></div>
                                <div id="strength-text" class="mt-1 small"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmar" 
                                           id="password_confirmar" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword('password_confirmar')">
                                        <i class="bi bi-eye" id="icon_password_confirmar"></i>
                                    </button>
                                </div>
                                <div id="match-message" class="mt-1 small"></div>
                            </div>
                            
                            <div class="alert alert-info password-requirements">
                                <strong><i class="bi bi-info-circle"></i> Requisitos de seguridad:</strong>
                                <ul class="mb-0 mt-2">
                                    <li id="req-length">Mínimo 8 caracteres</li>
                                    <li id="req-uppercase">Al menos una letra mayúscula</li>
                                    <li id="req-lowercase">Al menos una letra minúscula</li>
                                    <li id="req-number">Al menos un número</li>
                                    <li id="req-special">Al menos un carácter especial (!@#$%^&*...)</li>
                                    <li id="req-no-reuse">No reutilizar contraseñas anteriores</li>
                                </ul>
                            </div>
                            
                            <button type="submit" class="btn btn-warning" id="btnCambiarPassword">
                                <i class="bi bi-key-fill"></i> Cambiar Contraseña
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark-text-fill"></i> Documentos Oficiales
                    </div>
                    <div class="card-body">
                        
                        <!-- Acta de Nacimiento -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">
                                    <i class="bi bi-file-earmark-person"></i> Acta de Nacimiento
                                </h6>
                                <?php if ($documentos && $documentos['acta_nacimiento']): ?>
                                    <span class="documento-status documento-subido">
                                        <i class="bi bi-check-circle"></i> Subido
                                    </span>
                                <?php else: ?>
                                    <span class="documento-status documento-pendiente">
                                        <i class="bi bi-exclamation-circle"></i> Pendiente
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($documentos && $documentos['acta_nacimiento']): ?>
                                <div class="mb-2">
                                    <a href="../../<?php echo htmlspecialchars($documentos['acta_nacimiento']); ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver Documento
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" enctype="multipart/form-data" class="mt-2">
                                <input type="hidden" name="action" value="subir_documento">
                                <input type="hidden" name="tipo_documento" value="acta">
                                <div class="input-group input-group-sm">
                                    <input type="file" class="form-control" name="documento" 
                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-upload"></i> Subir
                                    </button>
                                </div>
                                <small class="form-text text-muted">PDF, JPG o PNG - Máx. 10MB</small>
                            </form>
                        </div>

                        <hr>

                        <!-- Carta Compromiso -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">
                                    <i class="bi bi-file-earmark-check"></i> Carta Compromiso
                                </h6>
                                <?php if ($documentos && $documentos['carta_compromiso']): ?>
                                    <span class="documento-status documento-subido">
                                        <i class="bi bi-check-circle"></i> Subido
                                    </span>
                                <?php else: ?>
                                    <span class="documento-status documento-pendiente">
                                        <i class="bi bi-exclamation-circle"></i> Pendiente
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($documentos && $documentos['carta_compromiso']): ?>
                                <div class="mb-2">
                                    <a href="../../<?php echo htmlspecialchars($documentos['carta_compromiso']); ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver Documento
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" enctype="multipart/form-data" class="mt-2">
                                <input type="hidden" name="action" value="subir_documento">
                                <input type="hidden" name="tipo_documento" value="carta">
                                <div class="input-group input-group-sm">
                                    <input type="file" class="form-control" name="documento" 
                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-upload"></i> Subir
                                    </button>
                                </div>
                                <small class="form-text text-muted">PDF, JPG o PNG - Máx. 10MB</small>
                            </form>
                        </div>

                        <hr>

                        <!-- CURP -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">
                                    <i class="bi bi-file-earmark-code"></i> CURP
                                </h6>
                                <?php if ($documentos && $documentos['curp_documento']): ?>
                                    <span class="documento-status documento-subido">
                                        <i class="bi bi-check-circle"></i> Subido
                                    </span>
                                <?php else: ?>
                                    <span class="documento-status documento-pendiente">
                                        <i class="bi bi-exclamation-circle"></i> Pendiente
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($documentos && $documentos['curp_documento']): ?>
                                <div class="mb-2">
                                    <a href="../../<?php echo htmlspecialchars($documentos['curp_documento']); ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver Documento
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" enctype="multipart/form-data" class="mt-2">
                                <input type="hidden" name="action" value="subir_documento">
                                <input type="hidden" name="tipo_documento" value="curp">
                                <div class="input-group input-group-sm">
                                    <input type="file" class="form-control" name="documento" 
                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-upload"></i> Subir
                                    </button>
                                </div>
                                <small class="form-text text-muted">PDF, JPG o PNG - Máx. 10MB</small>
                            </form>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Importante:</strong> Asegúrate de que los documentos sean legibles y estén 
                            actualizados. Los documentos son necesarios para trámites académicos.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Regresar -->
        <div class="text-center mt-4 mb-4">
            <a href="estudiante.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar al Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar/ocultar contraseñas
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('icon_' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Validación de fortaleza de contraseña en tiempo real
        document.getElementById('password_nueva').addEventListener('input', function() {
            const password = this.value;
            const meter = document.getElementById('strength-meter');
            const text = document.getElementById('strength-text');
            
            // Requisitos
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Actualizar indicadores visuales de requisitos
            updateRequirement('req-length', requirements.length);
            updateRequirement('req-uppercase', requirements.uppercase);
            updateRequirement('req-lowercase', requirements.lowercase);
            updateRequirement('req-number', requirements.number);
            updateRequirement('req-special', requirements.special);
            
            // Calcular fortaleza
            const fulfilled = Object.values(requirements).filter(Boolean).length;
            
            meter.className = 'strength-meter';
            
            if (fulfilled <= 2) {
                meter.classList.add('strength-weak');
                text.innerHTML = '<span class="text-danger">Contraseña débil</span>';
            } else if (fulfilled <= 4) {
                meter.classList.add('strength-medium');
                text.innerHTML = '<span class="text-warning">Contraseña media</span>';
            } else {
                meter.classList.add('strength-strong');
                text.innerHTML = '<span class="text-success">Contraseña fuerte</span>';
            }
        });
        
        // Función para actualizar el estilo de los requisitos
        function updateRequirement(id, fulfilled) {
            const element = document.getElementById(id);
            if (fulfilled) {
                element.style.color = '#28a745';
                element.innerHTML = element.innerHTML.replace(/^/, '✓ ');
            } else {
                element.style.color = '#6c757d';
                element.innerHTML = element.innerHTML.replace('✓ ', '');
            }
        }

        // Validación de coincidencia de contraseñas
        document.getElementById('password_confirmar').addEventListener('input', function() {
            const password = document.getElementById('password_nueva').value;
            const confirm = this.value;
            const message = document.getElementById('match-message');
            
            if (confirm.length === 0) {
                message.innerHTML = '';
                return;
            }
            
            if (password === confirm) {
                message.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Las contraseñas coinciden</span>';
            } else {
                message.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Las contraseñas no coinciden</span>';
            }
        });

        // Validación del formulario antes de enviar
        document.getElementById('formPassword').addEventListener('submit', function(e) {
            const password = document.getElementById('password_nueva').value;
            const confirm = document.getElementById('password_confirmar').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            // Validar requisitos
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            const allFulfilled = Object.values(requirements).every(Boolean);
            
            if (!allFulfilled) {
                e.preventDefault();
                alert('La contraseña no cumple con todos los requisitos de seguridad');
                return false;
            }
        });

        // Confirmar antes de cambiar contraseña
        document.getElementById('btnCambiarPassword').addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas cambiar tu contraseña? Se recomienda cerrar sesión después del cambio.')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>