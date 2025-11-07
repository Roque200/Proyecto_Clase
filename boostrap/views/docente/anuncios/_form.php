<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-megaphone-fill"></i> Nuevo Anuncio</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="docente.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="docente.php?seccion=anuncios" class="text-decoration-none">Anuncios</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Formulario de Publicación</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="docente.php?seccion=anuncios&action=create">
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título del Anuncio *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   placeholder="Inscripciones abiertas para curso de verano" 
                                   maxlength="200" required>
                            <div class="form-text">Máximo 200 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="contenido" class="form-label">Contenido *</label>
                            <textarea class="form-control" id="contenido" name="contenido" 
                                      rows="8" placeholder="Escribe aquí el contenido del anuncio..." required></textarea>
                            <div class="form-text">Describe el anuncio de manera clara y completa</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prioridad" class="form-label">Prioridad *</label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Importante">Importante</option>
                                    <option value="Urgente">Urgente</option>
                                </select>
                                <div class="form-text">Selecciona la prioridad del anuncio</div>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                                <input type="date" class="form-control" id="fecha_expiracion" name="fecha_expiracion">
                                <div class="form-text">Opcional: fecha en que el anuncio dejará de mostrarse</div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Guía de Prioridades</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li><strong>Normal:</strong> Anuncios informativos generales</li>
                                    <li><strong>Importante:</strong> Información relevante que requiere atención</li>
                                    <li><strong>Urgente:</strong> Comunicados críticos o de última hora</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Los campos marcados con * son obligatorios
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="docente.php?seccion=anuncios" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-success">
                                <i class="bi bi-send"></i> Publicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>