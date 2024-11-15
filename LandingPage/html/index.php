<?php
session_start();

// Límite de inactividad en segundos (por ejemplo, 10 minutos = 600 segundos)
$limite_inactividad = 28800;

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
if (!isset($_SESSION['worker_user'])) {
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
    <link rel="stylesheet" href="../css/index.css">
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
            <h1>CISTA</h1>
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
            <li><i class="fas fa-home"></i><a href="index.php"> Home</a></li>
            <li><i class="fas fa-user"></i><a href="#"> My account</a></li>
            <li><i class="fas fa-clipboard"></i><a href="#" id="prestamos-link"> Préstamos </a></li>
        </ul>
    </div>

    <!-- División para los cinco botones en forma de cartas -->
    <div id="button-cards-container">
        <div class="button-card"><i class="fas fa-home"></i> Inicio</div>
        <a href="materials.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-search"></i> Materiales
            </div>
        </a>
        <div class="button-card"><i class="fas fa-file-alt"></i> Formularios</div>
        <a href="users.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-user"></i> Usuarios
            </div>
        </a>
        <a href="history.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-clock"></i> Historiales
            </div>
        </a>

    </div>

    <!-- División para las cuatro cartas de contenido -->
    <div id="cards-container">
        <div class="card">Movimientos recientes en los materiales</div>
        <div class="card">Materiales más solicitados en la semana</div>
        <div class="card">Materiales críticos en el inventario</div>
        <div class="card">Próximos equipos de recibir mantenimiento</div>
    </div>
</body>

</html>