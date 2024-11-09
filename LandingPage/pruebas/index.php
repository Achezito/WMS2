<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="prueba.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>


</head>
<body>
    <?php 
    require_once('C:/xampp/htdocs/WMS2/LandingPage/pruebas/Models/users.php')
    
    ?>


<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form action="/WMS2/LandingPage/pruebas/phpFiles/login.php" method="POST">

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
        ¿No tienes una cuenta? <a href="#">Regístrate</a>
    </div>
</div>

</body>
</html>