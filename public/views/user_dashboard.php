<?php
// public/views/user_dashboard.php
session_start();

// CHULETA -> TABLA: users (id, name, email, instrument)
// CHULETA -> TABLA: events (id, title, description, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price)
// CHULETA -> TABLA: event_user (id, event_id, user_id, has_car, is_paid, price_modifier)

// SEGURIDAD: VERIFICACIÓN DE SESIÓN ACTIVA Y ROL DE USUARIO
// SI EL USUARIO NO ESTÁ LOGUEADO O NO TIENE ROL DE 'USER', SE REDIRIGE AL LOGIN
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// CONEXIÓN A LA BASE DE DATOS
require_once '../../config/database.php';

// CONSULTA: OBTENEMOS TODOS LOS EVENTOS DEL USUARIO MEDIANTE UN JOIN CON LA TABLA PIVOTE
$stmt = $conn->prepare("
    SELECT e.*, eu.is_paid as user_paid, eu.has_car, eu.price_modifier 
    FROM events e
    INNER JOIN event_user eu ON e.id = eu.event_id
    WHERE eu.user_id = ? 
    ORDER BY e.date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll();

// LÓGICA DE NEGOCIO: CÁLCULO DE TOTALES ECONÓMICOS PARA EL MÚSICO
$total_cobrado = 0;
$total_pendiente = 0;

foreach ($events as $event) {
    // SOLO SE SUMA SI EL EVENTO ESTÁ MARCADO COMO REMUNERADO (is_paid = 1)
    if ($event['is_paid'] == 1) {
        $importe = $event['base_price'] + $event['price_modifier'];
        if ($event['user_paid'] == 1) {
            $total_cobrado += $importe;
        } else {
            $total_pendiente += $importe;
        }
    }
}
?>

<?php 
// CARGA DE COMPONENTES DE INTERFAZ
require_once '../../includes/header_views.php'; 
require_once '../../includes/navbar_views.php'; 
?>

<!-- EL CONTENIDO PRINCIPAL TIENE UN PADDING-TOP DE 80PX PARA EVITAR SOLAPAMIENTO CON NAVBAR FIXED -->
<!-- BOOTSTRAP DOCU: LAYOUT https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="bg-light" style="min-height: 100vh; padding-top: 80px;">
   
    
    <div class="container py-5" style="max-width: 900px;">
        
        <!-- ENCABEZADO DE SECCIÓN -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold h2 mb-0">Zona del Músic</h1>
            <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
            <span class="badge bg-success fs-6">Músic: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        </div>

        <!-- BLOQUE DE ESTADÍSTICAS FINANCIERAS (CARDS INFORMATIVAS) -->
        <!-- BOOTSTRAP DOCU: GRID SYSTEM https://getbootstrap.com/docs/5.3/layout/grid/ -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <!-- BOOTSTRAP DOCU: CARDS https://getbootstrap.com/docs/5.3/components/card/ -->
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Total Cobrat</h6>
                        <h3 class="mb-0 text-success fw-bold"><?php echo number_format($total_cobrado, 2); ?> €</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Pendent de Cobrar</h6>
                        <h3 class="mb-0 text-warning fw-bold"><?php echo number_format($total_pendiente, 2); ?> €</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTADO DE CONVOCATORIAS -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">El meu historial de convocatòries</h5>
            </div>

            <div class="card-body">
            <?php // COMPROBACIÓN DE EXISTENCIA DE DATOS ?>
                <?php if(count($events) > 0): ?>
                    <!-- BOOTSTRAP DOCU: TABLES RESPONSIVE https://getbootstrap.com/docs/5.3/content/tables/#responsive-tables -->
                    <div class="table-responsive">
                        <!-- BOOTSTRAP DOCU: TABLES https://getbootstrap.com/docs/5.3/content/tables/ -->
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Acte</th>
                                    <th>Import</th> 
                                    <th>Estat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($events as $event): 
                                    $total_musico = $event['base_price'] + $event['price_modifier'];
                                ?>
                                    <tr>
                                        <td><strong><?php echo date('d/m/Y', strtotime($event['date'])); ?></strong></td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></div>
                                            <small class="text-muted d-block">
                                                Sede: <?php echo $event['meeting_time_sede'] ?: '--:--'; ?> | Lloc: <?php echo $event['meeting_time_lugar'] ?: '--:--'; ?>
                                            </small>
                                            
                                            <?php // DESCRIPCIÓN ACTO: NOTAS ADICIONALES DEL ADMINISTRADOR ?>
                                            <?php if(!empty($event['description'])): ?>
                                                <div class="mt-2 p-2 bg-light rounded border-start border-primary border-3 small">
                                                    <strong>Notes de l'acte:</strong><br>
                                                    <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php // INDICADOR DE VEHÍCULO SI EL USUARIO PONE SU COCHE ?>
                                            <?php if($event['has_car']): ?>
                                                <span class="badge bg-info text-dark mt-2" style="font-size: 0.7rem;">Vehicle Propi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            <?php if($event['is_paid'] == 1): ?>
                                                <?php echo number_format($total_musico, 2); ?> €
                                            <?php else: ?>
                                                <span class="text-muted small">Voluntari</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php // GESTIÓN VISUAL DE ESTADOS DE PAGO MEDIANTE PILLS ?>
                                            <?php if($event['is_paid'] == 1): ?>
                                                <?php if($event['user_paid'] == 1): ?>
                                                    <span class="badge rounded-pill bg-success px-3">COBRAT</span>
                                                <?php else: ?>
                                                    <span class="badge rounded-pill bg-warning text-dark px-3">PENDENT</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary px-3">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- ESTADO VACÍO SI NO HAY REGISTROS -->
                    <div class="text-center py-5">
                        <p class="text-center text-muted mt-3 mb-0">No tens actes a l'historial.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php 
// CARGA DEL PIE DE PÁGINA Y SCRIPTS
require_once '../../includes/footer.php'; 
?>