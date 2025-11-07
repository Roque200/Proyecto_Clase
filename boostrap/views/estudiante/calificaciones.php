<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-clipboard-check"></i> Mis Calificaciones</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="estudiante.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Calificaciones</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if (count($data) > 0): ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Historial de Calificaciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Materia</th>
                                <th>Créditos</th>
                                <th>Período</th>
                                <th>Calificación</th>
                                <th>Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $calificacion): 
                                $badge_class = '';
                                if ($calificacion['estatus'] == 'Aprobado') $badge_class = 'bg-success';
                                elseif ($calificacion['estatus'] == 'Reprobado') $badge_class = 'bg-danger';
                                elseif ($calificacion['estatus'] == 'Cursando') $badge_class = 'bg-info';
                                else $badge_class = 'bg-secondary';
                            ?>
                            <tr>
                                <td><strong><?php echo $calificacion['codigo']; ?></strong></td>
                                <td><?php echo $calificacion['materia']; ?></td>
                                <td><?php echo $calificacion['creditos']; ?></td>
                                <td><?php echo $calificacion['periodo']; ?></td>
                                <td>
                                    <span class="badge bg-primary fs-6">
                                        <?php echo $calificacion['calificacion'] ? $calificacion['calificacion'] : 'N/A'; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo $calificacion['estatus']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen de Calificaciones -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success">
                            <?php 
                            $aprobadas = 0;
                            foreach ($data as $c) {
                                if ($c['estatus'] == 'Aprobado') $aprobadas++;
                            }
                            echo $aprobadas;
                            ?>
                        </h3>
                        <p class="mb-0">Materias Aprobadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <h3 class="text-info">
                            <?php 
                            $cursando = 0;
                            foreach ($data as $c) {
                                if ($c['estatus'] == 'Cursando') $cursando++;
                            }
                            echo $cursando;
                            ?>
                        </h3>
                        <p class="mb-0">Materias Cursando</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h3 class="text-primary">
                            <?php 
                            $suma = 0;
                            $contador = 0;
                            foreach ($data as $c) {
                                if ($c['estatus'] == 'Aprobado' && $c['calificacion']) {
                                    $suma += $c['calificacion'];
                                    $contador++;
                                }
                            }
                            $promedio = $contador > 0 ? round($suma / $contador, 2) : 0;
                            echo $promedio;
                            ?>
                        </h3>
                        <p class="mb-0">Promedio General</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No tienes calificaciones registradas aún.
        </div>
    <?php endif; ?>
</div>