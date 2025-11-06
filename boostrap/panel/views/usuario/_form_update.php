<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Modificar Usuario
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="usuario.php?action=update&id=<?php echo htmlspecialchars($id); ?>" novalidate>
                        
                        <div class="mb-3">
                            <label for="correo" class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Correo Electrónico:
                            </label>
                            <input type="email" class="form-control form-control-lg" 
                                   id="correo" name="correo" 
                                   value="<?php echo htmlspecialchars($data['correo']); ?>" 
                                   required>
                            <small class="text-muted d-block mt-2">
                                Debe ser un email válido
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="bi bi-lock"></i> Nueva Contraseña:
                            </label>
                            <input type="password" class="form-control form-control-lg" 
                                   id="password" name="password" 
                                   placeholder="Dejar vacío si no desea cambiar" 
                                   minlength="6">
                            <small class="text-muted d-block mt-2">
                                Mínimo 6 caracteres. Dejar vacío para no cambiar la contraseña actual.
                            </small>
                        </div>
                        
                        <hr>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg" name="enviar">
                                <i class="bi bi-save"></i> Actualizar Usuario
                            </button>
                            <a href="usuario.php?action=read" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">