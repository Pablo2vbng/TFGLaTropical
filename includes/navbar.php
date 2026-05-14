<nav class="navbar navbar-expand-lg main-nav fixed-top">
  <?php // BOOTSTRAP: https://getbootstrap.com/docs/5.3/components/navbar/ ?>
  <?php // SCSS: .main-nav DEFINE EL FONDO TRANSLUCIDO Y EL FILTRO DE DESENFOQUE ?>
  
  <div class="container">
    <?php // BOOTSTRAP: https://getbootstrap.com/docs/5.3/layout/containers/ ?>
    
    <a class="navbar-brand main-nav__brand" href="index.php">
        <img src="assets/img/logo.jpg" alt="Logo La Tropical" height="40" class="d-inline-block align-text-top" style="border-radius: 8px;">
        <?php // SCSS: .main-nav__brand AJUSTA EL MARGEN Y EL DISENYO DEL LOGO ?>
    </a>
    
    <button class="navbar-toggler main-nav__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
      <?php // BOOTSTRAP: BOTON PARA COLAPSAR MENU EN DISPOSITIVOS MOBILES ?>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <?php // BOOTSTRAP: https://getbootstrap.com/docs/5.3/utilities/flex/ ?>
        
        <ul class="nav main-nav__list">
          <?php // SCSS: .main-nav__list GESTIONA EL ESPACIADO ENTRE ITEMS DEL MENU ?>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link active main-nav__link" aria-current="page" href="index.php">Inici</a>
            <?php // SCSS: .main-nav__link DEFINE EL COLOR DE VARIABLE --primary-text-color ?>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link" href="index.php#contact">Contacte</a>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link" href="login.php">Iniciar Sessió</a>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link disabled main-nav__link" aria-disabled="true">Intranet</a>
            <?php // BOOTSTRAP: https://getbootstrap.com/docs/5.3/components/navs-tabs/#disabled-links ?>
          </li>
        </ul>
        
    </div>
  </div>
</nav>