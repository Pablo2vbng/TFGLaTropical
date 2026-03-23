<?php session_start(); ?>
<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/navbar.php'; ?>

<main class="main-content d-flex align-items-center justify-content-center auth-wrapper">
    <?php // https://getbootstrap.com/docs/5.3/utilities/flex/ | d-flex = activa flexbox | align-items-center = centra vertical | justify-content-center = centra horizontal ?>
    
    <div class="container auth-container">
        <div class="contact" id="register">
            <h2 class="contact-tittle__s_color text-center mb-4"><strong>REGISTRAR-SE</strong></h2>
            <?php // text-center = centra el texto | mb-4 = margen inferior (margin-bottom) nivel 4 ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-custom alert-custom--danger text-center mb-4" role="alert">
                    <?php 
                        if($_GET['error'] == 'email_exists') {
                            echo "Aquest email ja està registrat. Prova a iniciar sessió.";
                        } else {
                            echo "S'ha produït un error en el registre. Torna a intentar-ho.";
                        }
                    ?>
                </div>
                <?php // https://getbootstrap.com/docs/5.3/components/alerts/ | alert = base del componente | text-center = texto centrado ?>
            <?php endif; ?>

            <form class="contact-form" method="POST" action="../controllers/RegisterController.php">
                <div class="contact-form-item mb-3">
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
                </div>
                <?php // https://getbootstrap.com/docs/5.3/forms/form-control/ | form-control = aplica estilo a los inputs (ancho 100%, bordes suaves, foco azul) | mb-3/4 = márgenes inferiores ?>
                
                <button class="contact-form-button w-100" type="submit">Crear compte</button>
                <?php // w-100 = utility class para 'width: 100%' (el botón ocupa todo el ancho) ?>
            </form>
            
            <div class="text-center mt-4">
                <a href="login.php" style="font-size: 0.9rem; font-weight: 500;">Ja tens compte? Inicia sessió ací</a>
                <?php // text-center = centra el link | mt-4 = margen superior (margin-top) nivel 4 ?>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>