<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Verificar si se ha recibido el ID del usuario desde la URL
if (isset($_GET['usuario_id'])) {
    $usuarioId = $_GET['usuario_id'];
    // Obtener los detalles del usuario
    $usuario = Usuario::get_one($usuarioId);
} else {
    echo "No se ha especificado el ID del usuario.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/users.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/index.js"></script>
</head>

<body>
    <header>
        <h1>Detalles del Usuario</h1>
    </header>
    <div id="users-content">
        <div class="content-box">
            <h2><?php echo $usuario->getNombre(); ?></h2>
        

        
   




        </div>
    </div>
  
       

</body>

</html>
