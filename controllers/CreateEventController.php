<?php

session_start();

// SI NO ES ADMIN O NO ESTÁ LOGUEADO LO ENVIAMOS A LOGIN
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/database.php';

// CHULETA -> TABLA: events (id, title, description, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price)
// CHULETA -> TABLA: event_user (id, event_id, user_id, has_car, is_paid, price_modifier)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // RECOGEMOS DATOS DEL FORMULARIO
    $title = trim($_POST['title']);
    $date = $_POST['date']; 
    $meeting_time_sede = !empty($_POST['meeting_time_sede']) ? $_POST['meeting_time_sede'] : null;
    $meeting_time_lugar = !empty($_POST['meeting_time_lugar']) ? $_POST['meeting_time_lugar'] : null;
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
    $is_paid_event = (int)$_POST['is_paid']; 
    $base_price = !empty($_POST['base_price']) ? (float)$_POST['base_price'] : 0.00;

    // RECOGEMOS ARRAYS DE MÚSICOS Y SUS DATOS EXTRA
    $musicians = isset($_POST['musicians']) ? $_POST['musicians'] : [];
    $has_car_data = isset($_POST['has_car']) ? $_POST['has_car'] : [];
    $price_mod_data = isset($_POST['price_modifier']) ? $_POST['price_modifier'] : [];

    try {
        // 1. INSERTAMOS EL EVENTO EN LA TABLA 'events'
        $sql = "INSERT INTO events (title, date, meeting_time_sede, meeting_time_lugar, is_paid, base_price, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$title, $date, $meeting_time_sede, $meeting_time_lugar, $is_paid_event, $base_price, $description]);
        
        // OBTENEMOS EL ID DEL EVENTO
        $event_id = $conn->lastInsertId();

        // 2. INSERTAMOS LOS MÚSICOS EN LA TABLA 'event_user'
        if (!empty($musicians)) {
        
            $sql_user = "INSERT INTO event_user (event_id, user_id, has_car, is_paid, price_modifier) 
                         VALUES (?, ?, ?, 0, ?)";
            $stmt_user = $conn->prepare($sql_user);
            
            foreach ($musicians as $user_id) {
                // COMPROBAMOS SI EL MÚSICO TIENE COCHOE
                $car = isset($has_car_data[$user_id]) ? 1 : 0;
                // COMPROBAMOS TAMBIÉN SI TIENE PRECIO EXTRA
                $extra = !empty($price_mod_data[$user_id]) ? (float)$price_mod_data[$user_id] : 0.00;
                
                $stmt_user->execute([$event_id, $user_id, $car, $extra]);
            }
        }

        // REDIRIGIMOS CON ÉXITO
        header("Location: ../public/views/admin_dashboard.php?success=event_created");
        exit();
        
    } catch(PDOException $e) {
        die("Error al crear l'acte i la convocatòria: " . $e->getMessage());
    }

} else {
    header("Location: ../public/views/admin_dashboard.php");
    exit();
}