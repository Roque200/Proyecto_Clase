<main class="container my-5">
    <section class="mb-5">
        <div class="table-container">
            <h3 class="section-title oswald-titulo text-center mb-4">INVESTIGADORES</h3>
            <div class="table-responsive">
                <table class="table table-investigadores">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre Completo</th>
                            <th>Institución</th>
                            <th>Especialidad</th>
                            <th>Contacto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($investigadores as $investigador): ?>
                        <tr>
                            <td>
                                <img src="images/investigadores/<?php echo htmlspecialchars($investigador['fotografia']); ?>" 
                                     class="rounded-circle border shadow-sm investigator-photo" 
                                     width="100" height="100"
                                     alt="<?php echo htmlspecialchars($investigador['nombre']); ?>"
                                     onerror="this.src='images/investigadores/default.png'">
                            </td>
                            <td class="fw-bold">
                                <?php 
                                echo htmlspecialchars($investigador['nombre_tratamiento']) . ' ' .
                                     htmlspecialchars($investigador['nombre']) . ' ' . 
                                     htmlspecialchars($investigador['primer_apellido']) . ' ' . 
                                     htmlspecialchars($investigador['segundo_apellido']); 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($investigador['nombre_institucion']); ?></td>
                            <td>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars(substr($investigador['semblance'], 0, 100)) . '...'; ?>
                                </small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                        data-bs-target="#modal<?php echo $investigador['id_investigador']; ?>">
                                    Ver más
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modales con información completa -->
    <?php foreach ($investigadores as $investigador): ?>
    <div class="modal fade" id="modal<?php echo $investigador['id_investigador']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <?php echo htmlspecialchars($investigador['nombre_tratamiento'] . ' ' . 
                                                     $investigador['nombre'] . ' ' . 
                                                     $investigador['primer_apellido']); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="images/investigadores/<?php echo htmlspecialchars($investigador['fotografia']); ?>" 
                                 class="img-fluid rounded-circle mb-3" 
                                 alt="<?php echo htmlspecialchars($investigador['nombre']); ?>">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Institución:</strong> <?php echo htmlspecialchars($investigador['nombre_institucion']); ?></p>
                            <p><strong>Semblanza:</strong></p>
                            <p><?php echo htmlspecialchars($investigador['semblance']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</main>