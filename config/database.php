<?php


$host = "localhost";
$db_name = "la_tropical_bbdd";
$username = "root"; 
$password = "";    

try {
   
    $conn = new PDO(
        "mysql:host=$host;dbname=$db_name",$username,$password
    );
    
    //ERRORES
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //ARRAYS ASOCIATIVOS
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $exception) {
    die("Error de connexión a la base de datos: " . $exception->getMessage());
}
?>