<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Red de Investigación - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
          <i class="fas fa-flask"></i> Red de Investigación
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="fas fa-home"></i> Inicio
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-folder"></i> Catálogos
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="institucion.php"><i class="fas fa-university"></i> Instituciones</a></li>
                <li><a class="dropdown-item" href="tratamiento.php"><i class="fas fa-user-md"></i> Tratamientos</a></li>
                <li><a class="dropdown-item" href="investigador.php"><i class="fas fa-users"></i> Investigadores</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-pdf"></i> Reportes
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="reportes.php"><i class="fas fa-list"></i> Menú de Reportes</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="reportes.php?accion=institucionesInvestigadores" target="_blank">
                  <i class="fas fa-university"></i> Instituciones e Investigadores
                </a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-users-cog"></i> Usuarios
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="usuario.php"><i class="fas fa-users"></i> Gestión de Usuarios</a></li>
              </ul>
            </li>
          </ul>
          <span class="navbar-text">
            <i class="fas fa-user-shield"></i> Panel de Administración
          </span>
        </div>
      </div>
    </nav>
    <div class="container-fluid mt-3">