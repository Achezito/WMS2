<?php
session_start();
// Incluir el archivo que contiene la función para dibujar el historial
require_once('../../../phpFiles/Models/historiales.php');
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'personal') {
    header('location: ../../../html/login.php');
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
    <link href="../../../css/fontawesome/fontawesome.css" rel="stylesheet" />
    <link href="../../../css/fontawesome/solid.css" rel="stylesheet" />
    <script src="../../../js/index.js"></script>
    <script src="../../../js/historiales.js"></script>
    <link rel="stylesheet" href="../../../css/hom2.css">
    <link rel="stylesheet" href="../../../css/historiales.css">
    

</head>

<body>
    <div class="container">




    <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <div class="logo-container">
            <!-- Contenedor para logo y nombre -->
            <h1 class="app-title">CISTA</h1>
            
          </div>
      <div class="profile">
      
          <!-- Perfil del usuario -->
          <img class="user-avatar" src="../../../img/Users/User.jpg" alt="User Avatar">

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
            
        <li><a href="../../../html/personal/indice/index.php">
              <label class="linkLabel">
                Home</label>
            </a></li>
            
        <li class="dropdown">
        <span class="dropdown-toggle">Formularios</span>
            <ul class="dropdown-menu">
                <li><a href="../../../formularios/personal_prestamos.php">Préstamos</a></li>
                <li><a href="../../../formularios/transacciones.php">Transacciones</a></li>
                <li><a href="../../../formularios/mantenimiento.php">Mantenimiento</a></li>
            </ul>
        </li>

        

        <li><a href="../../../html/personal/users/users.php">
            <label class="linkLabel">
                Usuarios</label> 
        </a></li>

          <li><a href="../../../html/personal/history/history.php">
            <label class="linkLabel">
                Historiales</label> 
          </a></li>
          <li><a href="../../../phpFiles/config/logout.php">
            <label class="linkLabel">
                Logout</label> 
        </a></li>

        </ul>
      </nav>
    </aside>
    </div>

    <main class="main-content">
    <section class="content">

        <!-- Botones para diferentes tipos de historial -->
        <div id="button-cards-container">
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
        
        <div class="scrollable-table">
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
        
                        <div id="modal-overlay" style="display: none;">
                <div id="modal-content">
                    <span id="close-modal">&times;</span>
                    <div id="modal-body">
                        <a href="#" class="ver-mas-btn">Ver más</a>
            </div>
        </div>
    </div>
</div>

    </section>
    </main>

    
</body>

</html>