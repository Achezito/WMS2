<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <div class="container">
        <div class="right-panel">
            <h2>Iniciar sesión</h2>

            <!-- Mostrar el mensaje de error si está presente -->
            <div id="error" style="color: red;"></div>

            <!-- Formulario de login -->
            <form id="loginForm" >
                <div class="form-group">
                    <label for="name">Nombre de usuario</label>
                    <input type="text" id="name" name="username" required>
                </div>
              
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button class="sign-in" type="submit">Iniciar Sesión</button>
                
            </form>
        </div>
       
        <div class="left-panel">
            <div class="branding">
                <img src="../img/Logos/LineLogo.png" alt="Logo" class="logo">
                <h1>Bienvenido a CISTA</h1>
            </div>
        </div>
        
    </div>

    <!-- Incluir el archivo JavaScript externo -->
    <script src="../js/login.js" defer></script>
</body>
</html>

