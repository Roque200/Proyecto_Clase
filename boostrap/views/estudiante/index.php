<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-primary" role="alert">
                <h4 class="alert-heading">
                    <i class="bi bi-person-badge"></i> Bienvenido, <?php echo $_SESSION['nombre']; ?>
                </h4>
                <p class="mb-0">
                    <strong>Matrícula:</strong> <?php echo $_SESSION['matricula']; ?> | 
                    <br>
                    <strong>Carrera:</strong> <?php echo $estudiante['carrera']; ?> | 
                    <br>
                    <strong>Semestre:</strong> <?php echo $estudiante['semestre']; ?>°
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Card Calificaciones -->
        <div class="col-md-4">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Calificaciones</h5>
                    <p class="card-text">Consulta tus calificaciones por materia y período</p>
                    <a href="estudiante.php?seccion=calificaciones" class="btn btn-primary">
                        Ver Calificaciones
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Horarios -->
        <div class="col-md-4">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar3 text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Horarios</h5>
                    <p class="card-text">Revisa tu horario de clases semanal</p>
                    <a href="estudiante.php?seccion=horarios" class="btn btn-success">
                        Ver Horarios
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Anuncios -->
        <div class="col-md-4">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <i class="bi bi-megaphone text-warning" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Anuncios</h5>
                    <p class="card-text">Lee los comunicados institucionales</p>
                    <a href="estudiante.php?seccion=anuncios" class="btn btn-warning">
                        Ver Anuncios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Calificaciones -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Últimas Calificaciones</h5>
                </div>
                <div class="card-body">
                    <?php if (count($calificaciones) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Materia</th>
                                        <th>Código</th>
                                        <th>Período</th>
                                        <th>Calificación</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $count = 0;
                                    foreach ($calificaciones as $calif): 
                                        if ($count >= 5) break;
                                        $badge_class = '';
                                        if ($calif['estatus'] == 'Aprobado') $badge_class = 'bg-success';
                                        elseif ($calif['estatus'] == 'Reprobado') $badge_class = 'bg-danger';
                                        elseif ($calif['estatus'] == 'Cursando') $badge_class = 'bg-info';
                                        else $badge_class = 'bg-secondary';
                                    ?>
                                    <tr>
                                        <td><?php echo $calif['materia']; ?></td>
                                        <td><?php echo $calif['codigo']; ?></td>
                                        <td><?php echo $calif['periodo']; ?></td>
                                        <td><strong><?php echo $calif['calificacion']; ?></strong></td>
                                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo $calif['estatus']; ?></span></td>
                                    </tr>
                                    <?php 
                                        $count++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No tienes calificaciones registradas aún.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Anuncios Recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-megaphone"></i> Anuncios Recientes</h5>
                </div>
                <div class="card-body">
                    <?php if (count($anuncios) > 0): ?>
                        <?php 
                        $count = 0;
                        foreach ($anuncios as $anuncio): 
                            if ($count >= 3) break;
                            $prioridad_class = '';
                            if ($anuncio['prioridad'] == 'Urgente') $prioridad_class = 'border-danger';
                            elseif ($anuncio['prioridad'] == 'Importante') $prioridad_class = 'border-warning';
                            else $prioridad_class = 'border-info';
                        ?>
                        <div class="card mb-3 <?php echo $prioridad_class; ?>" style="border-left: 4px solid;">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $anuncio['titulo']; ?></h6>
                                <p class="card-text small text-muted mb-2">
                                    <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($anuncio['fecha_publicacion'])); ?>
                                </p>
                                <p class="card-text"><?php echo substr($anuncio['contenido'], 0, 150); ?>...</p>
                            </div>
                        </div>
                        <?php 
                            $count++;
                        endforeach; 
                        ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No hay anuncios disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>