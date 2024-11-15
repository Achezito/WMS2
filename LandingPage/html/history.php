<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['worker_user'])) {
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
    <title>Historiales</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/historiales.css">
    <link href="../css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="../css/fontawesome/solid.css" rel="stylesheet" />
    <script src="../js/index.js"></script>
</head>

<body>
    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png">
            </div>
            <h1>Historiales</h1>
        </div>
        <div id="header-right">
            <h1 id="welcome-msg">
                <?php echo htmlspecialchars($_SESSION['worker_user']); ?>
            </h1>
            <div id="user-photo">
                <img src="../img/Users/User.jpg" alt="User Photo">
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
            <li><i class="fas fa-home"></i><a href="../html/index.php"> Home</a></li>
            <li><i class="fas fa-user"></i><a href="#"> My account</a></li>
            <li><i class="fas fa-clipboard"></i><a href="#" id="prestamos-link"> Préstamos </a></li>
        </ul>
    </div>

    <!-- Botones para diferentes tipos de historial -->
    <div id="button-cards-container">
        <div class="button-card"><i class="fas fa-user-clock"></i> Actividad Personal</div>
        <div class="button-card"><i class="fas fa-user-clock"></i> Usuarios</div>
        <div class="button-card"><i class="fas fa-clipboard-list"></i> Préstamos</div>
        <div class="button-card"><i class="fas fa-tools"></i> Mantenimientos</div>
        <div class="button-card"><i class="fas fa-check"></i> Cambios de estado</div>
        <div class="button-card"><i class="fas fa-truck"></i> Transacciones</div>
    </div>
    
    <!-- Recuadro grande para mostrar contenido -->
    <div id="historial-content">
        <div class="content-box">
            <h2>AQUI SE DIBUJARAN LOS HISTORIALES</h2>
            <!-- Aquí se mostrará el contenido específico según el historial seleccionado -->
        </div>
    </div>

</body>

</html>