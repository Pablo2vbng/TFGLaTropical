<?php 
// INICIO DE SESIÓN PARA MANEJAR LA AUTENTICACIÓN Y MENSAJES DE ESTADO
session_start(); 
?>

<?php // CHULETA -> TABLA: users (id, email, password, is_approved, role) ?>

<?php 
// CARGA DE COMPONENTES GLOBALES DE LA INTERFAZ
require_once '../includes/header.php'; 
require_once '../includes/navbar.php'; 
?>

<!-- CONTENEDOR PRINCIPAL: UTILIZA FLEXBOX PARA CENTRAR EL FORMULARIO VERTICAL Y HORIZONTALMENTE -->
<!-- BOOTSTRAP DOCU: FLEX https://getbootstrap.com/docs/5.3/utilities/flex/ -->
<main class="main-content d-flex align-items-center justify-content-center auth-wrapper" style="padding-top: 5rem">
    <?php // SCSS: .auth-wrapper DEFINE LA ALTURA MÍNIMA (MIN-VH-100) Y EL DISEÑO DE FONDO ?>
    
    <div class="container auth-container">
        <?php // SCSS: .auth-container LIMITA EL ANCHO MÁXIMO PARA UN ASPECTO DE TARJETA ESTRECHA ?>
        
        <div class="contact shadow-sm" id="login">
            <?php // AQUÍ GESTIONAMOS EL ACCESO DE USUARIOS REGISTRADOS ?>
            <h2 class="contact-tittle__s_color text-center mb-4"><strong>INICIAR SESSIÓ</strong></h2>
            
            <!-- SECCIÓN DE ALERTAS DE ERROR: SE ACTIVAN MEDIANTE PARÁMETROS GET DESDE EL CONTROLADOR -->
            <?php if(isset($_GET['error'])): ?>
                <!-- BOOTSTRAP DOCU: ALERTS https://getbootstrap.com/docs/5.3/components/alerts/ -->
                <div class="alert alert-custom alert-custom--danger text-center mb-4" role="alert">
                    <?php // SCSS: .alert-custom--danger DEFINE EL ESTILO CORPORATIVO PARA ERRORES ?>
                    <?php 
                        if($_GET['error'] == 'invalid_credentials') {
                            echo "Credencials incorrectes. Torna a intentar-ho.";
                        } elseif($_GET['error'] == 'no_approved') {
                            echo "El teu compte encara no ha estat aprovat per l'administrador. Espera a que et donen accés.";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <!-- SECCIÓN DE ALERTA DE ÉXITO: MENSAJE TRAS REGISTRO CORRECTO PENDIENTE DE APROBACIÓN -->
            <?php if(isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
                <div class="alert text-center mb-4" role="alert" style="border-radius: 12px; font-size: 0.9rem; font-weight: 500; background-color: #e8fce8; border-color: #e8fce8; color: #008000;">
                    Registre completat! Espera a que l'administrador aprove el teu compte per poder entrar.
                </div>
                <?php // BOOTSTRAP: .alert COMPONENTE DE NOTIFICACIÓN DE ÉXITO ?>
            <?php endif; ?>

            <!-- FORMULARIO DE ACCESO: ENVÍA DATOS AL CONTROLADOR DE AUTENTICACIÓN -->
            <form class="contact-form" method="POST" action="../controllers/AuthController.php">
                <?php // SCSS: .contact-form GESTIONA EL LAYOUT INTERNO DEL FORMULARIO ?>
                
                <div class="contact-form-item mb-3">
                    <!-- BOOTSTRAP DOCU: FORM CONTROLS https://getbootstrap.com/docs/5.3/forms/form-control/ -->
                    <label class="contact-form-item-label" for="email">Email: </label>
                    <input class="contact-form-item-input form-control" type="email" id="email" name="email" required>
                </div>
                
                <div class="contact-form-item mb-4">
                    <label class="contact-form-item-label" for="password">Contrasenya: </label>
                    <input class="contact-form-item-input form-control" type="password" id="password" name="password" required>
                </div>

                <!-- BOOTSTRAP DOCU: BUTTONS https://getbootstrap.com/docs/5.3/components/buttons/ -->
                <button class="contact-form-button w-100" type="submit">Entrar</button>
                <?php // SCSS: .contact-form-button DEFINE LOS COLORES Y TRANSICIONES DEL BOTÓN ?>
                <?php // BOOTSTRAP: .w-100 UTILIDAD PARA ANCHO COMPLETO ?>
            </form>

            <!-- NAVEGACIÓN ADICIONAL -->
            <div class="text-center mt-4">
                <?php // ENLACE HACIA EL REGISTRO PARA NUEVOS USUARIOS ?>
                <a href="register.php" style="font-size: 0.9rem; font-weight: 500;">No tens compte? Registra't ací</a>
                <?php // BOOTSTRAP: .mt-4 APLICA UN MARGEN SUPERIOR PARA SEPARAR DEL BOTÓN DE ACCIÓN ?>
            </div>
        </div>
    </div>
</main>

<?php 
// CARGA DEL PIE DE PÁGINA GLOBAL
require_once '../includes/footer.php'; 
?>