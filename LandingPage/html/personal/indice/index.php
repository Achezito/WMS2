<?php
session_start();

require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/inventario.php');

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
    $materiales = Inventario::obtenerMaterialesPorEdificio($edificio_id);
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
            <li><i class="fas fa-home"></i><a href="/WMS2/LandingPage/html/login.php"> Home</a></li>
            <li><i class="far fa-user"></i><a href="/WMS2/LandingPage/html/personal/indice/myAccount.php"> My account</a></li>
        </ul>
    </div>

    <!-- División para los cinco botones en forma de cartas -->
    <div id="button-cards-container">
        <a href="../inventario/materials.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-search"></i> Materiales
            </div>
        </a>
        <a href="/WMS2/LandingPage/html/personal/formularios/formularios.php">
            <div class="button-card">
                <i class="fas fa-file-alt"></i> Formularios
            </div>
        </a>
        <a href="../users/users.php" style="text-decoration: none; color: inherit;">

            <div class="button-card">
                <i class="fas fa-user"></i> Usuarios
            </div>
        </a>
        <a href="../history/history.php" style="text-decoration: none; color: inherit;">
            <div class="button-card">
                <i class="fas fa-clock"></i> Historiales
            </div>
        </a>

    </div>

    <!-- División para las cuatro cartas de contenido -->
    <div id="cards-container">
        <div class="card">
            <div class="header">MOVIMIENTOS RECIENTES LISTA DE MATERIALES </div>
            <?php
            if (!empty($materiales)) {
                echo "<table>";
                echo "<tr><td colspan='5'>" . $materiales[0]['edificio'] . "</td></tr>";
                echo "<tr><th>ID Material</th><th>Serie</th><th>Modelo</th><th>Tipo</th></tr>";
                foreach ($materiales as $material) {
                    echo "<tr>";
                    echo "<td>" . $material['material_id'] . "</td>";
                    echo "<td>" . $material['serie'] . "</td>";
                    echo "<td>" . $material['modelo'] . "</td>";
                    echo "<td>" . $material['tipo_material'] . "</td>";

                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No hay materiales vinculados a tu edificio.";
            }




            ?>

        </div>
        <div class="card">Materiales más solicitados en la semana</div>
        <div class="card">Materiales críticos en el inventario</div>
        <div class="card">Próximos equipos de recibir mantenimiento</div>
    </div>
</body>

</html>