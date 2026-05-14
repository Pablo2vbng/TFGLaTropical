<?php

session_start();

// CHULETA -> TABLA: events (id, title, description, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price)
// CHULETA -> TABLA: event_user (event_id, user_id, has_car, price_modifier)
// CHULETA -> TABLA: users (id, name, instrument, is_approved, role)

// SEGURIDAD: CONTROL DE ACCESO PARA USUARIOS ADMINISTRADORES
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../../config/database.php';

// VALIDACION Y RECOGIDA DEL ID DEL ACTO A EDITAR
if (!isset($_GET['id'])) die("ID d'acte no especificat");
$id = (int)$_GET['id'];

// CONSULTA PARA OBTENER LOS DATOS GENERALES DEL ACTO
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();
if (!$event) die("Acte no trobat");

// CONSULTA PARA EL LISTADO DE MUSICOS QUE PUEDEN SER CONVOCADOS
$all_musicians = $conn->query("SELECT id, name, instrument FROM users WHERE is_approved = 1 AND role = 'user' ORDER BY instrument ASC")->fetchAll();

// CONSULTA PARA SABER QUE MUSICOS YA ESTAN EN ESTE ACTO Y SUS CONDICIONES (COCHE/PLUS)
$stmt_convocados = $conn->prepare("SELECT user_id, has_car, price_modifier FROM event_user WHERE event_id = ?");
$stmt_convocados->execute([$id]);
// FETCH UNIQUE PARA TENER EL ID COMO CLAVE DEL ARRAY
$convocados_data = $stmt_convocados->fetchAll(PDO::FETCH_UNIQUE);
?>

<?php require_once '../../includes/header_views.php'; ?>
<?php require_once '../../includes/navbar_views.php'; ?>

<!-- BOOTSTRAP DOCU: CONTENEDORES https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="container py-5" style="margin-top: 80px; max-width: 900px;">
    
    <!-- BOOTSTRAP DOCU: ALERTAS https://getbootstrap.com/docs/5.3/components/alerts/ -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <strong>S'ha produït un error:</strong>
            <?php
            if ($_GET['error'] == 'empty_event_fields') echo "Tots els camps obligatoris han d'estar plens.";
            if ($_GET['error'] == 'negative_price') echo "El preu base no pot ser un valor negatiu.";
            if ($_GET['error'] == 'invalid_date') echo "No es poden crear actes en dates passades.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- BOOTSTRAP DOCU: CARDS https://getbootstrap.com/docs/5.3/components/card/ -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">
            Editar Acte i Musics: <?php echo htmlspecialchars($event['title']); ?>
        </div>

        <div class="card-body">
            <!-- BOOTSTRAP DOCU: FORMULARIOS GRID https://getbootstrap.com/docs/5.3/forms/layout/ -->
            <form method="POST" action="../../controllers/EventController.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

                <div class="row g-3">
                    <div class="col-md-8">
                        <!-- BOOTSTRAP DOCU: FORM CONTROLS https://getbootstrap.com/docs/5.3/forms/form-control/ -->
                        <label class="form-label fw-bold">Títol de l'acte</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Data i Hora</label>
                        <input type="datetime-local" name="date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($event['date'])); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Citació Seu</label>
                        <input type="time" name="meeting_time_sede" class="form-control" value="<?php echo $event['meeting_time_sede']; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Citació Lloc</label>
                        <input type="time" name="meeting_time_lugar" class="form-control" value="<?php echo $event['meeting_time_lugar']; ?>" required>
                    </div>

                    <div class="col-md-2">
                        <!-- BOOTSTRAP DOCU: SELECTS https://getbootstrap.com/docs/5.3/forms/select/ -->
                        <label class="form-label fw-bold">Remunerat?</label>
                        <select name="is_paid" class="form-select">
                            <option value="1" <?php echo ($event['is_paid'] == 1) ? 'selected' : ''; ?>>Si</option>
                            <option value="0" <?php echo ($event['is_paid'] == 0) ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Preu (€)</label>
                        <input type="number" step="0.01" name="base_price" class="form-control" value="<?php echo $event['base_price']; ?>">
                    </div>

                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold text-primary">Gestionar Músics Convocats</label>

                        <!-- CONTENEDOR CON SCROLL PERSONALIZADO PARA LISTADO LARGO -->
                        <div class="border p-3 bg-light" style="max-height: 400px; overflow-y: auto; border-radius: 8px;">
                            
                            <?php // UTILIZAMOS LA MISMA LÓGICA QUE EN LA CREACIÓN CON STRINGS DINÁMICOS PARA EL CONTROLADOR ?>
                            <?php foreach ($all_musicians as $m):
                                $uid = $m['id'];
                                $esta_convocado = isset($convocados_data[$uid]);
                                $modifier = $esta_convocado ? $convocados_data[$uid]['price_modifier'] : 0.00;
                                $tiene_coche = $esta_convocado ? $convocados_data[$uid]['has_car'] : 0;
                            ?>
                                <div class="row align-items-center mb-2 pb-2 border-bottom">
                                    <div class="col-md-5">
                                        <!-- BOOTSTRAP DOCU: CHECKS https://getbootstrap.com/docs/5.3/forms/checks-radios/ -->
                                        <div class="form-check">
                                            <!-- ARRAY SIMPLE PARA IDENTIFICAR SELECCIONADOS -->
                                            <input class="form-check-input" type="checkbox" name="musicians[]" value="<?php echo $uid; ?>" id="m_<?php echo $uid; ?>" <?php echo $esta_convocado ? 'checked' : ''; ?>>
                                            <label class="form-check-label small" for="m_<?php echo $uid; ?>">
                                                <strong><?php echo htmlspecialchars($m['instrument']); ?></strong> - <?php echo htmlspecialchars($m['name']); ?>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- BOOTSTRAP DOCU: INPUT GROUPS https://getbootstrap.com/docs/5.3/forms/input-group/ -->
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Plus €</span>
                                            <!-- NOMBRE DINÁMICO: price_modifier_ID -->
                                            <input type="number" step="0.01" name="price_modifier_<?php echo $uid; ?>" class="form-control" value="<?php echo $modifier; ?>" placeholder="+€">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <!-- NOMBRE DINÁMICO: has_car_ID -->
                                            <input class="form-check-input" type="checkbox" name="has_car_<?php echo $uid; ?>" value="1" id="car_<?php echo $uid; ?>" <?php echo $tiene_coche ? 'checked' : ''; ?>>
                                            <label class="small text-muted" for="car_<?php echo $uid; ?>">Té cotxe</label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripció</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                </div>

                <!-- BOOTSTRAP DOCU: FLEX UTILIDADES https://getbootstrap.com/docs/5.3/utilities/flex/ -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary">Cancel·lar</a>
                    <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                    <button type="submit" class="btn btn-success px-5">Actualitzar Acte i Convocatòria</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>