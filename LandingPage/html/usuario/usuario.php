<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'usuario') {
    header('Location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos (puedes ajustarlo)

if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    if ($inactividad > $limite_inactividad) {
        session_unset();
        session_destroy();
        header("Location: /WMS2/LandingPage/html/login.php?sesion=expirada");
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
    <link rel="stylesheet" href="../../css/solicitud.css">
    <link rel="stylesheet" href="../../css/homeUsuarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../../js/index.js"></script>
    <script src="../../js/inventario_prestamoAJAX.js"></script>
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

                    <li><a href="../../html/usuario/prestamosUser.php">
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
                <h2 class="form-title">Formulario de Solicitud</h2>
                <form id="solicitudForm" class="styled-form">
                    <div class="input-group">
                        <label for="nombre" class="label1">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="input-label" value="<?php echo $_SESSION['nombre']; ?>" readonly>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']; ?>">

                    <div class="input-group">
                        <div class="custom-select-wrapper">
                            <label for="material" class="label1">Material(es)</label>
                            <select id="material" name="material[]" class="custom-multi-select" multiple required>
                                <?php
                                if (!empty($materiales)) {
                                    foreach ($materiales as $material) {
                                        echo '<option value="' . htmlspecialchars($material['material_id']) . '">' . htmlspecialchars($material['modelo']) . ' (' . htmlspecialchars($material['tipo_material']) . ')</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay materiales disponibles</option>';
                                }
                                ?>
                            </select>
                            <small class="helper-text">Ctrl + Click para seleccionar múltiples materiales</small>
                        </div>

                    </div>

                        <div class="input-group">
                            <label for="comentarios" class="label1">Notas</label>
                            <textarea name="comentarios" class="input-field textarea" placeholder="Escribe tu comentario aquí" rows="4" required></textarea>
                        </div>

                        <div class="input-group">
                            <label for="fecha" class="label1">Fecha de Solicitud</label>
                            <input type="date" id="fecha" name="fecha" class="input-field" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>

                        <button type="submit" class="btn btn-submit">Enviar Solicitud</button>
                        <div id="message" class="form-message"></div>
                </form>
            </section>
        </main>

        <script>
            // Obtener la fecha actual en formato YYYY-MM-DD
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd; // Formato correcto para el input type="date"

            // Establecer la fecha actual en el campo de fecha
            document.getElementById('fecha').value = today;
        </script>

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