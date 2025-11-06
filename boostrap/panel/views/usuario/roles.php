<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-shield-lock"></i> Gestionar Roles - 
                <strong><?php echo htmlspecialchars($usuario['correo']); ?></strong>
            </h4>
        </div>
        <div class="card-body">
            
            <!-- Botón volver -->
            <a href="usuario.php?action=read" class="btn btn-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Volver a Usuarios
            </a>
            
            <!-- Contenedor principal en dos columnas -->
            <div class="row">
                
                <!-- Columna izquierda: Asignar roles -->
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-plus-circle"></i> Asignar Nuevo Rol
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="usuario.php?action=assignRole&id=<?php echo $id_usuario; ?>">
                                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                                
                                <div class="mb-3">
                                    <label for="id_role" class="form-label fw-bold">Seleccionar Rol:</label>
                                    <select class="form-control form-control-lg" id="id_role" name="id_role" required>
                                        <option value="">-- Seleccione un rol --</option>
                                        <?php 
                                        if(!empty($todos_roles)):
                                            foreach($todos_roles as $rol):
                                                // Verificar si el usuario ya tiene este rol
                                                $ya_tiene = false;
                                                foreach($roles_usuario as $rol_usuario):
                                                    if($rol_usuario['id_role'] == $rol['id_role']):
                                                        $ya_tiene = true;
                                                        break;
                                                    endif;
                                                endforeach;
                                                
                                                if(!$ya_tiene):
                                        ?>
                                        <option value="<?php echo $rol['id_role']; ?>">
                                            <?php echo htmlspecialchars($rol['role']); ?>
                                        </option>
                                        <?php 
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-plus-circle"></i> Asignar Rol
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Columna derecha: Roles actuales del usuario -->
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-check-circle"></i> Roles Actuales
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            if(!empty($roles_usuario)):
                            ?>
                            <div class="list-group">
                                <?php 
                                foreach($roles_usuario as $rol):
                                ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 mb-2 border rounded">
                                    <div>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                        </span>
                                        <span class="ms-2 fw-bold">
                                            <?php echo htmlspecialchars($rol['role']); ?>
                                        </span>
                                    </div>
                                    <a href="usuario.php?action=removeRole&id_usuario=<?php echo $id_usuario; ?>&id_role=<?php echo $rol['id_role']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Realmente desea remover este rol?');">
                                        <i class="bi bi-trash"></i> Remover
                                    </a>
                                </div>
                                <?php 
                                endforeach;
                                ?>
                            </div>
                            <?php 
                            else:
                            ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Sin roles asignados</strong> - Este usuario no tiene roles asignados actualmente.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php 
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Información adicional -->
            <div class="mt-4">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <strong>Información:</strong> Los roles controlan los permisos y accesos del usuario dentro del sistema.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">