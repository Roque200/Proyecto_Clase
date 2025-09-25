<?php
include_once("./views/header.php"); 
?>
    <br>

    <main class="container">
        <section class="mb-5">
            <div class="text-center">
                <h2 class="oswald-titulo"><ins>Registro Visual del Impacto Pluvial en Celaya</ins></h2>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <p class="lead text-center">
                        Durante los últimos años, las intensas precipitaciones han generado cambios significativos
                        en la geografía urbana y rural de Celaya, Guanajuato. Nuestra investigación documenta
                        estos impactos a través de evidencia fotográfica y análisis científico.
                    </p>
                </div>
                <div class="col-1"></div>
            </div>
        </section>

        <section class="mb-5">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <h3>850mm</h3>
                            <p>Precipitación promedio anual</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success">
                        <div class="card-body">
                            <h3>1,200mm</h3>
                            <p>Récord histórico 2023</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <h3>15%</h3>
                            <p>Incremento vs década anterior</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info">
                        <div class="card-body">
                            <h3>45 días</h3>
                            <p>Temporada de lluvias 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h3 class="mb-4 text-center">Evidencia Fotográfica del Impacto</h3>
            <br>
            <h4 class="text-primary mb-3">Inundaciones en Zona Urbana</h4>
            <br>
            <div class="mb-5">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div
                                style="height: 200px; background-color: #6c757d; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/centro historico.jpeg" class="card-img-top" alt="..." height="200px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Inundación en el Centro Histórico</h6>
                                <p class="small">Precipitación de 85mm en 2 horas. Nivel de agua: 45cm</p>
                                <span class="badge bg-danger">Crítico</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div
                                style="height: 200px; background-color: #6c757d; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/drenaje.jpeg" class="card-img-top" alt="..." height="200px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Saturación del Drenaje Pluvial</h6>
                                <p class="small">Colapso temporal del sistema de alcantarillado</p>
                                <span class="badge bg-warning">Moderado</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div
                                style="height: 200px; background-color: #6c757d; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/casas.jpeg" class="card-img-top" alt="..." height="200px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Afectación Residencial</h6>
                                <p class="small">Más de 200 viviendas reportaron daños menores</p>
                                <span class="badge bg-info">Leve</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <h4 class="text-success mb-3">Impacto en la Agricultura</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div
                                style="height: 200px; background-color: #198754; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/campos de maiz.jpeg" class="card-img-top" alt="..." height="200px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Mejora en Rendimiento de Cultivos</h6>
                                <p class="small">Incremento del 22% en producción vs año anterior</p>
                                <span class="badge bg-success">Positivo</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div
                                style="height: 200px; background-color: #ffc107; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/campos de sorgo.jpeg" class="card-img-top" alt="..." height="200px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Saturación de Suelos</h6>
                                <p class="small">Exceso de humedad en 30% de cultivos</p>
                                <span class="badge bg-warning">Precaución</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div
                                style="height: 200px; background-color: #dc3545; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/invernadero.jpeg" class="card-img-top" alt="..." height="200px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Daños en Infraestructura</h6>
                                <p class="small">15 invernaderos reportaron filtraciones</p>
                                <span class="badge bg-danger">Crítico</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <h4 class="text-info mb-3">Recarga de Acuíferos y Presas</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div
                                style="height: 250px; background-color: #0dcaf0; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/presa.jpeg" class="card-img-top" alt="..." height="250px" width="200px">
                            </div>
                            <div class="card-body">
                                <h6>Incremento en Nivel de Presas</h6>
                                <p class="small">Alcanzó el 95% de su capacidad total por primera vez en 8 años.
                                    Esto representa una reserva de 180 millones de metros cúbicos.</p>
                                <span class="badge bg-success">Excelente</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div
                                style="height: 250px; background-color: #20c997; display: flex; align-items: center; justify-content: center;">
                                <img src="images/desastres/mantos.jpeg" class="card-img-top" alt="..." height="250px" width="100px">
                            </div>
                            <div class="card-body">
                                <h6>Recarga de Acuíferos Subterráneos</h6>
                                <p class="small">Los pozos de monitoreo registran un aumento promedio de 2.5 metros
                                    en el nivel freático, beneficiando a comunidades rurales.</p>
                                <span class="badge bg-success">Positivo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h3 class="mb-4">Análisis Técnico y Proyecciones</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-secondary">
                            <h5>Datos Meteorológicos 2024</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><strong>Junio:</strong> 185mm (Normal: 120mm)</li>
                                <li><strong>Julio:</strong> 220mm (Normal: 140mm)</li>
                                <li><strong>Agosto:</strong> 245mm (Normal: 150mm)</li>
                                <li><strong>Septiembre:</strong> 195mm (Normal: 110mm)</li>
                            </ul>
                            <p class="small text-muted">*Comparación con promedio histórico 1990-2020</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-secondary">
                            <h5>Proyecciones 2025</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Tendencia esperada:</strong></p>
                            <ul>
                                <li>Incremento del 18% en precipitación total</li>
                                <li>Mayor frecuencia de eventos extremos</li>
                                <li>Necesidad de mejorar drenaje pluvial</li>
                                <li>Oportunidad para captación de agua</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php
include_once("./views/footer.php"); 
?>