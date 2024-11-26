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
                    <li><a href="/WMS2/LandingPage/html/admin/gestionar_inventario.php"><label class="linkLabel">Gestión de Inventario</label></a></li>
                    <li><a href="/WMS2/LandingPage/html/admin/gestionar_usuarios.php"><label class="linkLabel">Gestión de Usuarios</label></a></li>
                    <li><a href="/WMS2/LandingPage/html/admin/gestionar_prestamos.php"><label class="linkLabel">Gestión de Préstamos</label></a></li>
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
                                        <button class="btn btn-actions" onclick="openActionsModal()">Acciones</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

    </div>
    <!-- Modal para Editar Usuario -->
    <div id="editUserModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Editar Usuario</h2>
            <!-- Aquí puedes agregar el formulario de edición -->
            <form id="editUserForm">
                <label for="editUsername">Nuevo Nombre de Usuario</label>
                <input type="text" id="editUsername" name="editUsername" required>

                <label for="editPassword">Nueva Contraseña</label>
                <input type="password" id="editPassword" name="editPassword">

                <label for="editEmail">Nuevo Correo Electrónico</label>
                <input type="email" id="editEmail" name="editEmail">

                <button type="submit">Guardar Cambios</button>
            </form>
            <button id="closeEditUserModal">Cerrar</button>
        </div>
    </div>

    <!-- Modal de Confirmación para Eliminar Usuario -->
    <div id="confirmDeleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Confirmación de Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
            <button id="confirmDeleteBtn">Eliminar</button>
            <button id="cancelDeleteBtn">Cancelar</button>
        </div>
    </div>


    <div id="modalOverlay" class="modal-overlay" style="display: none;"></div>
    <div id="actionsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Acciones para el Usuario</h2>
            <button id="editUserBtn">Modificar</button>
            <button id="deleteUserBtn">Cambiar</button>
            <button id="closeModalBtn">Cerrar</button>
        </div>
    </div>
 
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

        </div>
    </div>
    <!-- Script para modal de acciones -->
    <script>
        // Obtener los elementos del DOM
        const actionsModal = document.getElementById('actionsModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        const editUserModal = document.getElementById('editUserModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closeEditUserModal = document.getElementById('closeEditUserModal');
        const deleteUserBtn = document.getElementById('deleteUserBtn');
        const editUserBtn = document.getElementById('editUserBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

        // Función para abrir el modal de acciones
        function openActionsModal() {
            actionsModal.style.display = 'block';
            modalOverlay.style.display = 'block';
        }

        // Función para cerrar el modal de acciones
        closeModalBtn.addEventListener('click', function() {
            actionsModal.style.display = 'none';
            modalOverlay.style.display = 'none';
        });

        // Cerrar el modal de acciones si se hace clic en el overlay
        modalOverlay.addEventListener('click', function() {
            actionsModal.style.display = 'none';
            modalOverlay.style.display = 'none';
            // Cerrar los otros modales si están abiertos
            confirmDeleteModal.style.display = 'none';
            editUserModal.style.display = 'none';
        });

        // Función para abrir el modal de eliminación
        deleteUserBtn.addEventListener('click', function() {
            actionsModal.style.display = 'none'; // Cerrar el modal de acciones
            confirmDeleteModal.style.display = 'block';
        });

        // Confirmar la eliminación del usuario
        confirmDeleteBtn.addEventListener('click', function() {
            // Aquí puedes agregar la lógica de eliminación del usuario
            alert('Usuario eliminado');
            confirmDeleteModal.style.display = 'none';
            modalOverlay.style.display = 'none';
        });

        // Cancelar la eliminación y cerrar el modal
        cancelDeleteBtn.addEventListener('click', function() {
            confirmDeleteModal.style.display = 'none';
            modalOverlay.style.display = 'none';
        });

        // Función para abrir el modal de edición
        editUserBtn.addEventListener('click', function() {
            actionsModal.style.display = 'none'; // Cerrar el modal de acciones
            editUserModal.style.display = 'block';
        });

        // Cerrar el modal de edición
        closeEditUserModal.addEventListener('click', function() {
            editUserModal.style.display = 'none';
            modalOverlay.style.display = 'none';
        });
    </script>
  <!-- Script para modal de añadir -->
    <script>
        // Obtener el botón y el modal
        const addUserBtn = document.getElementById('addUserBtn');
        const addUserModal = document.getElementById('addUserModal');

        // Función para abrir el modal
        addUserBtn.addEventListener('click', function() {
            addUserModal.style.display = 'block'; // Mostrar el modal
            document.getElementById('modalOverlay').style.display = 'block'; // Mostrar el overlay
        });

        // Función para cerrar el modal
        const closeModal = () => {
            addUserModal.style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none'; // Cerrar el overlay
        };

        // Cerrar el modal si se hace clic en el overlay
        document.getElementById('modalOverlay').addEventListener('click', closeModal);

        // Si quieres añadir un botón de "Cerrar" dentro del modal:
        // const closeModalBtn = document.getElementById('closeModalBtn');
        // closeModalBtn.addEventListener('click', closeModal);
    </script>

<!-- Script tipo de cuenta -->
    <script>
        // Obtener elementos del DOM
        document.getElementById('tipoCuenta').addEventListener('change', function() {
            var tipoCuenta = this.value;

            // Primero deshabilitamos todos los campos para ambos tipos
            var personalInputs = document.querySelectorAll('#personalFields input, #personalFields select');
            var usuarioInputs = document.querySelectorAll('#usuarioFields input, #usuarioFields select');

            // Deshabilitar todos los campos
            personalInputs.forEach(function(input) {
                input.disabled = true;
            });
            usuarioInputs.forEach(function(input) {
                input.disabled = true;
            });

            // Ocultar ambos conjuntos de campos
            document.getElementById('personalFields').style.display = 'none';
            document.getElementById('usuarioFields').style.display = 'none';

            // Mostrar solo los campos correspondientes dependiendo del tipo de cuenta
            if (tipoCuenta === 'personal') {
                document.getElementById('personalFields').style.display = 'block';
                personalInputs.forEach(function(input) {
                    input.disabled = false;
                });
            } else if (tipoCuenta === 'usuario') {
                document.getElementById('usuarioFields').style.display = 'block';
                usuarioInputs.forEach(function(input) {
                    input.disabled = false;
                });
            }
        });
    </script>



    <!-- Estilos para los modales -->
    <style>
        /* Estilos para el modal */
        .modal {
            display: none;
            /* Ocultar el modal por defecto */
            position: fixed;
            /* Fija el modal en la pantalla */
            top: 50%;
            /* Posiciona el modal en el centro vertical */
            left: 50%;
            /* Posiciona el modal en el centro horizontal */
            transform: translate(-50%, -50%);
            /* Ajusta el modal para centrarlo correctamente */
            background-color: rgba(0, 0, 0, 0.5);
            /* Fondo semi-transparente */
            width: 300px;
            /* Ancho del modal */
            padding: 20px;
            /* Espaciado dentro del modal */
            border-radius: 10px;
            /* Bordes redondeados */
            z-index: 1000;
            /* Asegura que el modal esté por encima de otros elementos */
        }

        .modal-content {
            background-color: #fff;
            /* Fondo blanco para el contenido del modal */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            /* Centrar el texto */
        }

        button {
            background-color: #007bff;
            /* Fondo azul para los botones */
            color: white;
            /* Texto blanco */
            border: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            /* Fondo más oscuro al pasar el mouse */
        }

        /* Fondo oscuro fuera del modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            /* Justo debajo del modal */
        }
    </style>

</body>

</html>