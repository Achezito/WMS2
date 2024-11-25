<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'usuario') {
    header('Location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos (puedes ajustarlo)

if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    if ($inactividad > $limite_inactividad) {
        session_unset();
        session_destroy();
        header("Location: /WMS2/LandingPage/html/login.php?sesion=expirada");
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time(); // Actualizar el último acceso




// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type'])) {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}

if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
    $materiales = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
} else {
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
}

?>

        
      


<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
 
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <script src="/WMS2/LandingPage/js/inventario_prestamoAJAX.js"></script>
</head>
<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <div class="logo-container">
            <!-- Contenedor para logo y nombre -->
            <h1 class="app-title">CISTA</h1>
            
          </div>
      <div class="profile">
      
          <!-- Perfil del usuario -->
          <img class="user-avatar" src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Avatar">

        <h3 class="titleName">
          <?php
          echo $_SESSION['username'];
          ?>
        </h3>
        <p class="titleMail">
        <?php 
      echo $_SESSION['correo'];
        ?>

        
        </p>
      </div>
      <nav>
        <ul>
            
        <li>
            
        <a href="/WMS2/LandingPage/html/usuario/usuario.php">
           <label class="linkLabel">
                Solicitar</label> 
        </a></li>

        <li><a href="/WMS2/LandingPage/html/usuario/PrestamosUser.php">
            <label class="linkLabel">
                Ver prestamos</label> 
        </a></li>
        <li><a href="/WMS2/LandingPage/phpFiles/config/logout.php">
            <label class="linkLabel">
                Logout</label> 
        </a></li>

    

        </ul>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
    <section class="content">
   

    </main>
  </div>
</body>
</html>
