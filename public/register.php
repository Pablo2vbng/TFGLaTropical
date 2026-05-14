<?php 
// INICIO DE SESIÓN PARA MANEJAR POSIBLES VARIABLES DE ESTADO DURANTE EL PROCESO DE ALTA
session_start(); 
?>

<?php // CHULETA -> TABLA: users (id, name, email, phone, instrument, password, role, is_approved) ?>

<?php 
// CARGA DE LOS COMPONENTES GLOBALES DE CABECERA Y NAVEGACIÓN
require_once '../includes/header.php'; 
require_once '../includes/navbar.php'; 
?>

<!-- CONTENEDOR PRINCIPAL: CENTRADO DE FORMULARIO MEDIANTE UTILIDADES DE FLEXBOX -->
<!-- BOOTSTRAP DOCU: FLEXBOX https://getbootstrap.com/docs/5.3/utilities/flex/ -->
<main class="main-content d-flex align-items-center justify-content-center auth-wrapper page" style="padding-top: 5rem">
    <?php // SCSS: .auth-wrapper DEFINE EL MIN-HEIGHT Y EL DISENYO DE FONDO PARA EL REGISTRO ?>

    <div class="container auth-container">
        <?php // SCSS: .auth-container LIMITA EL ANCHO PARA QUE EL FORMULARIO NO SE DEFORME EN PANTALLAS GRANDES ?>
        
        <div class="contact shadow-sm" id="register">
            <?php // AQUÍ GESTIONAMOS EL ALTA DE NUEVOS MÚSICOS. CHULETA -> TABLA: users ?>
            <h2 class="contact-tittle__s_color text-center mb-4"><strong>REGISTRAR-SE</strong></h2>
            
            <!-- SECCIÓN DE NOTIFICACIÓN DE ERRORES: VALIDACIONES DE BACKEND (EMAIL DUPLICADO, ETC.) -->
            <?php if(isset($_GET['error'])): ?>
                <!-- BOOTSTRAP DOCU: ALERTS https://getbootstrap.com/docs/5.3/components/alerts/ -->
                <div class="alert alert-custom alert-custom--danger text-center mb-4" role="alert">
                    <?php // SCSS: .alert-custom--danger APLICA EL ESTILO DE ALERTA ROJA PERSONALIZADO ?>
                    <?php 
                        if($_GET['error'] == 'email_exists') {
                            echo "Aquest email ja està registrat. Prova a iniciar sessió.";
                        } else {
                            echo "S'ha produït un error en el registre. Torna a intentar-ho.";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <!-- FORMULARIO DE REGISTRO: ENVÍA LOS DATOS AL CONTROLADOR DE REGISTRO MEDIANTE POST -->
            <form class="contact-form" method="POST" action="../controllers/RegisterController.php">
                <?php // SCSS: .contact-form GESTIONA EL DISENYO INTERNO Y ESPACIADO DEL FORMULARIO ?>
                
                <div class="contact-form-item mb-3">
                    <!-- BOOTSTRAP DOCU: FORM CONTROLS https://getbootstrap.com/docs/5.3/forms/form-control/ -->
                    <label class="contact-form-item-label" for="name">Nom complet: </label>
                    <input class="contact-form-item-input form-control" type="text" id="name" name="name" required>
                </div>
                
                <div class="contact-form-item mb-3">
                    <label class="contact-form-item-label" for="email">Email: </label>
                    <input class="contact-form-item-input form-control" type="email" id="email" name="email" required>
                </div>
                
                <div class="contact-form-item mb-3">
                    <label class="contact-form-item-label" for="phone">Telèfon: </label>
                    <input class="contact-form-item-input form-control" type="tel" id="phone" name="phone">
                </div>
                
                <div class="contact-form-item mb-3">
                    <label class="contact-form-item-label" for="instrument">Instrument: </label>
                    <input class="contact-form-item-input form-control" type="text" id="instrument" name="instrument" placeholder="Ex: Clarinet, Trompeta...">
                </div>

                <div class="contact-form-item mb-4">
                    <label class="contact-form-item-label" for="password">Contrasenya: </label>
                    <input class="contact-form-item-input form-control" type="password" id="password" name="password" required>
                    <?php // BOOTSTRAP: .mb-4 SEPARA EL CAMPO DE PASSWORD DEL SIGUIENTE BLOQUE (TERMINOS) ?>
                </div>

                <!-- SECCIÓN DE ACEPTACIÓN DE POLÍTICAS -->
                <div class="contact-form-terms mb-4">
                    <!-- BOOTSTRAP DOCU: CHECKS https://getbootstrap.com/docs/5.3/forms/checks-radios/ -->
                    <input class="contact-form-terms-checkbox form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="contact-form-terms-label form-check-label" for="terms">Accepte les <a class="contact-form-terms-label-link" href="#">condicions del servei i política de privacitat</a></label>
                    <?php // SCSS: LAS CLASES .contact-form-terms-* GESTIONAN EL COLOR DEL ENLACE Y EL ESPACIADO ?>
                </div>

                <!-- BOOTSTRAP DOCU: BUTTONS https://getbootstrap.com/docs/5.3/components/buttons/ -->
                <button class="contact-form-button w-100" type="submit">Crear compte</button>
                <?php // SCSS: .contact-form-button DEFINE COLORES CORPORATIVOS Y EFECTOS DE TRANSICIÓN ?>
            </form>
            
            <!-- ENLACE DE RETORNO AL LOGIN -->
            <div class="text-center mt-4">
                <?php // FACILITA LA NAVEGACIÓN A USUARIOS QUE YA DISPONEN DE CUENTA ?>
                <a href="login.php" style="font-size: 0.9rem; font-weight: 500;">Ja tens compte? Inicia sessió ací</a>
                <?php // BOOTSTRAP: .mt-4 APLICA MARGEN SUPERIOR PARA SEPARAR EL LINK DEL FORMULARIO ?>
            </div>
        </div>
    </div>
</main>

<!-- LÓGICA DE VALIDACIÓN EN LADO DEL CLIENTE -->
<script src="../js/validaciones.js"></script>

<?php 
// CARGA DEL PIE DE PÁGINA GLOBAL
require_once '../includes/footer.php'; 
?>