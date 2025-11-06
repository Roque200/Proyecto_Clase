<?php 
include_once("./views/login/header.php");
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Restablecer Contraseña</h2>

                    <form action="login.php?action=restablecer" method="post">
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required 
                                   placeholder="Ingrese su nueva contraseña" minlength="6">
                        </div>

                        <div class="mb-3">
                            <label for="confirmar" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmar" name="confirmar" required 
                                   placeholder="Confirme su contraseña" minlength="6">
                        </div>

                        <input name="token" type="hidden" class="form-control" id="token" value="<?php isset($peticion['token']) ? print($peticion['token']) : print($token)?>">
                        <input name="correo" type="hidden" class="form-control" id="correo" value="<?php isset($peticion['correo']) ? print($peticion['correo']) : print($correo)?>">

                        <button type="submit" class="btn btn-success w-100 mb-3">
                            Cambiar Contraseña
                        </button>
                    </form>

                    <hr>
                    <p class="text-center">
                        <a href="login.php">Volver al login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const contrasena = document.getElementById('contrasena').value;
        const confirmar = document.getElementById('confirmar').value;
        
        if(contrasena !== confirmar) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }
    });
</script>

<?php 
include_once('./views/login/footer.php');
?>