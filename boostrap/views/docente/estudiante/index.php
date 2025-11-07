<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-people"></i> Mis Estudiantes - <?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="docente.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Estudiantes</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Mostrando únicamente los estudiantes de 
        <strong><?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></strong>. 
        Como docente, solo puedes consultar la información de los estudiantes de tu carrera.
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Lista de Estudiantes - <?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>
            </h5>
        </div>
        <div class="card-body">
            <?php if (is_array($data) && count($data) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10%;">Matrícula</th>
                            <th style="width: 30%;">Nombre Completo</th>
                            <th style="width: 30%;">Email</th>
                            <th style="width: 15%;">Semestre</th>
                            <th style="width: 15%;">Fecha Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $estudiante): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($estudiante['matricula']); ?></strong></td>
                            <td><?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                            <td>
                                <span class="badge bg-info fs-6"><?php echo $estudiante['semestre']; ?>°</span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($estudiante['fecha_ingreso'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle"></i> 
                    Total de estudiantes en <?php echo htmlspecialchars($_SESSION['docente_carrera']); ?>: 
                    <strong><?php echo count($data); ?></strong>
                </p>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay estudiantes registrados en 
                <strong><?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></strong>.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <?php if (is_array($data) && count($data) > 0): ?>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">
                        <?php echo count($data); ?>
                    </h3>
                    <p class="mb-0">Estudiantes en mi Carrera</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">
                        <?php 
                        $activos = 0;
                        foreach ($data as $e) {
                            if ($e['activo'] == 1) $activos++;
                        }
                        echo $activos;
                        ?>
                    </h3>
                    <p class="mb-0">Estudiantes Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h3 class="text-info">
                        <?php 
                        // Contar semestres únicos
                        $semestres = array_unique(array_column($data, 'semestre'));
                        echo count($semestres);
                        ?>
                    </h3>
                    <p class="mb-0">Semestres Diferentes</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distribución por semestre -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Distribución por Semestre</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        // Agrupar por semestre
                        $por_semestre = array();
                        foreach ($data as $est) {
                            $sem = $est['semestre'];
                            if (!isset($por_semestre[$sem])) {
                                $por_semestre[$sem] = 0;
                            }
                            $por_semestre[$sem]++;
                        }
                        ksort($por_semestre);
                        
                        foreach ($por_semestre as $semestre => $cantidad):
                        ?>
                        <div class="col-md-3 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h4 class="text-info"><?php echo $cantidad; ?></h4>
                                    <p class="mb-0 small">Semestre <?php echo $semestre; ?>°</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla detallada por semestre -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-list-ul"></i> Detalle de Estudiantes por Semestre</h6>
                </div>
                <div class="card-body">
                    <?php 
                    ksort($por_semestre);
                    foreach ($por_semestre as $semestre => $cantidad): 
                    ?>
                    <div class="mb-4">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="bi bi-book"></i> Semestre <?php echo $semestre; ?>° 
                            <span class="badge bg-primary"><?php echo $cantidad; ?> estudiantes</span>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matrícula</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Fecha de Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($data as $estudiante): 
                                        if ($estudiante['semestre'] == $semestre):
                                    ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($estudiante['matricula']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellidos']); ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($estudiante['fecha_ingreso'])); ?></td>
                                    </tr>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Búsqueda rápida -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-search"></i> Búsqueda Rápida de Estudiantes</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="busquedaEstudiante" 
                                       placeholder="Buscar por matrícula, nombre o email..."
                                       onkeyup="buscarEstudiante()">
                            </div>
                        </div>
                    </div>
                    <div id="resultadosBusqueda"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Información adicional -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información Importante</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Como docente de <strong><?php echo htmlspecialchars($_SESSION['docente_carrera']); ?></strong>, 
                            solo puedes visualizar a los estudiantes de esta carrera.</li>
                        <li>No tienes permisos para crear, editar o eliminar estudiantes. 
                            Para solicitar cambios, contacta al administrador del sistema.</li>
                        <li>Puedes consultar las calificaciones de estos estudiantes en la sección de 
                            <a href="docente.php?seccion=calificaciones">Calificaciones</a>.</li>
                        <li>Si encuentras algún error en los datos de los estudiantes, repórtalo al departamento de Control Escolar.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Script para búsqueda en tiempo real -->
<script>
function buscarEstudiante() {
    const input = document.getElementById('busquedaEstudiante').value.toLowerCase();
    const resultadosDiv = document.getElementById('resultadosBusqueda');
    
    if (input.length < 2) {
        resultadosDiv.innerHTML = '';
        return;
    }
    
    // Datos de estudiantes desde PHP
    const estudiantes = <?php echo json_encode($data); ?>;
    
    const resultados = estudiantes.filter(est => {
        const nombre = (est.nombre + ' ' + est.apellidos).toLowerCase();
        const matricula = est.matricula.toLowerCase();
        const email = est.email.toLowerCase();
        
        return nombre.includes(input) || 
               matricula.includes(input) || 
               email.includes(input);
    });
    
    if (resultados.length === 0) {
        resultadosDiv.innerHTML = '<div class="alert alert-warning">No se encontraron estudiantes con ese criterio de búsqueda.</div>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
    html += '<thead class="table-dark"><tr><th>Matrícula</th><th>Nombre</th><th>Email</th><th>Semestre</th></tr></thead>';
    html += '<tbody>';
    
    resultados.forEach(est => {
        html += '<tr>';
        html += '<td><strong>' + est.matricula + '</strong></td>';
        html += '<td>' + est.nombre + ' ' + est.apellidos + '</td>';
        html += '<td>' + est.email + '</td>';
        html += '<td><span class="badge bg-info">' + est.semestre + '°</span></td>';
        html += '</tr>';
    });
    
    html += '</tbody></table></div>';
    html += '<p class="text-muted mt-2"><i class="bi bi-info-circle"></i> Se encontraron ' + resultados.length + ' resultado(s)</p>';
    
    resultadosDiv.innerHTML = html;
}
</script>

<style>
/* Estilos adicionales para mejorar la experiencia visual */
.table-hover tbody tr:hover {
    background-color: #f0f8ff;
    cursor: pointer;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.badge {
    font-weight: 500;
    padding: 0.4em 0.8em;
}

/* Animación suave para los resultados de búsqueda */
#resultadosBusqueda {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Mejorar visualización en móviles */
@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .card-body h3 {
        font-size: 1.8rem;
    }
}

/* Efecto hover en las tarjetas de estadísticas */
.border-primary:hover, .border-success:hover, .border-info:hover {
    border-width: 3px !important;
    transition: all 0.3s ease;
}

/* Estilo para la tabla de distribución por semestre */
.border-bottom {
    border-bottom: 2px solid #dee2e6 !important;
}

/* Highlight para la búsqueda */
.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-radius: 0.375rem;
}
</style>