<?php

session_start();

// CHULETA -> TABLA: events (id, title, date, meeting_time_sede, meeting_time_lugar, base_price, description)
// CHULETA -> TABLA: event_user (event_id, user_id, has_car, price_modifier)
// CHULETA -> TABLA: users (id, name, instrument)

// SEGURIDAD: SOLO EL ADMIN PUEDE VER LOS DETALLES DE CONVOCATORIA
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../../config/database.php';

// COMPROBAMOS QUE LLEGUE EL ID DEL ACTO POR URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$event_id = (int)$_GET['id'];

try {
    // 1. CONSULTA PARA OBTENER LOS DATOS DEL EVENTO ESPECIFICO
    $stmt_event = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt_event->execute([$event_id]);
    $event = $stmt_event->fetch();

    if (!$event) {
        die("L'acte no existeix.");
    }

    // 2. CONSULTA PARA LISTAR LOS MUSICOS CONVOCADOS UNIENDO LAS TABLAS EVENT_USER Y USERS
    $stmt_musicians = $conn->prepare("
        SELECT u.name, u.instrument, eu.has_car, eu.price_modifier, eu.is_paid as status_pay, e.base_price as base_price
        FROM event_user eu
        JOIN users u ON eu.user_id = u.id
        JOIN events e ON e.id = eu.event_id
        WHERE eu.event_id = ?
        ORDER BY u.instrument ASC
    ");
    $stmt_musicians->execute([$event_id]);
    $musicians = $stmt_musicians->fetchAll();

} catch (PDOException $e) {
    die("Error en la base de dades: " . $e->getMessage());
}
?>

<?php require_once '../../includes/header_views.php'; ?>
<?php require_once '../../includes/navbar_views.php'; ?>

<!-- BOOTSTRAP DOCU: GENERAL https://getbootstrap.com/docs/5.3/getting-started/introduction/ -->
<main class="main-content page-wrapper bg-light" style="min-height: 100vh;">
    <!-- BOOTSTRAP DOCU: CONTENEDORES https://getbootstrap.com/docs/5.3/layout/containers/ -->
    <div class="container py-5" style="max-width: 900px;">
        
        <!-- BOOTSTRAP DOCU: UTILIDADES FLEXBOX https://getbootstrap.com/docs/5.3/utilities/flex/ -->
        <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 3rem;">
            <?php // DIV CABECERA: MUESTRA EL NOMBRE DEL ACTO Y LA FECHA/HORA SELECCIONADA ?>
            <div>
                <h1 class="fw-bold h2 mb-1"><?php echo htmlspecialchars($event['title']); ?></h1>
                <p class="text-muted mb-0">Data: <?php echo date('d/m/Y', strtotime($event['date'])); ?> a les <?php echo date('H:i', strtotime($event['date'])); ?>h</p>
            </div>
            <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
            <a href="admin_dashboard.php" class="btn btn-outline-secondary">Tornar al Tauler</a>
            <?php // BOOTSTRAP: .btn-outline-secondary CREA EL BOTON CON BORDE GRIS SIN FONDO ?>
        </div>

        <!-- BOOTSTRAP DOCU: GRID SYSTEM https://getbootstrap.com/docs/5.3/layout/grid/ -->
        <div class="row g-4">
            <?php // BOOTSTRAP: https://getbootstrap.com/docs/5.3/layout/gutters/ (ESPACIO ENTRE COLUMNAS) ?>
            
            <div class="col-md-4">
                <?php // CHULETA -> TABLA: events ?>
                <!-- BOOTSTRAP DOCU: CARDS https://getbootstrap.com/docs/5.3/components/card/ -->
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-primary">Informació de l'acte</div>
                    <div class="card-body">
                        <p class="small mb-2"><strong>Sede:</strong> <?php echo $event['meeting_time_sede'] ?: '--:--'; ?></p>
                        <p class="small mb-2"><strong>Lloc:</strong> <?php echo $event['meeting_time_lugar'] ?: '--:--'; ?></p>
                        <p class="small mb-2"><strong>Preu Base:</strong> <?php echo number_format($event['base_price'], 2); ?> €</p>
                        <hr>
                        <p class="small text-muted italic"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <?php // AQUÍ VEMOS LA LISTA DE PERSONAS CONVOCADAS. CHULETA -> TABLA: event_user ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Músics Convocats</span>
                        <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
                        <span class="badge bg-primary"><?php echo count($musicians); ?> persones</span>
                    </div>
                    <div class="card-body p-0">
                        <?php // BOOTSTRAP: .p-0 ELIMINA EL PADDING PARA QUE LA TABLA OCUPE TODO EL ANCHO ?>
                        <!-- BOOTSTRAP DOCU: TABLAS RESPONSIVE https://getbootstrap.com/docs/5.3/content/tables/#responsive-tables -->
                        <div class="table-responsive">
                            <!-- BOOTSTRAP DOCU: ESTILOS DE TABLAS https://getbootstrap.com/docs/5.3/content/tables/ -->
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Músic / Instrument</th>
                                        <th class="text-center">Vehicle</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($musicians as $m): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($m['name']); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($m['instrument']); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($m['has_car']): ?>
                                                <span class="badge bg-success">Si</span>
                                            <?php else: ?>
                                                <span class="text-muted">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-bold">
                                            <?php echo number_format($m['price_modifier'] + $m['base_price'], 2); ?> €
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>