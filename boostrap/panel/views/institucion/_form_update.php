<h1>Editar Institucion</h1>
<form method = "POST" action="institucion.php?action=update&id<?php echo $id[0]['id_institucion']; ?>" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="intituto" class="form-label">Nombre de la Institucion</label>
        <input type="text" class="form-control" id="intituto" name="intituto" value="<?php echo $data['institucion']; ?>" placeholder="TecNM" required>
    </div>
    <div class="mb-3">
        <label for="logotipo" class="form-label">Logotipo</label>
        <input class="form-control" type="text" id="logotipo" name="logotipo" value="<?php echo $data['logotipo']; ?>" placeholder="logo.png" required>
    </div>
    <div class="mb-3">
        <input type="submit" class="btn btn-success" id="enviar" name="enviar" value="Enviar" required>
    </div>
</form>