<?php
session_start();

// SI NO LOGEADO Y NO ADMIN AL LOGIN DE VUELTA
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../../config/database.php';
require_once '../../models/AdminModel.php'; 

// INSTANCIA DEL MODELO
$adminModel = new AdminModel($conn);

//EJECUTAMOS LOS MÉTODOS PARA OBTENER LOS ARRAYS
$pending_users = $adminModel->getPendingUsers();
$events = $adminModel->getAllEvents();
$approved_musicians = $adminModel->getApprovedMusicians();
$pending_payments = $adminModel->getPendingPayments();
?>

<?php require_once '../../includes/header_views.php'; ?>
<?php require_once '../../includes/navbar_views.php'; ?>

<main class="main-content page-wrapper bg-light" style="min-height: 100vh;">
    <div class="container py-5" style="max-width: 1000px;">
    
        <div class="justify-content-between align-items-center mb-4 flex-wrap gap-4">
            <h1 class="fw-bold h2 " style="margin-top: 3rem;">Tauler d'Administració</h1>
            <span class="badge bg-primary fs-6">Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>

        <?php if(isset($_GET['success']) && $_GET['success'] == 'event_created'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Perfecte!</strong> Acte creat i músics convocats correctament.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['success']) && $_GET['success'] == 'payment_updated'): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Actualitzat!</strong> El pagament s'ha registrat correctament.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="accordion shadow-sm mb-5" id="accordionAdmin">
          
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong>Músics pendents d'aprovació</strong> 
                <?php if(count($pending_users) > 0): ?>
                    <span class="badge bg-danger ms-2"><?php echo count($pending_users); ?> nous</span>
                <?php endif; ?>
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionAdmin">
              <div class="accordion-body">
                <?php if(count($pending_users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Instrument</th>
                                    <th>Acció</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pending_users as $user): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['instrument']); ?></td>
                                        <td>
                                            <form method="POST" action="../../controllers/ApproveUserController.php" class="m-0">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-success">Aprovar Músic</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary mb-0" role="alert">
                        No hi ha cap músic pendent d'aprovació en aquest moment.
                    </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <strong>Gestió d'Actes i Esdeveniments</strong>
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionAdmin">
              <div class="accordion-body">
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white fw-bold">
                        Donar d'alta un nou acte
                    </div>
                    <div class="card-body">
                        <form class="row g-3" method="POST" action="../../controllers/CreateEventController.php">
                            <div class="col-md-6">
                                <label for="title" class="form-label fw-bold">Títol de l'acte</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="Ex: Concert de Santa Cecília">
                            </div>
                            <div class="col-md-6">
                                <label for="date" class="form-label fw-bold">Data i hora de l'acte</label>
                                <input type="datetime-local" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-4">
                                <label for="meeting_time_sede" class="form-label fw-bold">Citació a la Seu</label>
                                <input type="time" class="form-control" id="meeting_time_sede" name="meeting_time_sede">
                            </div>
                            <div class="col-md-4">
                                <label for="meeting_time_lugar" class="form-label fw-bold">Citació al Lloc</label>
                                <input type="time" class="form-control" id="meeting_time_lugar" name="meeting_time_lugar">
                            </div>
                            <div class="col-md-2">
                                <label for="is_paid" class="form-label fw-bold">Remunerat?</label>
                                <select class="form-select" id="is_paid" name="is_paid">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="base_price" class="form-label fw-bold">Preu Base (€)</label>
                                <input type="number" step="0.01" class="form-control" id="base_price" name="base_price" value="0.00">
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label fw-bold">Descripció o observacions</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Uniforme complet, recollida d'instruments..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold text-primary border-bottom w-100 pb-2">Convocatòria i Despeses de Transport:</label>
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-sm table-hover border">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Convocar</th>
                                                <th>Músic / Instrument</th>
                                                <th>Porta Cotxe?</th>
                                                <th>Plus Transport (€)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($approved_musicians as $musician): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="checkbox" name="musicians[]" value="<?php echo $musician['id']; ?>">
                                                </td>
                                                <td>
                                                    <small class="fw-bold"><?php echo htmlspecialchars($musician['instrument']); ?></small><br>
                                                    <?php echo htmlspecialchars($musician['name']); ?>
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input" type="checkbox" name="has_car[<?php echo $musician['id']; ?>]" value="1">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="price_modifier[<?php echo $musician['id']; ?>]" class="form-control form-control-sm" placeholder="0.00" style="width: 80px;">
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">Selecciona els músics i indica si aporten vehicle o tenen un plus de desplaçament.</small>
                            </div>
                            <div class="col-12 text-end mt-3">
                                <button type="submit" class="btn btn-primary">Guardar Acte i Convocar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 mt-4">Pròxims Actes Programats</h5>
                <?php if(count($events) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Acte</th>
                                    <th>Citació</th>
                                    <th>Remunerat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($events as $event): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($event['date']); ?></td>
                                        <td class="fw-bold text-primary"><?php echo htmlspecialchars($event['title']); ?></td>
                                        <td>
                                            <?php if($event['meeting_time_sede']): ?>
                                                <div class="small"><strong>Seu:</strong> <?php echo htmlspecialchars($event['meeting_time_sede']); ?></div>
                                            <?php endif; ?>
                                            <?php if($event['meeting_time_lugar']): ?>
                                                <div class="small"><strong>Lloc:</strong> <?php echo htmlspecialchars($event['meeting_time_lugar']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($event['is_paid']): ?>
                                                <span class="badge bg-success"><?php echo htmlspecialchars($event['base_price']); ?> €</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        Encara no hi ha cap acte registrat al sistema.
                    </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <strong>Control de Pagaments</strong>
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionAdmin">
              <div class="accordion-body">
                <h5 class="fw-bold mb-3">Músics pendents de cobrament</h5>
                <?php if(count($pending_payments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle border">
                            <thead class="table-warning">
                                <tr>
                                    <th>Acte</th>
                                    <th>Músic</th>
                                    <th>Total a Pagar</th>
                                    <th>Acció</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pending_payments as $pay): 
                                    $total = $pay['base_price'] + $pay['price_modifier']; 
                                ?>
                                    <tr>
                                        <td>
                                            <small class="text-muted"><?php echo $pay['date']; ?></small><br>
                                            <strong><?php echo htmlspecialchars($pay['title']); ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($pay['name']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($pay['instrument']); ?></small>
                                        </td>
                                        <td class="fw-bold text-danger">
                                            <?php echo number_format($total, 2); ?> €
                                        </td>
                                        <td>
                                            <form method="POST" action="../../controllers/UpdatePaymentController.php" class="m-0">
                                                <input type="hidden" name="registration_id" value="<?php echo $pay['reg_id']; ?>">
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-success">Marcar com Pagat</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border mb-0">
                        No hi ha pagaments pendents de liquidar.
                    </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>