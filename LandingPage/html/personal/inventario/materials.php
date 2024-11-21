<?php
session_start();
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/inventario.php');
require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/usuarios.php');
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
}


// Actualizar el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();

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
    <title>Historiales</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/materials.css">
    <link href="/WMS2/LandingPage/css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="/WMS2/LandingPage/css/fontawesome/solid.css" rel="stylesheet" />
    <script src="/WMS2/LandingPage/js/index.js"></script>
  
    <script src="/WMS2/LandingPage/js/materiales.js"></script>
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
            <h1>Inventario</h1>
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
        <li><i class="fas fa-user"></i><a href="/WMS2/LandingPage/html/personal/indice/myAccount.php"> My account</a></li>

    </ul>
</div>

    <!-- Recuadro grande para mostrar contenido -->

    <div id="users-content">
    <div class="content-box">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar...">
        <div class="scrollable-table">

            
            <?php
            if (!empty($materiales)) {
                echo "<table border='1'>";

                // Encabezado del edificio
                echo "<thead>";
                echo "<tr><th colspan='5'>" . htmlspecialchars($materiales[0]['edificio']) . "</th></tr>";
                echo "<tr>
                    <th>ID Material</th>
                    <th>Serie</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                </tr>";
                echo "</thead>";

                // Cuerpo de la tabla
                echo "<tbody>";
                foreach ($materiales as $material) {
                    echo "<tr onclick='onClickRow(" . htmlspecialchars($material['material_id']) . ")'>";
                    echo "<td>" . htmlspecialchars($material['material_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($material['serie']) . "</td>";
                    echo "<td>" . htmlspecialchars($material['modelo']) . "</td>";
                    echo "<td>" . htmlspecialchars($material['tipo_material']) . "</td>";
                    echo "<td>" . htmlspecialchars($material['estatus']) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";

                echo "</table>";
            } else {
                echo "No hay materiales vinculados a tu edificio.";
            }
            ?>
        </div>
    </div>
</div>


</body>

</html>