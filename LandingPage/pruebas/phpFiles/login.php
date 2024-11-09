<?php
require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/Models/users.php');

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userFetch = $_POST['username'];
    $passwordFetch = $_POST ['password'];


  

    $user = User::login($userFetch, $passwordFetch);

    if ($user) {
        // Inicio de sesión exitoso
        $_SESSION['id_user'] = $user->getIdUser();
        $_SESSION['user_name'] = $user->getUserName();
        header("Location: /WMS2/LandingPage/pruebas/dashboard.php");
        exit();
    } else {
        // Si el usuario o contraseña son incorrectos
        $error_message = "Nombre de usuario o contraseña incorrectos."; 
        echo $error_message; // Muestra el mensaje de error
    }
}

?>
