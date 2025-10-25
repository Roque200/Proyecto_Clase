<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Nueva Institución
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="institucion.php?action=create" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="instituto" class="form-label">
                                <i class="fas fa-university"></i> Nombre de la Institución *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="instituto" 
                                   name="instituto" 
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
                                   onchange="previewImage(event)">
                            <div class="form-text">
                                Seleccione una imagen PNG, JPG o GIF (máximo 5MB)
                            </div>
                            
                            <!-- Vista previa de la imagen -->
                            <div class="mt-3" id="preview-container" style="display: none;">
                                <h6>Vista Previa:</h6>
                                <img id="preview-image" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="institucion.php" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
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