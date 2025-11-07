<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-megaphone"></i> Anuncios Institucionales</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="estudiante.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Anuncios</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if (is_array($data) && count($data) > 0): ?>
        <div class="row">
            <?php foreach ($data as $anuncio): 
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
            <div class="col-md-6 mb-4">
                <div class="card h-100 <?php echo $prioridad_class; ?>" style="border-left: 5px solid;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo htmlspecialchars($anuncio['titulo']); ?></h5>
                            <span class="badge <?php echo $prioridad_badge; ?>">
                                <?php echo $anuncio['prioridad']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($anuncio['contenido'])); ?></p>
                        <hr>
                        <p class="card-text small text-muted mb-1">
                            <i class="bi bi-person"></i> <strong>Publicado por:</strong> <?php echo htmlspecialchars($anuncio['autor']); ?>
                        </p>
                        <p class="card-text small text-muted mb-1">
                            <i class="bi bi-calendar"></i> <strong>Fecha:</strong> 
                            <?php echo date('d/m/Y H:i', strtotime($anuncio['fecha_publicacion'])); ?>
                        </p>
                        <?php if (!empty($anuncio['fecha_expiracion'])): ?>
                        <p class="card-text small text-muted mb-0">
                            <i class="bi bi-calendar-x"></i> <strong>Expira:</strong> 
                            <?php echo date('d/m/Y', strtotime($anuncio['fecha_expiracion'])); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay anuncios disponibles en este momento.
        </div>
    <?php endif; ?>
</div>