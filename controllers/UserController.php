<?php
// controllers/UserController.php
session_start();
require_once '../config/database.php';

// CHULETA -> TABLA: users (Campos: id, name, email, phone, instrument, password, role, is_approved)
// CHULETA -> TABLA: audit_logs (id, action, target, user_id)

// PROTECCIÓN: SOLO EL ADMINISTRADOR TIENE ACCESO A LA GESTIÓN DE USUARIOS
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // RECOGEMOS LA ACCIÓN PARA DIFERENCIAR ENTRE APROBAR, REBUTJAR O ACTUALIZAR
    $action = $_POST['action'] ?? '';

    try {
        // INICIAMOS TRANSACCIÓN PARA QUE EL LOG Y EL CAMBIO EN EL USUARIO VAYAN DE LA MANO
        $conn->beginTransaction();

        // LÓGICA PARA APROBAR UN MÚSICO PENDIENTE 
        if ($action === 'approve') {
            $user_id = (int)$_POST['user_id'];
            
            // OBTENEMOS EL NOMBRE PARA QUE EL LOG SEA DESCRIPTIVO
            $stmt_name = $conn->prepare("SELECT name FROM users WHERE id = ?");
            $stmt_name->execute([$user_id]);
            $musician_name = $stmt_name->fetchColumn() ?: "Desconegut";

            // ACTUALIZAMOS EL CAMPO DE APROBACIÓN
            $stmt = $conn->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
            $stmt->execute([$user_id]);

            // REGISTRAMOS LA ACCIÓN EN EL HISTORIAL 
            $log_sql = "INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'users', ?)";
            $conn->prepare($log_sql)->execute(["Ha aprovat l'alta de: $musician_name", $_SESSION['user_id']]);
            
            $msg = "approved";
        }

        // LÓGICA PARA ELIMINAR UN MÚSICO PENDIENTE 
        elseif ($action === 'reject') {
            $user_id = (int)$_POST['user_id'];

            // OBTENEMOS EL NOMBRE ANTES DE BORRAR PARA EL LOG
            $stmt_name = $conn->prepare("SELECT name FROM users WHERE id = ?");
            $stmt_name->execute([$user_id]);
            $musician_name = $stmt_name->fetchColumn() ?: "Desconegut";

            // ELIMINAMOS EL REGISTRO DE LA BASE DE DATOS DEFINITIVAMENTE
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND is_approved = 0");
            $stmt->execute([$user_id]);

            // REGISTRAMOS LA ACCIÓN EN EL LOG
            $log_sql = "INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'users', ?)";
            $conn->prepare($log_sql)->execute(["Ha rebutjat i eliminat la sol·licitud de: $musician_name", $_SESSION['user_id']]);

            $msg = "rejected";
        }

        //LÓGICA PARA ACTUALIZAR EL PERFIL DE UN USUARIO EXISTENTE 
        elseif ($action === 'update') {
            
            // RECOGEMOS DATOS DEL FORMULARIO
            $user_id = (int)($_POST['user_id'] ?? 0);
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));
            $email = trim($_POST['email'] ?? '');
            $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
            $instrument = htmlspecialchars(trim($_POST['instrument'] ?? ''));
            $role = htmlspecialchars(trim($_POST['role'] ?? 'user'));
            $is_approved = (int)($_POST['is_approved'] ?? 0);

            // 2. VALIDACIONES BACKEND 

            if (empty($name) || empty($email)) {
                header("Location: ../public/views/edit_user.php?id=$user_id&error=empty_fields");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../public/views/edit_user.php?id=$user_id&error=invalid_email");
                exit();
            }

            // LANZAMOS EL UPDATE CON TODOS LOS CAMPOS MODIFICABLES
            $sql = "UPDATE users SET name = ?, email = ?, phone = ?, instrument = ?, role = ?, is_approved = ? WHERE id = ?";
            $conn->prepare($sql)->execute([$name, $email, $phone, $instrument, $role, $is_approved, $user_id]);

            //REGISTRAMOS EN EL LOG QUIÉN HA SIDO EDITADO
            $log_sql = "INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'users', ?)";
            $conn->prepare($log_sql)->execute(["Ha editat el perfil de l'usuari: $name", $_SESSION['user_id']]);
            
            $msg = "user_updated";
        }

        // SI NO HAY ERRORES, GUARDAMOS CAMBIOS DEFINITIVAMENTE
        $conn->commit();
        header("Location: ../public/views/admin_dashboard.php?success=$msg");
        exit();

    } catch (PDOException $e) {
        // SI ALGO FALLA, DESHACEMOS CUALQUIER CAMBIO EN LA BBDD PARA EVITAR DATOS CORRUPTOS
        $conn->rollBack();
        die("Error en UserController: " . $e->getMessage());
    }
} else {
    // PROTECCIÓN CONTRA ACCESO DIRECTO
    header("Location: ../public/views/admin_dashboard.php");
    exit();
}
?>