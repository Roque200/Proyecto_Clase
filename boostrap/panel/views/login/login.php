<?php 
include_once("./views/login/header.php");
?>
<form action="login.php?action=login" method="post">
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 form-label">Email</label>
        <div class="col-sm-10">
            <input name= "correo" type="text"  class="form-control" id="correo">
        </div>
        </div>
        <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 form-label">Password</label>
        <div class="col-sm-10">
            <input name="contrasena" type="password" class="form-control" id="contrasena">
        </div>
        <div class="mb-3">
            <input type="submit" class="btn btn-success" id="enviar" name="enviar" value="Entrar">
        </div>
    </div>
</form>

<?php 
include_once('./views/login/footer.php');
?>