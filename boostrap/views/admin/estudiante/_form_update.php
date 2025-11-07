<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-pencil"></i> Modificar Estudiante</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="admin.php?seccion=estudiantes" class="text-decoration-none">Estudiantes</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Formulario de Edición</h5>
                </div>
                <div class="card-body">
                    <?php if (!$data || $data === false): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Error: No se encontró el estudiante solicitado.
                        </div>
                        <a href="admin.php?seccion=estudiantes" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a la Lista
                        </a>
                    <?php else: ?>
                    <form method="POST" 
                          action="admin.php?seccion=estudiantes&action=update&id=<?php echo $data['id_estudiante_PK']; ?>" 
                          id="formEditEstudiante">
                        
                        <!-- Campo oculto con ID de usuario -->
                        <input type="hidden" name="id_usuario" value="<?php echo $data['id_usuario_FK']; ?>">
                        
                        <!-- Información del registro -->
                        <div class="alert alert-secondary">
                            <strong>ID Estudiante:</strong> <?php echo $data['id_estudiante_PK']; ?> | 
                            <strong>Fecha de registro:</strong> <?php echo isset($data['fecha_creacion']) ? date('d/m/Y', strtotime($data['fecha_creacion'])) : 'N/A'; ?>
                        </div>

                        <!-- Información Personal -->
                        <div class="border-start border-4 border-warning ps-3 mb-4">
                            <h6 class="text-warning mb-3">
                                <i class="bi bi-person-circle"></i> Información Personal
                            </h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre(s) *</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?php echo htmlspecialchars($data['nombre']); ?>"
                                           required
                                           maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidos" class="form-label">Apellidos *</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="apellidos" 
                                           name="apellidos" 
                                           value="<?php echo htmlspecialchars($data['apellidos']); ?>"
                                           required
                                           maxlength="100">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Institucional *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="<?php echo htmlspecialchars($data['email']); ?>"
                                           required
                                           maxlength="100">
                                </div>
                                <div class="form-text">Debe terminar en @itcelaya.edu.mx</div>
                            </div>
                        </div>

                        <!-- Información Académica -->
                        <div class="border-start border-4 border-primary ps-3 mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-book"></i> Información Académica
                            </h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="matricula" class="form-label">Matrícula *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                        <input type="text" 
                                               class="form-control" 
                                               id="matricula" 
                                               name="matricula" 
                                               value="<?php echo htmlspecialchars($data['matricula']); ?>"
                                               required
                                               maxlength="20"
                                               pattern="[0-9]{8}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="semestre" class="form-label">Semestre Actual *</label>
                                    <select class="form-select" id="semestre" name="semestre" required>
                                        <?php for($i = 1; $i <= 9; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                                <?php echo ($data['semestre'] == $i) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?>° Semestre
                                        </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="carrera" class="form-label">Carrera *</label>
                                <select class="form-select" id="carrera" name="carrera" required>
                                    <option value="Ingeniería en Sistemas Computacionales" 
                                            <?php echo ($data['carrera'] == 'Ingeniería en Sistemas Computacionales') ? 'selected' : ''; ?>>
                                        Ingeniería en Sistemas Computacionales
                                    </option>
                                    <option value="Ingeniería Industrial" 
                                            <?php echo ($data['carrera'] == 'Ingeniería Industrial') ? 'selected' : ''; ?>>
                                        Ingeniería Industrial
                                    </option>
                                    <option value="Ingeniería Mecánica" 
                                            <?php echo ($data['carrera'] == 'Ingeniería Mecánica') ? 'selected' : ''; ?>>
                                        Ingeniería Mecánica
                                    </option>
                                    <option value="Ingeniería Electrónica" 
                                            <?php echo ($data['carrera'] == 'Ingeniería Electrónica') ? 'selected' : ''; ?>>
                                        Ingeniería Electrónica
                                    </option>
                                    <option value="Ingeniería Química" 
                                            <?php echo ($data['carrera'] == 'Ingeniería Química') ? 'selected' : ''; ?>>
                                        Ingeniería Química
                                    </option>
                                    <option value="Ingeniería Bioquímica" 
                                            <?php echo ($data['carrera'] == 'Ingeniería Bioquímica') ? 'selected' : ''; ?>>
                                        Ingeniería Bioquímica
                                    </option>
                                    <option value="Ingeniería Mecatrónica" 
                                            <?php echo ($data['carrera'] == 'Ingeniería Mecatrónica') ? 'selected' : ''; ?>>
                                        Ingeniería Mecatrónica
                                    </option>
                                    <option value="Ingeniería en Gestión Empresarial" 
                                            <?php echo ($data['carrera'] == 'Ingeniería en Gestión Empresarial') ? 'selected' : ''; ?>>
                                        Ingeniería en Gestión Empresarial
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_ingreso" 
                                       name="fecha_ingreso" 
                                       value="<?php echo $data['fecha_ingreso']; ?>"
                                       required
                                       max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <!-- Alertas informativas -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle"></i> Nota Importante
                            </h6>
                            <p class="mb-0 small">
                                Para cambiar la contraseña del estudiante, contacte al administrador del sistema.
                                Los cambios realizados se aplicarán inmediatamente.
                            </p>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="admin.php?seccion=estudiantes" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-warning">
                                <i class="bi bi-save"></i> Actualizar Datos
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validación del correo institucional
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    if (!email.endsWith('@itcelaya.edu.mx')) {
        this.setCustomValidity('El correo debe terminar en @itcelaya.edu.mx');
        this.reportValidity();
    } else {
        this.setCustomValidity('');
    }
});

// Validación de la matrícula (solo números)
document.getElementById('matricula').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Validación de nombres (solo letras y espacios)
document.getElementById('nombre').addEventListener('input', function() {
    this.value = this.value.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, '');
});

document.getElementById('apellidos').addEventListener('input', function() {
    this.value = this.value.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, '');
});

// Confirmación antes de actualizar
document.getElementById('formEditEstudiante').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value;
    const apellidos = document.getElementById('apellidos').value;
    const matricula = document.getElementById('matricula').value;
    
    if (!confirm(`¿Confirmas la actualización de los datos?\n\nEstudiante: ${nombre} ${apellidos}\nMatrícula: ${matricula}`)) {
        e.preventDefault();
    }
});
</script>