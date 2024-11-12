
<?php

session_start();

var_dump($_SESSION); // Verifica todas las variables de sesión
if (!isset($_SESSION['worker_user'])) {
    header('location: /WMS2/LandingPage/html/login.php');
    exit();
} 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="C:/xampp/htdocs/WMS2/WMS2/LandingPage/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="C:/xampp/htdocs/WMS2/WMS2/LandingPage/js/index.js"></script>
    
</head>
<body>
<h1>
        Bienvenido <?php echo htmlspecialchars($_SESSION['worker_user']) . ' con id: ' . htmlspecialchars($_SESSION['personal_id']); ?>
    </h1>
    <header>
        <div id="header-left">
            <div id="header-menu" onclick="toggleMenu()" >
                <i class="fa fa-bars"></i>
            </div>
            <div id="header-logo">
                <img src="../img/Logos/LineLogo.png" >
            </div>
            <h1>CISTA</h1>
        </div>
        <div id="header-right">
            <div id="user-photo">
                <img src="../img/Users/User.jpg" alt="User Photo">
            </div>
            <div id="header-logos">
                <i class="fas fa-cog"></i>
                <i class="fas fa-globe"></i>
                <i class="fas fa-sign-out-alt" id="logout-icon"></i>
            </div>
        </div>
    </header>
    <div id="menu">
        <ul>
            <li><i class="fas fa-home"></i><a href="#"> Home</a></li>
            <li><i class="far fa-user"></i><a href="#"> My account</a></li>
            <li><i class="far fa-clipboard"></i><a href="#" id="prestamos-link"> Préstamos </a></li>
        </ul>
    </div>
</body>
</html>
