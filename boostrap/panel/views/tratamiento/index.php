<div class="container mt-4">
    <h1 class="mb-4">
        <i class="fas fa-user-md"></i> Tratamientos
    </h1>
    
    <div class="btn-group mb-3" role="group">
        <a class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
        </a>
        <a href="tratamiento.php?action=create" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tratamiento</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach($data as $tratamiento): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($tratamiento['id_tratamiento']); ?></th>
                        <td><?php echo htmlspecialchars($tratamiento['tratamiento']); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="tratamiento.php?action=update&id=<?php echo $tratamiento['id_tratamiento']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="tratamiento.php?action=delete&id=<?php echo $tratamiento['id_tratamiento']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Está seguro de eliminar este tratamiento?\n\nNOTA: No se puede eliminar si hay investigadores asignados.')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle"></i> No hay tratamientos registrados
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($data)): ?>
        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Total de tratamientos: <?php echo count($data); ?>
            </small>
        </div>
    <?php endif; ?>
</div>