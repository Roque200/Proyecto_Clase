<?php
require_once("../models/institucion.php");
require_once("../models/tratamiento.php");

$institucionApp = new Institucion();
$tratamientoApp = new Tratamiento();
$instituciones = $institucionApp->read();
$tratamientos = $tratamientoApp->read();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-user-plus"></i> Nuevo Investigador
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="investigador.php?action=create">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       placeholder="Juan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" 
                                       placeholder="Pérez" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                   placeholder="García">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_tratamiento" class="form-label">
                                    <i class="fas fa-user-md"></i> Tratamiento *
                                </label>
                                <select class="form-select" id="id_tratamiento" name="id_tratamiento" required>
                                    <option value="">Seleccione...</option>
                                    <?php if(!empty($tratamientos)): ?>
                                        <?php foreach($tratamientos as $tratamiento): ?>
                                            <option value="<?php echo $tratamiento['id_tratamiento']; ?>">
                                                <?php echo htmlspecialchars($tratamiento['tratamiento']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay tratamientos disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">
                                    <a href="tratamiento.php?action=create" target="_blank">+ Agregar nuevo tratamiento</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="id_institucion" class="form-label">
                                    <i class="fas fa-university"></i> Institución *
                                </label>
                                <select class="form-select" id="id_institucion" name="id_institucion" required>
                                    <option value="">Seleccione...</option>
                                    <?php if(!empty($instituciones)): ?>
                                        <?php foreach($instituciones as $institucion): ?>
                                            <option value="<?php echo $institucion['id_institucion']; ?>">
                                                <?php echo ($institucion['instituto']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay instituciones disponibles</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">
                                    <a href="institucion.php?action=create" target="_blank">+ Agregar nueva institución</a>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fotografia" class="form-label">
                                <i class="fas fa-camera"></i> Fotografía
                            </label>
                            <input type="file" class="form-control" id="fotografia" name="fotografia" 
                                   placeholder="investigador.jpg">
                            <div class="form-text">
                                Nombre del archivo de imagen (se guardará en /images/investigadores/)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="semblance" class="form-label">
                                <i class="fas fa-file-alt"></i> Semblanza
                            </label>
                            <textarea class="form-control" id="semblance" name="semblance" 
                                      rows="5" placeholder="Breve biografía del investigador..."></textarea>
                            <div class="form-text">
                                Información académica, líneas de investigación, logros, etc.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="investigador.php" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" name="enviar" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>