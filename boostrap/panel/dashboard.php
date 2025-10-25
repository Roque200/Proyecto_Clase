<?php
require_once('../models/sistem.php');

$app = new Sistema();

// Proteger: si no está logeado, redirigir al login
if (!$app->estaLogeado()) {
    header("Location: login.php");
    exit();
}

// Obtener datos de la sesión
$usuario = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
$correo = $_SESSION['correo'];
$roles = $_SESSION['roles'];
$permisos = $_SESSION['permisos'];

include_once('./views/header.php');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h2 class="mb-0">
                        <i class="fas fa-tachometer-alt"></i> Bienvenido al Panel de Administración
                    </h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-user-circle"></i> 
                        <strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?> 
                        (<?php echo htmlspecialchars($correo); ?>)
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <i class="fas fa-university fa-2x"></i>
                                    </h5>
                                    <p class="card-text">Instituciones</p>
                                    <a href="institucion.php" class="btn btn-light btn-sm">Gestionar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <i class="fas fa-user-md fa-2x"></i>
                                    </h5>
                                    <p class="card-text">Tratamientos</p>
                                    <a href="tratamiento.php" class="btn btn-dark btn-sm">Gestionar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <i class="fas fa-users fa-2x"></i>
                                    </h5>
                                    <p class="card-text">Investigadores</p>
                                    <a href="investigador.php" class="btn btn-light btn-sm">Gestionar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Roles Asignados:</h5>
                            <ul class="list-group">
                                <?php foreach ($roles as $rol): ?>
                                    <li class="list-group-item">
                                        <span class="badge bg-success"><?php echo htmlspecialchars($rol); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h5>Permisos:</h5>
                            <ul class="list-group">
                                <?php foreach ($permisos as $permiso): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success"></i> 
                                        <?php echo htmlspecialchars($permiso); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('./views/footer.php'); ?>