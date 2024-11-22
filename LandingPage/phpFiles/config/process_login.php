<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/cuentas.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $user = Cuenta::login($username, $password);

    if ($user instanceof Personal) {
        $_SESSION['user_type'] = 'personal';
        $_SESSION['user_id'] = $user->getPersonalId();
        $_SESSION['edificio_id'] = $user->getEdificioId();
        $_SESSION['fullname'] = $user->getFullname();
        $_SESSION['telefono'] = $user->getTelefono();
        $_SESSION['correo'] = $user->getCorreo();

        echo json_encode([
            "success" => true,
            "redirect" => "/WMS2/LandingPage/html/personal/indice/index.php",
            "debug" => "Sesión iniciada correctamente para personal"
        ]);
        exit;
    

    
    } else if ($user instanceof Usuario) {
        $_SESSION['user_type'] = 'usuario';
        $_SESSION['user_id'] = $user->getUsuarioId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['correo'] = $user->getCorreo();
        $_SESSION['nombre'] = $user->getNombre();
        $_SESSION['edificio_id'] = $user->getEdificioId();
       

        echo json_encode([
            "success" => true,
            "redirect" => "/WMS2/LandingPage/html/usuario/usuario.php" 
        ]);
        exit;
    } else {
        echo json_encode([
            "success" => false,
            "message" => $user
        ]);
        exit;
    }
}

// Respuesta JSON de error por defecto en caso de que algo falle inesperadamente
echo json_encode([
    "success" => false,
    "message" => "Ocurrió un error inesperado. Inténtalo más tarde."
]);
?>