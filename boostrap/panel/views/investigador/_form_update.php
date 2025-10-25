<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">
                        <i class="fas fa-edit"></i> Modificar Investigador
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="investigador.php?action=update&id=<?php echo isset($_GET['id']) ? ($_GET['id']) : ''; ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo ($data['nombre'] ?? ''); ?>" 
                                       placeholder="Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" 
                                       value="<?php echo ($data['primer_apellido'] ?? ''); ?>"
                                       placeholder="Pérez" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                   value="<?php echo ($data['segundo_apellido'] ?? ''); ?>"
                                   placeholder="García">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_tratamiento" class="form-label">
                                    <i class="fas fa-user-md"></i> Tratamiento *
                                </label>
                                <select class="form-select" id="id_tratamiento" name="id_tratamiento" required>
                                    <option value="">Seleccione...</option>
                                    <?php if(!empty($tratamientos)): ?>
                                        <?php foreach($tratamientos as $tratamiento): ?>
                                            <option value="<?php echo ($tratamiento['id_tratamiento']); ?>"
                                                    <?php echo ($data['id_tratamiento'] == $tratamiento['id_tratamiento']) ? 'selected' : ''; ?>>
                                                <?php echo ($tratamiento['tratamiento']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="id_institucion" class="form-label">
                                    <i class="fas fa-university"></i> Institución *
                                </label>
                                <select class="form-select" id="id_institucion" name="id_institucion" required>
                                    <option value="">Seleccione...</option>
                                    <?php if(!empty($instituciones)): ?>
                                        <?php foreach($instituciones as $institucion): ?>
                                            <option value="<?php echo ($institucion['id_institucion']); ?>"
                                                    <?php echo ($data['id_institucion'] == $institucion['id_institucion']) ? 'selected' : ''; ?>>
                                                <?php echo ($institucion['instituto']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fotografia" class="form-label">
                                <i class="fas fa-camera"></i> Fotografía
                            </label>
                            <input type="file" class="form-control" id="fotografia" name="fotografia" 
                                   accept="image/*"
                                   onchange="previewNewImage(event)">
                            <div class="form-text">
                                Seleccione una nueva imagen para cambiar la actual (PNG, JPG, GIF o WebP - máximo 5MB)
                            </div>
                        </div>

                        <!-- Imagen actual -->
                        <div class="mb-3">
                            <h6><i class="fas fa-check-circle text-success"></i> Imagen Actual:</h6>
                            <?php if (!empty($data['fotografia'])): ?>
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <img src="../images/investigadores/<?php echo ($data['fotografia']); ?>" 
                                             alt="Fotografía actual" 
                                             class="img-thumbnail" 
                                             style="max-width: 250px; max-height: 250px;"
                                             onerror="this.src='../images/investigadores/default.png'">
                                        <p class="mt-2 mb-0 small text-muted">
                                            <strong>Archivo:</strong> <?php echo ($data['fotografia']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <div class="bg-secondary rounded p-5" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                            <div>
                                                <i class="fas fa-user" style="font-size: 3rem; color: #999;"></i>
                                                <p class="text-muted mt-2">Sin fotografía</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Vista previa de la nueva imagen -->
                        <div class="mb-3" id="preview-container" style="display: none;">
                            <h6><i class="fas fa-eye"></i> Vista Previa de Nueva Imagen:</h6>
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <img id="preview-image" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 250px; max-height: 250px;">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="semblance" class="form-label">
                                <i class="fas fa-file-alt"></i> Semblanza
                            </label>
                            <textarea class="form-control" id="semblance" name="semblance" 
                                      rows="5" placeholder="Breve biografía del investigador..."><?php echo ($data['semblance'] ?? ''); ?></textarea>
                            <div class="form-text">
                                Información académica, líneas de investigación, logros, etc.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="investigador.php" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-warning">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewNewImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>