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
                                <i class="fas fa-image"></i> Logotipo
                            </label>
                            <input class="form-control" 
                                   type="text" 
                                   id="logotipo" 
                                   name="logotipo" 
                                   placeholder="Ej: tecnm_celaya.png"
                                   maxlength="100">
                            <div class="form-text">
                                Nombre del archivo de imagen (opcional)
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