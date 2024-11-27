<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
    header('location: ../../../html/login.php');
    exit();
}


// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
    $materiales = Inventario::obtenerMaterialesPorEdificio($edificio_id);
} else {
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historiales</title>
    <link rel="stylesheet" href="../../../css/index.css">
    <link rel="stylesheet" href="../../../css/index2.css">
    <link rel="stylesheet" href="../../../css/materials.css">
    <link href="../../../css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="../../../css/fontawesome/solid.css" rel="stylesheet" />
    <script src="../../../js/index.js"></script>
  
    <script src="../../../js/materiales.js"></script>
</head>

<body>
    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../../../img/Logos/LineLogo.png">
            </div>
            <h1>Inventario</h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="../../../img/Users/User.jpg" alt="User Photo">
            </div>
            <div id="header-logos">
                <a href="../../../phpFiles/config/logout.php">
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Menú lateral -->
   <div id="menu">
    <ul>
        <li><i class="fas fa-home"></i><a href="../../../html/personal/indice/index.php"> Home</a></li>
        <li><i class="fas fa-user"></i><a href="../../../html/personal/indice/myAccount.php"> My account</a></li>

    </ul>
</div>

    <!-- Recuadro grande para mostrar contenido -->

    <div id="users-content">
    <div class="content-box">
       
    </div>
</div>


</body>

</html>