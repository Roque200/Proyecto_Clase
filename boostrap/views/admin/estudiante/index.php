<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-people"></i> Gestión de Estudiantes</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Estudiantes</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="admin.php?seccion=estudiantes&action=create" class="btn btn-success">
                    <i class="bi bi-person-plus"></i> Nuevo Estudiante
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Lista de Estudiantes Registrados</h5>
        </div>
        <div class="card-body">
            <?php if (is_array($data) && count($data) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10%;">Matrícula</th>
                            <th style="width: 20%;">Nombre Completo</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 20%;">Carrera</th>
                            <th style="width: 10%;">Semestre</th>
                            <th style="width: 10%;">Fecha Ingreso</th>
                            <th style="width: 10%;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $estudiante): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($estudiante['matricula']); ?></strong></td>
                            <td><?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['carrera']); ?></td>
                            <td>
                                <span class="badge bg-info fs-6"><?php echo $estudiante['semestre']; ?>°</span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($estudiante['fecha_ingreso'])); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="admin.php?seccion=estudiantes&action=update&id=<?php echo $estudiante['id_estudiante_PK']; ?>" 
                                       class="btn btn-warning" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="admin.php?seccion=estudiantes&action=delete&id=<?php echo $estudiante['id_estudiante_PK']; ?>" 
                                       class="btn btn-danger"
                                       title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar este estudiante?\n\nEstudiante: <?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellidos']); ?>\nMatrícula: <?php echo htmlspecialchars($estudiante['matricula']); ?>');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle"></i> 
                    Total de estudiantes: <strong><?php echo count($data); ?></strong>
                </p>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay estudiantes registrados en el sistema.
                <hr>
                <a href="admin.php?seccion=estudiantes&action=create" class="btn btn-success btn-sm">
                    <i class="bi bi-person-plus"></i> Registrar Primer Estudiante
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <?php if (is_array($data) && count($data) > 0): ?>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">
                        <?php echo count($data); ?>
                    </h3>
                    <p class="mb-0">Total Estudiantes</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">
                        <?php 
                        $activos = 0;
                        foreach ($data as $e) {
                            if ($e['activo'] == 1) $activos++;
                        }
                        echo $activos;
                        ?>
                    </h3>
                    <p class="mb-0">Estudiantes Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">
                        <?php 
                        $carreras = array_unique(array_column($data, 'carrera'));
                        echo count($carreras);
                        ?>
                    </h3>
                    <p class="mb-0">Carreras Diferentes</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>