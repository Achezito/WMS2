<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/formularios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Registro de Préstamo de Equipos</title>
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
    
    <div id="button-cards-container">
        <a href="/WMS2/LandingPage/formularios/prestamos.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-hand-holding"></i> Préstamos
            </div>
        </a>
        <a href="/WMS2/LandingPage/formularios/transacciones.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-exchange-alt"></i> Transacciones
            </div>
        </a>
        <a href="/WMS2/LandingPage/formularios/mantenimiento.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-tools"></i> Mantenimiento
            </div>
        </a>
    </div>

    <script src="../js/index.js"></script>
</body>
</html>