<div class="footer flex-style">
    <?php // SCSS: CLASES .footer Y .flex-style DEFINIDAS EN SCSS PARA EL DISEÑO Y FLEXBOX ?>
    
    <div class="footer-allrights">
        <?php // SCSS: .footer-allrights__p_color DEFINE EL COLOR DESDE VARIABLES SCSS ?>
        <p class="footer-allrights__p_color">2026 Societat Musical La Tropical. Tots els drets reservats</p>
    </div>

    <?php // ENLACES LEGALES (OBLIGATORIOS POR LEY RGPD/LSSI) ?>
    <?php // BASE BOOTSTRAP: https://getbootstrap.com/docs/5.3/utilities/flex/ ?>
    <div class="footer-legal d-flex gap-3 flex-wrap justify-content-center my-2 my-md-0">
        <a href="privacy.php" class="text-decoration-none text-muted small">Política de Privacitat</a>
        <a href="cookies.php" class="text-decoration-none text-muted small">Política de Cookies</a>
        <a href="legal.php" class="text-decoration-none text-muted small">Avís Legal</a>
    </div>

    <div class="footer-icons">
        <a class="footer-icons-item" href="https://www.instagram.com/" target="_blank">
            <span><i class="bi bi-instagram"></i></span>
        </a>

        <a class="footer-icons-item" href="https://www.whatsapp.com/?lang=es" target="_blank">
            <span><i class="bi bi-whatsapp"></i></span>
        </a>
        <?php // SCSS: .footer-icons-item GESTIONA EL HOVER Y EL COLOR DESDE SCSS ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php // SCRIPT LOCAL PARA LA LOGICA DE VALIDACION DE FORMULARIOS ?>
<script src="../../js/validaciones.js"></script>

</body>
</html>