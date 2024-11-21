<?php
session_start();

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
if (!isset($_SESSION['user_id'])) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/index.js"></script>
</head>
<body>

    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()">
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png" >
            </div>
            <h1>CISTA</h1>
            <h1>
                Bienvenido <?php echo htmlspecialchars($_SESSION['user_id']); ?>
            </h1>
        </div>
        <div id="header-right">
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
            <li><i class="fas fa-home"></i><a href="#"> Home</a></li>
            <li><i class="far fa-user"></i><a href="#"> My account</a></li>
            <li><i class="far fa-clipboard"></i><a href="#" id="prestamos-link"> Préstamos </a></li>
        </ul>
    </div>
    
    <!-- División para los cinco botones en forma de cartas -->
    <div id="button-cards-container">
        <div class="button-card"><i class="fas fa-home"></i> Inicio</div>
        <div class="button-card"><i class="fas fa-search"></i> Materiales</div>
        <div class="button-card"><i class="fas fa-file-alt"></i> Formularios</div>
        <div class="button-card"><i class="fas fa-user"></i> Usuarios</div>
        <div class="button-card"><i class="fas fa-clock"></i> Historiales</div>
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