<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus"></i> Crear Nuevo Usuario
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="usuario.php?action=create" novalidate>
                        
                        <div class="mb-3">
                            <label for="correo" class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Correo Electrónico:
                            </label>
                            <input type="email" class="form-control form-control-lg" 
                                   id="correo" name="correo" 
                                   placeholder="usuario@ejemplo.com" 
                                   required>
                            <small class="text-muted d-block mt-2">
                                Debe ser un email válido y único en el sistema
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="bi bi-lock"></i> Contraseña:
                            </label>
                            <input type="password" class="form-control form-control-lg" 
                                   id="password" name="password" 
                                   placeholder="Mínimo 6 caracteres" 
                                   required minlength="6">
                            <small class="text-muted d-block mt-2">
                                Mínimo 6 caracteres. Será almacenada de forma segura con encriptación.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label fw-bold">
                                <i class="bi bi-lock"></i> Confirmar Contraseña:
                            </label>
                            <input type="password" class="form-control form-control-lg" 
                                   id="password_confirm" name="password_confirm" 
                                   placeholder="Repite la contraseña" 
                                   required minlength="6">
                        </div>
                        
                        <hr>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" name="enviar">
                                <i class="bi bi-save"></i> Guardar Usuario
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