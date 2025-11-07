<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-plus-circle"></i> Nueva Calificación</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="docente.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="docente.php?seccion=calificaciones" class="text-decoration-none">Calificaciones</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Formulario de Registro</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="docente.php?seccion=calificaciones&action=create">
                        
                        <div class="mb-3">
                            <label for="id_estudiante_FK" class="form-label">Estudiante *</label>
                            <select class="form-select" id="id_estudiante_FK" name="id_estudiante_FK" required>
                                <option value="">Seleccionar estudiante...</option>
                                <?php foreach ($estudiantes as $est): ?>
                                <option value="<?php echo $est['id_estudiante_PK']; ?>">
                                    <?php echo $est['matricula'] . ' - ' . $est['nombre_completo']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_materia_FK" class="form-label">Materia *</label>
                            <select class="form-select" id="id_materia_FK" name="id_materia_FK" required>
                                <option value="">Seleccionar materia...</option>
                                <?php foreach ($materias as $mat): ?>
                                <option value="<?php echo $mat['id_materia_PK']; ?>">
                                    <?php echo $mat['codigo'] . ' - ' . $mat['nombre']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="periodo" class="form-label">Período *</label>
                                <input type="text" class="form-control" id="periodo" name="periodo" 
                                       placeholder="2025-1" value="2025-1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="calificacion" class="form-label">Calificación</label>
                                <input type="number" class="form-control" id="calificacion" name="calificacion" 
                                       placeholder="0 - 100" min="0" max="100" step="0.01">
                                <div class="form-text">Dejar en blanco si aún no se asigna</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estatus" class="form-label">Estatus *</label>
                            <select class="form-select" id="estatus" name="estatus" required>
                                <option value="Cursando">Cursando</option>
                                <option value="Aprobado">Aprobado</option>
                                <option value="Reprobado">Reprobado</option>
                                <option value="NP">NP (No Presentó)</option>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Los campos marcados con * son obligatorios
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="docente.php?seccion=calificaciones" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>