<div class="container mt-4">
    <h1 class="mb-4">
        <i class="bi bi-people"></i> Gestión de Usuarios
    </h1>
    
    <div class="mb-3">
        <a href="usuario.php?action=create" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col" width="10%">#ID</th>
                    <th scope="col" width="40%">Correo Electrónico</th>
                    <th scope="col" width="20%">Fecha Token</th>
                    <th scope="col" width="30%">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(!empty($data)):
                    foreach ($data as $usuario): 
                ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($usuario['id_usuario']); ?></th>
                    <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                    <td>
                        <?php 
                        if(!empty($usuario['fecha_token'])):
                            echo htmlspecialchars($usuario['fecha_token']);
                        else:
                            echo '<span class="text-muted">N/A</span>';
                        endif;
                        ?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="usuario.php?action=update&id=<?php echo $usuario['id_usuario']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar Usuario">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="usuario.php?action=readRole&id=<?php echo $usuario['id_usuario']; ?>" 
                               class="btn btn-sm btn-info text-white" title="Gestionar Roles">
                                <i class="bi bi-shield-lock"></i> Roles
                            </a>
                            <a href="usuario.php?action=delete&id=<?php echo $usuario['id_usuario']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('¿Está seguro de eliminar este usuario?');" 
                               title="Eliminar Usuario">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    endforeach;
                else:
                ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-inbox"></i> No hay usuarios registrados
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">