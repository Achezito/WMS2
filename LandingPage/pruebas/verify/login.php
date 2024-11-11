<?php
session_start();
require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/Models/users.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitiza el nombre de usuario
    $userFetch = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');

    // Obtén la contraseña y hasheala

    $passwordFetch = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');



    // Intenta hacer login con los datos ingresados
    $user = User::login($userFetch, $passwordFetch);

    if ($user instanceof User) {
        $_SESSION['id_users'] = $user->getIdUser();
        $_SESSION['user_name'] = $user->getUserName();
        header("Location: /WMS2/LandingPage/pruebas/hom2.php");
        exit();
    } else {
        // Maneja el mensaje específico que devuelve el método login
        $_SESSION['error_message'] = $user;  // Puede ser "El nombre de usuario no existe." o "Contraseña incorrecta."
        header("Location: /WMS2/LandingPage/pruebas/dashboard/home.php");
        exit();
    }
}
