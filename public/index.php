<?php 
// INICIO DE SESIÓN PARA GESTIONAR ESTADOS DE USUARIO O MENSAJES DE SESIÓN
session_start(); 
?>

<?php // CHULETA -> TABLA: contact_messages (id, name, email, phone, message, created_at) ?>

<?php 
// CARGA DE COMPONENTES GLOBALES DE CABECERA Y NAVEGACIÓN
require_once '../includes/header.php'; 
require_once '../includes/navbar.php'; 
?>

<!-- EL CONTENIDO PRINCIPAL UTILIZA UN MARGIN-TOP DE 80PX PARA NO QUEDAR OCULTO BAJO LA NAVBAR FIXED-TOP -->
<!-- BOOTSTRAP DOCU: LAYOUT https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="main-content" style="margin-top: 80px;">

    <!-- SECCIÓN DE PRESENTACIÓN E HISTORIA -->
    <section class="title text-center py-5">
      
        <div class="container faq-container">
            
            <h1 class="title__title display-3 fw-bold mb-4">
                La nostra història.<br>
                <span class="title__subtitle">Pura harmonia.</span>
               
            </h1>
            
            <p class="title__description lead mx-auto">
                Som la Societat Musical La Tropical de Benigànim. Fem música per a tot tipus d'esdeveniments. Una gran família unida per la passió que emociona en cada nota des de juliol de 1978.
               
            </p>
        </div>
    </section>

    <!-- SECCIÓN DE PREGUNTAS FRECUENTES (FAQ) -->
    <section class="faq-section py-5">
        <div class="container faq-container">
            <h2 class="text-center fw-bold mb-5">Preguntes Freqüents</h2>
            
            <!-- BOOTSTRAP DOCU: ACORDEÓN https://getbootstrap.com/docs/5.3/components/accordion/ -->
            <div class="accordion" id="accordionExample">
              
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
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
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
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
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
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
        </div>
    </section>

    <!-- SECCIÓN DE CONTACTO CON FORMULARIO -->
    <section class="py-5 bg-light">
        
        <div class="container contact-container">
            <div class="contact" id="contact">
                <!-- SECCIÓN DE CONTACTO -->
                <h2 class="contact-tittle__s_color text-center mb-4"><strong>CONTACTE</strong></h2>
                
                <?php if(isset($_GET['success']) && $_GET['success'] == 'message_sent'): ?>
                    <!-- BOOTSTRAP DOCU: ALERTAS https://getbootstrap.com/docs/5.3/components/alerts/ -->
                    <div class="alert alert-success text-center mb-4" role="alert">
                        Missatge enviat correctament. Ens posarem en contacte amb tu prompte!
                    </div>
                <?php endif; ?>

                <!-- EL FORMULARIO ENVÍA LOS DATOS AL CONTROLADOR DE CONTACTO MEDIANTE POST -->
                <form class="contact-form" method="POST" action="../controllers/ContactController.php">
                    
                    <div class="contact-form-item mb-3">
                        <!-- BOOTSTRAP DOCU: FORM CONTROLS https://getbootstrap.com/docs/5.3/forms/form-control/ -->
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

                    <div class="contact-form-terms mb-4">
                        <!-- BOOTSTRAP DOCU: CHECKS & RADIOS https://getbootstrap.com/docs/5.3/forms/checks-radios/ -->
                        <input class="contact-form-terms-checkbox form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="contact-form-terms-label form-check-label" for="terms">Accepte les <a class="contact-form-terms-label-link" href="#">condicions del servei</a></label>
                    </div>

                    <!-- BOOTSTRAP DOCU: BUTTONS https://getbootstrap.com/docs/5.3/components/buttons/ -->
                    <button class="contact-form-button w-100" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php 
// CARGA DEL PIE DE PÁGINA GLOBAL
require_once '../includes/footer.php'; 
?>