<?php

session_start();
require_once '../config/database.php';

// CHULETA -> TABLA: users (Campos: id, name, email, phone, instrument, password, role, is_approved)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $instrument = trim($_POST['instrument']);
    $password = trim($_POST['password']);
    
    try {

        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->fetch()) {
            // SI MAIL DUPLICADO, DEVOLVEMOS ERROR
            header("Location: ../public/register.php?error=email_exists");
            exit();
        }
        
        // ENCRIPTAMOS LA CONTRASEÑA
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, phone, instrument, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        

        if ($stmt->execute([$name, $email, $phone, $instrument, $hashed_password])) {
            // SI TODO VA BIEN, MENSAJE DE EXITO
            header("Location: ../public/login.php?success=registered");
            exit();
        }
        
    } catch(PDOException $e) {
        die("Error en la base de dades: " . $e->getMessage());
    }
} else {
    header("Location: ../public/register.php");
    exit();
}
?>