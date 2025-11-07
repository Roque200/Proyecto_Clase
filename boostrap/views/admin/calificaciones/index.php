<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-clipboard-check"></i> Gestión de Calificaciones</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Calificaciones</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="admin.php?seccion=calificaciones&action=create" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva Calificación
                </a>
                <button class="btn btn-primary" disabled>
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Lista de Calificaciones</h5>
        </div>
        <div class="card-body">
            <?php if (count($data) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Matrícula</th>
                            <th>Estudiante</th>
                            <th>Materia</th>
                            <th>Código</th>
                            <th>Período</th>
                            <th>Calificación</th>
                            <th>Estatus</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $calificacion): 
                            $badge_class = '';
                            if ($calificacion['estatus'] == 'Aprobado') $badge_class = 'bg-success';
                            elseif ($calificacion['estatus'] == 'Reprobado') $badge_class = 'bg-danger';
                            elseif ($calificacion['estatus'] == 'Cursando') $badge_class = 'bg-info';
                            else $badge_class = 'bg-secondary';
                        ?>
                        <tr>
                            <td><strong><?php echo $calificacion['matricula']; ?></strong></td>
                            <td><?php echo $calificacion['estudiante']; ?></td>
                            <td><?php echo $calificacion['materia']; ?></td>
                            <td><?php echo $calificacion['codigo']; ?></td>
                            <td><?php echo $calificacion['periodo']; ?></td>
                            <td>
                                <span class="badge bg-primary fs-6">
                                    <?php echo $calificacion['calificacion'] ? $calificacion['calificacion'] : 'N/A'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $calificacion['estatus']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="admin.php?seccion=calificaciones&action=update&id=<?php echo $calificacion['id_calificacion_PK']; ?>" 
                                       class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="admin.php?seccion=calificaciones&action=delete&id=<?php echo $calificacion['id_calificacion_PK']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar esta calificación?');">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay calificaciones registradas.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>