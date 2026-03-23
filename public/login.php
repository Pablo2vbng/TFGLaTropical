<?php session_start(); ?>
<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/navbar.php'; ?>

<main class="main-content d-flex align-items-center justify-content-center auth-wrapper">
    <?php // https://getbootstrap.com/docs/5.3/utilities/flex/ | d-flex = activa flexbox | align-items-center = centra vertical | justify-content-center = centra horizontal ?>
    
    <div class="container auth-container">
        <div class="contact" id="login">
            <h2 class="contact-tittle__s_color text-center mb-4"><strong>INICIAR SESSIÓ</strong></h2>
            <?php // text-center = centra el texto | mb-4 = margen inferior (margin-bottom) nivel 4 ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-custom alert-custom--danger text-center mb-4" role="alert">
                    <?php 
                        if($_GET['error'] == 'invalid_credentials') {
                            echo "Credencials incorrectes. Torna a intentar-ho.";
                        } elseif($_GET['error'] == 'no_approved') {
                            echo "El teu compte encara no ha estat aprovat per l'administrador. Espera a que et donen accés.";
                        }
                    ?>
                </div>
                <?php // https://getbootstrap.com/docs/5.3/components/alerts/ | alert = base del componente | role="alert" = accesibilidad ?>
            <?php endif; ?>

            <?php if(isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
                <div class="alert text-center mb-4" role="alert" style="border-radius: 12px; font-size: 0.9rem; font-weight: 500; background-color: #e8fce8; border-color: #e8fce8; color: #008000;">
                    Registre completat! Espera a que l'administrador aprove el teu compte per poder entrar.
                </div>
                <?php // Aquí usamos 'alert' como base y estilos manuales para un diseño personalizado (color verde éxito) ?>
            <?php endif; ?>

            <form class="contact-form" method="POST" action="../controllers/AuthController.php">
                <div class="contact-form-item mb-3">
                    <label class="contact-form-item-label" for="email">Email: </label>
                    <input class="contact-form-item-input form-control" type="email" id="email" name="email" required>
                </div>
                
                <div class="contact-form-item mb-4">
                    <label class="contact-form-item-label" for="password">Contrasenya: </label>
                    <input class="contact-form-item-input form-control" type="password" id="password" name="password" required>
                </div>
                <?php // https://getbootstrap.com/docs/5.3/forms/form-control/ | form-control = aplica el estilo Bootstrap a los inputs (bordes suaves, ancho 100%, foco azul) ?>

                <button class="contact-form-button w-100" type="submit">Entrar</button>
                <?php // w-100 = utility class para 'width: 100%' (que el botón ocupe todo el ancho) ?>
            </form>

            <div class="text-center mt-4">
                <a href="register.php" style="font-size: 0.9rem; font-weight: 500;">No tens compte? Registra't ací</a>
                <?php // mt-4 = margen superior (margin-top) nivel 4 para separar el enlace del formulario ?>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>