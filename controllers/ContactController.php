<?php
// controllers/ContactController.php
session_start();

// CHULETA -> TABLA: contact_messages (id, name, email, phone, message, created_at)
// CHULETA -> TABLA: audit_logs (id, action, target, user_id, created_at)

require_once '../config/database.php';

// COMPROBAMOS QUE LA PETICIÓN VENGA POR MÉTODO POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        
        // SEGURIDAD: VERIFICAMOS QUE SOLO EL ADMINISTRADOR PUEDA ELIMINAR MENSAJES
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: ../public/login.php");
            exit();
        }

        $message_id = (int)$_POST['message_id'];

        try {
            // CONSULTA PREVIA: OBTENEMOS EL NOMBRE PARA EL LOG DE AUDITORÍA
            $stmt_info = $conn->prepare("SELECT name FROM contact_messages WHERE id = ?");
            $stmt_info->execute([$message_id]);
            $msg_info = $stmt_info->fetch();
            $remitente = $msg_info ? $msg_info['name'] : 'Desconegut';

            // BORRAMOS EL MENSAJE
            $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->execute([$message_id]);

            // REGISTRAMOS LA ACCIÓN EN EL HISTORIAL
            $log_sql = "INSERT INTO audit_logs (action, target, user_id) VALUES (?, 'messages', ?)";
            $conn->prepare($log_sql)->execute(["Missatge eliminat de: $remitente", $_SESSION['user_id']]);

        } catch (PDOException $e) {
            die("Error en eliminar el missatge: " . $e->getMessage());
        }

        // REDIRIGIMOS DE VUELTA AL PANEL DE ADMINISTRACIÓN
        header("Location: ../public/views/admin_dashboard.php?success=message_deleted");
        exit();
    }


    else {
        // RECOGEMOS Y SANEAMOS LOS DATOS. USAMOS HTMLSPECIALCHARS PARA EVITAR INYECCIONES DE SCRIPT
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $email = trim($_POST['email'] ?? '');
        $phone = htmlspecialchars(trim($_POST['phone-number'] ?? ''));
        $comments = htmlspecialchars(trim($_POST['comments'] ?? ''));

        // VALIDACIÓN DEL BACKEND: CAMPOS OBLIGATORIOS Y FORMATO DE EMAIL
        if (empty($name) || empty($email) || empty($comments)) {
            header("Location: ../public/index.php?error=empty_fields#contact");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../public/index.php?error=invalid_email#contact");
            exit();
        }

        try {
            // INSERTAMOS EL MENSAJE EN LA BASE DE DATOS
            $sql = "INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([$name, $email, $phone, $comments])) {
                // REDIRIGIMOS AL INDEX CON ÉXITO
                header("Location: ../public/index.php?success=message_sent#contact");
                exit();
            }
            
        } catch(PDOException $e) {
            die("Error al enviar el missatge: " . $e->getMessage());
        }
    }

} else {
    // SI SE INTENTA ACCEDER POR GET, EXPULSAMOS AL INDEX
    header("Location: ../public/index.php");
    exit();
}
?>