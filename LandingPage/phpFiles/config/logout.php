<?php

// logout.php
session_start(); // Inicia la sesión
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión actual

// Redirige al usuario a la página de inicio de sesión o principal
header("Location: /WMS2/LandingPage/html/login.php");
exit();

?>
