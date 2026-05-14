<?php 

session_start(); 
?>

<?php 
// CARGA DE LOS COMPONENTES GLOBALES DE LA INTERFAZ
require_once '../includes/header.php'; 
require_once '../includes/navbar.php'; 
?>

<!-- EL CONTENIDO PRINCIPAL UTILIZA UN MARGIN-TOP DE 80PX PARA EVITAR EL SOLAPAMIENTO CON LA NAVBAR FIXED-TOP -->
<!-- BOOTSTRAP DOCU: CONTAINERS https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="container py-5" style="margin-top: 80px; max-width: 800px; min-height: 70vh;">
    <?php // BOOTSTRAP: .container Y .py-5 CENTRAN Y DAN ESPACIADO. .max-width-800px MEJORA LA LECTURA ?>
    
    <!-- ENCABEZADO DE SECCIÓN -->
    <!-- BOOTSTRAP DOCU: TYPOGRAPHY https://getbootstrap.com/docs/5.3/content/typography/ -->
    <h1 class="fw-bold mb-4">Política de Privacitat</h1>
    
    <!-- BLOQUE INFORMATIVO CON TEXTO ATENUADO Y JUSTIFICADO PARA ASPECTO LEGAL -->
    <div class="text-muted text-justify">
        <p>D'acord amb el que estableix el Reglament (UE) 2016/679 del Parlament Europeu i del Consell (RGPD) i la Llei Orgànica 3/2018 de Protecció de Dades Personals i garantia dels drets digitals (LOPDGDD), informem els usuaris sobre la nostra política respecte al tractament i protecció de les dades de caràcter personal.</p>

        <h4 class="fw-bold mt-4">1. Responsable del tractament</h4>
        <p><strong>Identitat:</strong> Societat Musical La Tropical de Benigànim<br>
        <strong>Correu electrònic:</strong> contacte@smtropical.com</p>

        <h4 class="fw-bold mt-4">2. Finalitat del tractament</h4>
        <p>Les dades personals recollides a través dels formularis de la nostra web (com el de contacte o el de registre de músics) seran utilitzades exclusivament per a:</p>
        
        <ul>
            <li>Gestionar i respondre les consultes i missatges rebuts.</li>
            <li>Gestionar l'alta i accés a la Intranet dels músics de la Societat.</li>
            <li>Gestionar la convocatòria a actes i els pagaments associats.</li>
        </ul>

        <h4 class="fw-bold mt-4">3. Legitimació</h4>
        <p>La base legal per al tractament de les teues dades és el <strong>consentiment explícit</strong> atorgat en marcar la casella corresponent als formularis de contacte i registre.</p>

        <h4 class="fw-bold mt-4">4. Destinataris i Conservació</h4>
        <p>No se cediran dades a tercers, excepte obligació legal. Les dades es conservaran mentre es mantinga la relació amb la Societat o fins que l'usuari en sol·licite la supressió.</p>

        <h4 class="fw-bold mt-4">5. Drets de l'usuari</h4>
        <p>Pots exercir els teus drets d'accés, rectificació, supressió, oposició i limitació del tractament enviant un correu a l'adreça indicada al punt 1, adjuntant una còpia del teu DNI.</p>
    </div>
</main>

<?php 
// CARGA DEL PIE DE PÁGINA GLOBAL (CIERRE DE HTML Y SCRIPTS)
require_once '../includes/footer.php'; 
?>