<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">
                    <i class="bi bi-shield-lock"></i> Bienvenido, Administrador <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?>
                </h4>
                <p class="mb-0">
                    <strong>Email:</strong> <?php echo $_SESSION['email']; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Card Estudiantes -->
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Estudiantes</h5>
                    <h2 class="text-primary"><?php echo $total_estudiantes; ?></h2>
                    <a href="admin.php?seccion=estudiantes" class="btn btn-primary btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Calificaciones -->
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Calificaciones</h5>
                    <h2 class="text-success"><?php echo $total_calificaciones; ?></h2>
                    <a href="admin.php?seccion=calificaciones" class="btn btn-success btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Anuncios -->
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <i class="bi bi-megaphone text-warning" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Anuncios</h5>
                    <h2 class="text-warning"><?php echo count($anuncios_recientes); ?></h2>
                    <a href="admin.php?seccion=anuncios" class="btn btn-warning btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Usuarios -->
        <div class="col-md-3">
            <div class="card border-danger h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill text-danger" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Usuarios</h5>
                    <p class="text-muted">Próximamente</p>
                    <button class="btn btn-danger btn-sm" disabled>
                        Gestionar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>