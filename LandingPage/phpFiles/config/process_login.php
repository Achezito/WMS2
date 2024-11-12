<?php
session_start();

$limite_inactividad = 1000; // 10 minutos
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/personal.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitiza el nombre de usuario
    $userFetch = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');

   

    // Obtén la contraseña y hasheala

    $passwordFetch = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');



    // Intenta hacer login con los datos ingresados
    $userPersonal = Personal::login($userFetch ,$passwordFetch);

    if ($userPersonal instanceof Personal) {
        $_SESSION['personal_id'] = $userPersonal->getPersonalId();
$_SESSION['worker_user'] = $userPersonal->getWorkerUser();
$_SESSION['email'] = $userPersonal->getCorreo();

        header("Location: /WMS2/LandingPage/html/index.php");
        exit();
    } else {
        // Maneja el mensaje específico que devuelve el método login
        $_SESSION['error_message'] = $userPersonal;  // Puede ser "El nombre de usuario no existe." o "Contraseña incorrecta."
        header("Location: /WMS2//LandingPage/html/login.php");
        exit();
    }
}
