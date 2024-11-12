<?php
session_start();

// Verificar si hay un mensaje de error en la sesión
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Limpiar el mensaje de error de la sesión después de mostrarlo
unset($_SESSION['error_message']);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/alerts.js"></script>
</head>
<body>

    <div class="container">
        <div class="right-panel">
            <h2>Crear una cuenta</h2>
            <?php if ($error_message): ?>
        <div id="error" style="color: red;"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="/WMS2/WMS2/LandingPage/phpFiles/config/process_login.php" method="POST">
    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="username" required>
    </div>
    <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Iniciar Sesión</button>
</form>

        </div>
       
        <div class="left-panel">
            <div class="branding">
                <img src="../img/Logos/LineLogo.png" alt="Logo" class="logo">
                <h1>Bienvenido a CISTA</h1>
            </div>
        </div>
        
 
    </div>
</html>
