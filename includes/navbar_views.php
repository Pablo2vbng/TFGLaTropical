<?php
// includes/navbar_views.php

// CHULETA -> ESTE ARCHIVO NO CONSULTA TABLAS DIRECTAMENTE, PERO LEE LA SESION DEL USUARIO LOGUEADO
// COMPROBAMOS EL ROL PARA SABER A QUÉ PANEL ENVIARLO AL HACER CLIC EN EL LOGO O EN EL MENÚ
$dashboard_url = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php';
?>

<nav class="navbar navbar-expand-lg main-nav fixed-top bg-white shadow-sm">
  <?php // BASE BOOTSTRAP: https://getbootstrap.com/docs/5.3/components/navbar/ ?>
  <?php // SCSS: .main-nav APLICA TUS ESTILOS PERSONALIZADOS, .shadow-sm AÑADE UNA SOMBRA PARA SEPARAR EL MENÚ DEL CONTENIDO ?>
  
  <div class="container">
    
    <a class="navbar-brand main-nav__brand" href="<?php echo $dashboard_url; ?>">
        
        <img src="../assets/img/logo.jpg" alt="Logo La Tropical" height="40" class="d-inline-block align-text-top" style="border-radius: 8px;">
        <?php // SCSS: .main-nav__brand DEFINE EL ESTILO PROPIO DEL LOGOTIPO ?>
    </a>
    
    <button class="navbar-toggler main-nav__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavIntranet">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavIntranet">
        
        <ul class="nav main-nav__list align-items-center gap-3">
          <?php // SCSS: .main-nav__list ELIMINA LOS ESTILOS DE LISTA POR DEFECTO ?>
          
          <li class="nav-item main-nav__item">
            <a class="nav-link main-nav__link fw-bold text-primary" href="<?php echo $dashboard_url; ?>">El meu Tauler</a>
            <?php // SCSS: .main-nav__link DEFINE TUS ESTILOS BASE ?>
          </li>
          
          <li class="nav-item main-nav__item">
            <?php // ENLACE DINÁMICO AL CONTROLADOR DE LOGOUT PARA DESTRUIR LA SESIÓN ?>
            <a class="btn btn-outline-danger btn-sm px-3" href="../../controllers/LogoutController.php" onclick="return confirm('Segur que vols eixir de la sessió?');">
                Tancar Sessió
            </a>
          </li>
          
        </ul>
        
    </div>
  </div>
</nav>