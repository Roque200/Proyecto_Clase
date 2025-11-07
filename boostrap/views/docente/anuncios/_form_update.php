<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-pencil"></i> Modificar Anuncio</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="docente.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="docente.php?seccion=anuncios" class="text-decoration-none">Anuncios</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Formulario de Edición</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="docente.php?seccion=anuncios&action=update&id=<?php echo $data['id_anuncio_PK']; ?>">
                        
                        <div class="alert alert-secondary">
                            <strong>Publicado por:</strong> <?php echo $data['autor']; ?><br>
                            <strong>Fecha de publicación:</strong> <?php echo date('d/m/Y H:i', strtotime($data['fecha_publicacion'])); ?>
                        </div>

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título del Anuncio *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   value="<?php echo $data['titulo']; ?>"
                                   maxlength="200" required>
                            <div class="form-text">Máximo 200 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="contenido" class="form-label">Contenido *</label>
                            <textarea class="form-control" id="contenido" name="contenido" 
                                      rows="8" required><?php echo $data['contenido']; ?></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prioridad" class="form-label">Prioridad *</label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="Normal" <?php echo ($data['prioridad'] == 'Normal') ? 'selected' : ''; ?>>Normal</option>
                                    <option value="Importante" <?php echo ($data['prioridad'] == 'Importante') ? 'selected' : ''; ?>>Importante</option>
                                    <option value="Urgente" <?php echo ($data['prioridad'] == 'Urgente') ? 'selected' : ''; ?>>Urgente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                                <input type="date" class="form-control" id="fecha_expiracion" name="fecha_expiracion"
                                       value="<?php echo $data['fecha_expiracion']; ?>">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="docente.php?seccion=anuncios" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-warning">
                                <i class="bi bi-save"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>