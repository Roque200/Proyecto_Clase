
    <main class="container my-5">
        <section class="mb-5">
            <?php foreach ($instituciones as $institucion) : ?>
            <div class="table-container">
                <h3 class="section-title oswald-titulo">INSTITUCIONES</h3>
                <div class="table-responsive">
                    <table class="table table-investigadores">
                        <thead>
                            <tr>
                                <th>Nombre de la Institucion</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $institucion['instituto']; ?> </td>
                                <td><img src="images/instituciones/<?php echo ($institucion['logotipo']); ?>" class="rounded-circle border shadow-sm" width="100" height="100">
                                </td>
                            </tr>    
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <?php endforeach; ?>
    </main>
