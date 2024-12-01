<?php
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/phpFiles/Models/edificios.php';

$Edificios = Edificios::mostrarTodosLosEdificios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Sign In / Sign Up</title>
    <link rel="stylesheet" href="../../css/register.css">
    <link rel="stylesheet" href="../../css/homeUsuarios.css">
    <script src="../../js/register_user.js"></script>
    <script src="../../js/login.js"></script>
    <script src="../../js/registerAJAX.js"></script>
</head>

<body>
    <div class="container">
        <!-- Panel izquierdo con menú desplegable -->
        <div class="left-panel">
            <div class="toggle-menu">
                <button id="toggle-btn">▼</button> <!-- Cambiado de icono Font Awesome a texto (▼) -->
            </div>
            <div class="menu-content">
                <img src="../../img/Logos/LineLogo.png" alt="Logo" class="logo">
                <h1>Bienvenido a CISTA</h1>
                <p>Plataforma de Gestión de Materiales</p>
                <div class="tabs">
                    <a href="../../index.php">
                        <button id="signInTab" class="tab-button active">Iniciar Sesión</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Panel derecho con formulario -->
        <div class="right-panel">
            <div id="signUpForm">
                <h2>Regístrate</h2>
                <form id="registerForm">
                    <div class="form-group">
                        <label for="edificio">Edificio</label>
                        <select id="edificio" name="edificio" required>
                            <option value="">Seleccione un edificio</option>
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
                    </div>
                    <div class="form-group">
                        <label for="fullName">Nombre completo</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="newUsername">Nombre de usuario</label>
                        <input type="text" id="newUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Contraseña</label>
                        <input type="password" id="newPassword" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirmar contraseña</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button class="sign-in" type="submit">Registrarse</button>
                    <div id="error"></div>
                </form>
                <div id="message"></div>
            </div>
        </div>
    </div>

    <script>
        // Menú desplegable
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggle-btn');
            const leftPanel = document.querySelector('.left-panel');

            toggleBtn.addEventListener('click', () => {
                const isExpanded = leftPanel.classList.toggle('expanded');
                if (isExpanded) {
                    toggleBtn.textContent = '▲'; // Cambia a flecha hacia arriba cuando se expande
                } else {
                    toggleBtn.textContent = '▼'; // Cambia a flecha hacia abajo cuando se cierra
                }
            });
        });
    </script>
</body>

</html>
