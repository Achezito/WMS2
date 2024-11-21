<?php
session_start();
// Incluir el archivo que contiene la función para dibujar el historial
require_once('../../../phpFiles/Models/historiales.php');
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}


// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
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
    <title>Cista</title>
    <link rel="stylesheet" href="../../../css/index.css">
    <link href="../../../css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="../../../css/fontawesome/solid.css" rel="stylesheet" />
    <script src="../../../js/index.js"></script>
    <link rel="stylesheet" href="../../../css/historiales.css">
    <script>
        // Función para manejar el cambio de historial al hacer clic en un botón
        function cambiarHistorial(index) {
            // Ocultar todos los historiales
            const historiales = document.querySelectorAll('.historial');
            historiales.forEach(historial => {
                historial.style.display = 'none';
            });

            // Mostrar el historial seleccionado
            document.getElementById('historial-' + index).style.display = 'block';
        }

        // Al cargar la página, mostrar solo el historial 0 (Actividad Personal)
        window.onload = function() {
            cambiarHistorial(0);
        };
    </script>

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
                <a href="/WMS2/LandingPage/html/personal/indice/myAccount.php">
                    <img src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Photo">
                </a>
            </div>
            <div id="header-logos">
                <a href="/WMS2/LandingPage/phpFiles/config/logout.php">
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
                </a>
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
        <a href="../../../html/personal/indice/index.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-home"></i> Inicio
            </div>
        </a>
        <div class="button-card" data-index="0" onclick="cambiarHistorial(0)">
            <i class="fas fa-user-clock"></i> Actividad Personal
        </div>
        <div class="button-card" data-index="1" onclick="cambiarHistorial(1)">
            <i class="fas fa-clipboard-list"></i> Préstamos
        </div>
        <div class="button-card" data-index="2" onclick="cambiarHistorial(2)">
            <i class="fas fa-tools"></i> Mantenimientos
        </div>
        <div class="button-card" data-index="3" onclick="cambiarHistorial(3)">
            <i class="fas fa-truck"></i> Transacciones
        </div>
    </div>

    <!-- Recuadro grande para mostrar contenido -->
    <div id="historial-content">
        <div class="content-box">

            <!-- Historial 0: Actividad Personal -->
            <div id="historial-0" class="historial" style="display:none;">
                <?php
                dibujar_historial_operaciones($edificio_id);
                ?>
            </div>

            <!-- Historial 1: Préstamos -->
            <div id="historial-1" class="historial" style="display:none;">
                <?php
                dibujar_historial_prestamos($edificio_id);
                ?>
            </div>

            <!-- Historial 2: Mantenimientos -->
            <div id="historial-2" class="historial" style="display:none;">
                <?php
                dibujar_historial_mantenimientos($edificio_id);
                ?>
            </div>

            <!-- Historial 3: Transacciones -->
            <div id="historial-3" class="historial" style="display:none;">
                <?php
                dibujar_historial_transacciones($edificio_id);
                ?>
            </div>


        </div>
    </div>
</body>

</html>