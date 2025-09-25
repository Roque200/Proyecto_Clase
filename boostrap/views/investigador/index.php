 <main class="container my-5">
    <h2 class="mb-4 text-center">Miembros de la Red</h2>
    <div class="row g-4 justify-content-center">
        <?php foreach ($investigadores as $investigador): ?>
      <div class="col-md-4">
        <div class="card h-100 text-center">
          <img src="img/investigadores/1.jpg" class="card-img-top rounded-circle mx-auto mt-3" style="width:120px;height:120px;object-fit:cover;" alt="Miembro 1">
          <div class="card-body">
            <h5 class="card-title"><?php echo $investigador['nomre'].' '.$investigador['primer_apellido'].' '.$investigador['segundo_apellido'];?></h5>
            <p class="card-text"><?php echo $investigador['semblanza'];?></p>
            <a href="mailto:ana.martinez@redinvestigacion.com" class="btn btn-outline-primary btn-sm">Contactar</a>
          </div>
        </div>
      </div>
        <?php endforeach; ?>
    </div>
  </main>