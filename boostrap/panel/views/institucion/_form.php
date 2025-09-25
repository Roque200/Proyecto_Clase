<h1>Nueva Institucion</h1>
<form method = "POST" action="institucion.php?action=create" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="intituto" class="form-label">Nombre de la Institucion</label>
        <input type="text" class="form-control" id="intituto" name="intituto" placeholder="TecNM" required>
    </div>
    <div class="mb-3">
        <label for="logotipo" class="form-label">Logotipo</label>
        <input class="form-control" type="text" id="logotipo" name="logotipo" placeholder="TecNM" required>
    </div>
    <div class="mb-3">
        <input class="btn btn-success" type="submit" id="enviar" name="enviar" value="Enviar" required>
    </div>
</form>