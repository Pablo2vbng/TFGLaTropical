<?php

session_start();

// VACIAMOS ARRAY SESSIÓN
$_SESSION = array();

// 3. DESTRUIMOS SESION
session_destroy();

// 4. REDIRIGIMOS A index
header("Location: ../public/index.php");
exit();