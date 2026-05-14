<?php 
// INICIO DE SESIÓN PARA MANTENER EL ESTADO DEL USUARIO SI ESTÁ NAVEGANDO POR LA WEB PÚBLICA
session_start(); 
?>

<?php 
// CARGA DE COMPONENTES GLOBALES DE LA INTERFAZ
require_once '../includes/header.php'; 
require_once '../includes/navbar.php'; 
?>

<!-- EL CONTENIDO PRINCIPAL UTILIZA UN MARGIN-TOP DE 80PX PARA EVITAR EL SOLAPAMIENTO CON LA NAVBAR FIXED -->
<!-- BOOTSTRAP DOCU: CONTAINERS https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="container py-5" style="margin-top: 80px; max-width: 800px; min-height: 70vh;">
    <?php // BOOTSTRAP: .text-muted APLICA UN COLOR GRIS PARA FACILITAR LA LECTURA DE TEXTOS LARGOS ?>
    
    <!-- ENCABEZADO PRINCIPAL -->
    <!-- BOOTSTRAP DOCU: TYPOGRAPHY https://getbootstrap.com/docs/5.3/content/typography/ -->
    <h1 class="fw-bold mb-4">Política de Cookies</h1>
    
    <!-- CUERPO DE TEXTO INFORMATIVO -->
    <div class="text-muted text-justify">
        <p>Aquesta pàgina web utilitza cookies per millorar l'experiència de l'usuari i garantir el correcte funcionament de la Intranet. A continuació, t'expliquem què són, quines utilitzem i com pots gestionar-les.</p>

        <h4 class="fw-bold mt-4">1. Què són les cookies?</h4>
        <p>Una cookie és un xicotet arxiu de text que s'emmagatzema al teu navegador quan visites una pàgina web. La seua utilitat és recordar la teua visita quan tornes a navegar per eixa pàgina.</p>

        <h4 class="fw-bold mt-4">2. Quines cookies utilitza aquesta web?</h4>
        <p>Aquesta web utilitza únicament <strong>cookies tècniques i estrictament necessàries</strong>. En concret, utilitzem cookies de sessió (PHPSESSID) proporcionades pel mateix llenguatge de programació (PHP) que permeten:</p>
        
        <ul>
            <li>Mantenir la teua sessió oberta quan inicies sessió a la Intranet.</li>
            <li>Verificar els teus permisos (Músic o Administrador) per motius de seguretat.</li>
        </ul>
        <p><strong>Aquesta web NO utilitza cookies de tercers</strong>, cookies publicitàries, ni eines de seguiment de comportament com Google Analytics.</p>

        <h4 class="fw-bold mt-4">3. Consentiment</h4>
        <p>Atés que les úniques cookies que utilitzem són estrictament necessàries per al funcionament tècnic de la Intranet i la gestió de sessions d'usuaris, la normativa actual (LSSI-CE) no exigeix el consentiment exprés per a la seua instal·lació.</p>

        <h4 class="fw-bold mt-4">4. Com desactivar les cookies</h4>
        <p>Pots restringir, bloquejar o esborrar les cookies des del teu navegador. Tingues en compte que, si desactives les cookies tècniques, <strong>no podràs iniciar sessió ni accedir a la Intranet</strong>. Consulta l'ajuda del teu navegador (Chrome, Firefox, Safari, Edge) per a més detalls.</p>
    </div>
</main>

<?php 
// CARGA DEL PIE DE PÁGINA (CIERRE DE ESTRUCTURA Y SCRIPTS)
require_once '../includes/footer.php'; 
?>