<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Institución
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="institucion.php?action=update&id=<?php echo htmlspecialchars($data['id_institucion']); ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="instituto" class="form-label">
                                <i class="fas fa-university"></i> Nombre de la Institución *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="instituto" 
                                   name="instituto" 
                                   value="<?php echo htmlspecialchars($data['instituto'] ?? ''); ?>" 
                                   placeholder="Ej: TecNM Campus Celaya" 
                                   required
                                   maxlength="200">
                            <div class="form-text">
                                Ingrese el nombre completo de la institución
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="logotipo" class="form-label">
                                <i class="fas fa-image"></i> Logotipo (Imagen)
                            </label>
                            <input class="form-control" 
                                   type="file" 
                                   id="logotipo" 
                                   name="logotipo" 
                                   accept="image/*"
                                   onchange="previewNewImage(event)">
                            <div class="form-text">
                                Seleccione una nueva imagen para cambiar la actual (PNG, JPG o GIF - máximo 5MB)
                            </div>
                        </div>

                        <!-- Imagen actual -->
                        <div class="mb-3">
                            <h6><i class="fas fa-check-circle text-success"></i> Imagen Actual:</h6>
                            <?php if (!empty($data['logotipo'])): ?>
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <img src="../images/instituciones/<?php echo htmlspecialchars($data['logotipo']); ?>" 
                                             alt="Logotipo actual" 
                                             class="img-thumbnail" 
                                             style="max-width: 250px; max-height: 250px;"
                                             onerror="this.src='../images/instituciones/default.png'">
                                        <p class="mt-2 mb-0 small text-muted">
                                            <strong>Archivo:</strong> <?php echo htmlspecialchars($data['logotipo']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <div class="bg-secondary rounded p-5" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                            <div>
                                                <i class="fas fa-image" style="font-size: 3rem; color: #999;"></i>
                                                <p class="text-muted mt-2">Sin imagen</p>
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

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="institucion.php" class="btn btn-secondary me-md-2">
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