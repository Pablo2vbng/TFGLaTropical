<?php

session_start();

require_once '../config/database.php';

// CHULETA -> TABLA: users (Campos: id, name, email, password, role, is_approved)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // RECOGERMOS DATOS DEL FORMULARIO
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    try {
        //CONSULTA
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        
        // OBTENEMOS DATOS EN UN ARRAY... YA LE HABÍAMOS DICHO A LA CONEXIÓN CÓMO TRABAJAR
        $user = $stmt->fetch();
        
        // PASSWORD VERIFY PARA CONTRASEÑAS ENCRIPTADAS
        if ($user && password_verify($password, $user['password'])) {
            
            // COMPROBAR SI ADMIN HA DADO PERMISO PARA ENTRAR
            if ($user['is_approved'] == 1) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                
                // REDIRIGIMOS
                if ($user['role'] === 'admin') {
                    header("Location: ../public/views/admin_dashboard.php");
                } else {
                    header("Location: ../public/views/user_dashboard.php");
                }
                exit();
                
            } else {
                // EL ADMIN AÚN NO HA VALIDADO
                header("Location: ../public/login.php?error=no_approved");
                exit();
            }
            
        } else {
            // EL USUARIO NO EXISTE O CONTRASEÑA MAL
            header("Location: ../public/login.php?error=invalid_credentials");
            exit();
        }
        
    } catch(PDOException $e) {
       // ERROR EN LA BBDD
       die("Error en la base de dades: " . $e->getMessage());
    }
} else {
    // SI ALGUIEN INTENTA ENTRAR DIRECTAMENTE LO DEVOLVEMOS AL LOGIN
    header("Location: ../public/login.php");
    exit();
}
?>