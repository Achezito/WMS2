<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/personal.php';
require_once BASE_PATH . '/phpFiles/Models/edificios.php';


// Límite de inactividad en segundos (por ejemplo, 10 minutos = 600 segundos)
$limite_inactividad = 100000;

// Verificar el tiempo de inactividad
if (isset($_SESSION['ultimo_acceso'])) {
  $inactividad = time() - $_SESSION['ultimo_acceso'];

  // Si el tiempo de inactividad supera el límite, cerrar sesión y redirigir
  if ($inactividad > $limite_inactividad) {
    session_unset();
    session_destroy();

    // Redirigir a login.php con el mensaje de sesión expirada
    header("Location: /WMS2/LandingPage/html/login.php?sesion=expirada");
    exit();
  }
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type'])) {
  header('location: /WMS2/LandingPage/html/login.php');
  exit();
}

if (isset($_SESSION['edificio_id'])) {
  $edificio_id = $_SESSION['edificio_id'];
  $edificios = Edificios::mostrarInformacionPorEdificio($edificio_id);
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
  <title>Principal</title>
  <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
  <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
  <link rel="stylesheet" href="/WMS2/LandingPage/css/myAccount.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="/WMS2/LandingPage/js/index.js"></script>
</head>

<body>
  <header>
    <div id="header-left">
      <div id="header-menu" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
      </div>
      <div id="header-logo">
        <img src="/WMS2/LandingPage/img/Logos/LineLogo.png">
      </div>
      <h1>CISTA</h1>
    </div>
    <div id="header-right">
      <div id="user-photo">
        <a href="/WMS2/LandingPage/html/personal/indice/myAccount.php">
          <img src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Photo">
        </a>
      </div>
      <div id="header-logos">
        <i class="fas fa-cog"></i>
        <a href="/WMS2/LandingPage/phpFiles/config/logout.php">
          <i class="fas fa-sign-out-alt" id="logout-icon"></i>
        </a>
      </div>
    </div>
  </header>
  <div class="body-circle circle1"></div>
  <div class="body-circle circle2"></div>
  <div class="body-circle circle3"></div>
  <div class="body-circle circle4"></div>
  <div class="body-circle circle5"></div>

  <!-- Menú lateral -->
  <div id="menu">
    <ul>
      <li><i class="fas fa-home"></i><a href="/WMS2/LandingPage/html/personal/indice/index.php"> Home</a></li>
    </ul>
  </div>

  <!-- Contenedor principal -->
  <div class="main-container">
    <!-- Información del perfil -->
    <div class="profile-container">
      <div class="profile-header">
        <img src="/WMS2/LandingPage/img/Users/User.jpg" style="max-width: 150px; height:150px;" alt="User Photo">
        <h1>Juan Pérez</h1>
      </div>

      <div class="profile-details">
        <p class="profile-title">Desarrollador Web</p>
        <ul class="info-column">
          <li>
            <strong>Correo</strong>
            <span><?php echo $_SESSION['correo']; ?></span>
          </li>
          <li>
            <strong>Teléfono</strong>
            <span><?php echo $_SESSION['telefono']; ?></span>
          </li>
          <li>
            <strong>Edificio</strong>

            <span><?php echo $edificios->getNombre(); ?></span>

          </li>
        </ul>
  
      </div>
    </div>

 
</body>

</html>
