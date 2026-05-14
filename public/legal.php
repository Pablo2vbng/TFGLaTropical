<?php 
// INICIO DE SESIÓN PARA GESTIONAR EL ESTADO DEL USUARIO EN LA NAVEGACIÓN PÚBLICA
session_start(); 
?>

<?php // CHULETA -> ESTA PÁGINA ES ESTÁTICA Y NO REQUIERE CONEXIÓN A BASE DE DATOS ?>

<?php 
// CARGA DE COMPONENTES GLOBALES DE LA INTERFAZ (CABECERA Y BARRA DE NAVEGACIÓN)
require_once '../includes/header.php'; 
require_once '../includes/navbar.php'; 
?>

<!-- EL CONTENIDO PRINCIPAL UTILIZA UN MARGIN-TOP DE 80PX PARA EVITAR EL SOLAPAMIENTO CON LA NAVBAR FIXED -->
<!-- BOOTSTRAP DOCU: LAYOUT/CONTAINERS https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="container py-5" style="margin-top: 80px; max-width: 800px; min-height: 70vh;">
    <?php // BOOTSTRAP: .mb-4 CREA MARGEN INFERIOR BAJO EL TÍTULO PARA SEPARAR EL ENCABEZADO ?>
    
    <!-- ENCABEZADO DE SECCIÓN -->
    <!-- BOOTSTRAP DOCU: TYPOGRAPHY https://getbootstrap.com/docs/5.3/content/typography/ -->
    <h1 class="fw-bold mb-4">Avís Legal</h1>
    
    <!-- BLOQUE INFORMATIVO CON ESTILOS DE TEXTO ATENUADO Y JUSTIFICADO -->
    <div class="text-muted text-justify">
        <p>En compliment de l'article 10 de la Llei 34/2002, d'11 de juliol, de Serveis de la Societat de la Informació i Comerç Electrònic (LSSI-CE), s'informa els usuaris de les dades identificatives del titular d'aquest lloc web:</p>

        <!-- DATOS IDENTIFICATIVOS: LISTA CON MARGEN SUPERIOR -->
        <!-- BOOTSTRAP DOCU: LISTS https://getbootstrap.com/docs/5.3/content/typography/#lists -->
        <ul class="mt-3">
            <li><strong>Denominació Social:</strong> Societat Musical La Tropical</li>
            <li><strong>Domicili Social:</strong> [Avda Camino de Albaida,,  Benigànim]</li>
            <li><strong>NIF:</strong> [G-XXXXXXXX]</li>
            <li><strong>Correu electrònic:</strong> contacte@smtropical.com</li>
        </ul>

        <h4 class="fw-bold mt-4">Condicions d'ús</h4>
        <p>L'accés i ús d'aquest lloc web atribueix la condició d'Usuari, la qual cosa implica l'acceptació plena i sense reserves de totes i cadascuna de les disposicions incloses en aquest Avís Legal. L'usuari es compromet a fer un ús adequat i lícit del lloc web i dels seus continguts, d'acord amb la legislació aplicable, la bona fe i l'ordre públic.</p>

        <h4 class="fw-bold mt-4">Propietat Intel·lectual i Industrial</h4>
        <p>Tots els continguts del lloc web, incloent, a títol enunciatiu i no limitatiu, textos, fotografies, gràfics, imatges, icones, tecnologia, programari, així com el seu disseny gràfic i codis font, pertanyen a la Societat Musical La Tropical o a tercers que n'han autoritzat l'ús. Queda expressament prohibida la reproducció, distribució i comunicació pública de tot o part dels continguts sense l'autorització expressa del titular.</p>

        <h4 class="fw-bold mt-4">Exclusió de responsabilitat</h4>
        <p>La Societat no es fa responsable dels danys i perjudicis de qualsevol naturalesa que puguen derivar-se de la falta de disponibilitat o de continuïtat del funcionament del lloc web, ni dels errors tècnics, virus informàtics o qualsevol altra incidència fora del seu control.</p>
    </div>
</main>

<?php 
// CARGA DEL PIE DE PÁGINA (CIERRE DE ESTRUCTURA HTML Y SCRIPTS)
require_once '../includes/footer.php'; 
?>