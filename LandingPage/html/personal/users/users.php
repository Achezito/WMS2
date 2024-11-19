<?php
session_start();
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
    $usuarios = Usuario::obtenerUsuarios($edificio_id);
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
    <link rel="stylesheet" href="/WMS2/LandingPage/css/users.css">
    <link href="/WMS2/LandingPage/css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="/WMS2/LandingPage/css/fontawesome/solid.css" rel="stylesheet" />
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <script src="/WMS2/LandingPage/js/usuarios.js"></script>
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
            <h1>Usuarios</h1>
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
            <li><i class="fas fa-home"></i><a href="../html/index.php"> Home</a></li>
            <li><i class="fas fa-user"></i><a href="#"> My account</a></li>
            <li><i class="fas fa-clipboard"></i><a href="#" id="prestamos-link"> Préstamos </a></li>
        </ul>
    </div>

    <!-- Recuadro grande para mostrar contenido -->
    <div id="users-content">
        <div class="content-box">
            <h2>AQUI SE DIBUJARA LA LISTA DE USUARIOS</h2>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar...">

            <?php
            if (!empty($usuarios)) {
                // Crear la tabla con encabezados
                echo "<table border='1'>";
                echo "<tr>
            <th>ID Usuario</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha de Creación</th>
            <th>Estado</th>
            <th>ID Edificio</th>
          </tr>";

                // Iterar sobre los usuarios y mostrar sus datos
                foreach ($usuarios as $usuario) {
                    echo "<tr onclick='onClickRow(" . $usuario->getUsuarioId() . ")'>";
                    echo "<td>" . $usuario->getUsuarioId() . "</td>";  // Usar getter para obtener el ID de usuario
                    echo "<td>" . $usuario->getNombre() . "</td>";      // Usar getter para obtener el nombre
                    echo "<td>" . $usuario->getDescripcion() . "</td>"; // Usar getter para obtener la descripción
                    echo "<td>" . $usuario->getFechaCreacion() . "</td>"; // Usar getter para obtener la fecha de creación
                    echo "<td>" . $usuario->getEstado() . "</td>";      // Usar getter para obtener el estado
                    echo "<td>" . $usuario->getEdificioId() . "</td>";  // Usar getter para obtener el ID del edificio
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "No hay usuarios disponibles.";
            }
            ?>




        </div>
    </div>

</body>

</html>