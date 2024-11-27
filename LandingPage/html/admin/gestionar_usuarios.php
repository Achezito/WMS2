<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/inventario.php';
require_once BASE_PATH . '/phpFiles/Models/usuarios.php';
require_once BASE_PATH . '/phpFiles/Models/cuentas.php';
require_once BASE_PATH . '/phpFiles/Models/edificios.php';
// Verificar si el usuario es administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'administrador') {
    header('Location: /WMS2/LandingPage/html/login.php');
    exit();
}

// Verificar el tiempo de inactividad
$limite_inactividad = 100000; // Tiempo en segundos
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


/// Intentar obtener el id del edificio desde la sesión si no está en la URL
if (isset($_GET['id'])) {
    // Si el id está en la URL, lo asignamos y lo guardamos en la sesión
    $edificio_id = $_GET['id'];
    $_SESSION['edificio_id'] = $edificio_id;  // Almacenar el ID del edificio en la sesión
} elseif (isset($_SESSION['edificio_id'])) {
    // Si no hay id en la URL, verificamos si ya está en la sesión
    $edificio_id = $_SESSION['edificio_id'];
} else {
    // Si no hay edificio asignado ni en la URL ni en la sesión, mostrar un mensaje de error
    echo "Error: No se ha asignado un edificio al usuario actual.";
    exit();
}

// Obtener los materiales y los usuarios asociados al edificio
$edificios = Inventario::obtenerMaterialesPorEdificioYEstatus($edificio_id);
$cuentas = Cuenta::obtenerCuentasPorEdificio($edificio_id);

$Edificios = Edificios::mostrarTodosLosEdificios();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - CISTA</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/admin.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/hom2.css">
    <link rel="stylesheet" href="/WMS2/LandingPage/css/gestionarUsuario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/WMS2/LandingPage/js/index.js"></script>
    <script src="/WMS2/LandingPage/js/inventario_prestamoAJAX.js"></script>
    <script src="/WMS2/LandingPage/js/registerAdminAJAX.js"></script>
    <script src="/WMS2/LandingPage/js/gestionar_usuariosModals.js"></script>

</head>

<body>
    <div class="container">
        <!-- Barra lateral -->
        <aside class="sidebar">
            <div class="logo-container">
                <h1 class="app-title">CISTA</h1>
            </div>
            <div class="profile">
                <img class="user-avatar" src="/WMS2/LandingPage/img/Users/User.jpg" alt="User Avatar">
                <h3 class="titleName">
                    <?php echo $_SESSION['username']; ?>
                </h3>
                <p class="titleMail"> <?php echo $_SESSION['username']; ?></p>
            </div>
            <nav>
                <ul>
                    <li><a href="/WMS2/LandingPage/html/admin/indexAdmin.php"><label class="linkLabel">Home</label></a></li>
                    <li><a href="/WMS2/LandingPage/html/admin/gestion_inventario.php"><label class="linkLabel">Gestión de Inventario</label></a></li>
                    <li><a href="/WMS2/LandingPage/html/admin/gestionar_usuarios.php"><label class="linkLabel">Gestión de Usuarios</label></a></li>
                    <li><a href="/WMS2/LandingPage/html/admin/gestion_prestamos.php"><label class="linkLabel">Gestión de Préstamos</label></a></li>
                    <li><a href="/WMS2/LandingPage/html/admin/reportes.php"><label class="linkLabel">Reportes</label></a></li>
                    <li><a href="/WMS2/LandingPage/phpFiles/config/logout.php"><label class="linkLabel">Logout</label></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <section class="content">
                <h2>Gestión de Usuarios</h2>
                <p>Agrega, modifica o elimina usuarios desde esta sección.</p>

                <!-- Botón flotante para agregar usuario -->
                <button class="btn btn-add" id="addUserBtn">+ Añadir Usuario</button>

                <!-- Contenedor con scroll para la tabla de usuarios -->
                <div class="table-container">
                    <!-- Tabla de usuarios -->
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Cuenta</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cuentas as $c): ?>
                                <tr class="user-row" data-id="<?php echo $c['cuenta_id']; ?>" data-usuario="<?php echo $c['nombre_usuario']; ?>" data-cuenta="<?php echo $c['tipo_cuenta']; ?>">
                                    <td><?php echo $c['cuenta_id']; ?></td>
                                    <td><?php echo $c['nombre_usuario']; ?></td>
                                    <td><?php echo $c['tipo_cuenta']; ?></td>
                                    <td>
                                        <button class="btn btn-actions" id="">Acciones</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

    </div>

    <!-- Modal para añadir usuario -->
    <!-- Modal para añadir usuario -->
    <!-- Modal de agregar usuario -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <h2>Agregar Nuevo Usuario</h2>
            <!-- Formulario para añadir un nuevo usuario -->
            <form id="addUserForm" method="POST">

                <!-- Campo de Edificio -->
                <label for="edificio">Edificio</label>
                <select id="edificio" name="edificio">
                    <?php
                    if (!empty($Edificios)) {
                        foreach ($Edificios as $e) {
                            echo '<option value="' . htmlspecialchars($e['edificio_id']) . '">' . htmlspecialchars($e['nombre']) . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay edificios</option>';
                    }
                    ?>
                </select>

                <!-- Tipo de Cuenta -->
                <label for="tipoCuenta">Tipo de cuenta</label>
                <select id="tipoCuenta" name="tipoCuenta">
                    <option value="personal">Personal</option>
                    <option value="usuario">Usuario</option>
                </select>
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" required>

                <!-- Contraseña y Confirmar Contraseña -->
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmPassword">Confirmar Contraseña</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>

                <!-- Campos específicos para "Personal" -->
                <div id="personalFields" class="personal-input" style="display: none;">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="firstName">Primer Apellido</label>
                    <input type="text" id="firstName" name="firstName" required>

                    <label for="lastName">Segundo Apellido</label>
                    <input type="text" id="lastName" name="lastName" required>

                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" required>

                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>

                </div>

                <!-- Campos específicos para "Usuario" -->
                <div id="usuarioFields" class="usuario-input" style="display: none;">
                    <label for="usuarioNombre">Nombre</label>
                    <input type="text" id="usuarioNombre" name="usuarioNombre" required>

                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="alta">Alta</option>
                        <option value="baja">Baja</option>
                    </select>

                    <label for="usuarioEmail">Correo Electrónico</label>
                    <input type="email" id="usuarioEmail" name="usuarioEmail" required>
                </div>

                <button type="submit">Registrar Usuario</button>
                <div id="message" style="display:none;"></div>
            </form>
            <button id="closeAddUserModal">Cerrar</button>
        </div>
    </div>




    <div id="actionModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Acciones</h2>
            <div id="modalBody">
                <form id="modifyuserForm">

                    <!-- Campo de Edificio -->

                    <!-- Campos específicos para "Personal" -->
                    <div id="personalFields2" class="personal-input" style="display: none;">
                        <label for="estado">Estado</label>
                        <select id="estadoModify" name="estado">
                            <option value="personal">personal</option>
                            <option value="usuario">usuario</option>
                        </select>

                    </div>

                    <!-- Campos específicos para "Usuario" -->
                    <div id="usuarioFields2" class="usuario-input" style="display: none;">
                        <label for="estado">Estado</label>
                        <select id="estado2" name="estado">
                            <option value="alta">Alta</option>
                            <option value="baja">Baja</option>
                        </select>


                    </div>
                    <button type="submit">Actualizar</button>
                    <div id="message2" style="display:none;"></div>
                </form>
                <button id="closeActionModal">Cerrar</button>

            </div>
        </div>
    </div>
        <!-- Estilos para los modales -->
        <style>
            /* Modal general */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
            }

            /* Contenido del modal */
            .modal-content {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                max-width: 400px;
                width: 100%;
                text-align: center;
                max-height: 80vh;
                /* Limita la altura máxima al 80% de la ventana */
                overflow-y: auto;
                /* Habilita el desplazamiento vertical */
            }

            /* Botones del modal */
            .modal button {
                margin: 10px;
            }
        </style>

</body>

</html>