<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-calendar3"></i> Mi Horario de Clases</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="estudiante.php" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item active">Horarios</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if (count($data) > 0): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Período 2025-1</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>Día</th>
                                <th>Materia</th>
                                <th>Código</th>
                                <th>Docente</th>
                                <th>Aula</th>
                                <th>Horario</th>
                                <th>Grupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $horario): ?>
                            <tr>
                                <td><strong><?php echo $horario['dia_semana']; ?></strong></td>
                                <td><?php echo $horario['materia']; ?></td>
                                <td><?php echo $horario['codigo']; ?></td>
                                <td><?php echo $horario['docente']; ?></td>
                                <td><span class="badge bg-info"><?php echo $horario['aula']; ?></span></td>
                                <td>
                                    <?php 
                                    echo date('H:i', strtotime($horario['hora_inicio'])) . ' - ' . 
                                         date('H:i', strtotime($horario['hora_fin'])); 
                                    ?>
                                </td>
                                <td><?php echo $horario['grupo']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vista por días -->
        <div class="row mt-4">
            <?php 
            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            foreach ($dias as $dia): 
                $clases_dia = array_filter($data, function($h) use ($dia) {
                    return $h['dia_semana'] == $dia;
                });
            ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><?php echo $dia; ?></h6>
                    </div>
                    <div class="card-body">
                        <?php if (count($clases_dia) > 0): ?>
                            <?php foreach ($clases_dia as $clase): ?>
                            <div class="border-start border-4 border-success ps-3 mb-3">
                                <p class="mb-1"><strong><?php echo $clase['materia']; ?></strong></p>
                                <p class="small mb-1">
                                    <i class="bi bi-clock"></i> 
                                    <?php echo date('H:i', strtotime($clase['hora_inicio'])); ?> - 
                                    <?php echo date('H:i', strtotime($clase['hora_fin'])); ?>
                                </p>
                                <p class="small mb-0">
                                    <i class="bi bi-geo-alt"></i> <?php echo $clase['aula']; ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small mb-0">Sin clases</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay horarios disponibles.
        </div>
    <?php endif; ?>
</div>