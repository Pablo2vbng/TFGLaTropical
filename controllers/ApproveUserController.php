<?php
// controllers/ApproveUserController.php
session_start();

// PSOLO EL ADMIN PUEDE EJECUTAR ESTE ARCHIVO
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

// CHULETA -> TABLA: users (Campos: id, is_approved)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    
    $user_id = (int)$_POST['user_id'];

    try {
        // ACTUALIZAMOS EL CAMPO APPROVED 
        $sql = "UPDATE users SET is_approved = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$user_id])) {
            // SI FUNCIONA VOLVEMOS AL PANEL ADMIN
            header("Location: ../public/views/admin_dashboard.php?success=user_approved");
            exit();
        }
        
    } catch(PDOException $e) {
        die("Error en la base de dades: " . $e->getMessage());
    }

} else {
    // SI ALGUIEN INTENTA ENTRAR SIN ID LO VOLVEMOS DE VUELTA
    header("Location: ../public/views/admin_dashboard.php");
    exit();
}