<?php
 require_once('C:/xampp/htdocs/WMS2/LandingPage/phpFiles/Models/edificios.php');


 $edificios = Edificios::mostrarTodosLosEdificios();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up</title>
    <link rel="stylesheet" href="/WMS2/LandingPage/css/register.css">
    <script src="/WMS2/LandingPage/js/register_user.js"></script>
    <script src="/WMS2/LandingPage/js/login.js"></script>
    <script src="/WMS2/LandingPage/js/registerAJAX.js"></script>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <div class="branding">
                <img src="/WMS2/LandingPage/img/Logos/LineLogo.png" alt="Logo" class="logo">
                <h1>Bienvenido a CISTA</h1>
                <p>Plataforma de Gestión de Materiales</p>
            </div>
            <div class="tabs">
                <a href="/WMS2/LandingPage/html/login.php">
                    <button id="signInTab" class="tab-button active">Iniciar Sesión</button>
                </a>
            </div>
        </div>

        <div class="right-panel">
            <div id="signUpForm">
                <h2>Regístrate</h2>
                <form id="registerForm">
                    <div class="form-group">
                        <label for="edificio">Edificio</label>
                        <select id="edificio" name="edificio" required>
                            <option value="">Seleccione un edificio</option>
                            <?php
                            foreach ($edificios as $edificio) {
                                echo "<option value='" . $edificio->getEdificioId() . "'>" . $edificio->getNombre() . "</option>";
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

    <div id="message"></div>
            </div>
        </div>
    </div>
</body>

</html>
