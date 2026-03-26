<?php

session_start();

// VACIAMOS ARRAY SESSIÓN
$_SESSION = array();

// DESTRUIMOS SESION
session_destroy();

// REDIRIGIMOS A index
header("Location: ../public/index.php");
exit();