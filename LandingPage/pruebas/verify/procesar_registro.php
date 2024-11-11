<?php 
require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/Models/users.php');
session_start();

$error_message = ''; // Variable para manejar el mensaje de error

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $user_insert = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password_insert = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Intentar registrar al nuevo usuario
    $NewUser = User::process_register($user_insert, $password_insert);

    if ($NewUser === null) {
        // Si el registro fue exitoso, redirigir al usuario
        $_SESSION['user_name'] = $user_insert;  // Opcional: guardar el nombre de usuario en la sesión
        $_SESSION['error_message'] = $NewUser;
        header("Location: /WMS2/LandingPage/pruebas/dashboard/registro.php");
        exit();  // Terminar el script después de la redirección
    } else {
        $_SESSION['error_message'] = $NewUser;
        header("Location: /WMS2/LandingPage/pruebas/dashboard/registro.php");
        // Si el registro falló, asignar el mensaje de error
        exit();
    }
}
?>












