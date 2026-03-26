<nav class="navbar navbar-expand-lg main-nav fixed-top">
  <?php // https://getbootstrap.com/docs/5.3/components/navbar/?>
  
  <div class="container">
    
    <a class="navbar-brand main-nav__brand" href="../index.php">
        <img src="../assets/img/logo.jpg" alt="Logo La Tropical" height="40" class="d-inline-block align-text-top" style="border-radius: 8px;">
    </a>
    
    <button class="navbar-toggler main-nav__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
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
          </li>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link text-danger" href="../../controllers/LogoutController.php">Tancar Sessió</a>
          </li>
        </ul>
        
    </div>
  </div>
</nav>