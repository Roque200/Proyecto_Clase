<div class="container mt-4">
    <h1>Instituciones</h1>
    
    <div class="btn-group mb-3" role="group" aria-label="Basic mixed styles example">
        <a class="btn btn-primary">
            <i href = "./reporte.php?accion=institucionesInvestigadores" target="_blanck" class="fas fa-print"></i> Imprimir
        </a>
        <a href="institucion.php?action=create" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Logotipo</th>
                    <th scope="col">Institución</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach($data as $institucion): ?>
                    <tr>
                        <th scope="row"><?php echo ($institucion['id_institucion']); ?></th>
                        <td>
                            <?php if (!empty($institucion['logotipo'])): ?>
                                <img src="../images/instituciones/<?php echo ($institucion['logotipo']); ?>" 
                                     width="75" height="75" 
                                     class="rounded-circle border" 
                                     alt="Logo <?php echo ($institucion['instituto']); ?>"
                                     onerror="this.src='../images/instituciones/default.png'">
                            <?php else: ?>
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 75px; height: 75px;">
                                    <i class="fas fa-university text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo ($institucion['instituto']); ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Opciones">
                                <a href="institucion.php?action=update&id=<?php echo $institucion['id_institucion']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="institucion.php?action=delete&id=<?php echo $institucion['id_institucion']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Está seguro de que desea eliminar esta institución?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle"></i> No hay instituciones registradas
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
                Total de instituciones: <?php echo count($data); ?>
            </small>
        </div>
    <?php endif; ?>
</div>