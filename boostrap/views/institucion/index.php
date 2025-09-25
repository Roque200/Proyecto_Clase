
    <main class="container my-5">
        <section class="mb-5">
            <?php foreach ($instituciones as $institucion) : ?>
            <div class="table-container">
                <h3 class="section-title oswald-titulo">INSTITUCIONES</h3>
                <div class="table-responsive">
                    <table class="table table-investigadores">
                        <thead>
                            <tr>
                                <th>Nombre de laa Institucion</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $institucion['intituto']; ?> </td>
                                <td><img src="images/institucion/<?php echo $institucion['logotipo'];?> alt="Dr. Carlos Mendoza class="investigator-photo">
                                </td>
                            </tr>    
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
