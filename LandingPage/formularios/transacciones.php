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
    <link rel="stylesheet" href="../css/forms.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Registro de Transacción</title>
</head>
<body>
    <div class="body-circle circle1"></div>
    <div class="body-circle circle2"></div>
    <div class="body-circle circle3"></div>
    <div class="body-circle circle4"></div>
    <div class="body-circle circle5"></div>
    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png">
            </div>
            <h1>CISTA</h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="../img/Users/User.jpg" alt="User Photo">
            </div>
            <div id="header-logos">
                <a href="/WMS2/LandingPage/phpFiles/config/logout.php">
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
                </a>
            </div>
        </div>
    </header>
<div class="container">
    <h2>Registro de Transacción</h2>
    <form action="/registro_transaccion" method="POST">
        
        <div class="form-group">
            <label for="transaccion_id">ID de la Transacción:</label>
            <input type="text" id="transaccion_id" name="transaccion_id" required>
        </div>
        
        <div class="form-group">
            <label for="tipo_transaccion">Tipo de Transacción:</label>
            <input type="text" id="tipo_transaccion" name="tipo_transaccion" required>
        </div>
        
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        
        <div class="form-group">
            <label for="fecha_final">Fecha Final:</label>
            <input type="date" id="fecha_final" name="fecha_final">
        </div>
        
        <div class="form-group">
            <label for="notas">Notas:</label>
            <textarea id="notas" name="notas" rows="4"></textarea>
        </div>
        
        <button type="submit">Registrar Transacción</button>
        
    </form>
</div>
<div id="menu">
    <ul>
        <li><i class="fas fa-home"></i><a href="/WMS2/LandingPage/html/personal/indice/index.php"> Home</a></li>
        <li><i class="fas fa-user"></i><a href="/WMS2/LandingPage/html/personal/indice/myAccount.php"> My account</a></li>

    </ul>
</div>

<script src="../js/index.js"></script>
</body>
</html>
