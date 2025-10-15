<div class="container mt-4">
    <h1>investigado$investigadores</h1>
    
    <div class="btn-group mb-3" role="group" aria-label="Basic mixed styles example">
        <a class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
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
                    <th scope="col">Fotografía</th> 
                    <th scope="col">Primer Apellido</th>
                    <th scope="col">Segundo Apellido</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Institución</th>
                    <th scope="col">Semblanza</th>
                    <th scope="col">Tratamiento</th>
                    <th scope="col">Acciones</th>
                 </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach($data as $investigador): ?>
                    <tr>
                        <th scope="row"><?php echo ($investigador['id_investigador']); ?></th>
                        <td>
                            <?php if (!empty($investigador['fotografia'])): ?>
                                <img src="../images/investigador/<?php echo ($investigador['fotografia']); ?>" 
                                     width="75" height="75" 
                                     class="rounded-circle border" 
                                     alt="Foto <?php echo ($investigador['nombre']); ?>"
                                     onerror="this.src='../images/investigador/default.png'">
                            <?php else: ?>
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 75px; height: 75px;">
                                    <i class="fas fa-university text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo ($investigador['investigador']); ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Opciones">
                                <a href="investigador.php?action=update&id=<?php echo $investigador['id_investigador']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="investigador.php?action=delete&id=<?php echo $investigador['id_investigador']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Está seguro de que desea eliminar este investigador?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle"></i> No hay investigadores registrados
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
                Total de investigadores: <?php echo count($data); ?>
            </small>
        </div>
    <?php endif; ?>
</div>