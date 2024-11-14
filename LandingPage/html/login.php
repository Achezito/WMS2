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
            
                <h2>Iniciar sesion</h2>
                <div class="line"></div>
                <div class="form-group">
                    <label for="name">Usuario</label>
                    <input type="text" id="name" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" required>
                </div>
                <div class="buttons">
                    <button class="sign-in" onclick="iniciarSesion()">Iniciar Sesión</button>
                </div>
            
        </div>

        <div class="left-panel">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
            <div class="circle5"></div>
            <div class="branding">
                <img src="../img/Logos/LineLogo.png" alt="Logo" class="logo">
                <h1>Bienvenido a CISTA</h1>
            </div>
        </div>


    </div>

</html>