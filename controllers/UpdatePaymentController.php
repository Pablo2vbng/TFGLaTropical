<?php
session_start();

// PSOLO ADMIN ENTRA
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

// PROCESAMIENTO DE LA ACCIÓN DE MARCAR COMO PAGADO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registration_id'])) {
    
    $reg_id = (int)$_POST['registration_id'];
    $new_status = (int)$_POST['status']; // 1 PAGADO, 0 IMPAGADO

    try {
        // ACTUALIZAMOS EL PAGO
        $sql = "UPDATE event_user SET is_paid = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$new_status, $reg_id])) {
            header("Location: ../public/views/admin_dashboard.php?success=payment_updated");
            exit();
        }
        
    } catch(PDOException $e) {
        die("Error al actualitzar el pagament: " . $e->getMessage());
    }

} else {
    header("Location: ../public/views/admin_dashboard.php");
    exit();
}