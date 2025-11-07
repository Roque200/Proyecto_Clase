<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-person-plus"></i> Nuevo Estudiante</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="docente.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="docente.php?seccion=estudiantes" class="text-decoration-none">Estudiantes</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Formulario de Registro</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="docente.php?seccion=estudiantes&action=create" id="formEstudiante">
                        
                        <!-- Información Personal -->
                        <div class="border-start border-4 border-success ps-3 mb-4">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-person-circle"></i> Información Personal
                            </h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre(s) *</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombre" 
                                           name="nombre" 
                                           placeholder="Juan Carlos" 
                                           required
                                           maxlength="100">
                                    <div class="form-text">Solo letras y espacios</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidos" class="form-label">Apellidos *</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="apellidos" 
                                           name="apellidos" 
                                           placeholder="Pérez García" 
                                           required
                                           maxlength="100">
                                    <div class="form-text">Apellido paterno y materno</div>
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
                                           placeholder="juan.perez@itcelaya.edu.mx" 
                                           required
                                           maxlength="100">
                                </div>
                                <div class="form-text">Debe terminar en @itcelaya.edu.mx</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña Temporal *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Mínimo 8 caracteres" 
                                           required
                                           minlength="8">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">El estudiante deberá cambiarla en su primer acceso</div>
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
                                               placeholder="20210001" 
                                               required
                                               maxlength="20"
                                               pattern="[0-9]{8}">
                                    </div>
                                    <div class="form-text">8 dígitos numéricos</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="semestre" class="form-label">Semestre Actual *</label>
                                    <select class="form-select" id="semestre" name="semestre" required>
                                        <option value="">Seleccionar semestre...</option>
                                        <option value="1">1° Semestre</option>
                                        <option value="2">2° Semestre</option>
                                        <option value="3">3° Semestre</option>
                                        <option value="4">4° Semestre</option>
                                        <option value="5">5° Semestre</option>
                                        <option value="6">6° Semestre</option>
                                        <option value="7">7° Semestre</option>
                                        <option value="8">8° Semestre</option>
                                        <option value="9">9° Semestre</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="carrera" class="form-label">Carrera *</label>
                                <select class="form-select" id="carrera" name="carrera" required>
                                    <option value="">Seleccionar carrera...</option>
                                    <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
                                    <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                                    <option value="Ingeniería Mecánica">Ingeniería Mecánica</option>
                                    <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                                    <option value="Ingeniería Química">Ingeniería Química</option>
                                    <option value="Ingeniería Bioquímica">Ingeniería Bioquímica</option>
                                    <option value="Ingeniería Mecatrónica">Ingeniería Mecatrónica</option>
                                    <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="fecha_ingreso" 
                                       name="fecha_ingreso" 
                                       required
                                       max="<?php echo date('Y-m-d'); ?>">
                                <div class="form-text">Fecha en que el estudiante ingresó al instituto</div>
                            </div>
                        </div>

                        <!-- Información Importante -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle"></i> Información Importante
                            </h6>
                            <ul class="mb-0 small">
                                <li>Los campos marcados con <strong>*</strong> son obligatorios</li>
                                <li>La matrícula debe ser única en el sistema</li>
                                <li>El correo institucional no puede repetirse</li>
                                <li>La contraseña será temporal y el estudiante deberá cambiarla</li>
                            </ul>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="docente.php?seccion=estudiantes" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                            </button>
                            <button type="submit" name="enviar" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar Estudiante
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar/ocultar contraseña
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}

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

// Confirmación antes de enviar
document.getElementById('formEstudiante').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value;
    const apellidos = document.getElementById('apellidos').value;
    const matricula = document.getElementById('matricula').value;
    
    if (!confirm(`¿Confirmas el registro del estudiante?\n\nNombre: ${nombre} ${apellidos}\nMatrícula: ${matricula}`)) {
        e.preventDefault();
    }
});
</script>