<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/Models/historiales.php';
require_once BASE_PATH . '/phpFiles/Models/prestamos.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'usuario') {
    header('Location: ../../html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos (puedes ajustarlo)

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




// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_type'])) {
    header('location: ../../html/login.php');
    exit();
}

if (isset($_SESSION['edificio_id'])) {
    $edificio_id = $_SESSION['edificio_id'];
    $materiales = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
} else {
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Principal</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/prestamo.css">
    
    <link rel="stylesheet" href="../../css/homeUsuarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../../js/index.js"></script>
    <script src="../../js/inventario_prestamoAJAX.js"></script>
    <script src="../../js/putamamadademierda.js"></script>
</head>

<body>
    <div class="container">
    <button class="menu-toggle">☰</button>
        <!-- Barra lateral -->
        <aside class="sidebar">
            <div class="logo-container">
                <!-- Contenedor para logo y nombre -->
                <h1 class="app-title">CISTA</h1>

            </div>
            <div class="profile">

                <!-- Perfil del usuario -->
                <img class="user-avatar" src="../../img/Users/User.jpg" alt="User Avatar">

                <h3 class="titleName">
                    <?php
                    echo $_SESSION['username'];
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

                        <a href="../../html/usuario/usuario.php">
                            <label class="linkLabel">
                                Solicitar</label>
                        </a>
                    </li>

                    <li><a href="../../html/usuario/PrestamosUser.php">
                            <label class="linkLabel">
                                Ver prestamos</label>
                        </a></li>
                        <li><a href="../../html/usuario/historialPrestamos.php">
                            <label class="linkLabel">
                                Ver historial</label>
                        </a></li>
                    <li><a href="../../phpFiles/config/logout.php">
                            <label class="linkLabel">
                                Logout</label>
                        </a></li>



                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <section class="content">
                <h2>Prestamos de <?php
                                    echo $_SESSION['nombre'];
                                    ?></h2>
                <div class="contenedor-historial">
                    <div class="busqueda-prestamos">

                    </div>
                    <?php
$usuario_id = $_SESSION['user_id'];

// Capturar el estatus de la búsqueda
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

// Llamar al método con los parámetros adecuados
$historial = Prestamo::obtenerHistorialDeUsuarioPermanentes($usuario_id, $estatus);
echo "<form method='get' action=''>";
echo "<label for='estatus'>Filtrar por Estatus:</label>";
echo "<select name='estatus' id='estatus'>";
echo "<option value=''>--Seleccionar Estatus--</option>";
echo "<option value='pendiente'" . ($estatus == 'pendiente' ? ' selected' : '') . ">Pendiente</option>";
echo "<option value='aprobado'" . ($estatus == 'aprobado' ? ' selected' : '') . ">Aprobado</option>";
echo "<option value='rechazado'" . ($estatus == 'rechazado' ? ' selected' : '') . ">Rechazado</option>";
echo "<option value='finalizado'" . ($estatus == 'finalizado' ? ' selected' : '') . ">Finalizado</option>";
echo "</select>";
echo "<button type='submit' class= 'btn-filtrar'>Filtrar</button>";
echo "</form>";

if (!empty($historial)) {
    echo '<h2 class="titulo-historial">Historial de prestamos actuales</h2>';
    echo '<div class="lista-prestamos">';

    foreach ($historial as $prestamo) {
        echo '<div class="tarjeta-prestamo" onclick="abrirPopup(' . $prestamo['operacion_id'] . ')">';
        echo '<h3>Préstamo: ' . htmlspecialchars($prestamo['operacion_id']) . '</h3>';
        echo '<p><strong>Notas:</strong> ' . htmlspecialchars($prestamo['notas']) . '</p>';
        echo '<p><strong>Estatus:</strong> ' . htmlspecialchars($prestamo['estatus']) . '</p>';
        
        // Mostrar todos los materiales asociados
        echo '<p><strong>Material(es):</strong> ' . implode(', ', $prestamo['materiales']) . '</p>';
        
        echo '<p><strong>Responsable:</strong> ' . htmlspecialchars($prestamo['responsable']) . '</p>';
        echo '<p><strong>Fecha de salida:</strong> ' . htmlspecialchars($prestamo['fecha_salida']) . '</p>';
        echo '<p><strong>Fecha de devolución:</strong> ' . htmlspecialchars($prestamo['fecha_devolucion']) . '</p>';
        echo '</div>';
    }
    

    echo '</div>';
} else {
    echo '<div class="mensaje-sin-prestamos">';
    echo '<h3>No se encontraron préstamos para este usuario.</h3>';
    echo '</div>';
}
?>


                </div>
                <div id="popup" class="popup" style="display: none;">
                    <div class="popup-content">
                    <input type="hidden" id="operacion_id" name="operacion_id" />
                        <div id="popup-detalles">
                            <!-- Detalles del préstamo se insertarán aquí -->


                        </div>
                    </div>
                </div>


        </main>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.querySelector(".sidebar");
        const menuToggle = document.querySelector(".menu-toggle");

        menuToggle.addEventListener("click", () => {
            sidebar.classList.toggle("hidden");
            menuToggle.classList.toggle("hidden");
        });
    });
</script>
</body>

</html>