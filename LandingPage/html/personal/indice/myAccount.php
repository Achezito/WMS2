<?php
session_start();
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/personal.php');


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
            <h1>

            </h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Photo">
            </div>
            <div id="header-logos">
                <i class="fas fa-cog"></i>
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
            </div>
        </div>
    </header>

    <!-- Menú lateral -->
    <div id="menu">
    <ul>
        <li><i class="fas fa-home"></i><a href="/WMS2/LandingPage/html/personal/indice/index.php"> Home</a></li>
    </ul>
</div>

    <!-- División para los cinco botones en forma de cartas -->
    <div class="profile-container">
    <div class="profile-header">
      <img class="profile-avatar" src="avatar.jpg" alt="Mi Avatar">
      <h1>Juan Pérez</h1>
      <p class="profile-title">Desarrollador Web</p>
      <div class="profile-actions">
        <button onclick="editProfile()">Editar Perfil</button>
      </div>
    </div>

    <div class="profile-details">
      <h2>Información Personal</h2>
      <ul>
        <li><strong>Correo:</strong> juan@example.com</li>
        <li><strong>Teléfono:</strong> <?php echo $_SESSION['telefono']; ?></li>
        <li><strong>Ubicación:</strong> Ciudad de México, México</li>
        <li><strong>Redes Sociales:</strong>
          <ul>
            <li><a href="https://linkedin.com" target="_blank">LinkedIn</a></li>
            <li><a href="https://github.com" target="_blank">GitHub</a></li>
          </ul>
        </li>
      </ul>
    </div>

    <div class="profile-projects">
      <h2>Proyectos Recientes</h2>
      <ul>
        <li><a href="#">Gestión de Inventarios WMS</a></li>
        <li><a href="#">Sistema de Reservas de Salas</a></li>
      </ul>
    </div>
  </div>
  <script>
    function editProfile() {
        window.open(`/WMS2/LandingPage/html/personal/indice/editMyProfile.php`, '_blank');
    }
  </script>

</body>

</html>