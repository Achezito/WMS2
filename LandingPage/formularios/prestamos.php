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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="../css/forms.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>
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
            
        <li>
            
        <li class="dropdown">
        <span class="dropdown-toggle">Formularios</span>
            <ul class="dropdown-menu">
                <li><a href="/WMS2/LandingPage/formularios/prestamos.php">Préstamos</a></li>
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

        </ul>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
      <section class="content">
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
      </section>
    </main>
</body>
</html>


