<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">
                    <i class="bi bi-person-badge"></i> Bienvenido, Profesor <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?>
                </h4>
                <p class="mb-0">
                    <strong>Número de Empleado:</strong> <?php echo $_SESSION['numero_empleado']; ?> | 
                    <strong>Carrera:</strong> <?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Card Estudiantes de mi Carrera -->
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Mis Estudiantes</h5>
                    <h2 class="text-primary"><?php echo $total_estudiantes; ?></h2>
                    <p class="small text-muted mb-2"><?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></p>
                    <a href="docente.php?seccion=estudiantes" class="btn btn-primary btn-sm">
                        Ver Estudiantes
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Calificaciones -->
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Calificaciones</h5>
                    <h2 class="text-success"><?php echo $total_calificaciones; ?></h2>
                    <a href="docente.php?seccion=calificaciones" class="btn btn-success btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Anuncios -->
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <i class="bi bi-megaphone text-warning" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Anuncios</h5>
                    <h2 class="text-warning"><?php echo count($anuncios_recientes); ?></h2>
                    <a href="docente.php?seccion=anuncios" class="btn btn-warning btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Reportes -->
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-bar-graph text-info" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Reportes</h5>
                    <p class="text-muted">Próximamente</p>
                    <button class="btn btn-info btn-sm" disabled>
                        Generar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="docente.php?seccion=estudiantes" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Ver Mis Estudiantes
                        </a>
                        <a href="docente.php?seccion=calificaciones&action=create" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle"></i> Nueva Calificación
                        </a>
                        <a href="docente.php?seccion=anuncios&action=create" class="btn btn-outline-warning">
                            <i class="bi bi-megaphone-fill"></i> Nuevo Anuncio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Carrera -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-bookmark"></i> Mi Carrera</h6>
                </div>
                <div class="card-body">
                    <h5><?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></h5>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="bi bi-people-fill text-primary"></i>
                            <strong><?php echo $total_estudiantes; ?></strong> estudiantes
                        </div>
                        <div>
                            <i class="bi bi-clipboard-check text-success"></i>
                            <strong><?php echo $total_calificaciones; ?></strong> calificaciones
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos Anuncios -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="bi bi-megaphone"></i> Mis Últimos Anuncios</h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <?php if (count($anuncios_recientes) > 0): ?>
                        <?php 
                        $count = 0;
                        foreach ($anuncios_recientes as $anuncio): 
                            // Solo mostrar anuncios del docente actual
                            if ($anuncio['id_usuario_FK'] != $_SESSION['usuario_id']) continue;
                            if ($count >= 5) break;
                            
                            $prioridad_badge = '';
                            if ($anuncio['prioridad'] == 'Urgente') $prioridad_badge = 'bg-danger';
                            elseif ($anuncio['prioridad'] == 'Importante') $prioridad_badge = 'bg-warning';
                            else $prioridad_badge = 'bg-info';
                        ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($anuncio['titulo']); ?></h6>
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($anuncio['fecha_publicacion'])); ?>
                                </p>
                            </div>
                            <span class="badge <?php echo $prioridad_badge; ?>">
                                <?php echo $anuncio['prioridad']; ?>
                            </span>
                        </div>
                        <?php 
                            $count++;
                        endforeach; 
                        
                        if ($count == 0):
                        ?>
                        <p class="text-muted mb-0">No has publicado anuncios aún.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay anuncios publicados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>