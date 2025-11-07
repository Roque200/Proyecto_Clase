<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-pencil"></i> Modificar Calificación</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="admin.php?seccion=calificaciones" class="text-decoration-none">Calificaciones</a></li>
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
                    <form method="POST" action="admin.php?seccion=calificaciones&action=update&id=<?php echo $data['id_calificacion_PK']; ?>">
                        
                        <div class="alert alert-secondary">
                            <strong>Estudiante:</strong> <?php echo $data['estudiante']; ?><br>
                            <strong>Materia:</strong> <?php echo $data['materia']; ?> (<?php echo $data['codigo']; ?>)<br>
                            <strong>Período:</strong> <?php echo $data['periodo']; ?>
                        </div>

                        <div class="mb-3">
                            <label for="calificacion" class="form-label">Calificación</label>
                            <input type="number" class="form-control" id="calificacion" name="calificacion" 
                                   value="<?php echo $data['calificacion']; ?>"
                                   placeholder="0 - 100" min="0" max="100" step="0.01">
                        </div>

                        <div class="mb-3">
                            <label for="estatus" class="form-label">Estatus *</label>
                            <select class="form-select" id="estatus" name="estatus" required>
                                <option value="Cursando" <?php echo ($data['estatus'] == 'Cursando') ? 'selected' : ''; ?>>Cursando</option>
                                <option value="Aprobado" <?php echo ($data['estatus'] == 'Aprobado') ? 'selected' : ''; ?>>Aprobado</option>
                                <option value="Reprobado" <?php echo ($data['estatus'] == 'Reprobado') ? 'selected' : ''; ?>>Reprobado</option>
                                <option value="NP" <?php echo ($data['estatus'] == 'NP') ? 'selected' : ''; ?>>NP (No Presentó)</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="admin.php?seccion=calificaciones" class="btn btn-secondary">
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