<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'administrador') {
    header('Location: ../../html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos
if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    if ($inactividad > $limite_inactividad) {
        session_unset();
        session_destroy();
        header("Location: ../../html/login.php?sesion=expirada");
        exit();
    }
}
$_SESSION['ultimo_acceso'] = time(); // Actualizar el último acceso


// Verificar si el edificio está asignado
if (isset($_GET['id'])) {
    $edificio_id = $_GET['id'];
    $_SESSION['edificio_id'] = $edificio_id; // Almacenar el ID del edificio en la sesión
    $edificios = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
} else {
    // Si no hay edificio asignado, verifica si ya está en la sesión
    if (isset($_SESSION['edificio_id'])) {
        $edificio_id = $_SESSION['edificio_id'];
        $edificios = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
    } else {
        echo "Error: No se ha asignado un edificio al usuario actual.";
        exit();
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - CISTA</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/hom2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../../js/index.js"></script>
    <script src="../../js/inventario_prestamoAJAX.js"></script>
</head>
<body>
  <div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
            <div class="logo-container">
                <h1 class="app-title">CISTA</h1>
            </div>
            <div class="profile">
                <img class="user-avatar" src="../../img/Users/User.jpg" alt="User Avatar">
                <h3 class="titleName">
                    <?php echo $_SESSION['username']; ?>
                </h3>
                <p class="titleMail"> <?php echo $_SESSION['username']; ?></p>
            </div>
            <nav>
                <ul>
                    <li><a href="../../html/admin/indexAdmin.php"><label class="linkLabel">Home</label></a></li>
                    <li><a href="../../html/admin/gestion_inventario.php"><label class="linkLabel">Gestión de Inventario</label></a></li>
                    <li><a href="../../html/admin/gestionar_usuarios.php"><label class="linkLabel">Gestión de Usuarios</label></a></li>
                    <li><a href="../../html/admin/gestion_prestamos.php"><label class="linkLabel">Gestión de Préstamos</label></a></li>
                    <li><a href="../../html/admin/reportes.php"><label class="linkLabel">Reportes</label></a></li>
                    <li><a href="../../phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
                </ul>
            </nav>
        </aside>


    <!-- Contenido principal -->
    <main class="main-content">
    <section class="content">
        <div class="welcome-section">
            <h2>Panel de Control - Administrador</h2>
            <p>Accede rápidamente a las funcionalidades principales del sistema.</p>
        </div>

        <!-- Tarjetas de opciones -->
        <div class="cards-container">
            <div class="cardAdmin">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>Gestión de Inventario</h3>
                <p>Visualiza, organiza y actualiza el inventario de materiales.</p>
                <a href="../../html/admin/gestion_inventario.php" class="card-btn">Gestionar</a>
            </div>

            <div class="cardAdmin">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gestión de Usuarios</h3>
                <p>Administra las cuentas y permisos de los usuarios.</p>
                <a href="../../html/admin/gestionar_usuarios.php" class="card-btn">Administrar</a>
            </div>

            <div class="cardAdmin">
                <div class="card-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Gestión de Préstamos</h3>
                <p>Revisa y administra las solicitudes de préstamos.</p>
                <a href="../../html/admin/gestion_prestamos.php" class="card-btn">Revisar</a>
            </div>

            <div class="cardAdmin">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Reportes</h3>
                <p>Genera reportes detallados sobre el uso del sistema.</p>
                <a href="../../html/admin/reportes.php" class="card-btn">Ver Reportes</a>
            </div>
        </div>
    </section>
</main>

  </div>
</body>
</html>
