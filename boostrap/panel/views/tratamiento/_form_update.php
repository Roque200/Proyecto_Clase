<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Tratamiento
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="tratamiento.php?action=update&id=<?php echo $data['id_tratamiento']; ?>">
                        <div class="mb-3">
                            <label for="tratamiento" class="form-label">
                                <i class="fas fa-user-md"></i> Nombre del Tratamiento *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tratamiento" 
                                   name="tratamiento" 
                                   value="<?php echo htmlspecialchars($data['tratamiento'] ?? ''); ?>" 
                                   placeholder="Ej: Dr., Dra., Ing., M.C., etc." 
                                   required
                                   maxlength="50"
                                   minlength="2">
                            <div class="form-text">
                                Ingrese el tratamiento (título académico o profesional)
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="tratamiento.php" class="btn btn-secondary me-md-2">
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