<?php

session_start();
header('Content-Type: application/json'); // Establece el encabezado JSON

require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/personal.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar las entradas de usuario
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Intenta hacer login con los datos ingresados
    $user = Personal::login($username, $password);

    if ($user instanceof Personal) {
        // Configurar las variables de sesión en caso de éxito
        $_SESSION['personal_id'] = $user->getPersonalId();
        $_SESSION['worker_user'] = $user->getWorkerUser();
        $_SESSION['email'] = $user->getCorreo();

        // Respuesta JSON de éxito
        echo json_encode([
            "success" => true,
            "redirect" => "/WMS2/LandingPage/html/index.php"
        ]);
    } else {
        // El login falló, por ejemplo, usuario no encontrado o contraseña incorrecta
        echo json_encode([
            "success" => false,
            "message" => $user // El mensaje de error que tu método login devuelve
        ]);
    }
}

    
?>

