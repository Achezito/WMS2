<?php
// En hom2.php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('location: /WMS2/LandingPage/pruebas/dashboard/home.php');
    exit();
} 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <h1>
        Bienvenido <?php echo htmlspecialchars($_SESSION['user_name']) . ' con id: ' . htmlspecialchars($_SESSION['id_users']); ?>
    </h1>
</body>
</html>
