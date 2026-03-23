<nav class="navbar navbar-expand-lg main-nav fixed-top">
  <?php // https://getbootstrap.com/docs/5.3/components/navbar/ | navbar = base del menú | navbar-expand-lg = se despliega en PC | fixed-top = fijado arriba ?>
  
  <div class="container">
    <?php ?>
    
    <a class="navbar-brand main-nav__brand" href="../index.php">
        <img src="../assets/img/logo.jpg" alt="Logo La Tropical" height="40" class="d-inline-block align-text-top" style="border-radius: 8px;">
        <?php // navbar-brand = estilo logo ?>
    </a>
    
    <button class="navbar-toggler main-nav__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      <?php // navbar-toggler = botón hamburguesa para móvil ?>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        
        <ul class="nav main-nav__list">
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link" href="../index.php">Inici</a>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link" href="../index.php#contact">Contacte</a>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link active main-nav__link text-primary fw-bold" href="admin_dashboard.php">Intranet (Tauler)</a>
            <?php // active = indica página actual | text-primary = color azul informativo | fw-bold = texto en negrita ?>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link text-danger" href="../../controllers/LogoutController.php">Tancar Sessió</a>
            <?php // text-danger = color rojo para indicar una acción como salir o borrar ?>
          </li>
        </ul>
        
    </div>
  </div>
</nav>