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
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <title>Registro de Préstamo de Equipos</title>
</head>
<body>
    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png" alt="Logo">
            </div>
            <h1>CISTA</h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="../img/Users/User.jpg" alt="Foto del Usuario">
            </div>
            <div id="header-logos">
                <i class="fas fa-cog"></i>
                <i class="fas fa-globe"></i>
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
            </div>
        </div>
    </header>

    <div class="body-circle circle1"></div>
    <div class="body-circle circle2"></div>
    <div class="body-circle circle3"></div>
    <div class="body-circle circle4"></div>
    <div class="body-circle circle5"></div>

    <div class="container">
        <h2>Registro de Préstamo de Equipos</h2>
        <form action="/formularios/registrar.php" method="POST">
            <div class="form-group">
                <label for="personasID">ID del Usuario:</label>
                <input type="text" id="personasID" name="personasID" required>
            </div>

            <div class="form-group">
                <label for="personalesID">ID del Personal:</label>
                <input type="text" id="personalesID" name="personalesID" required>
            </div>

            <div class="form-group">
                <label for="fechaSalida">Fecha de Salida:</label>
                <input type="date" id="fechaSalida" name="fechaSalida" required>
            </div>

            <div class="form-group">
                <label for="fechaDevolucion">Fecha de Devolución:</label>
                <input type="date" id="fechaDevolucion" name="fechaDevolucion">
            </div>

            <div class="form-group">
                <label for="notas">Notas:</label>
                <textarea id="notas" name="notas" rows="4"></textarea>
            </div>

            <button type="submit">Registrar Préstamo</button>
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
