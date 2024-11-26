<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';
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
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Principal</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
</head>
<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <div class="logo-container">
            <!-- Contenedor para logo y nombre -->
            <h1 class="app-title">CISTA</h1>
            
          </div>
      <div class="profile">
      
          <!-- Perfil del usuario -->
          <img class="user-avatar" src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Avatar">

        <h3 class="titleName">

        <?php 
        echo $_SESSION['fullname'];

        ?>

        </h3>
        <p class="titleMail">

        <?php 
        echo $_SESSION['correo'];

        ?>
        </p>
      </div>
      <nav>
        <ul>
            
        <li><a href="/WMS2/LandingPage/html/personal/indice/index.php">
              <label class="linkLabel">
                Home</label>
            </a></li>
            
        <li class="dropdown">
        <span class="dropdown-toggle">Formularios</span>
            <ul class="dropdown-menu">
                <li><a href="/WMS2/LandingPage/formularios/personal_prestamos.php">Préstamos</a></li>
                <li><a href="/WMS2/LandingPage/formularios/transacciones.php">Transacciones</a></li>
                <li><a href="/WMS2/LandingPage/formularios/mantenimiento.php">Mantenimiento</a></li>
            </ul>
        </li>


        <li><a href="/WMS2/LandingPage/html/personal/users/users.php">
            <label class="linkLabel">
                Usuarios</label> 
        </a></li>

          <li><a href="/WMS2/LandingPage/html/personal/history/history.php">
            <label class="linkLabel">
                Historiales</label> 
          </a></li>
          <li><a href="/WMS2/LandingPage/phpFiles/config/logout.php">
            <label class="linkLabel">
                Logout</label> 
        </a></li>

        </ul>
      </nav>
    </aside>
    <!-- Contenido principal -->
    <main class="main-content">
      <section class="content">
  
       
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




     

      </section>
    </main>
  </div>
</body>
</html>
