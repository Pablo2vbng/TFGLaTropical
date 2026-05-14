<?php
session_start();
require_once '../config/database.php';

// CHULETA -> TABLA: users (Campos: id, name, email, phone, instrument, password, role, is_approved)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // EL ?? '' EVITA ERRORES SI EL CAMPO NO LLEGA EN EL POST, TRIM QUITA ESPACIOS FINAL Y PRINCIPIO Y HTML... EVITA SCRIPTS. 
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $instrument = htmlspecialchars(trim($_POST['instrument'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    
  
    // VALIDACIONES 

    
    // 1. COMPROBAR CAMPOS OBLIGATORIOS (NUNCA FIARSE DEL 'REQUIRED' DEL HTML)
    if (empty($name) || empty($email) || empty($password)) {
        header("Location: ../public/register.php?error=empty_fields");
        exit();
    }

    // 2. COMPROBAR FORMATO DE EMAIL VALIDO
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../public/register.php?error=invalid_email");
        exit();
    }

    // 3. COMPROBAR SEGURIDAD BÁSICA DE LA CONTRASEÑA (MINIMO 6 CARACTERES)
    if (strlen($password) < 6) {
        header("Location: ../public/register.php?error=weak_password");
        exit();
    }

    try {
        // COMPROBAMOS SI EL CORREO YA EXISTE EN LA BASE DE DATOS
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt_check->execute([$email]);
        
        if ($stmt_check->fetch()) {
            // SI MAIL DUPLICADO, DEVOLVEMOS ERROR
            header("Location: ../public/register.php?error=email_exists");
            exit();
        }
        
        // ENCRIPTAMOS LA CONTRASEÑA DE FORMA SEGURA ANTES DE GUARDARLA
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // INSERTAMOS EL NUEVO USUARIO
        $sql = "INSERT INTO users (name, email, phone, instrument, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$name, $email, $phone, $instrument, $hashed_password])) {
            // SI TODO VA BIEN, MENSAJE DE EXITO Y REDIRECCION AL LOGIN
            header("Location: ../public/login.php?success=registered");
            exit();
        }
        
    } catch(PDOException $e) {
        // SI HAY UN ERROR A NIVEL DE BASE DE DATOS, SE MUESTRA AQUI
        die("Error en la base de dades: " . $e->getMessage());
    }
} else {
    // SI INTENTAN ENTRAR AL CONTROLADOR POR URL DIRECTA (GET), LOS MANDAMOS AL FORMULARIO
    header("Location: ../public/register.php");
    exit();
}
?>