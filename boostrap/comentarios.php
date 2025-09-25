<?php
include_once("./views/header.php"); 
?>
    <main class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="oswald-titulo">Preguntas Frecuentes</h2>

                <div class="accordion" id="accordionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                ¿Qué es la Red de Investigación TecNM?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                La Red de Investigación del Tecnológico Nacional de México es una iniciativa
                                colaborativa
                                que reúne a investigadores, académicos y estudiantes comprometidos con el desarrollo
                                científico
                                y tecnológico del país, con especial énfasis en temas de sustentabilidad y cambio
                                climático.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                ¿Cómo puedo participar en los proyectos de investigación?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Pueden participar estudiantes de licenciatura y posgrado, investigadores y profesores
                                del TecNM.
                                Para unirse, debe contactar al investigador principal del proyecto de su interés o
                                enviar su
                                propuesta al comité de investigación a través del formulario de contacto.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                ¿Cuáles son las fuentes de financiamiento?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Nuestros proyectos son financiados por CONACYT, el TecNM, empresas privadas del sector
                                industrial del Bajío y organismos internacionales interesados en el desarrollo
                                sustentable.
                                También contamos con convenios de colaboración con universidades nacionales e
                                internacionales.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                ¿Cómo accedo a las publicaciones y resultados?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Las publicaciones están disponibles en repositorios académicos y revistas científicas
                                indexadas.
                                Los informes técnicos y resultados preliminares pueden solicitarse directamente al
                                equipo de
                                investigación. Próximamente habilitaremos una sección de descargas en este sitio web.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                ¿Ofrecen servicio de consultoría?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Sí, ofrecemos servicios de consultoría especializada en cambio climático, energías
                                renovables,
                                sustentabilidad empresarial y análisis de datos ambientales. Las empresas interesadas
                                pueden
                                solicitar información a través del formulario de contacto.
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mt-5 mb-4">Envíanos tus Comentarios</h3>
                <form>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="institucion" class="form-label">Institución/Empresa</label>
                        <input type="text" class="form-control" id="institucion">
                    </div>

                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <select class="form-select" id="asunto">
                            <option selected>Seleccione un tema...</option>
                            <option value="1">Consulta sobre proyectos</option>
                            <option value="2">Propuesta de colaboración</option>
                            <option value="3">Solicitud de información</option>
                            <option value="4">Sugerencia o comentario</option>
                            <option value="5">Reporte de error en el sitio</option>
                            <option value="6">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" rows="5" required></textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="privacidad">
                        <label class="form-check-label" for="privacidad">
                            Acepto el aviso de privacidad y el tratamiento de mis datos personales
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary mb-4">Enviar Mensaje</button>
                </form>
            </div>

            <div class="col-lg-4">
                <h3 class="mb-4">Información de Contacto</h3>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Oficina Principal</h5>
                        <p class="card-text">
                            <strong>Dirección:</strong><br>
                            Av. Tecnológico s/n<br>
                            Col. Centro<br>
                            Celaya, Guanajuato<br>
                            C.P. 38000
                        </p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Contacto Directo</h5>
                        <p class="card-text">
                            <strong>Teléfono:</strong><br>
                            +52 (461) 123-4567<br><br>
                            <strong>Correo:</strong><br>
                            investigacion@tecnm.mx<br>
                            colaboracion@tecnm.mx
                        </p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Horario de Atención</h5>
                        <p class="card-text">
                            Lunes a Viernes<br>
                            9:00 AM - 6:00 PM<br><br>
                            Sábados<br>
                            9:00 AM - 1:00 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Avisos Importantes</h3>
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">Convocatoria Abierta</h5>
                    <p>Está abierta la convocatoria para estudiantes de posgrado interesados en participar en
                        proyectos de investigación sobre cambio climático. Fecha límite: 31 de marzo de 2025.</p>
                </div>

                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">Mantenimiento del Sitio</h5>
                    <p>El sitio web estará en mantenimiento el día 15 de febrero de 8:00 AM a 12:00 PM.
                        Disculpe las molestias.</p>
                </div>
            </div>
        </div>
    </main>

    <br>

       <?php
include_once("./views/footer.php"); 
?>