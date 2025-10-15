<div class="container mt-4">
    <h1 class="mb-4">
        <i class="fas fa-users"></i> Investigadores
    </h1>
    
    <div class="btn-group mb-3" role="group">
        <a class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
        </a>
        <a href="investigador.php?action=create" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fotografía</th>
                    <th scope="col">Nombre Completo</th>
                    <th scope="col">Institución</th>
                    <th scope="col">Tratamiento</th>
                    <th scope="col">Semblanza</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach($data as $investigador): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($investigador['id_investigador']); ?></th>
                        <td>
                            <?php if (!empty($investigador['fotografia'])): ?>
                                <img src="../images/investigadores/<?php echo htmlspecialchars($investigador['fotografia']); ?>" 
                                     width="60" height="60" 
                                     class="rounded-circle border" 
                                     alt="Foto <?php echo htmlspecialchars($investigador['nombre']); ?>"
                                     onerror="this.src='../images/investigadores/default.png'">
                            <?php else: ?>
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong>
                            <?php 
                            echo htmlspecialchars($investigador['primer_apellido']) . ' ' . 
                                 htmlspecialchars($investigador['segundo_apellido']) . ' ' . 
                                 htmlspecialchars($investigador['nombre']); 
                            ?>
                            </strong>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                <?php echo htmlspecialchars($investigador['nombre_institucion'] ?? 'Sin institución'); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary">
                                <?php echo htmlspecialchars($investigador['nombre_tratamiento'] ?? 'Sin tratamiento'); ?>
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php 
                                $semblanza = $investigador['semblanza'] ?? '';
                                echo htmlspecialchars(substr($semblanza, 0, 80)) . (strlen($semblanza) > 80 ? '...' : ''); 
                                ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="investigador.php?action=update&id=<?php echo $investigador['id_investigador']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="investigador.php?action=delete&id=<?php echo $investigador['id_investigador']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Está seguro de eliminar este investigador?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
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