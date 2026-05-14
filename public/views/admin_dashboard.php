<?php

session_start();

 // CULETA BBDD
 // TABLA: users (id, name, email, password, role, is_approved, created_at)
 // TABLA: events (id, title, description, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price)
 // TABLA: event_user (id, event_id, user_id, has_car, is_paid, price_modifier)
 // TABLA: contact_messages (id, name, email, phone, message, created_at)
 // TABLA: audit_logs (id, action, target, user_id, created_at)
 

// SEGURIDAD: VERIFICACIÓN DE SESIÓN ACTIVA Y ROL DE ADMINISTRADOR
// SI EL USUARIO NO ES ADMIN, SE REPELE HACIA EL LOGIN
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// CONEXIÓN A LA BASE DE DATOS
require_once '../../config/database.php';

// CONSULTAS PARA ALIMENTAR LAS TABLAS DEL PANEL_ADMIN
// USUARIOS QUE HAN SOLICITADO REGISTRO PERO NO ESTÁN VALIDADOS
$pending_users = $conn->query("SELECT * FROM users WHERE is_approved = 0 AND role = 'user' ORDER BY created_at DESC")->fetchAll();

// LISTADO COMPLETO DE MÚSICOS ACTIVOS PARA LA CONVOCATORIA
$all_users = $conn->query("SELECT * FROM users WHERE is_approved = 1 AND role = 'user' ORDER BY instrument ASC")->fetchAll();

// TODOS LOS EVENTOS REGISTRADOS
$events = $conn->query("SELECT * FROM events ORDER BY date ASC")->fetchAll();

// MENSAJES RECIBIDOS DESDE EL FORMULARIO DE CONTACTO PÚBLICO
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();

// REGISTROS DE AUDITORÍA: QUIÉN HIZO QUÉ Y CUÁNDO (LIMITADO A LOS ÚLTIMOS 5)
$logs = $conn->query("SELECT al.*, u.name as admin_name 
                    FROM audit_logs al 
                    JOIN users u 
                    ON al.user_id = u.id 
                    ORDER BY al.created_at 
                    DESC LIMIT 5")->fetchAll();

// CONSULTA PARA PAGOS PENDIENTES CON UN JOIN PARA REUNIR DATOS DE VARIAS TABLAS
$stmt_pay = $conn->prepare("
    SELECT eu.id as reg_id, e.title, e.date, u.name, u.instrument, e.base_price, eu.price_modifier 
    FROM event_user eu 
    JOIN events e ON eu.event_id = e.id 
    JOIN users u ON eu.user_id = u.id 
    WHERE e.is_paid = 1 AND eu.is_paid = 0 
    ORDER BY e.date DESC
");
$stmt_pay->execute();
$pending_payments = $stmt_pay->fetchAll();

// CARGA DE CABECERA Y NAVEGACIÓN
require_once '../../includes/header_views.php'; 
require_once '../../includes/navbar_views.php'; 
?>

<!-- EL CONTENIDO PRINCIPAL TIENE UN PADDING-TOP DE 80PX PARA NO QUEDAR OCULTO BAJO LA NAVBAR FIX-TOP -->
<!-- BOOTSTRAP DOCU: GENERAL https://getbootstrap.com/docs/5.3/getting-started/introduction/ -->
<!-- BOOTSTRAP DOCU: LAYOUT/GRID https://getbootstrap.com/docs/5.3/layout/grid/ -->
<main class="bg-light" style="min-height: 100vh; padding-top: 5rem;"> 
    <div class="container py-5">

        <!-- ENCABEZADO DEL DASHBOARD -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h1 class="fw-bold h2 text-dark">Tauler d'Administració</h1>
            <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
            <span class="badge bg-primary px-3 py-2">Admin: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        </div>

        <!-- NOTIFICACIONES DE ÉXITO: SE ACTIVAN MEDIANTE PARÁMETROS GET DESDE LOS CONTROLADORES -->
        <?php if (isset($_GET['success'])): ?>
            <!-- BOOTSTRAP DOCU: ALERTAS https://getbootstrap.com/docs/5.3/components/alerts/ -->
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <strong>Operació realitzada:</strong>
                <?php
                if ($_GET['success'] == 'event_created') echo "L'acte s'ha creat i la convocatòria s'ha enviat correctament.";
                if ($_GET['success'] == 'event_updated') echo "L'acte i els músics s'han actualitzat correctament.";
                if ($_GET['success'] == 'payment_updated') echo "El pagament ha sigut registrat en el sistema.";
                if ($_GET['success'] == 'message_deleted') echo "El missatge de contacte ha sigut eliminat.";
                if ($_GET['success'] == 'approved') echo "El músic ha sigut aprovat correctament.";
                if ($_GET['success'] == 'rejected') echo "La sol·licitud ha sigut rebutjada i el músic eliminat.";
                if ($_GET['success'] == 'user_updated') echo "Perfil actualitzat correctament.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <!-- BOOTSTRAP DOCU: ALERTAS https://getbootstrap.com/docs/5.0/components/close-button/ -->
                
            </div>
        <?php endif; ?>

        <!-- NOTIFICACIONES DE ERROR -->
        <?php if (isset($_GET['error'])): ?>
            <!-- BOOTSTRAP DOCU: ALERTAS https://getbootstrap.com/docs/5.3/components/alerts/ -->
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <strong>S'ha produït un error:</strong>
                <?php
                if ($_GET['error'] == 'empty_event_fields') echo "Tots els camps obligatoris han d'estar plens.";
                if ($_GET['error'] == 'negative_price') echo "El preu base no pot ser un valor negatiu.";
                if ($_GET['error'] == 'invalid_date') echo "No es poden crear actes en dates passades.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <!-- BOOTSTRAP DOCU: ALERTAS https://getbootstrap.com/docs/5.0/components/close-button/ -->
            </div>
        <?php endif; ?>

        <!-- BOOTSTRAP DOCU: ACORDEÓN https://getbootstrap.com/docs/5.3/components/accordion/ -->
        <div class="accordion shadow-sm mb-5" id="accordionAdmin">
            
            <!-- 1. GESTIÓN DE MÚSICOS PENDIENTES -->
            <div class="accordion-item border-0 mb-2 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        <strong>Músics pendents d'aprovació</strong>
                        <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
                        <?php if (count($pending_users) > 0): ?> <span class="badge bg-primary ms-2"><?php echo count($pending_users); ?></span> <?php endif; ?>
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionAdmin">
                    <div class="accordion-body bg-white text-dark">
                        <?php if (count($pending_users) > 0): ?>
                            <!-- BOOTSTRAP DOCU: TABLAS RESPONSIVE https://getbootstrap.com/docs/5.3/content/tables/#responsive-tables -->
                            <div class="table-responsive">
                                <!-- BOOTSTRAP DOCU: TABLAS https://getbootstrap.com/docs/5.3/content/tables/ -->
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Instrument</th>
                                            <th>Accions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pending_users as $u): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($u['name']); ?></td>
                                                <td><?php echo htmlspecialchars($u['instrument']); ?></td>
                                                <td>
                                                    <form method="POST" action="../../controllers/UserController.php" class="d-inline">
                                                        <input type="hidden" name="action" value="approve">
                                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                        <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                                        <button class="btn btn-sm btn-success">Aprovar</button>
                                                    </form>
                                                    <form method="POST" action="../../controllers/UserController.php" class="d-inline" onsubmit="return confirm('Segur que vols eliminar aquesta sol·licitud?');">
                                                        <input type="hidden" name="action" value="reject">
                                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                        <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                                        <button class="btn btn-sm btn-danger">Rebutjar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0 py-2">No hi ha sol·licituds pendents.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- ALTA Y GESTIÓN DE ACTOS -->
            <!-- BOOTSTRAP DOCU: ACORDEÓN https://getbootstrap.com/docs/5.3/components/accordion/ -->
            <div class="accordion-item border-0 mb-2 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        <strong>Gestió d'Actes i Esdeveniments</strong>
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionAdmin">
                    <div class="accordion-body bg-white">
                        <h5 class="fw-bold mb-3 border-start border-primary border-4 ps-2">Alta de Nou Acte</h5>
                        <!-- BOOTSTRAP DOCU: FORMULARIOS GRID https://getbootstrap.com/docs/5.3/forms/layout/ -->   
                        <form class="row g-3 p-3 bg-light rounded border mb-4" method="POST" action="../../controllers/EventController.php">
                            <input type="hidden" name="action" value="create">

                            <div class="col-md-8">
                                <!-- BOOTSTRAP DOCU: FORM CONTROLS https://getbootstrap.com/docs/5.3/forms/form-control/ -->
                                <label class="form-label small fw-bold">Títol de l'acte</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Data i hora</label>
                                <input type="datetime-local" class="form-control" name="date" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Hora Sede</label>
                                <input type="time" class="form-control" name="meeting_time_sede">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Hora Lloc</label>
                                <input type="time" class="form-control" name="meeting_time_lugar">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Preu Base (€)</label>
                                <input type="number" step="0.01" class="form-control" name="base_price" value="0.00" min="0">
                            </div>
                            <div class="col-md-3">
                                <!-- BOOTSTRAP DOCU: SELECTS https://getbootstrap.com/docs/5.3/forms/select/ -->
                                <label class="form-label small fw-bold">Remunerat?</label>
                                <select class="form-select" name="is_paid">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Descripció / Observacions</label>
                                <textarea class="form-control" name="description" rows="2" placeholder="Ex: Uniforme complet..."></textarea>
                            </div>

                            <!-- CONVOCATORIA DE MÚSICOS DENTRO DEL ALTA DEL ACTO -->
                            <div class="col-12">
                                <label class="form-label small fw-bold">Convocatòria de Músics</label>
                                <div class="border rounded p-3 bg-white" style="max-height: 250px; overflow-y: auto;">
                                    <div class="row row-cols-1 row-cols-md-2 g-2">
                                        <?php foreach ($all_users as $m): ?>
                                            <div class="col">
                                                <!-- BOOTSTRAP DOCU: CHECKS https://getbootstrap.com/docs/5.3/forms/checks-radios/ -->
                                                <div class="form-check p-2 border rounded d-flex align-items-center gap-2">
                                                    <!-- IDENTIFICADOR DEL MÚSICO -->
                                                    <input class="form-check-input ms-0" type="checkbox" name="musicians[]" value="<?php echo $m['id']; ?>" id="m_<?php echo $m['id']; ?>">
                                                    <label class="form-check-label small flex-grow-1" for="m_<?php echo $m['id']; ?>">
                                                        <strong><?php echo $m['instrument']; ?></strong> - <?php echo $m['name']; ?>
                                                    </label>
                                                    <!-- PLUS ECONÓMICO INDIVIDUAL -->
                                                    <!-- BOOTSTRAP DOCU: INPUT GROUPS https://getbootstrap.com/docs/5.3/forms/input-group/ -->
                                                    <input type="number" step="0.01" name="price_modifier_<?php echo $m['id']; ?>" class="form-control form-control-sm w-25" placeholder="+€">
                                                    <!-- DISPONIBILIDAD DE VEHÍCULO -->
                                                    <div class="form-check m-0">
                                                        <input class="form-check-input" type="checkbox" name="has_car_<?php echo $m['id']; ?>" value="1" id="car_<?php echo $m['id']; ?>">
                                                        <label class="small text-muted" for="car_<?php echo $m['id']; ?>">Cotxe</label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end pt-2">
                                <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                <button type="submit" class="btn btn-primary">Crear Acte</button>
                            </div>
                        </form>

                        <!-- TABLA DE ACTOS PARA EDITAR O VER DETALLES -->
                        <!-- BOOTSTRAP DOCU: TABLAS https://getbootstrap.com/docs/5.3/content/tables/ -->
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-dark small">
                                    <tr>
                                        <th>Data</th>
                                        <th>Acte</th>
                                        <th>Accions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($events as $e): ?>
                                        <tr>
                                            <td><?php echo date('d/m/y', strtotime($e['date'])); ?></td>
                                            <td><?php echo htmlspecialchars($e['title']); ?></td>
                                            <td class="text-end">
                                                <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                                <a href="event_details.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-info text-white">Detalls</a>
                                                <a href="edit_event.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-warning text-dark">Editar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 3. CONTROL DE PAGOS -->
            <!-- BOOTSTRAP DOCU: ACORDEÓN https://getbootstrap.com/docs/5.3/components/accordion/ -->
            <div class="accordion-item border-0 mb-2 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                        <strong>Control de Pagaments Pendents</strong>
                        <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
                        <?php if (count($pending_payments) > 0): ?> <span class="badge bg-primary text-dark ms-2"><?php echo count($pending_payments); ?></span> <?php endif; ?>
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionAdmin">
                    <div class="accordion-body bg-white">
                        <?php if (count($pending_payments) > 0): ?>
                            <!-- BOOTSTRAP DOCU: TABLAS https://getbootstrap.com/docs/5.3/content/tables/ -->
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead class="table-warning small">
                                        <tr>
                                            <th>Acte</th>
                                            <th>Músic</th>
                                            <th>Total</th>
                                            <th>Acció</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pending_payments as $pay): $total = $pay['base_price'] + $pay['price_modifier']; ?>
                                            <tr>
                                                <td><small><?php echo date('d/m', strtotime($pay['date'])); ?></small> <?php echo $pay['title']; ?></td>
                                                <td><?php echo $pay['name']; ?></td>
                                                <td class="fw-bold text-danger"><?php echo number_format($total, 2); ?> €</td>
                                                <td>
                                                    <form method="POST" action="../../controllers/EventController.php" class="m-0">
                                                        <input type="hidden" name="action" value="update_payment">
                                                        <input type="hidden" name="registration_id" value="<?php echo $pay['reg_id']; ?>">
                                                        <input type="hidden" name="status" value="1">
                                                        <!-- BOOTSTRAP DOCU BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                                        <button type="submit" class="btn btn-sm btn-outline-success">Marcar Pagat</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">No hi ha pagaments pendents.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 4. LLISTAT DE MÚSICOS -->
            <!-- BOOTSTRAP DOCU: ACORDEÓN https://getbootstrap.com/docs/5.3/components/accordion/ -->
            <div class="accordion-item border-0 mb-2 rounded shadow-sm">
                <h2 class="accordion-header"><button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMusics"><strong>Llistat Complet de Músics</strong></button></h2>
                <div id="collapseMusics" class="accordion-collapse collapse" data-bs-parent="#accordionAdmin">
                    <div class="accordion-body bg-white">
                        <div class="table-responsive">
                             <!-- BOOTSTRAP DOCU: TABLAS https://getbootstrap.com/docs/5.3/content/tables/ -->
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light small">
                                    <tr>
                                        <th>Instrument</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Accions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_users as $u): ?>
                                        <tr>
                                            <td><small><?php echo htmlspecialchars($u['instrument']); ?></small></td>
                                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                                            <td><small><?php echo htmlspecialchars($u['email']); ?></small></td>
                                            <td class="text-end">
                                                <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                                <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. MENSAJES DE CONTACTO -->
            <!-- BOOTSTRAP DOCU: ACORDEÓN https://getbootstrap.com/docs/5.3/components/accordion/ -->
            <div class="accordion-item border-0 mb-2 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMessages">
                        <strong>Missatges de Contacte</strong>
                        <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
                        <?php if (count($messages) > 0): ?> <span class="badge bg-primary text-dark ms-2"><?php echo count($messages); ?></span> <?php endif; ?>
                    </button>
                </h2>
                <div id="collapseMessages" class="accordion-collapse collapse" data-bs-parent="#accordionAdmin">
                    <div class="accordion-body p-0 bg-white">
                        <?php if (count($messages) > 0): ?>
                            <!-- BOOTSTRAP DOCU: LIST GROUPS https://getbootstrap.com/docs/5.3/components/list-group/ -->
                            <div class="list-group list-group-flush">
                                <?php foreach ($messages as $msg): ?>
                                    <div class="list-group-item p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($msg['name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($msg['email']); ?> - <?php echo date('d/m H:i', strtotime($msg['created_at'])); ?></small>
                                            </div>
                                            <form method="POST" action="../../controllers/ContactController.php" onsubmit="return confirm('Eliminar missatge?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                                <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                                                <button class="btn btn-sm btn-outline-danger border-0">Eliminar</button>
                                            </form>
                                        </div>
                                        <div class="mt-2 p-2 bg-light rounded small italic">"<?php echo nl2br(htmlspecialchars($msg['message'])); ?>"</div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted">No hi ha missatges de contacte pendents.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN DE AUDIT LOGS: REGISTRO DE ACTIVIDAD DEL SISTEMA -->
        <!-- BOOTSTRAP DOCU: CARDS https://getbootstrap.com/docs/5.3/components/card/ -->
        <div class="card shadow-sm border-0 rounded mt-5">
            <div class="card-header bg-dark text-white py-3">ÚLTIMS MOVIMENTS DEL SISTEMA</div>
            <!-- BOOTSTRAP DOCU: LIST GROUPS https://getbootstrap.com/docs/5.3/components/list-group/ -->
            <ul class="list-group list-group-flush small">
                <?php foreach ($logs as $log): ?>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <span><strong><?php echo htmlspecialchars($log['admin_name']); ?>:</strong> <?php echo htmlspecialchars($log['action']); ?></span>
                        <span class="text-muted"><?php echo date('H:i - d/m', strtotime($log['created_at'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card-footer bg-white text-center py-2">
                <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                <a href="audit_logs.php" class="btn btn-sm btn-link text-decoration-none">Veure l'historial complet</a>
            </div>
        </div>

    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>