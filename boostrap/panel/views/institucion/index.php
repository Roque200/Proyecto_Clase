<h1>Instituciones</h1>
<div class="btn-group" role="group" aria-label="Basic mixed styles example">
    <a class="btn btn-primary">Imprimir</a>
    <a href="institucion.php?action=create&id" class="btn btn-success">Nuevo</a>
</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">logotipo</th>
      <th scope="col">Institucion</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($data as $institucion):?>
     <tr>
        <th scope="row"><?php echo $institucion['id_institucion'];?></th>
        <td><img src="../images/instituciones/<?php echo $institucion ['logotipo'];?>" while="75" height= "75" class="round-circle" alt="logo"></td>
        <td><?php echo $institucion['intituto']; ?></td>
        <td>
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <a href="institucion.php?action=update&id=<?php echo $institucion['id_institucion']; ?>"class="btn btn-warning">Editar</a>
                <a href="institucion.php?action=delete&id=<?php echo $institucion['id_institucion']; ?>" class="btn btn-danger">Eliminar</a>
            </div>
        </td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>