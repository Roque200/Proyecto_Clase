<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /siga-itc/auth/login.php");
    exit();
}

// Verificar timeout de sesión (1 hora)
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_inactivo > 3600) {
        session_destroy();
        header("Location: /siga-itc/auth/login.php?timeout=1");
        exit();
    }
}
$_SESSION['ultimo_acceso'] = time();

// Determinar color de navbar según tipo de usuario
$navbar_color = 'bg-primary';
if ($_SESSION['tipo_usuario'] === 'administrador') {
    $navbar_color = 'bg-danger';
} elseif ($_SESSION['tipo_usuario'] === 'docente') {
    $navbar_color = 'bg-primary';
} elseif ($_SESSION['tipo_usuario'] === 'estudiante') {
    $navbar_color = 'bg-success';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>SIGA-ITC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .content-wrapper {
            min-height: calc(100vh - 120px);
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark <?php echo $navbar_color; ?>">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php 
                if ($_SESSION['tipo_usuario'] === 'administrador') echo '/siga-itc/admin.php';
                elseif ($_SESSION['tipo_usuario'] === 'docente') echo '/siga-itc/docente.php';
                else echo '/siga-itc/estudiante.php';
            ?>">
                <i class="bi bi-building"></i> SIGA-ITC
                <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
                    <span class="badge bg-light text-danger">ADMIN</span>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/admin.php">
                                <i class="bi bi-house-door"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/admin.php?seccion=reportes">
                                <i class="bi bi-file-earmark-pdf"></i> Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/admin.php?seccion=estudiantes">
                                <i class="bi bi-people"></i> Estudiantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/admin.php?seccion=calificaciones">
                                <i class="bi bi-clipboard-check"></i> Calificaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/admin.php?seccion=anuncios">
                                <i class="bi bi-megaphone"></i> Anuncios
                            </a>
                        </li>
                    <?php elseif ($_SESSION['tipo_usuario'] === 'docente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/docente.php">
                                <i class="bi bi-house-door"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/docente.php?seccion=estudiantes">
                                <i class="bi bi-people"></i> Estudiantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/docente.php?seccion=calificaciones">
                                <i class="bi bi-clipboard-check"></i> Calificaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/docente.php?seccion=anuncios">
                                <i class="bi bi-megaphone"></i> Anuncios
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/estudiante.php">
                                <i class="bi bi-house-door"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/estudiante.php?seccion=calificaciones">
                                <i class="bi bi-clipboard-check"></i> Mis Calificaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/estudiante.php?seccion=horarios">
                                <i class="bi bi-calendar3"></i> Mis Horarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/siga-itc/estudiante.php?seccion=anuncios">
                                <i class="bi bi-megaphone"></i> Anuncios
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> 
                            <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?>
                            <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
                                <span class="badge bg-light text-danger">Admin</span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php 
                                    if ($_SESSION['tipo_usuario'] === 'administrador') {
                                        echo '/siga-itc/admin.php?seccion=perfil';
                                    } elseif ($_SESSION['tipo_usuario'] === 'docente') {
                                        echo '/siga-itc/docente.php?seccion=perfil';
                                    } else {
                                        echo '/siga-itc/views/estudiante/mi_perfil.php';
                                    }
                                ?>">
                                    <i class="bi bi-person"></i> Mi Perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/siga-itc/auth/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid content-wrapper">