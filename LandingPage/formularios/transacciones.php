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
      <h2>Registro de Transacción</h2>
    <form action="/registro_transaccion" method="POST">
        
        <div class="form-group">
            <label for="transaccion_id">ID de la Transacción:</label>
            <input type="text" id="transaccion_id" name="transaccion_id" required>
        </div>
        
        <div class="form-group">
            <label for="tipo_transaccion">Tipo de Transacción:</label>
            <input type="text" id="tipo_transaccion" name="tipo_transaccion" required>
        </div>
        
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        
        <div class="form-group">
            <label for="fecha_final">Fecha Final:</label>
            <input type="date" id="fecha_final" name="fecha_final">
        </div>
        
        <div class="form-group">
            <label for="notas">Notas:</label>
            <textarea id="notas" name="notas" rows="4"></textarea>
        </div>
        
        <button type="submit">Registrar Transacción</button>
        
    </form>
      </section>
    </main>
</body>
</html>


