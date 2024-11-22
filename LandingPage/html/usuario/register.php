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
    <link rel="stylesheet" href="/WMS2/LandingPage/css/login.css">
    <script src="/WMS2/LandingPage/js/register_user.js"></script>
    <script src="/WMS2/LandingPage/js/login.js"></script>
    <script src="/WMS2/LandingPage/js/registerAJAX.js"></script>
</head>

<body>

    <div class="container">
        <!-- Panel izquierdo -->

        <!-- Panel derecho -->
        <div class="right-panel">
            <!-- Tabs para cambiar entre Sign In y Sign Up -->


            <!-- Formulario de inicio de sesión -->
            <div id="signInForm">
                <h2>Sign In</h2>
                <div id="error" style="color: red;"></div>
                <form id="loginForm">
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button class="sign-in" type="submit">Iniciar Sesión</button>
                </form>
            </div>

            <!-- Formulario de registro -->
            <div id="signUpForm" style="display: none;">
                <h2>Sign Up</h2>
                <form id="registerForm" action="/WMS2/LandingPage/phpFiles/config/process_register.php" method="POST">
                    <!-- Datos personales -->
                    <div class="form-group">
                        <label for="fullName">Edificio</label>
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
                    <!-- Datos de cuenta -->
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
                </form>
            </div>
        </div>
        <div class="left-panel">
            <div class="branding">
                <img src="/WMS2/LandingPage/img/Logos/LineLogo.png" alt="Logo" class="logo">
                <h1>Bienvenido a CISTA</h1>
            </div>
            <div class="tabs">
                <button id="signInTab" class="tab-button active">Iniciar Sesión</button>
                <button id="signUpTab" class="tab-button">Registrarse</button>
            </div>
        </div>
    </div>

    <script>
        // Cambiar entre Sign In y Sign Up
        document.getElementById('signInTab').addEventListener('click', function() {
            document.getElementById('signInForm').style.display = 'block';
            document.getElementById('signUpForm').style.display = 'none';
            this.classList.add('active');
            document.getElementById('signUpTab').classList.remove('active');
        });

        document.getElementById('signUpTab').addEventListener('click', function() {
            document.getElementById('signUpForm').style.display = 'block';
            document.getElementById('signInForm').style.display = 'none';
            this.classList.add('active');
            document.getElementById('signInTab').classList.remove('active');
        });




    </script>
</body>

</html>