<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-file-earmark-pdf"></i> Generador de Reportes</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alertas de estado -->
    <div id="estado" class="mb-4"></div>

    <!-- Grid de generadores -->
    <div class="row mb-5">
        
        <!-- Reporte de Usuarios PDF -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-people"></i> Usuarios (PDF)</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Reportes de todos los usuarios del sistema.</p>
                </div>
                <div class="card-footer bg-light">
                    <button class="btn btn-primary btn-sm w-100" onclick="generarReporte('pdf_usuarios')">
                        <i class="bi bi-file-pdf"></i> Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Reporte de Estudiantes PDF -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-person-badge"></i> Estudiantes (PDF)</h6>
                </div>
                <div class="card-body">
                    <label class="form-label small"><strong>Carrera:</strong></label>
                    <select class="form-select form-select-sm" id="selectCarrera">
                        <option>Ingeniería en Sistemas</option>
                        <option>Ingeniería Electrónica</option>
                        <option>Ingeniería Mecánica</option>
                        <option>Ingeniería Industrial</option>
                    </select>
                </div>
                <div class="card-footer bg-light">
                    <button class="btn btn-success btn-sm w-100" onclick="generarReporteEstudiantes()">
                        <i class="bi bi-file-pdf"></i> Generar PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas</h6>
                </div>
                <div class="card-body">
                    <label class="form-label small"><strong>Carrera:</strong></label>
                    <select class="form-select form-select-sm" id="selectCarreraStats">
                        <option>Ingeniería en Sistemas</option>
                        <option>Ingeniería Electrónica</option>
                        <option>Ingeniería Mecánica</option>
                        <option>Ingeniería Industrial</option>
                    </select>
                </div>
                <div class="card-footer bg-light">
                    <button class="btn btn-warning btn-sm w-100" onclick="cargarEstadisticas()">
                        <i class="bi bi-graph-up"></i> Cargar
                    </button>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <!-- SECCIÓN DE REPORTES GENERADOS Y ESTADÍSTICAS -->
    <div class="row mt-5">
        
        <!-- Panel de Reportes Generados -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Reportes Generados</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <div id="listaReportes">
                        <p class="text-muted text-center"><i class="bi bi-info-circle"></i> Los reportes generados aparecerán aquí</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Estadísticas -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <div id="panelEstadisticas">
                        <p class="text-muted text-center"><i class="bi bi-info-circle"></i> Las estadísticas aparecerán aquí</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    // Array para almacenar reportes generados
    let reportesGenerados = [];

    function mostrarEstado(mensaje, tipo) {
        const estado = document.getElementById('estado');
        const alertClass = tipo === 'success' ? 'alert-success' : (tipo === 'info' ? 'alert-info' : 'alert-danger');
        const icon = tipo === 'success' ? 'bi-check-circle' : (tipo === 'info' ? 'bi-hourglass-split' : 'bi-exclamation-circle');
        
        estado.innerHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="bi ${icon}"></i> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        
        // Auto-cerrar después de 5 segundos
        setTimeout(() => {
            estado.innerHTML = '';
        }, 5000);
    }

    function agregarReporteAlista(nombre, archivo, tipo) {
        const ahora = new Date().toLocaleString('es-ES');
        const extension = archivo.split('.').pop().toUpperCase();
        
        const nuevoReporte = {
            nombre: nombre,
            archivo: archivo,
            tipo: tipo,
            fecha: ahora
        };
        
        reportesGenerados.unshift(nuevoReporte);
        actualizarListaReportes();
    }

    function actualizarListaReportes() {
        const lista = document.getElementById('listaReportes');
        
        if (reportesGenerados.length === 0) {
            lista.innerHTML = '<p class="text-muted text-center"><i class="bi bi-info-circle"></i> No hay reportes generados</p>';
            return;
        }
        
        let html = '<div class="list-group">';
        reportesGenerados.forEach((reporte, index) => {
            const extension = reporte.archivo.split('.').pop().toUpperCase();
            const iconoClase = extension === 'PDF' ? 'bi-file-pdf text-danger' : 'bi-file-text text-primary';
            
            html += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><i class="bi ${iconoClase}"></i> ${reporte.nombre}</h6>
                            <small class="text-muted">${reporte.fecha}</small>
                        </div>
                        <a href="/siga-itc/api/descargar_archivo.php?archivo=${encodeURIComponent(reporte.archivo.split('/')[1])}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="bi bi-download"></i> Descargar
                        </a>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        lista.innerHTML = html;
    }

    function generarReporte(tipo) {
        mostrarEstado('⏳ Generando reporte de usuarios...', 'info');
        
        const formData = new FormData();
        formData.append('tipo', tipo);
        
        const url = '/siga-itc/api/generar_pdf.php';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.exito) {
                mostrarEstado(`✅ ${data.mensaje}`, 'success');
                agregarReporteAlista(
                    'Reporte de Usuarios',
                    data.archivo,
                    'pdf'
                );
            } else {
                mostrarEstado(`❌ Error: ${data.error}`, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarEstado(`❌ Error: ${error.message}`, 'error');
        });
    }

    function generarReporteEstudiantes() {
        const carrera = document.getElementById('selectCarrera').value;
        mostrarEstado('⏳ Generando reporte de estudiantes...', 'info');
        
        const formData = new FormData();
        formData.append('tipo', 'pdf_estudiantes');
        formData.append('carrera', carrera);
        
        fetch('/siga-itc/api/generar_pdf.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.exito) {
                mostrarEstado(`✅ ${data.mensaje}`, 'success');
                agregarReporteAlista(
                    'Reporte de Estudiantes - ' + carrera,
                    data.archivo,
                    'pdf'
                );
            } else {
                mostrarEstado(`❌ Error: ${data.error}`, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarEstado(`❌ Error: ${error.message}`, 'error');
        });
    }

    function cargarEstadisticas() {
        const carrera = document.getElementById('selectCarreraStats').value;
        mostrarEstado('⏳ Cargando estadísticas...', 'info');
        
        const formData = new FormData();
        formData.append('tipo', 'estadisticas_carrera');
        formData.append('carrera', carrera);
        
        fetch('/siga-itc/api/generar_reporte.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.exito) {
                mostrarEstado(`✅ ${data.mensaje}`, 'success');
                mostrarEstadisticas(carrera, data.datos);
            } else {
                mostrarEstado(`❌ Error: ${data.error}`, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarEstado(`❌ Error: ${error.message}`, 'error');
        });
    }

    function mostrarEstadisticas(carrera, stats) {
        const panel = document.getElementById('panelEstadisticas');
        
        const html = `
            <div class="card-body">
                <h6 class="mb-3"><strong>📊 ${carrera}</strong></h6>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Total de Estudiantes</small>
                            <h4 class="mb-0">${stats.total_estudiantes || 0}</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Promedio</small>
                            <h5 class="mb-0 text-primary">${stats.promedio || 'N/A'}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Calificaciones</small>
                            <h5 class="mb-0 text-info">${stats.total_calificaciones || 0}</h5>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Mínima</small>
                            <h5 class="mb-0 text-warning">${stats.minimo || 'N/A'}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted">Máxima</small>
                            <h5 class="mb-0 text-success">${stats.maximo || 'N/A'}</h5>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Estadísticas actualizadas: ${new Date().toLocaleString('es-ES')}
                </small>
            </div>
        `;
        
        panel.innerHTML = html;
    }
</script>