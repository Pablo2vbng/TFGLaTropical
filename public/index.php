<?php session_start(); ?>
<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/navbar.php'; ?>

<main class="main-content" style="margin-top: 80px;">

    <section class="title text-center py-5">
        <div class="container faq-container">
            <h1 class="title__title display-3 fw-bold mb-4">
                La nostra història.<br>
                <span class="title__subtitle">Pura harmonia.</span>
            </h1>
            <?php // https://getbootstrap.com/docs/5.3/utilities/text/ | display-3 = letra grande | fw-bold = negrita (font-weight) | mb-4 = margen inferior (margin-bottom) ?>
            
            <p class="title__description lead mx-auto">
                Som la Societat Musical La Tropical de Benigànim. Fem música per a tot tipus d'esdeveniments. Una gran família unida per la passió que emociona en cada nota des de juliol de 1978.
            </p>
            <?php // lead = hace que el párrafo resalte (letra más grande y ligera) | mx-auto = centra el bloque horizontalmente ?>
        </div>
    </section>
    <section class="faq-section py-5">
        <div class="container faq-container">
            <h2 class="text-center fw-bold mb-5">Preguntes Freqüents</h2>
            
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Com puc apuntar-me a l'escola de música?
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <strong>Pots contactar amb nosaltres a través del formulari.</strong> L'escola està oberta a totes les edats. Oferim classes de llenguatge musical i de tots els instruments de vent i percussió.
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Feu actuacions per a Moros i Cristians?
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <strong>Sí, tenim una àmplia experiència.</strong> Acompanyem a filaes i comparses arreu de la Comunitat Valenciana amb un repertori de marxes mores i cristianes molt complet.
                  </div>
                </div>
              </div>
              
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    On assegeu i quins dies?
                  </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <strong>Assegem al nostre local social.</strong> Normalment els assajos generals de la banda són els divendres per la nit i diumenges pel matí, preparant els propers concerts o actes de carrer.
                  </div>
                </div>
              </div>
            </div>
            <?php // https://getbootstrap.com/docs/5.3/components/accordion/ | accordion = contenedor | accordion-item = cada fila | collapse show = abierto por defecto | collapsed = cerrado ?>
        </div>
    </section>
    <section class="py-5 bg-light">
        <div class="container contact-container">
            <div class="contact" id="contact">
                <h2 class="contact-tittle__s_color text-center mb-4"><strong>CONTACTE</strong></h2>
                
                <form class="contact-form" method="POST" action="../controllers/contacto.php">
                    <div class="contact-form-item mb-3">
                        <label class="contact-form-item-label" for="name">Nom: </label>
                        <input class="contact-form-item-input form-control" type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="contact-form-item mb-3">
                        <label class="contact-form-item-label" for="email">Email: </label>
                        <input class="contact-form-item-input form-control" type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="contact-form-item mb-3">
                        <label class="contact-form-item-label" for="phone-number">Telèfon</label>
                        <input class="contact-form-item-input form-control" type="tel" id="phone-number" name="phone-number">
                    </div>
                    
                    <div class="contact-form-item mb-3">
                        <label class="contact-form-item-label" for="comments">Comentaris</label>
                        <textarea class="contact-form-item-input form-control" name="comments" id="comments" rows="5"></textarea>
                    </div>
                    <?php // https://getbootstrap.com/docs/5.3/forms/form-control/ | form-control = da el estilo a los inputs y textarea (bordes, focus azul, ancho 100%) | mb-3 = margen inferior para separar campos ?>

                    <div class="contact-form-terms mb-4">
                        <input class="contact-form-terms-checkbox form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="contact-form-terms-label form-check-label" for="terms">Accepte les <a class="contact-form-terms-label-link" href="#">condicions del servei</a></label>
                    </div>
                    <?php // https://getbootstrap.com/docs/5.3/forms/checks-radios/ | form-check-input = estilo para el checkbox | form-check-label = estilo para el texto del check ?>

                    <button class="contact-form-button w-100" type="submit">Enviar</button>
                    <?php // w-100 = clase de Bootstrap para que el botón ocupe el 100% del ancho del contenedor ?>
                </form>
            </div>
        </div>
    </section>
    </main>

<?php require_once '../includes/footer.php'; ?>