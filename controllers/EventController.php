<?php
// controllers/EventController.php
session_start();
require_once '../config/database.php';

// CHULETA -> TABLA: events (id, title, description, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price)
// CHULETA -> TABLA: event_user (id, event_id, user_id, has_car, is_paid, price_modifier)
// CHULETA -> TABLA: audit_logs (id, action, target, user_id)

// COMPROBACIÓN DE SEGURIDAD. SI NO ES ADMIN NO PASA
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECOGEMOS LA ACCIÓN SEGÚN DE DONDE VENGA EL POST
    $action = $_POST['action'] ?? '';

    try {
        //INICIAMOS TRANSACCIÓN PARA NO DEJAR NADA A MEDIAS... SINO HARÁ UN ROLLBACK
        $conn->beginTransaction();

        //CREACIÓN DE NUEVO ACTO
        if ($action === 'create') {

            // HTMLSPECIALCHARS PARA EVITAR INYECCIONES SCRIPT, TRIM PARA QUITAR ESPACIONS
            $title = htmlspecialchars(trim($_POST['title'] ?? ''));
            $date = trim($_POST['date'] ?? '');
            $is_paid = (int)($_POST['is_paid'] ?? 0);
            $base_price = (float)($_POST['base_price'] ?? 0);
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            $meeting_sede = $_POST['meeting_time_sede'] ?? '';
            $meeting_lugar = $_POST['meeting_time_lugar'] ?? '';

            // VALIDACIONES BACKEND
            if (empty($title) || empty($date) || empty($meeting_sede) || empty($meeting_lugar)) {
                header("Location: ../public/views/admin_dashboard.php?error=empty_event_fields");
                exit();
            }
            if ($base_price < 0) {
                header("Location: ../public/views/admin_dashboard.php?error=negative_price");
                exit();
            }
            if (strtotime($date) < strtotime(date('Y-m-d'))) {
                header("Location: ../public/views/admin_dashboard.php?error=invalid_date");
                exit();
            }

            // INSERTAMOS EN LA TABLA EVENTS
            $sql = "INSERT INTO events (title, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $conn->prepare($sql)->execute([$title, $date, $meeting_sede, $meeting_lugar, $is_paid, $base_price, $description]);

            // GUARDAMOS EL ID DEL EVENTO... NOS VENDRÁ BIEN DESPUÉS
            $event_id = $conn->lastInsertId();

            // CONVOCATIORIA DE MÚSICOS Y ENVIAMOS A TABLA EVENT_USER
            if (!empty($_POST['musicians'])) {
                $stmt_user = $conn->prepare("INSERT INTO event_user (event_id, user_id, has_car, is_paid, price_modifier) VALUES (?, ?, ?, 0, ?)");

                // RECOGEMOS LOS MÚSICOS UNO POR UNO
                foreach ($_POST['musicians'] as $u_id) {

                    //VARIABLES EN LAS QUE CONCATENAMOS UN ARRAY PARA IDENTIFICAR LOS PLUSES
                    $nombre_campo_coche = 'has_car_' . $u_id;
                    $nombre_campo_plus = 'price_modifier_' . $u_id;

                    // BUSCAMOS EN EL FORMULARIO POST
                    $car = isset($_POST[$nombre_campo_coche]) ? 1 : 0;
                    $extra = !empty($_POST[$nombre_campo_plus]) ? (float)$_POST[$nombre_campo_plus] : 0.00;

                    // INSERTAMOS EN LA BBDD
                    $stmt_user->execute([$event_id, $u_id, $car, $extra]);
                }
            }

            // GUARDAMOS EN REGISTRO DE LOGS
            $log_text = "Nou acte creat: $title";
            $conn->prepare("INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'events', ?)")
                ->execute([$log_text, $_SESSION['user_id']]);

            $msg = "event_created";
        }

        //EDITAR ACTO
        elseif ($action === 'update') {

            $event_id = (int)$_POST['event_id'];
            $title = htmlspecialchars(trim($_POST['title'] ?? ''));
            $date = trim($_POST['date'] ?? '');
            $is_paid = (int)($_POST['is_paid'] ?? 0);
            $base_price = (float)($_POST['base_price'] ?? 0);
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            $meeting_sede = $_POST['meeting_time_sede'] ?? '';
            $meeting_lugar = $_POST['meeting_time_lugar'] ?? '';

            // VALIDACIONES BACKEND
            if (empty($title) || empty($date) || empty($meeting_sede) || empty($meeting_lugar)) {
                header("Location: ../public/views/edit_vent.php?error=empty_event_fields");
                exit();
            }

            if ($base_price < 0) {
                header("Location: ../public/views/edit_vent.php?error=negative_price");
                exit();
            }
            if (strtotime($date) < strtotime(date('Y-m-d'))) {
                header("Location: ../public/views/edit_vent.php?error=invalid_date");
                exit();
            }

            // ACTUALIZAMOS LOS DATOS DEL EVENTO
            $sql = "UPDATE events SET title = ?, date = ?, meeting_time_sede = ?, meeting_time_lugar = ?, is_paid = ?, base_price = ?, description = ? WHERE id = ?";
            $conn->prepare($sql)->execute([$title, $date, $meeting_sede, $meeting_lugar, $is_paid, $base_price, $description, $event_id]);

            //BORRAMOS TODOS LOS MÚSICOS PARA CREAR EL EVENTO DE NUEVO (ME DABA FALLOS SI NO LO HACÍA ASÍ...)
            $conn->prepare("DELETE FROM event_user WHERE event_id = ?")->execute([$event_id]);

            if (!empty($_POST['musicians'])) {
                $stmt_user = $conn->prepare("INSERT INTO event_user (event_id, user_id, has_car, is_paid, price_modifier) VALUES (?, ?, ?, 0, ?)");

                foreach ($_POST['musicians'] as $u_id) {

                    // LÓGICA DE NOMBRES DINÁMICOS COMO AL CREAR EL ACTO
                    $nombre_campo_coche = 'has_car_' . $u_id;
                    $nombre_campo_plus = 'price_modifier_' . $u_id;

                    $car = isset($_POST[$nombre_campo_coche]) ? 1 : 0;
                    $extra = !empty($_POST[$nombre_campo_plus]) ? (float)$_POST[$nombre_campo_plus] : 0.00;

                    $stmt_user->execute([$event_id, $u_id, $car, $extra]);
                }
            }

            // GUARDAMOS EN EL LOG
            $log_text = "Acte i musics actualitzats: $title";
            $conn->prepare("INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'events', ?)")
                ->execute([$log_text, $_SESSION['user_id']]);

            $msg = "event_updated";
        }

        //PAGOS

        elseif ($action === 'update_payment') {
            $reg_id = (int)$_POST['registration_id'];
            $status = (int)$_POST['status'];

            // BUSCAMOS EL NOMBRE DEL MÚSICO Y EL ACTO PARA ESCRIBIR EN EL LOG
            $stmt_info = $conn->prepare("
                SELECT u.name, e.title 
                FROM event_user eu 
                JOIN users u ON eu.user_id = u.id 
                JOIN events e ON eu.event_id = e.id 
                WHERE eu.id = ?
            ");
            $stmt_info->execute([$reg_id]);
            $data = $stmt_info->fetch();
            $musician = $data ? $data['name'] : "Desconegut";
            $event_name = $data ? $data['title'] : "Acte desconegut";

            // CAMBIAMOS EL ESTADO DE IS_PAID = 1
            $conn->prepare("UPDATE event_user SET is_paid = ? WHERE id = ?")->execute([$status, $reg_id]);

            // ESCRIBIMOS EN EL LOG
            $log_text = "Pagament realitzat a $musician per l'acte: $event_name";
            $conn->prepare("INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'payments', ?)")
                ->execute([$log_text, $_SESSION['user_id']]);

            $msg = "payment_updated";
        }

        // SI TODO ESTÁ BIEN CONFIRMAMOS LOS DATOS EN LA BBDD
        $conn->commit();
        header("Location: ../public/views/admin_dashboard.php?success=$msg");
        exit();
    } catch (PDOException $e) {
        // SI HAY ALGÚN ERROR HACEMOS ROLLBACK Y LO MOSTRAMOS
        $conn->rollBack();
        die("Error en EventController: " . $e->getMessage());
    }
}
