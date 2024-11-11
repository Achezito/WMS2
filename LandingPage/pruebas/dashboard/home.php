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
    <link rel="stylesheet" href="/WMS2/LandingPage/pruebas/prueba.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php 
    require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/Models/users.php');
    ?>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if ($error_message): ?>
        <div style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="/WMS2/LandingPage/pruebas/verify/login.php" method="POST">
        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="login-btn">Entrar</button>
    </form>
    <div class="footer-text">
        ¿No tienes una cuenta? <a href="/WMS2/LandingPage/pruebas/dashboard/registro.php">Registrarse</a>
    </div>
</div>

</body>
</html>
