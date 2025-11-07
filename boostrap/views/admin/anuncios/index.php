<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-megaphone"></i> Gestión de Anuncios</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Anuncios</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="admin.php?seccion=anuncios&action=create" class="btn btn-success">
                    <i class="bi bi-megaphone-fill"></i> Nuevo Anuncio
                </a>
                <button class="btn btn-primary" disabled>
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Lista de Anuncios</h5>
        </div>
        <div class="card-body">
            <?php if (count($data) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">Título</th>
                            <th style="width: 35%;">Contenido</th>
                            <th style="width: 10%;">Prioridad</th>
                            <th style="width: 10%;">Fecha</th>
                            <th style="width: 10%;">Expira</th>
                            <th style="width: 10%;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $anuncio): 
                            $prioridad_badge = '';
                            if ($anuncio['prioridad'] == 'Urgente') $prioridad_badge = 'bg-danger';
                            elseif ($anuncio['prioridad'] == 'Importante') $prioridad_badge = 'bg-warning text-dark';
                            else $prioridad_badge = 'bg-info';
                        ?>
                        <tr>
                            <td><strong><?php echo $anuncio['id_anuncio_PK']; ?></strong></td>
                            <td><?php echo $anuncio['titulo']; ?></td>
                            <td><?php echo substr($anuncio['contenido'], 0, 100); ?>...</td>
                            <td>
                                <span class="badge <?php echo $prioridad_badge; ?>">
                                    <?php echo $anuncio['prioridad']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($anuncio['fecha_publicacion'])); ?></td>
                            <td>
                                <?php 
                                if ($anuncio['fecha_expiracion']) {
                                    echo date('d/m/Y', strtotime($anuncio['fecha_expiracion']));
                                } else {
                                    echo '<span class="text-muted">Sin fecha</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="admin.php?seccion=anuncios&action=update&id=<?php echo $anuncio['id_anuncio_PK']; ?>" 
                                       class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="admin.php?seccion=anuncios&action=delete&id=<?php echo $anuncio['id_anuncio_PK']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar este anuncio?');">
                                        <i class="bi bi-trash"></i>
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
                <i class="bi bi-info-circle"></i> No hay anuncios registrados.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Vista de Cards -->
    <div class="row mt-4">
        <div class="col-12">
            <h4>Vista Previa de Anuncios</h4>
        </div>
        <?php if (count($data) > 0): ?>
            <?php 
            $count = 0;
            foreach ($data as $anuncio): 
                if ($count >= 6) break;
                $prioridad_class = '';
                $prioridad_badge = '';
                if ($anuncio['prioridad'] == 'Urgente') {
                    $prioridad_class = 'border-danger';
                    $prioridad_badge = 'bg-danger';
                } elseif ($anuncio['prioridad'] == 'Importante') {
                    $prioridad_class = 'border-warning';
                    $prioridad_badge = 'bg-warning text-dark';
                } else {
                    $prioridad_class = 'border-info';
                    $prioridad_badge = 'bg-info';
                }
            ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 <?php echo $prioridad_class; ?>" style="border-left: 5px solid;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?php echo $anuncio['titulo']; ?></h6>
                            <span class="badge <?php echo $prioridad_badge; ?>">
                                <?php echo $anuncio['prioridad']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text small"><?php echo substr($anuncio['contenido'], 0, 150); ?>...</p>
                        <p class="card-text small text-muted mb-0">
                            <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($anuncio['fecha_publicacion'])); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php 
                $count++;
            endforeach; 
            ?>
        <?php endif; ?>
    </div>
</div>