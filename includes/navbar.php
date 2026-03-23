<nav class="navbar navbar-expand-lg main-nav fixed-top">
  <?php // https://getbootstrap.com/docs/5.3/components/navbar/ | navbar = base del menú | navbar-expand-lg = se despliega en pantallas grandes | fixed-top = se queda pegado arriba al hacer scroll ?>
  
  <div class="container">
    <?php // container = centra el contenido del menú y le da márgenes laterales ?>
    
    <a class="navbar-brand main-nav__brand" href="index.php">
        <img src="assets/img/logo.jpg" alt="Logo La Tropical" height="40" class="d-inline-block align-text-top" style="border-radius: 8px;">
        <?php // navbar-brand = estilo para el logo/nombre | d-inline-block = alinea imagen y texto | align-text-top = alinea verticalmente arriba ?>
    </a>
    
    <button class="navbar-toggler main-nav__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      <?php // navbar-toggler = el botón "hamburguesa" para móviles | navbar-toggler-icon = dibuja las tres rayitas del icono ?>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <?php // collapse navbar-collapse = contenedor que se esconde/muestra en móviles | justify-content-end = empuja el menú a la derecha ?>
        
        <ul class="nav main-nav__list">
          <?php // nav = base de la lista de navegación ?>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link active main-nav__link" aria-current="page" href="index.php">Inici</a>
            <?php // nav-link = estilo de los enlaces del menú | active = resalta la página actual ?>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link" href="index.php#contact">Contacte</a>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link" href="login.php">Iniciar Sessió</a>
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link disabled main-nav__link" aria-disabled="true">Intranet</a>
            <?php // disabled = hace que el enlace no sea clicable y se vea gris (hasta que el usuario haga login) ?>
          </li>
        </ul>
        
    </div>
  </div>
</nav>