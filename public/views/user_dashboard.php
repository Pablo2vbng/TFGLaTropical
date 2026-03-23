<?php
session_start();

// SI NO ESTÁ LOGUEADO O NO ES USER LO SACAMOS AL LOGIN
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

require_once '../../config/database.php';

// CHULETA -> TABLA: users (id, name, email, instrument)
// CHULETA -> TABLA: events (id, title, description, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price)
// CHULETA -> TABLA: event_user (id, event_id, user_id, has_car, is_paid, price_modifier)

// CONSULTA PARA SABER ACTO, MÚSICO Y PRECIO
// LOGICA SI EL PAGO ESTÁ HECHO DESAPARECE Y SI LA FECHA ES INFERIOR A HOY
$stmt = $conn->prepare("
    SELECT e.*, eu.is_paid as user_paid, eu.has_car, eu.price_modifier 
    FROM events e
    INNER JOIN event_user eu ON e.id = eu.event_id
    WHERE eu.user_id = ? 
    AND (e.date >= CURDATE() OR eu.is_paid = 0)
    ORDER BY e.date ASC
");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll();
?>

<?php require_once '../../includes/header_views.php'; ?>
<?php require_once '../../includes/navbar_views.php'; ?>

<main class="main-content page-wrapper bg-light" style="min-height: 100vh;">
    <div class="container py-5" style="max-width: 900px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold h2 mb-0" style="margin-top:3rem;">Zona del Músic</h1>
            <span class="badge bg-success fs-6">Músic: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <?php // https://getbootstrap.com/docs/5.3/components/badge/ | bg-success = color verde para diferenciarlo del azul del admin ?>
        </div>

        <div class="card shadow-sm border-0">
            <?php // https://getbootstrap.com/docs/5.3/components/card/ | shadow-sm = sombra suave | border-0 = sin bordes ?>
            
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">Les meues convocatòries</h5>
            </div>

            <div class="card-body">
                <?php // USAMOS EL METODO COUNT PARA VER LOS EVENTOS ?>
                <?php if(count($events) > 0): ?>
                    <div class="table-responsive">
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
                                    // CALCULAMOS EL TOTAL INDIVIDUAL
                                    $total_musico = $event['base_price'] + $event['price_modifier'];
                                ?>
                                    <tr>
                                        <td><strong><?php echo $event['date']; ?></strong></td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></div>
                                            <small class="text-muted d-block">
                                                S: <?php echo $event['meeting_time_sede']; ?> | LL: <?php echo $event['meeting_time_lugar']; ?>
                                            </small>
                                            <?php if($event['has_car']): ?>
                                                <span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                    <i class="bi bi-car-front"></i> Vehicle Propi
                                                </span>
                                                <?php // badge = pastilla informativa | bg-info = azul claro | bi-car-front = icono coche ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            <?php echo number_format($total_musico, 2); ?> €
                                            <?php // BASE + PLUS TRANSPORTE EN SU CASO | number_format = 2 decimales ?>
                                        </td>
                                        <td>
                                            <?php if($event['user_paid'] == 1): ?>
                                                <span class="badge rounded-pill bg-success px-3">COBRAT</span>
                                                <?php // bg-success = color verde (finalizado) ?>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-warning text-dark px-3">PENDENT</span>
                                                <?php // bg-warning = color amarillo (atención) | text-dark = texto negro ?>
                                            <?php endif; ?>
                                            <?php // rounded-pill = forma de cápsula redondeada ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php // https://getbootstrap.com/docs/5.3/content/tables/ | table-hover = resalta fila | align-middle = centra verticalmente ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                        <p class="text-center text-muted mt-3 mb-0">No tens actes pendents ni per cobrar.</p>
                        <?php // fs-1 = tamaño icono grande | text-muted = color gris suave ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>