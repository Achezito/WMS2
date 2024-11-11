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
    <h2>SIGN UP</h2>
    <?php if (!empty($error_message)): ?>
        <div class="error-message" style="color: red;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <form action="/WMS2/LandingPage/pruebas/verify/procesar_registro.php" method="POST">

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
        ¿Ya tienes una cuenta? <a href="/WMS2/LandingPage/pruebas/dashboard/home.php">Inicia sesion</a>
    </div>
</div>

</body>
</html>